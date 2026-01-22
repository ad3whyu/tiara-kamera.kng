<?php
include "komponen/navbar.php";
include "koneksi/koneksi.php";

// ambil data sewa + pelanggan
$q_sewa = mysqli_query($conn, "
    SELECT s.*, p.nama
    FROM sewa s
    JOIN pelanggan p ON s.id_pelanggan = p.id
    ORDER BY s.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sewa</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/datatables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" />
</head>
<body>

<div class="main">
    <div class="container-fluid">

        <?php include "komponen/alert.php"; ?>

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Data Sewa</h1>
        </div>

        <div class="card mb-5">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Daftar Transaksi Sewa</h5>
                    <a href="index.php?page=tambah-sewa" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Sewa
                    </a>
                </div>

                <table class="table table-striped table-hover" id="sewaTable">
                    <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th width="50%">Pelanggan</th>
                        <th>Tgl Sewa</th>
                        <th >Tgl Kembali</th>
                        <th>Status</th>
                        <th>Total Bayar (Rp)</th>
                        <th>Denda (Rp)</th>
                        <th>Status Alat</th>
                        <th width="20%">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if (mysqli_num_rows($q_sewa) > 0) {
                        $no = 1;
                        while ($sewa = mysqli_fetch_array($q_sewa, MYSQLI_ASSOC)) {
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($sewa['nama']) ?></td>
                        <td><?= htmlspecialchars(date('d-m-Y', strtotime($sewa['tanggal_sewa']))) ?></td>
                        <td><?= htmlspecialchars(date('d-m-Y', strtotime($sewa['tanggal_kembali']))) ?></td>
                        <td>
                            <?php if ($sewa['status'] == 'dipinjam'): ?>
                                <span class="badge bg-warning text-dark">Dipinjam</span>
                            <?php elseif ($sewa['status'] == 'dikembalikan'): ?>
                                <span class="badge bg-success">Dikembalikan</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($sewa['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= number_format($sewa['total_bayar'], 0, ',', '.') ?></td>
                        <td><?= number_format($sewa['denda'], 0, ',', '.') ?></td>
                        <td>
                            <?php if ($sewa['status_alat'] == 'Rusak'): ?>
                                <span class="badge bg-warning text-dark">Rusak</span>
                            <?php elseif ($sewa['status_alat'] == 'Baik'): ?>
                                <span class="badge bg-success">Baik</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($sewa['status_alat']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <?php if ($sewa['status'] == 'dipinjam'): ?>
                                    <a href="index.php?page=proses-pengembalian&id=<?= $sewa['id'] ?>" 
                                    class="btn btn-sm btn-outline-warning" 
                                    title="Proses Pengembalian">
                                        <i class="bi bi-box-arrow-in-left"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="index.php?page=sewa-detail&id=<?= $sewa['id'] ?>" 
                                    class="btn btn-sm btn-outline-info" title="Detail Sewa">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus Sewa"
                                        onclick="hapusData(<?= $sewa['id'] ?>, 'sewa', '<?= htmlspecialchars($sewa['id']) ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else {
                    ?>
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2">Belum ada data sewa</p>
                                <a href="index.php?page=tambah-sewa" class="btn btn-primary">Tambah Sewa Pertama</a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>

<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#sewaTable').DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});

function hapusData(id, tabel, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus data sewa ID "' + nama + '"?\nData yang dihapus tidak dapat dikembalikan.')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'komponen/hapus.php';
        
        var inputAksi = document.createElement('input');
        inputAksi.type = 'hidden';
        inputAksi.name = 'aksi';
        inputAksi.value = 'hapus';
        form.appendChild(inputAksi);
        
        var inputTabel = document.createElement('input');
        inputTabel.type = 'hidden';
        inputTabel.name = 'tabel';
        inputTabel.value = tabel;
        form.appendChild(inputTabel);
        
        var inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = 'id';
        inputId.value = id;
        form.appendChild(inputId);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

</body>
</html>
