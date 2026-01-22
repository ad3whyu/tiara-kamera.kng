<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm p-4">
                    <h3 class="text-center mb-4 fw-bold">Register</h3>
                    <h5 class="text-center mb-4 fw-bold text-info-emphasis">TIARA SEWA ALAT PHOTOGRAPY</h5>
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger text-center"><?php echo htmlspecialchars($_GET['error']); ?></div>
                    <?php elseif (isset($_GET['sukses'])): ?>
                        <div class="alert alert-success text-center"><?php echo htmlspecialchars($_GET['sukses']); ?></div>
                    <?php endif; ?>
                    <form method="post" action="../aksi/proses-register.php">
                        <div class="mb-3 text-start">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">DAFTAR</button>
                        </div>
                        <div class="mt-3 text-center">
                            <span>Sudah punya akun? <a href="../../komponen/login.php">Login</a></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>