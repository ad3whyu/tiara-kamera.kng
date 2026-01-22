<?php
if (!isset($conn)) {
    include __DIR__ . '/../koneksi/koneksi.php';
}

$pelanggan = mysqli_query($conn, "SELECT COUNT(*) as total FROM pelanggan")->fetch_assoc();
$alat = mysqli_query($conn, "SELECT COUNT(*) as total FROM alat")->fetch_assoc();

//penyewa aktif
$query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM sewa WHERE status = 'dipinjam'");
$data = mysqli_fetch_assoc($query);
$penyewaanAktif = $data['total'];
//total pendapatan
$total = mysqli_query($conn, "SELECT SUM(total_bayar) AS total_pendapatan FROM sewa");
$data = mysqli_fetch_assoc($total);
$totalPendapatan = $data['total_pendapatan'] ?? 0;
$data_chart = [];
$labels = [];
//statistik 
$q_sewa = mysqli_query($conn, "
    SELECT s.*, p.nama
    FROM sewa s
    JOIN pelanggan p ON s.id_pelanggan = p.id
    ORDER BY s.id DESC
");
$q = mysqli_query($conn, "
    SELECT 
    DATE_FORMAT(tanggal_sewa, '%M %Y') AS bulan,
    COUNT(*) AS total
    FROM sewa
    GROUP BY YEAR(tanggal_sewa), MONTH(tanggal_sewa)
    ORDER BY tanggal_sewa DESC
    LIMIT 6
");

while ($row = mysqli_fetch_assoc($q)) {
    $labels[] = $row['bulan'];
    $data_chart[] = $row['total'];
}

// urutkan dari bulan lama ke baru
$labels = array_reverse($labels);
$data_chart = array_reverse($data_chart);

$q_aktifitas = mysqli_query($conn, "
    SELECT 
        s.id,
        p.nama AS pelanggan,
        a.nama_alat,
        s.status,
        s.tanggal_sewa,
        s.tanggal_dikembalikan
    FROM sewa s
    JOIN pelanggan p ON p.id = s.id_pelanggan
    LEFT JOIN sewa_detail sd ON sd.id_sewa = s.id
    LEFT JOIN alat a ON a.id = sd.id_alat
    ORDER BY s.tanggal_kembali DESC
    LIMIT 5
");

// Notifikasi booking online masuk (status 'menunggu')
$notif_booking = mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM booking_online WHERE status = 'menunggu'")->fetch_assoc();
$daftar_booking = mysqli_query($conn, "SELECT * FROM booking_online WHERE status = 'menunggu' ORDER BY created_at DESC LIMIT 10");
$booking_list = [];
while ($row = mysqli_fetch_assoc($daftar_booking)) {
    $booking_list[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sewa Alat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
            min-height: 100vh;
            max-width: 100vw;
            box-sizing: border-box;
        }
        @media (max-width: 1200px) {
            .main-content {
                margin-left: 0;
                padding: 10px;
            }
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            font-weight: 600;
        }
        
        .welcome-card {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
        }
        
        .stat-card .icon {
            font-size: 2.5rem;
            opacity: 0.7;
        }
        
        .stat-card .count {
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        .stat-card .label {
            opacity: 0.8;
            font-size: 0.9rem;
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table th {
            background-color: var(--light-color);
            font-weight: 600;
        }
        
        .badge-rental {
            background-color: #f72585;
        }
        
        .badge-returned {
            background-color: #4cc9f0;
        }
        
        .badge-overdue {
            background-color: #f8961e;
        }
        
        @media (max-width: 768px) {
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.active {
                margin-left: 250px;
            }
        }
    </style>
</head>
<body>
        <div class="content">
        <div class="main-content">
            <!-- Header -->
            <?php include "alert.php"?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Dashboard</h3>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-outline-primary position-relative me-2" data-bs-toggle="modal" data-bs-target="#notifBookingModal">
                        <i class="bi bi-envelope-fill"></i>
                        <?php if($notif_booking['jumlah'] > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $notif_booking['jumlah'] ?>
                        </span>
                        <?php endif; ?>
                    </button>
                </div>
            </div>

            <!-- Welcome Card -->
            <div class="card welcome-card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="card-title">Selamat datang, <?= $_SESSION['admin_nama'];?></h4>
                            <p class="card-text">Anda memiliki <?= $penyewaanAktif?> penyewaan aktif hari ini.</p>
                            <a href="index.php?page=sewa" class="btn btn-light">Lihat Penyewaan</a>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="bi bi-gem" style="font-size: 5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="label">Total Pelanggan</h6>
                                    <h2 class="count"><?= $pelanggan['total']?></h2>
                                </div>
                                <div class="icon text-primary">
                                    <i class="bi bi-people"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="label">Total Alat</h6>
                                    <h2 class="count"><?= $alat['total']?></h2>
                                </div>
                                <div class="icon text-success">
                                    <i class="bi bi-tools"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="label">Penyewaan Aktif</h6>
                                    <h2 class="count"><?= $penyewaanAktif ?></h2>
                                </div>
                                <div class="icon text-warning">
                                    <i class="bi bi-cart-check"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="label">Total Pendapatan</h6>
                                    <h2 class="count"><?= number_format($totalPendapatan, 0, ',', '.') ?></h2>
                                </div>
                                <div class="icon text-info">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Tables -->
            <div class="row mt-4">
                <!-- Chart -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Statistik Penyewaan 6 Bulan Terakhir</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="rentalChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Aktivitas Terkini</h5>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        <?php while ($a = mysqli_fetch_assoc($q_aktifitas)) : ?>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="bi <?= ($a['status'] == 'dikembalikan') ? 'bi-check-circle text-success' : 'bi-cart-plus text-primary' ?> fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0"><?= ucfirst($a['status']) ?></h6>
                                <p class="mb-0 small">
                                    <?= htmlspecialchars($a['pelanggan']) ?> 
                                    <?= $a['status'] == 'dipinjam' ? 'menyewa' : 'mengembalikan' ?> 
                                    <?= htmlspecialchars($a['nama_alat']) ?>
                                </p>
                                <small class="text-muted">
                                    <?php
                                    $tanggal = ($a['status'] == 'dikembalikan') ? $a['tanggal_dikembalikan'] : $a['tanggal_sewa'];
                                    echo date('d-m-Y H:i', strtotime($tanggal));
                                    ?>
                                </small>
                            </div>
                        </div>
                        <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Rentals Table -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Penyewaan Terkini</h5>
                            <a href="index.php?page=laporan" class="btn btn-primary">Lihat Semua</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID Sewa</th>
                                            <th>Pelanggan</th>
                                            <th>Tanggal Sewa</th>
                                            <th>Tanggal Kembali</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        if (mysqli_num_rows($q_sewa) > 0) {
                                            $no = 1;
                                            while ($sewa = mysqli_fetch_array($q_sewa, MYSQLI_ASSOC)) {
                                        ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($sewa['nama']) ?></td>
                                            <td><?= htmlspecialchars(date('d-m-Y', strtotime($sewa['tanggal_sewa']))) ?></td>
                                            <td><?= htmlspecialchars(date('d-m-Y', strtotime($sewa['tanggal_kembali']))) ?></td>
                                            <td>
                                                <?php if ($sewa['status'] == 'dipinjam'): ?>
                                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                                <?php elseif ($sewa['status'] == 'dikembalikan'): ?>
                                                    <span class="badge bg-success">Dikembalikan</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?= htmlspecialchars($sewa['status']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= number_format($sewa['total_bayar'], 0, ',', '.') ?></td>
                                            <td>
                                            <a href="index.php?page=sewa" class="btn btn-sm btn-outline-primary">Detail</a>
                                            </td>
                                        </tr>
                                        <?php 
                                            } 
                                        } else {
                                        ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                                    <p class="mt-2">Belum ada data sewa</p>
                                                    <a href="index.php?page=tambah-sewa" class="btn btn-sm btn-outline-primary">Tambah Sewa Pertama</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Notifikasi Booking Online -->
    <div class="modal fade" id="notifBookingModal" tabindex="-1" aria-labelledby="notifBookingModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="notifBookingModalLabel">Booking Online Menunggu Proses</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-2">
            <?php if($notif_booking['jumlah'] == 0): ?>
              <div class="alert alert-success text-center mb-0">Tidak ada booking online yang menunggu proses.</div>
            <?php else: ?>
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>No HP</th>
                    <th>Email</th>
                    <th width="30%">Tanggal Sewa</th>
                    <th width="30%">Waktu Booking</th>
                    <th>Status Pembayaran</th>
                    <th width="50%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no=1; foreach($booking_list as $row): ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['no_hp']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars(date('d-m-Y', strtotime($row['tanggal_sewa']))) ?></td>
                    <td><?= htmlspecialchars(date('d-m-Y H:i', strtotime($row['created_at']))) ?></td>
                    <td>
                        <?php if ($row['status_pembayaran'] == 'Belum bayar'): ?>
                                <span class="badge bg-secondary text-dark">Belum Bayar</span>
                            <?php elseif ($row['status_pembayaran'] == 'Sudah bayar'): ?>
                                <span class="badge bg-success">Sudah Bayar</span>
                            <?php else: ?>
                                <span class="badge bg-warning"><?= htmlspecialchars($row['status_pembayaran']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                      <button type="button" class="btn btn-outline-info btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#modalDetailBooking<?= $row['id'] ?>">Detail</button>
                      <form method="post" action="komponen/proses-booking-online.php" class="d-inline">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" name="aksi" value="setujui" class="btn btn-outline-success btn-sm mb-1" onclick="return confirm('Setujui booking ini?')">Setujui</button>
                      </form>
                      <form method="post" action="komponen/proses-booking-online.php" class="d-inline">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" name="aksi" value="tolak" class="btn btn-outline-danger btn-sm" onclick="return confirm('Tolak booking ini?')">Tolak</button>
                      </form>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Detail Booking -->
    <?php foreach($booking_list as $row): ?>
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
              <li>Tanggal Sewa: <?= htmlspecialchars(date('d-m-Y', strtotime($row['tanggal_sewa']))) ?></li>
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
                  while ($d = mysqli_fetch_assoc($q_detail)) :
                    $total += $d['subtotal'];
                  ?>
                  <tr>
                    <td><?= htmlspecialchars($d['nama_alat']) ?></td>
                    <td><?= $d['jumlah'] ?></td>
                    <td><?= $d['durasi_hari'] ?></td>
                    <td>Rp <?= number_format($d['subtotal'],0,',','.') ?></td>
                  </tr>
                  <?php endwhile; ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th>Rp <?= number_format($total,0,',','.') ?></th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    const ctx = document.getElementById('rentalChart').getContext('2d');
    const rentalChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Total Sewa',
            data: <?= json_encode($data_chart) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            borderRadius: 5
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                mode: 'index',
                intersect: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }});
</script>
</body>
</html>