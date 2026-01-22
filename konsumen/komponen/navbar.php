<?php
session_start();
$page = isset($_GET['page']) ? $_GET['page'] : 'beranda';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tiara Kamera</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- Sidebar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
      <img src="../assets/image/logo.png" alt="" class="img-fluid" style="max-width: 15%; height: auto;"> Tiara Kamera
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
        <li class="nav-item">
          <a class="nav-link text-white<?php if($page=='beranda') echo ' active'; ?>" href="index.php?page=beranda"><i class="bi bi-house-door me-1"></i>Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white<?php if($page=='alat') echo ' active'; ?>" href="index.php?page=alat"><i class="bi bi-list-ul me-1"></i>Daftar Alat</a>
        </li>
        <li class="nav-item">
          <?php if(isset($_SESSION['pelanggan_nama'])): ?>
            <a class="nav-link text-white<?php if($page=='sewa-sekarang') echo ' active'; ?>" href="index.php?page=sewa-sekarang"><i class="bi bi-bag-plus me-1"></i>Sewa Sekarang</a>
          <?php else: ?>
            <a class="nav-link text-white" href="../komponen/login.php"><i class="bi bi-bag-plus me-1"></i>Sewa Sekarang</a>
          <?php endif; ?>
        </li>
        <?php if(isset($_SESSION['pelanggan_nama'])): ?>
        <li class="nav-item">
          <a class="nav-link text-white<?php if($page=='cek-status') echo ' active'; ?>" href="index.php?page=cek-status"><i class="bi bi-search me-1"></i>Cek Status Booking</a>
        </li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-3">
        <?php if(isset($_SESSION['pelanggan_nama'])): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Hai, <?php echo htmlspecialchars($_SESSION['pelanggan_nama']); ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="../komponen/logout.php">Logout</a></li>
          </ul>
        </li>
        <?php else: ?>
        <li class="nav-item">
          <a class="btn btn-light text-primary fw-bold px-3" href="../komponen/login.php">Login</a>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
