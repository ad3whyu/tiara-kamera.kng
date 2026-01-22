<?php
include '../koneksi/koneksi.php';
header('Content-Type: application/json');
$id = intval($_GET['id'] ?? 0);
$data = null;
if ($id > 0) {
  $q = mysqli_query($conn, "SELECT harga_sewa_per_hari FROM alat WHERE id='$id'");
  $data = mysqli_fetch_assoc($q);
}
echo json_encode($data);
