<?php
include '../../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Cek email sudah terdaftar
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$email'");
    if (mysqli_num_rows($cek) > 0) {
        header('Location: ../komponen/register.php?error=Email sudah terdaftar');
        exit;
    }

    $sql = "INSERT INTO users (username, password, nama) VALUES ('$email', '$password', '$nama')";
    if (mysqli_query($conn, $sql)) {
        header('Location: ../komponen/register.php?sukses=Registrasi berhasil, silakan login');
        exit;
    } else {
        header('Location: ../komponen/register.php?error=Gagal mendaftar');
        exit;
    }
}
