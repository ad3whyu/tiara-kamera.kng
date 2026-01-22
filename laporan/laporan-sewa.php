<?php

// Ambil data filter tanggal dari GET
$tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
$tanggal_selesai = $_POST['tanggal_selesai'] ?? '';

// Filter SQL
$filter = "";
if ($tanggal_mulai && $tanggal_selesai) {
    $filter = "WHERE DATE(s.tanggal_sewa) BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'";
}

// Query laporan dengan filter tanggal
$q_laporan = mysqli_query($conn, "
    SELECT 
        s.id AS id_sewa,
        p.nama AS nama_pelanggan,
        s.tanggal_sewa,
        s.tanggal_kembali,
        s.tanggal_dikembalikan,
        s.status,
        s.total_bayar,
        s.denda,
        s.status_pembayaran,
        s.metode_pembayaran,
        a.nama_alat,
        sd.jumlah,
        sd.durasi_hari,
        sd.subtotal
    FROM 
        sewa s
    JOIN 
        pelanggan p ON s.id_pelanggan = p.id
    JOIN 
        sewa_detail sd ON s.id = sd.id_sewa
    JOIN 
        alat a ON sd.id_alat = a.id
    $filter
    ORDER BY 
        s.id DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Sewa</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <style>
        th, td { white-space: nowrap; vertical-align: middle; }
        .dataTables_wrapper .dataTables_filter { float: right; }
        .table-responsive { overflow-x: auto; }
        .dt-buttons .btn { box-shadow: none !important; border-radius: 4px; }
    </style>
</head>
<body>
<div class="main">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Laporan Sewa</h1>
        </div>

        <!-- Form Filter Tanggal -->
        <form method="POST" action="index.php?page=laporan" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" value="<?= htmlspecialchars($tanggal_mulai) ?>">
            </div>
            <div class="col-md-3">
                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" value="<?= htmlspecialchars($tanggal_selesai) ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
        </form>

        <!-- Tabel Laporan -->
        <div class="card mb-5">
            <div class="card-body table-responsive">
                <h5 class="card-title mb-3">Rekap Laporan Transaksi Sewa</h5>
                <table class="table table-striped table-hover" id="laporanTable">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Alat</th>
                            <th>Jumlah</th>
                            <th>Durasi (hari)</th>
                            <th>Harga Sewa (Rp)</th>
                            <th>Tanggal Sewa</th>
                            <th>Tanggal Kembali</th>
                            <th>Tanggal Dikembalikan</th>
                            <th>Status Pembayaran</th>
                            <th>Metode Pembayaran</th>
                            <th>Denda (Rp)</th>
                            <th>Status Sewa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($data = mysqli_fetch_assoc($q_laporan)) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($data['nama_pelanggan']) ?></td>
                            <td><?= htmlspecialchars($data['nama_alat']) ?></td>
                            <td><?= $data['jumlah'] ?></td>
                            <td><?= $data['durasi_hari'] ?></td>
                            <td><?= number_format($data['subtotal'], 0, ',', '.') ?></td>
                            <td><?= date('d-m-Y', strtotime($data['tanggal_sewa'])) ?></td>
                            <td><?= date('d-m-Y', strtotime($data['tanggal_kembali'])) ?></td>
                            <td><?= $data['tanggal_dikembalikan'] ? date('d-m-Y', strtotime($data['tanggal_dikembalikan'])) : '-' ?></td>
                            <td><?= $data['status_pembayaran'] ?></td>
                            <td><?= $data['metode_pembayaran'] ?></td>
                            <td><?= number_format($data['denda'], 0, ',', '.') ?></td>
                            <td>
                                <?php if ($data['status'] == 'dipinjam'): ?>
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                <?php elseif ($data['status'] == 'dikembalikan'): ?>
                                    <span class="badge bg-success">Dikembalikan</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script>
$(document).ready(function () {
    $('#laporanTable').DataTable({
        dom: '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"row mt-3"<"col-sm-6"i><"col-sm-6"p>>',
        buttons: [
            {
                extend: 'pdfHtml5',
                text: '<i class="bi bi-file-earmark-pdf"></i> Export PDF',
                className: 'btn btn-danger me-2',
                title: 'Laporan Transaksi Sewa',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="bi bi-file-earmark-excel"></i> Export Excel',
                className: 'btn btn-success me-2',
                title: 'Laporan Transaksi Sewa',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'print',
                text: '<i class="bi bi-printer"></i> Cetak',
                className: 'btn btn-secondary',
                title: 'Laporan Transaksi Sewa',
                exportOptions: { columns: ':visible' }
            }
        ],
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        responsive: true
    });
});
</script>
</body>
</html>
