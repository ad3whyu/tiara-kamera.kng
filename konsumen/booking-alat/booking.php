<?php
include '../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['identitas'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat'] ?? '');
    $tanggal_sewa = mysqli_real_escape_string($conn, $_POST['tanggal_sewa']);
    $waktu_ambil = mysqli_real_escape_string($conn, $_POST['waktu_ambil']);
    $alat = $_POST['alat'] ?? [];
    $jumlah = $_POST['jumlah'] ?? [];
    $durasi = $_POST['durasi'] ?? [];
    $subtotal = $_POST['subtotal'] ?? [];
    $metode = $_POST['metode_pembayaran'] ?? 'Cash';
    $order_id = rand();

    // Simpan ke tabel pelanggan jika belum ada
    $cek_pelanggan = mysqli_query($conn, "SELECT id FROM pelanggan WHERE nama='$nama'");
    if (mysqli_num_rows($cek_pelanggan) == 0) {
        mysqli_query($conn, "INSERT INTO pelanggan (nama, no_hp, alamat) VALUES ('$nama', '$no_hp', '$alamat')");
    }

    // Simpan booking utama
    $q = "INSERT INTO booking_online (nama, no_hp, email, tanggal_sewa, waktu_pengambilan, status, metode_pembayaran, status_pembayaran, order_id) 
      VALUES ('$nama', '$no_hp', '$email', '$tanggal_sewa','$waktu_ambil', 'Menunggu', '$metode', 'Belum bayar', $order_id)";

    if (mysqli_query($conn, $q)) {
        // Setelah berhasil insert ke booking_online, ambil ID-nya di sini
        $id_booking = mysqli_insert_id($conn);

        // Simpan detail alat
        foreach ($alat as $i => $id_alat) {
            $id_alat = intval($id_alat);
            $jml = intval($jumlah[$i]);
            $drs = intval($durasi[$i]);
            $sub = intval(str_replace(['Rp', ',', '.', ' '], '', $subtotal[$i] ?? 0));

            // Validasi bahwa alat memang ada di tabel 'alat'
            $cek = mysqli_query($conn, "SELECT id FROM alat WHERE id = $id_alat");
            if (mysqli_num_rows($cek) > 0) {
                mysqli_query($conn, "INSERT INTO booking_online_detail (id_booking, id_alat, jumlah, durasi_hari, subtotal) 
                                    VALUES ('$id_booking', '$id_alat', '$jml', '$drs', '$sub')");
            }
        }

        if ($metode === 'Transfer') {
            header("Location: ../midtrans/examples/snap/checkout-process-simple-version.php?order_id=$order_id");
            exit;
        } else {
              header("Location: index.php?page=sewa-sekarang&status=success");
              exit;
          }
    } else {
        echo "<script>alert('Gagal menyimpan booking.'); window.history.back();</script>";
    }
}

$sukses = '';
if (isset($_GET['status']) && $_GET['status'] == 'success') {
  $sukses = 'Booking berhasil dikirim! Silakan tunggu konfirmasi admin.';
}

