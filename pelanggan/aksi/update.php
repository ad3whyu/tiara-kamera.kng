<?php
session_start();
include "../../koneksi/koneksi.php";
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['aksi']) && $_POST['aksi'] == "edit") {

    $nama = trim($_POST['nama']);
    $nope = trim($_POST['nope']);
    $alamat = trim($_POST['alamat']);
    $id = intval($_POST['id'] ?? 0);

    // Validasi sederhana
    if (empty($nama) || empty($nope) || empty($alamat)) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Semua field wajib diisi.'];
        header("Location: ../../index.php?page=tambah-pelanggan");
        exit;
    }

    // Query insert
    $query = "UPDATE pelanggan SET  nama = ?, no_hp = ?, alamat = ? WHERE id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $nama, $nope, $alamat, $id);

    if ($stmt->execute()) {
        $_SESSION['alert'] = "success";
        $_SESSION['message'] = "Data berhasil diedit!";
    } else {
        @unlink($target_file);
        throw new Exception("Data gagal diedit: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    // Redirect ke halaman testimoni
    header("Location: ../../index.php?page=edit-pelanggan&id=" . base64_encode($id) . "&success=1");

    exit;

} else {
    // Akses langsung tanpa post
    $_SESSION['alert'] = "danger";
    $_SESSION['message'] = $e->getMessage();
    header("Location: ../../index.php?page=edit-pelanggan&id=" . base64_encode($id));
    exit;
}
?>
