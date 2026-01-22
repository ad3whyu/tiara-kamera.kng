<?php
include '../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['aksi'])) {
    $id = intval($_POST['id']);
    $aksi = $_POST['aksi'];
    $status = '';
    $pesan = '';
    if ($aksi === 'setujui') {
        $status = 'disetujui';
        $pesan = 'Booking disetujui, silahkan untuk datang ke toko sesuai dengan tanggal sewa';

        // Ambil data booking
        $q = mysqli_query($conn, "SELECT * FROM booking_online WHERE id=$id");
        $booking = mysqli_fetch_assoc($q);

        // Cari id_pelanggan
        $q_pel = mysqli_query($conn, "SELECT id FROM pelanggan WHERE no_hp='".$booking['no_hp']."' LIMIT 1");
        $pel = mysqli_fetch_assoc($q_pel);
        $id_pelanggan = $pel ? $pel['id'] : null;

        if ($id_pelanggan) {
            // Ambil alat pertama untuk durasi
            $q_detail = mysqli_query($conn, "SELECT * FROM booking_online_detail WHERE id_booking=$id LIMIT 1");
            $d_pertama = mysqli_fetch_assoc($q_detail);
            $durasi = $d_pertama ? (int)$d_pertama['durasi_hari'] : 1;
            $tanggal_sewa = $booking['tanggal_sewa'];
            $tanggal_kembali = date('Y-m-d', strtotime("$tanggal_sewa +$durasi days"));
            $status_pembayaran = $booking['status_pembayaran'];
            $metode_pembayaran = $booking['metode_pembayaran'];

            
            // Insert ke tabel sewa (tambahkan tanggal_kembali)
            mysqli_query($conn, "INSERT INTO sewa (id_pelanggan, tanggal_sewa, tanggal_kembali, status, total_bayar, denda, status_pembayaran, metode_pembayaran) VALUES ('$id_pelanggan', '$tanggal_sewa', '$tanggal_kembali', 'dipinjam', 0, 0, '$status_pembayaran', '$metode_pembayaran')");
            $id_sewa = mysqli_insert_id($conn);
            
            // Insert detail alat & update stok
            $q_detail = mysqli_query($conn, "SELECT * FROM booking_online_detail WHERE id_booking=$id");
            while ($d = mysqli_fetch_assoc($q_detail)) {
                mysqli_query($conn, "INSERT INTO sewa_detail (id_sewa, id_alat, jumlah, durasi_hari, subtotal) VALUES ('$id_sewa', '".$d['id_alat']."', '".$d['jumlah']."', '".$d['durasi_hari']."', '".$d['subtotal']."')");
                // Update stok alat sesuai jumlah
                mysqli_query($conn, "UPDATE alat SET stok = stok - " . intval($d['jumlah']) . " WHERE id = " . intval($d['id_alat']));
            }
            // Update total_bayar di tabel sewa
            $q_total = mysqli_query($conn, "SELECT SUM(subtotal) as total FROM sewa_detail WHERE id_sewa=$id_sewa");
            $total = mysqli_fetch_assoc($q_total)['total'];
            mysqli_query($conn, "UPDATE sewa SET total_bayar='$total' WHERE id=$id_sewa");

            mysqli_query($conn, "UPDATE booking_online SET status='disetujui' WHERE id=$id");
        }
    } elseif ($aksi === 'tolak') {
        $status = 'ditolak';
        $pesan = 'Booking ditolak, alat kosong';
    }
    if ($status) {
        $update = mysqli_query($conn, "UPDATE booking_online SET status='$status', pesan='$pesan' WHERE id=$id");
        // Set general alert message regardless of action
        $_SESSION['alert'] = 'success';
        $_SESSION['message'] = 'Status booking telah diupdate';
        if ($update) {
            header('Location: ../index.php?');
            exit;
        } else {
            header('Location: ../index.php');
            exit;
        }
    }
}
header('Location: ../index.php');
exit;
?>