// Ambil data alat untuk form
$id_alat_terpilih = isset($_GET['id']) ? intval($_GET['id']) : 0;
$q_alat = mysqli_query($conn, "SELECT * FROM alat WHERE stok > 0 ORDER BY nama_alat ASC");
?>
<style>
.select2-container--default .select2-selection--single {
  height: 38px;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
  line-height: 36px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
  height: 36px;
}
</style>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-g84m3Nj4jsEnOmbR"></script>
<div class="container py-5">
  <h2 class="mb-4 fw-bold text-primary"><i class="bi bi-bag-plus me-2"></i>Form Booking Sewa Alat</h2>
  <?php if (!empty($sukses)): ?>
    <div class="alert alert-success">Booking berhasil dikirim! Silakan tunggu konfirmasi admin.</div>
    <?php endif; ?>
  <form method="post">
    <input type="hidden" name="identitas" value="1">
    <div class="card mb-4">
      <div class="card-header bg-primary text-white">Identitas Diri</div>
      <div class="card-body row g-3">
        <div class="col-md-6">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama lengkap Anda" value="<?php echo isset($_SESSION['pelanggan_nama']) ? htmlspecialchars($_SESSION['pelanggan_nama']) : ''; ?>" readonly>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required value="<?php echo isset($_SESSION['pelanggan_email']) ? htmlspecialchars($_SESSION['pelanggan_email']) : ''; ?>" readonly>
        </div>
        <div class="col-md-6">
          <label class="form-label">No. HP</label>
          <input type="text" name="telepon" class="form-control" required placeholder="Masukkan nomor yang bisa dihubungi">
        </div>
        <div class="col-md-6">
            <label for="tanggal_sewa" class="form-label">Tanggal Sewa</label>
            <input type="date" class="form-control" id="tanggal_sewa" name="tanggal_sewa" required>
        </div>
        <div class="col-md-6">
            <label for="waktu_ambil" class="form-label">Waktu Pengambilan</label>
              <select class="form-select" name="waktu_ambil" id="waktu_ambil" required>
                  <option value="">-- Pilih Waktu --</option>
                      <option value="08.00 WIB">08.00 WIB</option>
                      <option value="10.00 WIB">10.00 WIB</option>
                      <option value="13.00 WIB">13.00 WIB</option>
                      <option value="15.00 WIB">15.00 WIB</option>
                      <option value="19.00 WIB">19.00 WIB</option>
              </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Alamat</label>
          <textarea name="alamat" class="form-control" rows="2" required placeholder="Masukkan alamat yang lengkap"></textarea>
        </div>
      </div>
    </div>
  <div class="card mb-4">
      <div class="card-header bg-primary text-white">Pilih Alat & Jumlah</div>
      <div class="card-body">
        
        <div id="alat-list">
          <div class="row g-2 alat-item mb-2 align-items-center">
            <div class="col-md-3">
              <label class="form-label">Pilih Alat</label>
              <select name="alat[]" class="form-select alat-select pilih-gambar">
                <option value="">-- Pilih Alat --</option>
                <?php 
                  $q_alat = mysqli_query($conn, "SELECT id, nama_alat, harga_sewa_per_hari, stok, gambar FROM alat WHERE stok > 0 ORDER BY nama_alat ASC");
                  while ($a = mysqli_fetch_assoc($q_alat)): 
                    $pathGambar = '/PROJECT-NATIVE/sewa-alat.kng/uploads/' . htmlspecialchars($a['gambar']);
                ?>
                  <option value="<?php echo $a['id']; ?>" data-harga="<?php echo $a['harga_sewa_per_hari']; ?>" data-gambar="<?php echo $pathGambar; ?>">
                    <?php echo htmlspecialchars($a['nama_alat']); ?> (Stok: <?php echo $a['stok']; ?>)
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="col-md-2">
              <label class="form-label">Harga Sewa / Hari</label>
              <input type="text" class="form-control harga-sewa" readonly>
            </div>
            <div class="col-md-2">
              <label class="form-label">Jumlah</label>
              <input type="number" name="jumlah[]" class="form-control jumlah-input" min="1" placeholder="Jumlah" >
            </div>
            <div class="col-md-2">
              <label class="form-label">Durasi (Hari)</label>
              <input type="number" name="durasi[]" class="form-control durasi-input" min="1" placeholder="Durasi" >
            </div>
            <div class="col-md-2">
              <label class="form-label">Subtotal (Rp)</label>
              <input type="text" name="subtotal[]" class="form-control subtotal" readonly>
            </div>
            <div class="col-md-1">
              <label class="form-label invisible">Aksi</label>
              <button type="button" class="btn btn-danger btn-remove-alat w-100">X</button>
            </div>
          </div>
        </div>

        <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="btn-tambah-alat">+ Tambah Alat</button>
      </div>
    </div>

    <div id="alat-item-template" style="display: none;">
      <div class="row g-2 alat-item mb-2 align-items-center">
        <div class="col-md-3">
          <label class="form-label">Pilih Alat</label>
          <select name="alat[]" class="form-select alat-select pilih-gambar">
            <option value="">-- Pilih Alat --</option>
            <?php 
              if (isset($q_alat) && mysqli_num_rows($q_alat) > 0) {
                mysqli_data_seek($q_alat, 0); 
                while ($a = mysqli_fetch_assoc($q_alat)): 
                  $pathGambar = '/PROJECT-NATIVE/sewa-alat.kng/uploads/' . htmlspecialchars($a['gambar']);
            ?>
              <option value="<?php echo $a['id']; ?>" data-harga="<?php echo $a['harga_sewa_per_hari']; ?>" data-gambar="<?php echo $pathGambar; ?>">
                <?php echo htmlspecialchars($a['nama_alat']); ?> (Stok: <?php echo $a['stok']; ?>)
              </option>
            <?php 
                endwhile; 
              }
            ?>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Harga Sewa / Hari</label>
          <input type="text" class="form-control harga-sewa" readonly>
        </div>
        <div class="col-md-2">
          <label class="form-label">Jumlah</label>
          <input type="number" name="jumlah[]" class="form-control jumlah-input" min="1" placeholder="Jumlah" >
        </div>
        <div class="col-md-2">
          <label class="form-label">Durasi (Hari)</label>
          <input type="number" name="durasi[]" class="form-control durasi-input" min="1" placeholder="Durasi" >
        </div>
        <div class="col-md-2">
          <label class="form-label">Subtotal (Rp)</label>
          <input type="text" name="subtotal[]" class="form-control subtotal" readonly>
        </div>
        <div class="col-md-1">
          <label class="form-label invisible">Aksi</label>
          <button type="button" class="btn btn-danger btn-remove-alat w-100">X</button>
        </div>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header bg-primary text-white">Metode Pembayaran</div>
      <div class="card-body">
        <select name="metode_pembayaran" class="form-select" required>
          <option value="">-- Pilih Metode Pembayaran --</option>
          <option value="Cash">Bayar di Tempat (Cash)</option>
          <option value="Transfer">Transfer (Via Midtrans)</option>
        </select>
      </div>
    </div>

    <div class="d-grid">
      <button type="submit" class="btn btn-primary btn-lg">KIRIM BOOKING</button>
    </div>

  </form>
