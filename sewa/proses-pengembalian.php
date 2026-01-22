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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Proses Pengembalian</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/dashboard.css" rel="stylesheet" />
</head>
<body>

<div class="main">
    <div class="container-fluid">

        <?php include "komponen/alert.php"; ?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Proses Pengambilan</h1>
        </div>
        <?php include "komponen/alert.php"; ?>
        <div class="card mb-4">
            <div class="card-body">

                <form action="sewa/aksi/proses-pengambilan.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_sewa" value="<?= $sewa['id'] ?>">
                    <input type="hidden" name="id_pelanggan" value="<?= $sewa['id_pelanggan'] ?>">
                    <div class="row mb-3">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($sewa['nama']) ?>" readonly>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Nama Pengambil</label>
                            <input type="text" class="form-control" name="nama_pengambil" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tanggal Pengambilan</label>
                            <input type="date" name="tanggal_pengambilan" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Upload Bukti Penyerahan</label>
                            <input type="file" name="bukti_penyerahan" class="form-control"  required>
                            <small class="text-muted">Foto KTP, foto serah terima, atau tanda tangan.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Catatan Kondisi Alat</label>
                            <textarea name="catatan_kondisi" class="form-control" rows="3" placeholder="Contoh: kabel lengkap, tanpa kerusakan..."></textarea>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <a href="index.php?page=sewa" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Proses Pengambilan</button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Proses Pengembalian</h1>
        </div>

        <div class="card mb-4">
            <div class="card-body">

                <form action="sewa/aksi/proses-pengembalian.php" method="post">
                    <input type="hidden" name="id_sewa" value="<?= $sewa['id'] ?>">

                    <div class="row mb-3">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($sewa['nama']) ?>" readonly>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tanggal Sewa</label>
                            <input type="text" class="form-control" value="<?= date('d-m-Y', strtotime($sewa['tanggal_sewa'])) ?>" readonly>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Rencana Kembali</label>
                            <input type="text" class="form-control" value="<?= date('d-m-Y', strtotime($sewa['tanggal_kembali'])) ?>" readonly>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="tanggal_pengembalian" class="form-label">Tanggal Pengembalian</label>
                            <input type="date" class="form-control" name="tanggal_pengembalian" id="tanggal_pengembalian" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status_alat" class="form-label">Status Alat</label>
                            <select class="form-select" name="status_alat" id="status_alat" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Baik">Baik</option>
                                <option value="Rusak">Rusak</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="denda" class="form-label">Denda (Rp)</label>
                            <input type="number" class="form-control" name="denda" id="denda" value="0" min="0" readonly>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <a href="index.php?page=sewa" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Proses Pengembalian</button>
                        </div>

                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const rencanaKembali = '<?= $sewa['tanggal_kembali'] ?>'; // format Y-m-d
    const tanggalKembali = new Date(rencanaKembali);
    const inputTanggalPengembalian = document.getElementById('tanggal_pengembalian');
    const inputDenda = document.getElementById('denda');

    const DENDA_PER_HARI = 20000; // 20 ribu per hari telat

    function hitungDenda() {
        const tglPengembalian = new Date(inputTanggalPengembalian.value);

        // Hitung selisih hari
        const selisihMs = tglPengembalian - tanggalKembali;
        const selisihHari = Math.ceil(selisihMs / (1000 * 60 * 60 * 24));

        let denda = 0;

        if (selisihHari > 0) {
            denda = selisihHari * DENDA_PER_HARI;
        }

        inputDenda.value = denda.toLocaleString('id-ID');
    }

    // Event saat tanggal pengembalian diubah
    inputTanggalPengembalian.addEventListener('change', hitungDenda);

    // Jalankan sekali saat halaman dibuka
    hitungDenda();

});
</script>
</body>
</html>
