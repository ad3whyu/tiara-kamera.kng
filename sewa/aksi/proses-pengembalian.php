<?php
session_start();
include "../../koneksi/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $id_sewa = $_POST['id_sewa'];
    $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
    $denda = $_POST['denda'];
    $status_alat = $_POST['status_alat'];

    // Update status sewa
    $update = mysqli_query($conn, "
        UPDATE sewa
        SET status = 'dikembalikan',
            tanggal_dikembalikan = '$tanggal_pengembalian',
            denda = '$denda',
            status_alat = '$status_alat'
        WHERE id = '$id_sewa'
    ");

    if ($update) {
        // Restore stock alat:
        $q_detail = mysqli_query($conn, "SELECT * FROM sewa_detail WHERE id_sewa = '$id_sewa'");
        while ($d = mysqli_fetch_array($q_detail, MYSQLI_ASSOC)) {
            $id_alat = $d['id_alat'];
            $qty     = $d['jumlah'];

            // Tambahkan stock kembali
            mysqli_query($conn, "
                UPDATE alat
                SET stok = stok + $qty
                WHERE id = '$id_alat'
            ");
        }

        $_SESSION['alert_success'] = 'Pengembalian berhasil diproses & stock alat diperbarui.';
    } else {
        $_SESSION['alert_error'] = 'Gagal memproses pengembalian.';
    }

} else {
    $_SESSION['alert_error'] = 'Akses tidak valid.';
}

header("Location: ../../index.php?page=sewa");
exit();
?>
