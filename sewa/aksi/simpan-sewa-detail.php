<?php
session_start();
include "../../koneksi/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_sewa      = $_POST['id_sewa'] ?? '';
    $id_alat      = $_POST['id_alat'] ?? '';
    $jumlah       = $_POST['jumlah'] ?? 0;
    $durasi_hari  = $_POST['durasi_hari'] ?? 0;
    $subtotal = intval($_POST['subtotal'] ?? 0);

    // Validasi input
    if (empty($id_sewa) || empty($id_alat) || $jumlah < 1 || $durasi_hari < 1) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Input tidak valid.'];
        header("Location: ../../index.php?page=sewa-detail&id=$id_sewa");
        exit;
    }

    $stmt = $conn->prepare("SELECT stok FROM alat WHERE id = ?");
    $stmt->bind_param("i", $id_alat);
    $stmt->execute();
    $result = $stmt->get_result();
    $alat = $result->fetch_assoc();
    $stmt->close();


    if (!$alat) {
        $_SESSION['alert'] = "danger";
        $_SESSION['message'] = 'Alat tidak ditemukan.';
        header("Location: ../../index.php?page=sewa-detail&id=$id_sewa");
        exit;
    }

    if ($jumlah > $alat['stok']) {
        $_SESSION['alert'] = "danger";
        $_SESSION['message'] = "Jumlah melebihi stok yang tersedia ({$alat['stok']}).";
        header("Location: ../../index.php?page=sewa-detail&id=$id_sewa");
        exit;
    }

    // Insert ke sewa_detail pakai bind_param
    $query = "INSERT INTO sewa_detail (id_sewa, id_alat, jumlah, durasi_hari, subtotal)
                VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiid", $id_sewa, $id_alat, $jumlah, $durasi_hari, $subtotal);

    if ($stmt->execute()) {

        // Kurangi stok alat
        $q_update_stok = $conn->prepare("UPDATE alat SET stok = stok - ? WHERE id = ?");
        $q_update_stok->bind_param("ii", $jumlah, $id_alat);
        $q_update_stok->execute();
        $q_update_stok->close();

        
        // Update total_bayar di tabel sewa
        $q_update_total = $conn->prepare("
            UPDATE sewa 
            SET total_bayar = (
                SELECT COALESCE(SUM(subtotal), 0) 
                FROM sewa_detail 
                WHERE id_sewa = ?
            )
            WHERE id = ?
        ");
        $q_update_total->bind_param("ii", $id_sewa, $id_sewa);
        $q_update_total->execute();
        $q_update_total->close();


        $_SESSION['alert'] = "success";
        $_SESSION['message'] = "Data berhasil ditambahkan!";

    } else {
        $_SESSION['alert'] = "danger";
        $_SESSION['message'] = $e->getMessage();
    }

    $stmt->close();
    $conn->close();

    header("Location: ../../index.php?page=sewa-detail&id=$id_sewa");
    exit;

} else {
    $_SESSION['alert'] = "danger";
    $_SESSION['message'] ='Metode tidak diperbolehkan.';
    header("Location: ../../index.php?page=sewa");
    exit;
}
