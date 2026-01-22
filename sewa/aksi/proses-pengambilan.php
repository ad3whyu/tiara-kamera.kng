<?php
include '../../koneksi/koneksi.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id_sewa = $_POST['id_sewa'];
    $id_pelanggan = $_POST['id_pelanggan'];
    $nama_pengambil = $_POST['nama_pengambil'];
    $tanggal_pengambilan = $_POST['tanggal_pengambilan'];
    $catatan = $_POST['catatan_kondisi'];

    // Proses upload bukti
    $bukti = $_FILES['bukti_penyerahan']['name'];
    $tmp = $_FILES['bukti_penyerahan']['tmp_name'];
    $ext = pathinfo($bukti, PATHINFO_EXTENSION);
    $newName = 'bukti_' . time() . '.' . $ext;
    $path = '../../uploads/bukti_pengambilan/' . $newName;

    if (move_uploaded_file($tmp, $path)) {
        $save = mysqli_query($conn, "
            INSERT INTO pengambilan_barang 
            (id_sewa, id_pelanggan, nama_pengambil, tanggal_pengambilan, bukti, catatan)
            VALUES 
            ('$id_sewa', '$id_pelanggan', '$nama_pengambil', '$tanggal_pengambilan', '$newName', '$catatan')
        ");

        // Update status sewa (optional)
    mysqli_query($conn, "UPDATE sewa 
                     SET status = 'dipinjam', 
                         status_pembayaran = 'Sudah Bayar' 
                     WHERE id = '$id_sewa'");
    

    } else {
        echo "Upload gagal. Pastikan folder `uploads/bukti_pengambilan/` memiliki permission yang benar.";
    }
}else {
    $_SESSION['alert_error'] = 'Akses tidak valid.';
}

header("Location: ../../index.php?page=sewa");
exit();
?>