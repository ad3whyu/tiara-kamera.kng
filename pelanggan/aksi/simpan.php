<?php
session_start();
include "../../koneksi/koneksi.php";
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['aksi']) && $_POST['aksi'] == "tambah") {

    $nama = trim($_POST['nama']);
    $nope = trim($_POST['nope']);
    $alamat = trim($_POST['alamat']);

    // Validasi sederhana
    if (empty($nama) || empty($nope) || empty($alamat)) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Semua field wajib diisi.'];
        header("Location: ../../index.php?page=tambah-pellanggan");
        exit;
    }

    // Query insert
    $query = "INSERT INTO pelanggan (nama, no_hp, alamat) VALUES (?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $nama, $nope, $alamat);

    if ($stmt->execute()) {
        $_SESSION['alert'] = "success";
        $_SESSION['message'] = "Data berhasil ditambahkan!";
    } else {
        @unlink($target_file);
        throw new Exception("Data gagal ditambahkan: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    // Redirect ke halaman testimoni
    header("Location: ../../index.php?page=tambah-pelanggan");

    exit;

} else {
    // Akses langsung tanpa post
    $_SESSION['alert'] = "danger";
    $_SESSION['message'] = $e->getMessage();
    header("Location: ../../index.php?page=tambah-pelanggan");
    exit;
}
?>
