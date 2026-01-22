<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    header('Location: ../komponen/login.php?error=Silakan login terlebih dahulu');
    exit;
}
?>