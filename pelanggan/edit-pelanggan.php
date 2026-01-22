<?php
$id = base64_decode($_GET['id']);
$query = "SELECT * FROM pelanggan WHERE id='" . $id . "'";
$result = mysqli_query($conn,$query);
$data = mysqli_fetch_array($result,MYSQLI_ASSOC);
?>
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
            <h1 class="h2">Edit Data Pelanggan</h1>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <?php include "komponen/alert.php"; ?>
                <?php if (isset($_GET['success']) && $_GET['success'] == '1') : ?>
                    <script>
                        setTimeout(function() {
                            window.location.href = 'index.php?page=pelanggan';
                        }, 2000);
                    </script>
                <?php endif; ?>
                <form action="pelanggan/aksi/update.php" method="post">
                    <input type="hidden" name="aksi" value="edit">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']) ?>">

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?= $data['nama']?>"required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nope" class="form-label">Nomor HP</label>
                            <input type="text" class="form-control" id="nope" name="nope" value="<?= $data['no_hp']?>"required>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control" rows="5" required><?= $data['alamat']?></textarea>
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