</div>


<script>
$(document).ready(function() {
    
    // Fungsi untuk format tampilan Select2 dengan gambar
    function formatAlat(alat) {
        if (!alat.id) {
            return alat.text;
        }
        var gambarUrl = $(alat.element).data('gambar');
        if (!gambarUrl) {
            return alat.text;
        }
        var $alat = $(
            '<span><img src="' + gambarUrl + '" style="height: 20px; width: 30px; object-fit: cover; margin-right: 8px;" /> ' + alat.text + '</span>'
        );
        return $alat;
    }

    // Fungsi untuk inisialisasi Select2
    function initializeSelect2(element) {
        element.select2({
            templateResult: formatAlat,
            templateSelection: formatAlat
        });
    }

    // Inisialisasi Select2 untuk semua baris yang sudah ada saat halaman dimuat
    initializeSelect2($('.pilih-gambar'));

    // Event handler untuk tombol Tambah Alat
    $('#btn-tambah-alat').on('click', function() {
        // Ambil HTML dari template
        var templateHtml = $('#alat-item-template').html();
        // Tambahkan template ke dalam daftar
        $('#alat-list').append(templateHtml);
        
        // INI BARIS KUNCI: Inisialisasi Select2 HANYA pada baris terakhir/baru
        initializeSelect2($('#alat-list .alat-item:last-child .pilih-gambar'));
    });

    // Event handler untuk tombol Hapus Alat
    $('#alat-list').on('click', '.btn-remove-alat', function() {
        if ($('#alat-list .alat-item').length > 1) {
            $(this).closest('.alat-item').remove();
        }
    });

    // Event handler untuk kalkulasi subtotal
    $('#alat-list').on('change input', '.alat-select, .jumlah-input, .durasi-input', function() {
        const item = $(this).closest('.alat-item');
        const selectedOption = item.find('.alat-select option:selected');
        const harga = parseInt(selectedOption.data('harga')) || 0;
        const jumlah = parseInt(item.find('.jumlah-input').val()) || 0;
        const durasi = parseInt(item.find('.durasi-input').val()) || 0;

        item.find('.harga-sewa').val(harga ? 'Rp ' + harga.toLocaleString('id-ID') : '');

        if (harga && jumlah && durasi) {
            const subtotal = harga * jumlah * durasi;
            item.find('.subtotal').val('Rp ' + subtotal.toLocaleString('id-ID'));
        } else {
            item.find('.subtotal').val('');
        }
    });
});
</script>
