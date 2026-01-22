<?php
session_start();
include "../../koneksi/koneksi.php";
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['aksi']) && $_POST['aksi'] == "edit") {

    $nama_alat = trim($_POST['nama_alat']);
    $kategori = trim($_POST['kategori']);
    $stok = trim($_POST['stok']);
    $harga_sewa = trim($_POST['harga_sewa']);
    $id = intval($_POST['id'] ?? 0);

    // Validasi sederhana
    if (empty($nama_alat) || empty($kategori) || empty($stok) || empty($harga_sewa)) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Semua field wajib diisi.'];
        header("Location: ../../index.php?page=edit-alat&id=" . base64_encode($id));
        exit;
    }

    // Ambil gambar lama
    $gambar_lama = null;
    $q = $conn->query("SELECT gambar FROM alat WHERE id='$id'");
    if ($q && $row = $q->fetch_assoc()) {
        $gambar_lama = $row['gambar'];
    }

    $gambar = $gambar_lama;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $nama_file = 'sewa_' . time() . '_' . rand(100,999) . '.' . $ext;
        $target_file = '../../uploads/' . $nama_file;
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            // Hapus gambar lama jika ada
            if ($gambar_lama && file_exists('../../uploads/' . $gambar_lama)) {
                @unlink('../../uploads/' . $gambar_lama);
            }
            $gambar = $nama_file;
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Upload gambar gagal!'];
            header("Location: ../../index.php?page=edit-alat&id=" . base64_encode($id));
            exit;
        }
    }

    // Query update
    $query = "UPDATE alat SET nama_alat = ?, kategori = ?, stok = ?, harga_sewa_per_hari = ?, gambar = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $nama_alat, $kategori, $stok, $harga_sewa, $gambar, $id);

    if ($stmt->execute()) {
        $_SESSION['alert'] = "success";
        $_SESSION['message'] = "Data berhasil diedit!";
    } else {
        throw new Exception("Data gagal diedit: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    header("Location: ../../index.php?page=edit-alat&id=" . base64_encode($id) . "&success=1");
    exit;

} else {
    $_SESSION['alert'] = "danger";
    $_SESSION['message'] = $e->getMessage();
    header("Location: ../../index.php?page=edit-alat&id=" . base64_encode($id));
    exit;
}
?>
