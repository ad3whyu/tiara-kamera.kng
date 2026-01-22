<?php
include "koneksi/koneksi.php";
include "komponen/session.php";

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<!-- Sidebar -->
<?php include "komponen/navbar.php"; ?>
    <!-- Page Content -->
    <div class="container-fluid">
        <?php
        if ($page == 'dashboard') {
            include "komponen/dashboard.php";

        } elseif ($page == 'alat') {
            include "alat/alat.php";
        } elseif ($page == 'tambah-alat') {
            include "alat/tambah-alat.php";
        } elseif ($page == 'edit-alat') {
            include "alat/edit-alat.php";
        }

        elseif ($page == 'pelanggan') {
            include "pelanggan/pelanggan.php";
        } elseif ($page == 'tambah-pelanggan') {
            include "pelanggan/tambah-pelanggan.php";
        } elseif ($page == 'edit-pelanggan') {
            include "pelanggan/edit-pelanggan.php";
        }

        elseif ($page == 'sewa') {
            include "sewa/sewa.php";
        } elseif ($page == 'tambah-sewa') {
            include "sewa/tambah-sewa.php";
        } elseif ($page == 'edit-sewa') {
            include "sewa/edit-sewa.php";
        }elseif ($page == 'proses-pengembalian') {
            include "sewa/proses-pengembalian.php";
        }
        elseif ($page == 'sewa-detail') {
            include "sewa/sewa-detail.php";
        }
        elseif ($page == 'pengambilan_barang') {
            include "sewa/data-pengambilan.php";
        }

        elseif ($page == 'laporan') {
            include "laporan/laporan-sewa.php";
        }
        elseif ($page == 'register') {
            include "../konsumen/komponen/register.php";
        }
        else {
            echo "<h3>Halaman tidak ditemukan.</h3>";
        }
        ?>
    </div>

<!-- Bootstrap JS -->
<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jQuery CDN (wajib untuk DataTables) -->


</body>
</html>