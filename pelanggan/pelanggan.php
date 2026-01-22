<?php
// query data alat
$data = mysqli_query($conn, "SELECT * FROM pelanggan ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Pelanggan - Admin</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/datatables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" />
</head>
<body>
    <div class="main">
        <div class="container-fluid">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Data Pelanggan</h1>
            </div>
            <?php include "komponen/alert.php"; ?>
            <!-- Data Alat -->
            <div class="card mt-3 mb-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Daftar Pelanggan</h5>
                        <a href="index.php?page=tambah-pelanggan" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Pelanggan
                        </a>
                    </div>
                    <table class="table table-striped table-hover" id="alatTable">
                        <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Nomor HP</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                        if (mysqli_num_rows($data) > 0) {
                            $no = 1;
                            while ($d = mysqli_fetch_array($data, MYSQLI_ASSOC)) {
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($d['nama']) ?></td>
                            <td><?= htmlspecialchars($d['no_hp']) ?></td>
                            <td><?= $d['alamat'] ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="index.php?page=edit-pelanggan&id=<?= base64_encode($d['id']) ?>" 
                                        class="btn btn-sm btn-outline-success" 
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="hapusData(<?= $d['id'] ?>, 'pelanggan', '<?= htmlspecialchars($d['nama']) ?>')">
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
                            <td colspan="6" class="text-center py-4">
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
    if (confirm('Apakah Anda yakin ingin menghapus Pelanggan "' + nama + '"?\nData yang dihapus tidak dapat dikembalikan.')) {
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
