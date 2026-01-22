
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tambah Data Pelanggan</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/dashboard.css" rel="stylesheet" />
</head>
<body>

<div class="main">
    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Tambah Data Pelanggan</h1>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <?php include "komponen/alert.php"; ?>
                <form action="pelanggan/aksi/simpan.php" method="post">
                    <input type="hidden" name="aksi" value="tambah">

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nope" class="form-label">Nomor HP</label>
                            <input type="text" class="form-control" id="nope" name="nope" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control" rows="5" required></textarea>
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
