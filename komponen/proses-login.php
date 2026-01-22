<?php
session_start();
include "../koneksi/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Query ke tabel users, cek username dan password langsung
    $query = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Hapus session role lain agar tidak bentrok
        unset($_SESSION['pelanggan_id'], $_SESSION['pelanggan_nama'], $_SESSION['pelanggan_email'], $_SESSION['admin_id'], $_SESSION['admin_username'], $_SESSION['admin_nama']);
        // Cek role
        if ($user['role'] == 'admin') {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_nama'] = $user['nama'];
            $_SESSION['alert'] = 'success';
            $_SESSION['message'] = "Login Admin Berhasil!";
            header("Location: ../index.php");
            exit;
        } elseif ($user['role'] == 'pelanggan') {
            $_SESSION['pelanggan_id'] = $user['id'];
            $_SESSION['pelanggan_nama'] = $user['nama'];
            $_SESSION['pelanggan_email'] = $user['username'];
            $_SESSION['alert'] = 'success';
            $_SESSION['message'] = "Login Berhasil!";
            header("Location: ../konsumen/index.php");
            exit;
        } else {
            $_SESSION['alert'] = 'danger';
            $_SESSION['message'] = "Role tidak dikenali.";
            header("Location: login.php?error=Role tidak dikenali!");
            exit;
        }
    } else {
        // Login gagal
        $_SESSION['alert'] = 'danger';
        $_SESSION['message'] = "Username atau Password salah!";
        header("Location: login.php?error=Username atau Password salah!");
        exit;
    }
} else {
    $_SESSION['alert'] = 'danger';
    $_SESSION['message'] = "Akses tidak valid.";
    header("Location: login.php");
    exit;
}
?>
