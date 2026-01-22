<?php
include '../koneksi/koneksi.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil data booking berdasarkan email user login
$booking_list = [];
if (isset($_SESSION['pelanggan_email'])) {
    $email = mysqli_real_escape_string($conn, $_SESSION['pelanggan_email']);
    $query = mysqli_query($conn, "SELECT * FROM booking_online WHERE email='$email' ORDER BY id DESC");

    while ($row = mysqli_fetch_assoc($query)) {
        $booking_list[] = $row;
    }
}
?>

<div class="container py-5">
  <h2 class="mb-4 fw-bold text-primary"><i class="bi bi-search me-2"></i>Cek Status Booking</h2>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle table-striped">
      <thead class="table-primary text-center align-middle">
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>No HP</th>
          <th>Email</th>
          <th>Tanggal Sewa</th>
          <th>Status</th>
          <th width="30%">Pesan</th>
          <th>Status Pembayaran</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($booking_list)): ?>
          <tr>
            <td colspan="8" class="text-center text-muted py-4">
              <div class="mb-3">Belum ada data booking.</div>
              <a href="index.php?page=sewa-sekarang" class="btn btn-primary">
                <i class="bi bi-bag-plus me-1"></i> Sewa Sekarang
              </a>
            </td>
          </tr>
        <?php else: $no = 1; foreach ($booking_list as $row): ?>
          <tr>
            <td class="text-center fw-bold"><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['no_hp']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= date('d-m-Y', strtotime($row['tanggal_sewa'])) ?></td>
            <td class="text-center">
              <?php
                $status = $row['status'];
                if ($status == 'menunggu') {
                  echo '<span class="badge bg-warning text-dark px-3 py-2 fs-6"><i class="bi bi-hourglass-split me-1"></i>Menunggu</span>';
                } elseif ($status == 'diterima' || $status == 'disetujui') {
                  echo '<span class="badge bg-success px-3 py-2 fs-6"><i class="bi bi-check-circle me-1"></i>Diterima</span>';
                } elseif ($status == 'ditolak') {
                  echo '<span class="badge bg-danger px-3 py-2 fs-6"><i class="bi bi-x-circle me-1"></i>Ditolak</span>';
                } else {
                  echo '<span class="badge bg-secondary px-3 py-2 fs-6">' . htmlspecialchars($status) . '</span>';
                }
              ?>
            </td>
            <td><?= htmlspecialchars($row['pesan']) ?></td>
            <td><?= htmlspecialchars($row['status_pembayaran'])?></td>
            <td>
              <button type="button" class="btn btn-outline-info btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#modalDetailBooking<?= $row['id'] ?>">
                Detail
              </button>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Detail Booking -->
<?php foreach ($booking_list as $row): ?>
  <div class="modal fade" id="modalDetailBooking<?= $row['id'] ?>" tabindex="-1" aria-labelledby="modalDetailBookingLabel<?= $row['id'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDetailBookingLabel<?= $row['id'] ?>">Detail Booking #<?= $row['id'] ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h6 class="fw-bold mb-2">Identitas Pemesan</h6>
          <ul class="mb-3">
            <li>Nama: <b><?= htmlspecialchars($row['nama']) ?></b></li>
            <li>Email: <?= htmlspecialchars($row['email']) ?></li>
            <li>No HP: <?= htmlspecialchars($row['no_hp']) ?></li>
            <li>Tanggal Sewa: <?= date('d-m-Y', strtotime($row['tanggal_sewa'])) ?></li>
            <li>Waktu Pengambilan: <?= htmlspecialchars($row['waktu_pengambilan']) ?></li>
            <li>Metode Pembayaran: <?= htmlspecialchars($row['metode_pembayaran']) ?></li>
            <li>Status Pembayaran: <?= htmlspecialchars($row['status_pembayaran']) ?></li>
          </ul>

          <h6 class="fw-bold mb-2">Alat yang Akan Disewa</h6>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Nama Alat</th>
                  <th>Jumlah</th>
                  <th>Durasi (Hari)</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $q_detail = mysqli_query($conn, "SELECT d.*, a.nama_alat FROM booking_online_detail d JOIN alat a ON d.id_alat = a.id WHERE d.id_booking = " . intval($row['id']));
                  $total = 0;
                  while ($d = mysqli_fetch_assoc($q_detail)) {
                    $total += $d['subtotal'];
                ?>
                <tr>
                  <td><?= htmlspecialchars($d['nama_alat']) ?></td>
                  <td><?= $d['jumlah'] ?></td>
                  <td><?= $d['durasi_hari'] ?></td>
                  <td>Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3" class="text-end">Total</th>
                  <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>
