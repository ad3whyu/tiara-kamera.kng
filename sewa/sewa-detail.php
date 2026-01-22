<?php
include "komponen/navbar.php";
include "koneksi/koneksi.php";

$id_sewa = $_GET['id'] ?? '';

if (empty($id_sewa)) {
    $_SESSION['alert_error'] = 'ID sewa tidak valid.';
    header("Location: index.php?page=sewa");
    exit();
}

$q_sewa = mysqli_query($conn, "
    SELECT s.*, p.nama 
    FROM sewa s
    JOIN pelanggan p ON s.id_pelanggan = p.id
    WHERE s.id = '$id_sewa'
");

if (mysqli_num_rows($q_sewa) == 0) {
    $_SESSION['alert_error'] = 'Data sewa tidak ditemukan.';
    header("Location: index.php?page=sewa");
    exit();
}

$sewa = mysqli_fetch_array($q_sewa, MYSQLI_ASSOC);

// Query alat
$q_alat = mysqli_query($conn, "SELECT * FROM alat ORDER BY nama_alat ASC");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tambah Detail Sewa</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/dashboard.css" rel="stylesheet" />
</head>
<body>

<div class="main">
    <div class="container-fluid">

        <?php include "komponen/alert.php"; ?>

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Tambah Detail Sewa</h1>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <? include "komponen/alert.php"?>
                <form action="sewa/aksi/simpan-sewa-detail.php" method="post">
                    <input type="hidden" name="id_sewa" value="<?= $sewa['id'] ?>">
                    <input type="hidden" name="aksi" value="tambah">
                    <div class="row mb-3">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($sewa['nama']) ?>" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="id_alat" class="form-label">Pilih Alat</label>
                            <select class="form-select" name="id_alat" id="id_alat" required>
                                <option value="">-- Pilih Alat --</option>
                                <?php while ($alat = mysqli_fetch_array($q_alat, MYSQLI_ASSOC)): ?>
                                    <option value="<?= $alat['id'] ?>"
                                            data-harga="<?= $alat['harga_sewa_per_hari'] ?>"
                                            data-stock="<?= $alat['stok'] ?>">
                                        <?= htmlspecialchars($alat['nama_alat']) ?> (Stock: <?= $alat['stok'] ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Harga Sewa / Hari</label>
                            <input type="text" class="form-control" id="harga_sewa" disabled>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah" id="jumlah" min="1" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="durasi_hari" class="form-label">Durasi (Hari)</label>
                            <input type="number" class="form-control" name="durasi_hari" id="durasi_hari" min="1" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="subtotal" class="form-label">Subtotal (Rp)</label>
                            <input type="text" class="form-control" id="subtotal" readonly>
                            <input type="hidden" name="subtotal" id="subtotal_hidden">
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <a href="index.php?page=sewa" class="btn btn-secondary me-2">Selesai</a>
                            <button type="submit" class="btn btn-primary">Tambah Alat</button>
                        </div>

                    </div>
                </form>

            </div>
        </div>

        <!-- Tabel alat yang sudah disewa -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Alat Yang Disewa</h5>

                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Alat</th>
                            <th>Jumlah</th>
                            <th>Durasi (Hari)</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q_detail = mysqli_query($conn, "
                            SELECT sd.*, a.nama_alat 
                            FROM sewa_detail sd
                            JOIN alat a ON sd.id_alat = a.id
                            WHERE sd.id_sewa = '$id_sewa'
                        ");
                        $no = 1;
                        $grand_total = 0;
                        if (mysqli_num_rows($q_detail) > 0):
                            while ($detail = mysqli_fetch_array($q_detail, MYSQLI_ASSOC)):
                                $grand_total += $detail['subtotal'];
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($detail['nama_alat']) ?></td>
                            <td><?= $detail['jumlah'] ?></td>
                            <td><?= $detail['durasi_hari'] ?></td>
                            <td>Rp <?= number_format($detail['subtotal'],0,",",".") ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="hapusData(<?= $detail['id'] ?>, 'sewa', '<?= htmlspecialchars($detail['nama_alat']) ?>')">
                                        <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada alat disewa.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Grand Total</th>
                            <th colspan="2">Rp <?= number_format($grand_total,0,",",".") ?></th>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>

    </div>
</div>

<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {

    // Saat pilih alat → update harga
    $("#id_alat").on("change", function() {
        var harga = $('option:selected', this).data('harga') || 0;
        $("#harga_sewa").val("Rp " + harga.toLocaleString());
        hitungSubtotal();
    });

    $("#jumlah, #durasi_hari").on("input", function() {
        hitungSubtotal();
    });

    function hitungSubtotal() {
        var harga = $('option:selected', '#id_alat').data('harga') || 0;
        var jumlah = parseInt($("#jumlah").val()) || 0;
        var durasi = parseInt($("#durasi_hari").val()) || 0;

        var subtotal = harga * jumlah * durasi;
        
        // update tampilan
        $("#subtotal").val("Rp " + subtotal.toLocaleString());
        
        // update input hidden supaya ikut ke POST
        $("#subtotal_hidden").val(subtotal);
    }

});
</script>

</body>
</html>
