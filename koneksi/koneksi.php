<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_sewa_alat2";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

return $conn;
