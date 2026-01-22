<?php
session_start();
if (!isset($_SESSION['admin_username']) || !isset($_SESSION['admin_nama'])) {
    header("Location: komponen/login.php");
    exit;
}
?>
