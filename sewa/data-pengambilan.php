<?php
include "koneksi/koneksi.php";
$query = "
SELECT 
    pb.id AS id,
    p.nama AS nama_pelanggan,
    s.tanggal_sewa,
    s.total_bayar,
    pb.nama_pengambil,
    pb.tanggal_pengambilan,
    pb.bukti,
    pb.catatan
FROM 
    pengambilan_barang pb
JOIN 
    sewa s ON pb.id_sewa = s.id
JOIN 
    pelanggan p ON pb.id_pelanggan = p.id
ORDER BY 
    pb.tanggal_pengambilan DESC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Pengambilan - Admin</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/datatables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" />
</head>
<body>
    <div class="main">
        <div class="container-fluid">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Data Pengambilan</h1>
            </div>
            <div class="card mt-3 mb-5">
                <div class="card-body">
                    <table class="table table-striped table-hover" id="pengambilanTable">
                        <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Pelanggan</th>
                            <th>Tanggal Sewa</th>
                            <th>Total Bayar</th>
                            <th>Nama Pengambil</th>
                            <th>Tanggal Pengambilan</th>
                            <th>Bukti</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                        if (mysqli_num_rows($result) > 0) {
                            $no = 1;
                            while ($data = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($data['nama_pelanggan']) ?></td>
                            <td><?= $data['tanggal_sewa'] ?></td>
                            <td>Rp <?= number_format($data['total_bayar'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($data['nama_pengambil']) ?></td>
                            <td><?= $data['tanggal_pengambilan'] ?></td>
                            <td>
                                <?php if ($data['bukti']) : ?>
                                    <a href="uploads/bukti_pengambilan/<?= $data['bukti'] ?>" target="_blank">Lihat Bukti</a>
                                <?php else : ?>
                                    <span class="badge bg-secondary">Tidak Ada</span>
                                <?php endif; ?>
                            </td>
                            <td><?= nl2br(htmlspecialchars($data['catatan'])) ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="hapusData(<?= $data['id'] ?>, 'pengambilan_barang', '<?= htmlspecialchars($data['id']) ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>
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
                                    <p class="mt-2">Belum ada Data Pengambilan</p>
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
    $('#pengambilanTable').DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});

function hapusData(id, tabel, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus data pengambilan oleh "' + nama + '"?\nData yang dihapus tidak dapat dikembalikan.')) {
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
