<?php
$id = base64_decode($_GET['id']);
$query = "SELECT * FROM alat WHERE id='" . $id . "'";
$result = mysqli_query($conn,$query);
$data = mysqli_fetch_array($result,MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>TamEditbah Data Alat</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/dashboard.css" rel="stylesheet" />
</head>
<body>

<div class="main">
    <div class="container-fluid">
        <?php include "komponen/navbar.php"; ?>

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Edit Data Alat</h1>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <?php include "komponen/alert.php"; ?>
                <?php if (isset($_GET['success']) && $_GET['success'] == '1') : ?>
                    <script>
                        setTimeout(function() {
                            window.location.href = 'index.php?page=alat';
                        }, 2000);
                    </script>
                <?php endif; ?>
                <form action="alat/aksi/update-alat.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="aksi" value="edit">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']) ?>">

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="nama_alat" class="form-label">Nama Alat</label>
                            <input type="text" class="form-control" id="nama_alat" name="nama_alat" value="<?= $data['nama_alat']?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Kamera" <?= ($data['kategori'] == 'Kamera') ? 'selected' : '' ?>>Kamera</option>
                                <option value="Video" <?= ($data['kategori'] == 'Video') ? 'selected' : '' ?>>Video</option>
                                <option value="Audio" <?= ($data['kategori'] == 'Audio') ? 'selected' : '' ?>>Audio</option>
                                <option value="Lighting" <?= ($data['kategori'] == 'Lighting') ? 'selected' : '' ?>>Lighting</option>
                                <option value="Lainnya" <?= ($data['kategori'] == 'Lainnya') ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" min="0" value="<?= $data['stok']?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="harga_sewa_per_hari" class="form-label">Harga Sewa per Hari</label>
                            <input type="number" class="form-control" id="harga_sewa" name="harga_sewa" min="0" step="0.01" value="<?= $data['harga_sewa_per_hari']?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="gambar" class="form-label">Gambar Alat</label>
                            <?php if (!empty($data['gambar'])): ?>
                                <div class="mb-2">
                                    <img src="../uploads/<?= htmlspecialchars($data['gambar']) ?>" alt="Gambar Alat" style="max-width:120px;max-height:120px;object-fit:cover;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-secondary me-2" type="reset">Reset</button>
                            <button class="btn btn-primary" type="submit">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
