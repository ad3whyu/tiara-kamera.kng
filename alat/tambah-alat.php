<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tambah Data Alat</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/dashboard.css" rel="stylesheet" />
</head>
<body>

<div class="main">
    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Tambah Data Alat</h1>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <?php include "komponen/alert.php"; ?>
                <form action="alat/aksi/simpan-alat.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="aksi" value="tambah">

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="nama_alat" class="form-label">Nama Alat</label>
                            <input type="text" class="form-control" id="nama_alat" name="nama_alat" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Kamera">Kamera</option>
                                <option value="Video">Video</option>
                                <option value="Audio">Audio</option>
                                <option value="Lighting">Lighting</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" min="0" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="harga_sewa_per_hari" class="form-label">Harga Sewa per Hari</label>
                            <input type="number" class="form-control" id="harga_sewa" name="harga_sewa" min="0" step="0.01" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="gambar" class="form-label">Gambar Alat</label>
                            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
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
