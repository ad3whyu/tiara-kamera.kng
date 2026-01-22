<?php
require_once '../Midtrans.php';
include '../../koneksi/koneksi.php';

\Midtrans\Config::$serverKey = 'Mid-server-XXX';
\Midtrans\Config::$isProduction = false;

$id_booking = intval($_GET['id_booking']);
$booking = mysqli_query($conn, "SELECT * FROM booking_online WHERE id = $id_booking");
$data = mysqli_fetch_assoc($booking);

// Hitung total
$total = 0;
$items = [];
$result = mysqli_query($conn, "SELECT d.*, a.nama_alat, a.harga_sewa_per_hari 
    FROM booking_online_detail d 
    JOIN alat a ON d.id_alat = a.id 
    WHERE d.id_booking = $id_booking");

while ($row = mysqli_fetch_assoc($result)) {
    $subtotal = $row['durasi_hari'] * $row['jumlah'] * $row['harga_sewa_per_hari'];
    $total += $subtotal;

    $items[] = [
        'id' => $row['id_alat'],
        'price' => $row['harga_sewa_per_hari'],
        'quantity' => $row['jumlah'] * $row['durasi_hari'],
        'name' => $row['nama_alat']
    ];
}

$params = [
    'transaction_details' => [
        'order_id' => 'ORDER-' . $id_booking . '-' . time(),
        'gross_amount' => $total,
    ],
    'customer_details' => [
        'first_name' => $data['nama'],
        'email' => $data['email'],
        'phone' => $data['no_hp'],
    ],
    'item_details' => $items,
];

try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    echo json_encode(['snapToken' => $snapToken]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
