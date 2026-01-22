<?php
session_start();
include "../../koneksi/koneksi.php";

// Cek apakah form dikirim dengan method POST
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {

    // Ambil data dari form
    $id_pelanggan      = mysqli_real_escape_string($conn, $_POST['id_pelanggan']);
    $tanggal_sewa      = mysqli_real_escape_string($conn, $_POST['tanggal_sewa']);
    $tanggal_kembali   = mysqli_real_escape_string($conn, $_POST['tanggal_kembali']);
    $status            = mysqli_real_escape_string($conn, $_POST['status']); 

    $total_bayar       = 0; 
    $denda             = 0;

    // Handle upload gambar
    $gambar_name = '';
    if (!empty($_FILES['gambar']['name'])) {
        $upload_folder = "../../assets/image/";
        if (!is_dir($upload_folder)) {
            mkdir($upload_folder, 0777, true);
        }

        $ext_allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $file_name   = $_FILES['gambar']['name'];
        $file_tmp    = $_FILES['gambar']['tmp_name'];
        $file_size   = $_FILES['gambar']['size'];
        $file_ext    = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Validasi file
        if (in_array($file_ext, $ext_allowed) && $file_size <= 2097152) {
            $new_name = 'sewa_' . time() . '_' . rand(100,999) . '.' . $file_ext;
            if (move_uploaded_file($file_tmp, $upload_folder . $new_name)) {
                $gambar_name = $new_name;
            } else {
                $_SESSION['alert_error'] = 'Gagal upload gambar.';
                header("Location: ../../index.php?page=tambah-sewa");
                exit();
            }
        } else {
            $_SESSION['alert_error'] = 'Format file tidak sesuai atau ukuran file terlalu besar.';
            header("Location: ../../index.php?page=tambah-sewa");
            exit();
        }
    }

    // Simpan ke database
    $query = "
        INSERT INTO sewa 
        (id_pelanggan, tanggal_sewa, tanggal_kembali, total_bayar, denda, status, gambar) 
        VALUES 
        ('$id_pelanggan', '$tanggal_sewa', '$tanggal_kembali', '$total_bayar', '$denda', '$status', '$gambar_name')
    ";

    if (mysqli_query($conn, $query)) {
        $_SESSION['alert_success'] = 'Data sewa berhasil disimpan.';

        // Ambil ID sewa terbaru
        $id_sewa_baru = mysqli_insert_id($conn);

        // Redirect ke sewa-detail untuk input alat
        header("Location: ../../index.php?page=sewa-detail&id=$id_sewa_baru");
        exit();

    } else {
        $_SESSION['alert_error'] = 'Gagal menyimpan data sewa: ' . mysqli_error($conn);
        header("Location: ../../index.php?page=tambah-sewa");
        exit();
    }

} else {
    $_SESSION['alert_error'] = 'Akses tidak valid.';
    header("Location: ../../index.php?page=sewa");
    exit();
}
?>
