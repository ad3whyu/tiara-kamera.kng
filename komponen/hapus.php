<?php
session_start();

try {
    if ($_SERVER['REQUEST_METHOD'] !== "POST") {
        throw new Exception("Invalid request method.");
    }

    if (!isset($_POST['aksi']) || $_POST['aksi'] !== 'hapus') {
        throw new Exception("Invalid action.");
    }

    if (empty($_POST['tabel'])) {
        throw new Exception("Tabel tidak ditentukan.");
    }

    $tabel = $_POST['tabel'];
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        throw new Exception("ID tidak valid.");
    }

    // Daftar tabel yang diperbolehkan
    $allowed_tables = ['sewa', 'alat', 'pelanggan', 'pengambilan_barang'];

    if (!in_array($tabel, $allowed_tables)) {
        throw new Exception("Tabel tidak diizinkan.");
    }

    include "../koneksi/koneksi.php";

    if (!$conn) {
        throw new Exception("Koneksi database gagal.");
    }

    $gambar = null;
    if ($tabel === 'sewa') {
        $stmtSelect = $conn->prepare("SELECT gambar FROM sewa WHERE id = ?");
        $stmtSelect->bind_param("i", $id);
        $stmtSelect->execute();
        $stmtSelect->bind_result($gambar);
        $stmtSelect->fetch();
        $stmtSelect->close();
    }

    // Query hapus
    $stmtDelete = $conn->prepare("DELETE FROM `$tabel` WHERE id = ?");
    $stmtDelete->bind_param("i", $id);

    if ($stmtDelete->execute()) {
        $_SESSION['alert'] = "success";
        $_SESSION['message'] = ucfirst(str_replace('_', ' ', $tabel)) . " berhasil dihapus.";

        // Hapus file gambar kalau ada
        if ($tabel === 'sewa' && $gambar) {
            $filePath = dirname(__DIR__, 3) . "/assets/gambar/" . $gambar;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    } else {
        throw new Exception("Gagal menghapus data: " . $stmtDelete->error);
    }

    $stmtDelete->close();
    $conn->close();

    header("Location: ../index.php?page=". $tabel);
    exit;
    

} catch (Exception $e) {
    $_SESSION['alert'] = "danger";
    $_SESSION['message'] = $e->getMessage();

    $redirect = trim($_POST['redirect'] ?? '');
    if ($redirect === '') {
        $redirect = "index.php?page=" . ($_POST['tabel'] ?? '');
    }

    header("Location: ../../" . $redirect);
    exit;
}
?>
