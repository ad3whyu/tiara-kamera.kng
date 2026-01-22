<?php
include '../../../koneksi/koneksi.php';
require_once '../../../midtrans/Midtrans.php';

$order_id = $_GET['order_id'];
$query = mysqli_query($conn, "SELECT * FROM booking_online WHERE order_id = '$order_id'");
$data = mysqli_fetch_assoc($query);
$id_booking = $data['id'];
mysqli_query($conn, "UPDATE booking_online SET status_pembayaran = 'Sudah Bayar' WHERE id = $id_booking");

\Midtrans\Config::$serverKey = 'Mid-server-LDUd7iPcq7UdSR0W2-7SQ7a-';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

$q_total = mysqli_query($conn, "SELECT SUM(subtotal) as total FROM booking_online_detail WHERE id_booking = $id_booking");
$total_row = mysqli_fetch_assoc($q_total);
$total_harga = $total_row['total'] ?? 0;

$transaction_details = [
    'order_id' => $order_id, // Jangan tambah "ORDER-" lagi karena sudah unik
    'gross_amount' => $total_harga,
];

$items = [];
$q_detail = mysqli_query($conn, "SELECT d.*, a.nama_alat FROM booking_online_detail d JOIN alat a ON d.id_alat = a.id WHERE d.id_booking = $id_booking");
while ($row = mysqli_fetch_assoc($q_detail)) {
    $items[] = [
        'id' => $row['id_alat'],
        'price' => $row['subtotal'] / $row['jumlah'],
        'quantity' => $row['jumlah'],
        'name' => $row['nama_alat'] . ' x ' . $row['durasi_hari'] . ' hari'
    ];
}

$customer_details = [
    'first_name' => $data['nama'],
    'email' => $data['email'],
    'phone' => $data['no_hp']
];

$params = [
    'transaction_details' => $transaction_details,
    'item_details' => $items,
    'customer_details' => $customer_details
];

$snapToken = \Midtrans\Snap::getSnapToken($params);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Pembayaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-g84m3Nj4jsEnOmbR"></script>
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-lg">
          <div class="card-body text-center">
            <h4 class="card-title mb-3 text-primary">Selesaikan Pembayaran Anda</h4>
            <p class="card-text text-muted mb-4">
              Klik tombol di bawah untuk menyelesaikan proses pembayaran sewa alat Anda.
            </p>

            <button id="pay-button" class="btn btn-success btn-lg px-4">
              <i class="bi bi-wallet2"></i> Bayar Sekarang
            </button>

            <div class="mt-4 text-muted" style="font-size: 0.9rem;">
              Tiara Kamera &copy; 2022
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <script type="text/javascript">
    document.getElementById('pay-button').addEventListener('click', function () {
      snap.pay('<?php echo $snapToken; ?>', {
        onSuccess: function(result){
          const idBooking = '<?php echo $id_booking; ?>';
          window.location.href = 'http://localhost/PROJECT-NATIVE/sewa-alat.kng/konsumen/index.php?page=sewa-sekarang&status=success';
        },
        onPending: function(result){
          window.location.href = 'http://localhost/PROJECT-NATIVE/sewa-alat.kng/konsumen/index.php?page=sewa-sekarang&status=pending';
        },
        onError: function(result){
          alert("Terjadi kesalahan dalam proses pembayaran. Silakan coba lagi.");
        }
      });
    });
  </script>
</body>
</html>
