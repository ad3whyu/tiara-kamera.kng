<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar p-3 bg-white shadow-sm rounded">
    <h4 class="text-center text-primary fw-bold mb-4">
        <i class="bi bi-person-fill-gear"></i> Admin Panel
    </h4>

    <ul class="nav flex-column mb-4">
        <li class="nav-item mb-2">
            <a href="index.php?page=dashboard" class="nav-link d-flex align-items-center <?php echo ($page == 'dashboard') ? 'active text-white' : 'text-dark'; ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="index.php?page=alat" class="nav-link d-flex align-items-center <?php echo ($page == 'alat') ? 'active text-white' : 'text-dark'; ?>">
                <i class="bi bi-tools"></i> Data Alat
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="index.php?page=pelanggan" class="nav-link d-flex align-items-center <?php echo ($page == 'pelanggan') ? 'active text-white' : 'text-dark'; ?>">
                <i class="bi bi-people"></i> Data Pelanggan
            </a>
        </li>
        <li class="nav-item mb-2 pb-2">
            <a href="index.php?page=sewa" class="nav-link d-flex align-items-center <?php echo ($page == 'sewa') ? 'active text-white' : 'text-dark'; ?>">
                <i class="bi bi-cart-check"></i> Data Sewa Alat
            </a>
        </li>
        <li class="nav-item mb-2 border-bottom pb-2">
            <a href="index.php?page=pengambilan_barang" class="nav-link d-flex align-items-center <?php echo ($page == 'pengambilan_barang') ? 'active text-white' : 'text-dark'; ?>">
                <i class="bi bi-box"></i> Data Pengambilan
            </a>
        </li>
    </ul>

    <h6 class="sidebar-heading px-3 text-muted text-uppercase fw-bold mb-2">
        <i class="me-2"></i> Laporan
    </h6>
    <ul class="nav flex-column mb-4">
        <li class="nav-item mb-2">
            <a href="index.php?page=laporan" class="nav-link d-flex align-items-center <?php echo ($page == 'laporan') ? 'active text-white' : 'text-dark'; ?>">
                <i class="bi bi-graph-up-arrow me-2"></i> Laporan Sewa
            </a>
        </li>
    </ul>

    <div class="border-top pt-3">
        <a href="komponen/logout.php" class="nav-link text-danger d-flex align-items-center">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>
    </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
