<?php
// query data alat
$q_alat = mysqli_query($conn, "SELECT * FROM alat ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Alat - Admin</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/datatables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" />
</head>
<body>
    <div class="main">
        <div class="container-fluid">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Data Alat</h1>
            </div>
            <?php include "komponen/alert.php"; ?>
            <!-- Data Alat -->
            <div class="card mt-3 mb-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Daftar Alat</h5>
                        <a href="index.php?page=tambah-alat" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Alat
                        </a>
                    </div>
                    <table class="table table-striped table-hover" id="alatTable">
                        <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Alat</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Harga Sewa / Hari</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                        if (mysqli_num_rows($q_alat) > 0) {
                            $no = 1;
                            while ($alat = mysqli_fetch_array($q_alat, MYSQLI_ASSOC)) {
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($alat['nama_alat']) ?></td>
                            <td><?= htmlspecialchars($alat['kategori']) ?></td>
                            <td><?= $alat['stok'] ?></td>
                            <td>Rp <?= number_format($alat['harga_sewa_per_hari'], 0, ',', '.') ?></td>
                            <td>
                                <?php 
                                $gambarPath = __DIR__ . '/../uploads/' . $alat['gambar'];
                                $gambarUrl = '/PROJECT-NATIVE/sewa-alat.kng/uploads/' . htmlspecialchars($alat['gambar']);
                                ?>
                                <?php if(!empty($alat['gambar']) && file_exists($gambarPath)): ?>
                                    <img src="<?= $gambarUrl ?>"
                                        class="img-thumbnail"
                                        width="60"
                                        height="60"
                                        style="object-fit:cover;cursor:pointer;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#imageModal<?= $alat['id'] ?>">
                                    <!-- Modal preview -->
                                    <div class="modal fade" id="imageModal<?= $alat['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><?= htmlspecialchars($alat['nama_alat']) ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="<?= $gambarUrl ?>" class="img-fluid" style="max-height:400px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span class="badge bg-secondary">No Image</span>
                                    <?php if(!empty($alat['gambar'])): ?>
                                        <div>
                                            <small>Nama file: <?= htmlspecialchars($alat['gambar']) ?></small><br>
                                            <small>File ada? <?= file_exists($gambarPath) ? 'YA' : 'TIDAK' ?></small><br>
                                            <small>Path: <?= $gambarPath ?></small><br>
                                            <small>Current dir: <?= getcwd() ?></small>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="index.php?page=edit-alat&id=<?= base64_encode($alat['id']) ?>" 
                                        class="btn btn-sm btn-outline-success" 
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="hapusData(<?= $alat['id'] ?>, 'alat', '<?= htmlspecialchars($alat['nama_alat']) ?>')">
                                            <i class="bi bi-trash"></i>
                                    </button>
                                </div>                               
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                        ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                    <p class="mt-2">Belum ada Data Alat</p>
                                    <a href="index.php?page=tambah-alat" class="btn btn-primary">Tambah Alat Pertama</a>
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

<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#alatTable').DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});
function hapusData(id, tabel, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus Alat "' + nama + '"?\nData yang dihapus tidak dapat dikembalikan.')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'komponen/hapus.php';
        
        var inputAksi = document.createElement('input');
        inputAksi.type = 'hidden';
        inputAksi.name = 'aksi';
        inputAksi.value = 'hapus';
        form.appendChild(inputAksi);
        
        var inputTabel = document.createElement('input');
        inputTabel.type = 'hidden';
        inputTabel.name = 'tabel';
        inputTabel.value = tabel;
        form.appendChild(inputTabel);
        
        var inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = 'id';
        inputId.value = id;
        form.appendChild(inputId);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
</body>
</html>
