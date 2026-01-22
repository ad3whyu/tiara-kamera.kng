<?php
include '../koneksi/koneksi.php';

// Ambil semua kategori unik
$data_kategori = [];
$q_kategori = mysqli_query($conn, "SELECT DISTINCT kategori FROM alat ORDER BY kategori ASC");
while ($kat = mysqli_fetch_assoc($q_kategori)) {
    $data_kategori[] = $kat['kategori'];
}
?>
<style>
.alat-img {
  width: 100%;
  height: 180px;
  object-fit: contain;
  object-position: center;
  background: #ffffff;
  border-radius: 0.5rem 0.5rem 0 0;
  padding: 10px;
}
</style>
<div class="container py-4">
  <h2 class="mb-4 fw-bold text-primary"><i class="bi bi-list-ul me-2"></i>Daftar Alat Fotografi</h2>
  <?php foreach ($data_kategori as $kategori): ?>
    <h4 class="mt-4 text-secondary border-bottom pb-1"><i class="bi bi-tags"></i> <?php echo htmlspecialchars($kategori); ?></h4>
    <div class="row g-4">
      <?php
      $q_alat = mysqli_query($conn, "SELECT * FROM alat WHERE kategori='" . mysqli_real_escape_string($conn, $kategori) . "'");
      $ada = false;
      while ($row = mysqli_fetch_assoc($q_alat)):
        $ada = true;
      ?>
        <div class="col-md-4 col-lg-3">
          <div class="card h-100 shadow-sm">
            <img src="/PROJECT-NATIVE/sewa-alat.kng/uploads/<?php echo htmlspecialchars($row['gambar'] ?? ''); ?>" class="card-img-top alat-img" alt="<?php echo htmlspecialchars($row['nama_alat']); ?>">
            <div class="card-body">
              <h5 class="card-title fw-semibold mb-2"><?php echo htmlspecialchars($row['nama_alat']); ?></h5>
              <h6 class="mb-2 fw-normal">
                <i class="fas fa-tag" style="color: #e67e22;"></i>
                Rp <?= number_format($row['harga_sewa_per_hari'], 0, ',', '.'); ?> / hari</strong>
              </h6>
              <p class="mb-1">Stok: <span class="fw-bold"><?php echo $row['stok']; ?></span></p>
                <?php if ($row['stok'] == 0): ?>
                  <span class="badge bg-danger" disabled>Tidak Tersedia</span>
                <?php else: ?>
                  <span class="badge bg-success" disabled>Tersedia</span>
                <?php endif; ?>
              </p>
            </div>
            <div class="card-footer bg-white border-0 text-center pb-3">
              <?php if ($row['stok'] > 0): ?>
                <?php if (isset($_SESSION['pelanggan_nama'])): ?>
                  <a href="index.php?page=sewa-sekarang&id=<?php echo $row['id_alat'] ?? $row['id']; ?>" class="btn btn-primary btn-sm w-100 mt-2"><i class="bi bi-cart-plus me-1"></i> Sewa Sekarang</a>
                <?php else: ?>
                  <a href="../komponen/login.php" class="btn btn-primary btn-sm w-100 mt-2"><i class="bi bi-cart-plus me-1"></i> Sewa Sekarang</a>
                <?php endif; ?>
              <?php else: ?>
                <button class="btn btn-secondary btn-sm w-100 mt-2" disabled><i class="bi bi-cart-x me-1"></i> Tidak Bisa Dipesan</button>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
      <?php if (!$ada): ?>
        <div class="col-12 text-muted fst-italic">Belum ada alat di kategori ini.</div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>
