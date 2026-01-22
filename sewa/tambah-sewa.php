<?php
// Ambil data pelanggan
$q_pelanggan = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Sewa</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/dashboard.css" rel="stylesheet">
</head>
<body>

<div class="main">
    <div class="container-fluid">

        <?php include "komponen/alert.php"; ?>

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Tambah Data Sewa</h1>
        </div>

        <div class="card mb-4">
            <div class="card-body">

                <form action="sewa/aksi/simpan.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="aksi" value="tambah">

                    <div class="row mb-3">

                        <!-- Pelanggan -->
                        <div class="col-md-6 mb-3">
                            <label for="id_pelanggan" class="form-label">Pilih Pelanggan</label>
                            <select class="form-select" id="id_pelanggan" name="id_pelanggan" required>
                                <option value="">-- Pilih Pelanggan --</option>
                                <?php while ($p = mysqli_fetch_array($q_pelanggan, MYSQLI_ASSOC)) { ?>
                                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nama']) ?> (<?= htmlspecialchars($p['no_hp']) ?>)</option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Tanggal Sewa -->
                        <div class="col-md-3 mb-3">
                            <label for="tanggal_sewa" class="form-label">Tanggal Sewa</label>
                            <input type="date" class="form-control" id="tanggal_sewa" name="tanggal_sewa" required>
                        </div>

                        <!-- Tanggal Kembali (rencana) -->
                        <div class="col-md-3 mb-3">
                            <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                            <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali" required>
                        </div>

                        <!-- Upload Gambar (opsional: bukti sewa / ktp / kwitansi) -->
                        <div class="col-md-6 mb-3">
                            <label for="gambar" class="form-label">Upload KTP/SIM</label>
                            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                            <div class="form-text">Format: JPG, PNG, GIF, WEBP. Max: 2MB</div>
                        </div>

                        <!-- Status default -->
                        <input type="hidden" name="status" value="dipinjam">

                        <!-- Tombol -->
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-secondary me-2" type="reset">Reset</button>
                            <button class="btn btn-primary" type="submit">Simpan</button>
                        </div>
                    </div> <!-- row -->
                </form>

            </div>
        </div>

    </div>
</div>

<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
