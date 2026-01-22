<?php
include "../koneksi/koneksi.php";

$page = isset($_GET['page']) ? $_GET['page'] : 'beranda';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tiara Kamera</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>

<!-- Sidebar -->
<?php include "komponen/navbar.php"; ?>
    <!-- Page Content -->
    <div class="container-fluid">
        <?php
        if ($page == 'beranda') {
            include "komponen/beranda.php";

        } elseif ($page == 'alat') {
            include "alat/daftar-alat.php";
        } elseif ($page == 'sewa-sekarang') {
            include "booking-alat/booking.php";
        } elseif ($page == 'edit-alat') {
            include "alat/edit-alat.php";
        }elseif ($page == 'cek-status') {
            include "booking-alat/cek-status.php";
        }elseif ($page == 'update-pembayaran') {
            include "aksi/update-pembayaran.php";
        }