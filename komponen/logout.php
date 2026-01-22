<?php
session_start();

// Hapus session admin
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);
unset($_SESSION['admin_nama']);
// Hapus session pelanggan
unset($_SESSION['pelanggan_id']);
unset($_SESSION['pelanggan_nama']);
unset($_SESSION['pelanggan_email']);
// Hapus session alert/message
unset($_SESSION['alert']);
unset($_SESSION['message']);

// Redirect ke landing page utama
header('Location: /PROJECT-NATIVE/sewa-alat.kng/konsumen/index.php');
exit;
?>
