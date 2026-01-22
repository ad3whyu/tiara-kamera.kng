<?php
session_start();
include "../../koneksi/koneksi.php";
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['aksi']) && $_POST['aksi'] == "tambah") {

    $nama_alat = trim($_POST['nama_alat']);
    $kategori = trim($_POST['kategori']);
    $stok = trim($_POST['stok']);
    $harga_sewa = trim($_POST['harga_sewa']);

    // Validasi sederhana
    if (empty($nama_alat) || empty($kategori) || empty($stok) || empty($harga_sewa)) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Semua field wajib diisi.'];
        header("Location: ../../index.php?page=tambah-alat");
        exit;
    }

    // Proses upload gambar
    $gambar = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $nama_file = 'sewa_' . time() . '_' . rand(100,999) . '.' . $ext;
        $target_file = '../../uploads/' . $nama_file;
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            $gambar = $nama_file;
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Upload gambar gagal!'];
            header("Location: ../../index.php?page=tambah-alat");
            exit;
        }
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gambar wajib diupload!'];
        header("Location: ../../index.php?page=tambah-alat");
        exit;
    }

    // Query insert
    $query = "INSERT INTO alat (nama_alat, kategori, stok, harga_sewa_per_hari, gambar) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $nama_alat, $kategori, $stok, $harga_sewa, $gambar);

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
    header("Location: ../../index.php?page=tambah-alat&success=1");

    exit;

} else {
    // Akses langsung tanpa post
    $_SESSION['alert'] = "danger";
    $_SESSION['message'] = $e->getMessage();
    header("Location: ../../index.php?page=tambah-alat");
    exit;
}
?>
