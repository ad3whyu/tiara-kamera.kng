<?php 

ini_set('session.cookie_samesite', 'Lax'); 
ini_set('session.cookie_secure', '0'); 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


include '../koneksi/koneksi.php';
require_once '../midtrans/Midtrans.php';

\Midtrans\Config::$serverKey = 'Mid-server-LDUd7iPcq7UdSR0W2-7SQ7a-';
\Midtrans\Config::$isProduction = false;

$id_booking = $_GET['id_booking'] ?? null;

if ($id_booking) {
    // Ambil order_id berdasarkan id_booking
    $query = mysqli_query($conn, "SELECT order_id FROM booking_online WHERE id = $id_booking");
    $data = mysqli_fetch_assoc($query);
    $order_id = $data['order_id'] ?? null;

    $status_json = Midtrans\Transaction::status($order_id); // ini mengembalikan object stdClass

    // Pastikan itu object
    if (is_object($status_json)) {
        if (isset($status_json->transaction_status) && 
            ($status_json->transaction_status == 'capture' || $status_json->transaction_status == 'settlement')) {
            
            mysqli_query($conn, "UPDATE booking_online SET status_pembayaran = 'Sudah Bayar' WHERE id = $id_booking");
            header("Location: ../konsumen/booking-alat/booking.php?status=success&id_booking=" . $id_booking);
            exit();

        } else {
            header("Location: ../index.php?page=sewa-sekarang&status=pending");
            exit();
        }
    } else {
        echo "Gagal mendapatkan status transaksi dari Midtrans.";
    }
} else {
    echo "ID booking tidak ditemukan.";
}
?>
