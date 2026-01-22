-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Jul 2025 pada 06.31
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sewa_alat2`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alat`
--

CREATE TABLE `alat` (
  `id` int(11) NOT NULL,
  `nama_alat` varchar(100) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `harga_sewa_per_hari` bigint(10) DEFAULT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alat`
--

INSERT INTO `alat` (`id`, `nama_alat`, `kategori`, `stok`, `harga_sewa_per_hari`, `gambar`) VALUES
(20, 'Canon Eos 1200D', 'Kamera', 0, 80000, 'sewa_1751816701_354.jpg'),
(21, 'Canon Eos 600D', 'Kamera', 3, 105000, 'sewa_1751816762_994.jpg'),
(23, 'Lensa Fix Sony Fe 1,8/50mm', 'Kamera', 2, 85000, 'sewa_1751816878_533.jpeg'),
(24, 'Lensa Fix For Canon/Nikon', 'Kamera', 2, 50000, 'sewa_1751816920_858.jpg'),
(25, 'Lensa Tele/Sigma', 'Kamera', 4, 60000, 'sewa_1751816948_180.jpg'),
(26, 'Nikon D3400', 'Kamera', 1, 100000, 'sewa_1751816991_299.jpg'),
(27, 'Mirroless Canon M10', 'Video', 3, 100000, 'sewa_1751817022_483.jpg'),
(28, 'Mirroless Sony A6100', 'Video', 2, 140000, 'sewa_1751817084_904.jpg'),
(29, 'Mirroless Lumix G7', 'Video', 1, 130000, 'sewa_1751817112_917.jpg'),
(30, 'Baterai Kamera', 'Lainnya', 4, 20000, 'sewa_1751817145_698.jpg'),
(31, 'Cardreader Memory', 'Lainnya', 6, 5000, 'sewa_1751817169_813.jpeg'),
(32, 'Flash Eksternal', 'Lighting', 3, 60000, 'sewa_1751817200_515.jpg'),
(33, 'Stabilizer Feiyu AK 2000 S', 'Lainnya', 3, 120000, 'sewa_1751817242_666.jpg'),
(34, 'Gimbal For HP', 'Lainnya', 5, 104000, 'sewa_1751817273_469.jpg'),
(35, 'Memory Card 32/64GB', 'Lainnya', 5, 20000, 'sewa_1751817308_407.jpg'),
(36, 'Microphone Boya', 'Audio', 4, 25000, 'sewa_1751817337_236.jpg'),
(37, 'Microphone Clip', 'Audio', 4, 50000, 'sewa_1751817364_896.jpeg'),
(38, 'Ringlight 45cm', 'Lighting', 4, 30000, 'sewa_1751817393_414.jpg'),
(39, 'Trigger', 'Lainnya', 5, 30000, 'sewa_1751817422_333.jpg'),
(40, 'Tripod Kamera', 'Lainnya', 5, 20000, 'sewa_1751817443_320.jpg'),
(41, 'Canon Eos 1300D', 'Kamera', 2, 80000, 'sewa_1751817689_187.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `booking_online`
--

CREATE TABLE `booking_online` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tanggal_sewa` date DEFAULT NULL,
  `waktu_pengambilan` varchar(40) NOT NULL,
  `status` enum('menunggu','disetujui','ditolak') DEFAULT 'menunggu',
  `metode_pembayaran` enum('Cash','Transfer') NOT NULL DEFAULT 'Cash',
  `status_pembayaran` enum('Sudah bayar','Belum bayar') NOT NULL DEFAULT 'Belum bayar',
  `order_id` varchar(30) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `pesan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `booking_online`
--

INSERT INTO `booking_online` (`id`, `nama`, `no_hp`, `email`, `tanggal_sewa`, `waktu_pengambilan`, `status`, `metode_pembayaran`, `status_pembayaran`, `order_id`, `created_at`, `pesan`) VALUES
(73, 'Reza Saputra', '0898902', 'reza@cileley.com', '2025-06-01', '10.00 WIB', 'disetujui', 'Transfer', 'Sudah bayar', '819209819', '2025-07-27 10:37:23', 'Booking disetujui, silahkan untuk datang ke toko sesuai dengan tanggal sewa'),
(74, 'M Yusup', '0865191', 'yusup777@gmail.com', '2025-06-11', '13.00 WIB', 'disetujui', 'Cash', 'Belum bayar', '496217077', '2025-07-27 10:39:16', 'Booking disetujui, silahkan untuk datang ke toko sesuai dengan tanggal sewa'),
(75, 'Malika Shalsabila A', '0801', 'malika123@gmail.com', '2025-07-08', '10.00 WIB', 'disetujui', 'Transfer', 'Sudah bayar', '2118633690', '2025-07-27 10:40:36', 'Booking disetujui, silahkan untuk datang ke toko sesuai dengan tanggal sewa'),
(78, 'Ade Wahyu', '0896681', 'adew@gmail.com', '2025-07-27', '10.00 WIB', 'disetujui', 'Transfer', 'Sudah bayar', '299609741', '2025-07-27 11:04:07', 'Booking disetujui, silahkan untuk datang ke toko sesuai dengan tanggal sewa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `booking_online_detail`
--

CREATE TABLE `booking_online_detail` (
  `id` int(11) NOT NULL,
  `id_booking` int(11) DEFAULT NULL,
  `id_alat` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `durasi_hari` int(11) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `booking_online_detail`
--

INSERT INTO `booking_online_detail` (`id`, `id_booking`, `id_alat`, `jumlah`, `durasi_hari`, `subtotal`) VALUES
(103, 74, 20, 2, 1, 160000.00),
(104, 74, 27, 1, 1, 100000.00),
(105, 75, 26, 1, 2, 200000.00),
(110, 78, 20, 1, 2, 160000.00),
(111, 78, 40, 1, 2, 40000.00),
(112, 78, 30, 1, 2, 40000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `no_hp`, `alamat`) VALUES
(31, 'Reza Saputra', '0898902', 'Cileuleuy, Cigugur'),
(32, 'M Yusup', '0865191', 'Talaga, Majalengka'),
(33, 'Malika Shalsabila A', '0801', 'Cidahu'),
(35, 'Ade Wahyu', '0896681', 'Lebakwangi RT 08 RW 03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengambilan_barang`
--

CREATE TABLE `pengambilan_barang` (
  `id` int(11) NOT NULL,
  `id_sewa` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `nama_pengambil` varchar(100) DEFAULT NULL,
  `tanggal_pengambilan` date DEFAULT NULL,
  `bukti` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengambilan_barang`
--

INSERT INTO `pengambilan_barang` (`id`, `id_sewa`, `id_pelanggan`, `nama_pengambil`, `tanggal_pengambilan`, `bukti`, `catatan`) VALUES
(7, 18, 32, 'Yusup', '2025-07-27', 'bukti_1753588137.jpg', 'Alat lengkap dengan kondisi baik'),
(8, 17, 33, 'Malika', '2025-07-09', 'bukti_1753588258.png', 'Alat baik dengan lengkap'),
(9, 20, 35, 'Ade Wahyu', '2025-07-27', 'bukti_1753589288.png', 'Alat lengkap tanpa kerusakan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sewa`
--

CREATE TABLE `sewa` (
  `id` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `tanggal_sewa` date DEFAULT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `tanggal_dikembalikan` date DEFAULT NULL,
  `total_bayar` decimal(12,2) DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan') DEFAULT 'dipinjam',
  `status_alat` enum('Baik','Rusak','-') NOT NULL DEFAULT '-',
  `denda` decimal(12,2) DEFAULT 0.00,
  `gambar` varchar(255) NOT NULL,
  `status_pembayaran` varchar(50) DEFAULT NULL,
  `metode_pembayaran` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sewa`
--

INSERT INTO `sewa` (`id`, `id_pelanggan`, `tanggal_sewa`, `tanggal_kembali`, `tanggal_dikembalikan`, `total_bayar`, `status`, `status_alat`, `denda`, `gambar`, `status_pembayaran`, `metode_pembayaran`) VALUES
(17, 33, '2025-07-08', '2025-07-10', '2025-07-10', 200000.00, 'dikembalikan', 'Baik', 0.00, '', 'Sudah Bayar', 'Transfer'),
(18, 32, '2025-06-11', '2025-06-12', '2025-06-09', 260000.00, 'dikembalikan', 'Baik', 0.00, '', 'Sudah Bayar', 'Cash'),
(20, 35, '2025-07-27', '2025-07-29', '2025-07-29', 240000.00, 'dikembalikan', 'Baik', 0.00, '', 'Sudah Bayar', 'Transfer');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sewa_detail`
--

CREATE TABLE `sewa_detail` (
  `id` int(11) NOT NULL,
  `id_sewa` int(11) DEFAULT NULL,
  `id_alat` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `durasi_hari` int(11) DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sewa_detail`
--

INSERT INTO `sewa_detail` (`id`, `id_sewa`, `id_alat`, `jumlah`, `durasi_hari`, `subtotal`) VALUES
(38, 17, 26, 1, 2, 200000.00),
(39, 18, 20, 2, 1, 160000.00),
(40, 18, 27, 1, 1, 100000.00),
(44, 20, 20, 1, 2, 160000.00),
(45, 20, 40, 1, 2, 40000.00),
(46, 20, 30, 1, 2, 40000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'pelanggan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `role`) VALUES
(1, 'admin@sewaalat.kng', 'admin123', 'Admin', 'admin'),
(2, 'kasir@sewaalat.kng', 'kasir123', 'Kasir', 'admin'),
(3, 'owner@sewaalat.kng', '123owner', 'Ade Wahyu', 'admin'),
(7, 'reza@cileley.com', 'reza123', 'Reza Saputra', 'pelanggan'),
(8, 'yusup777@gmail.com', 'yusup123', 'M Yusup', 'pelanggan'),
(9, 'malika123@gmail.com', 'malika123', 'Malika Shalsabila A', 'pelanggan'),
(11, 'adew@gmail.com', 'adew2950', 'Ade Wahyu', 'pelanggan');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alat`
--
ALTER TABLE `alat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `booking_online`
--
ALTER TABLE `booking_online`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `booking_online_detail`
--
ALTER TABLE `booking_online_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_booking` (`id_booking`),
  ADD KEY `id_alat` (`id_alat`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengambilan_barang`
--
ALTER TABLE `pengambilan_barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `pengambilan_barang_ibfk_1` (`id_sewa`);

--
-- Indeks untuk tabel `sewa`
--
ALTER TABLE `sewa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indeks untuk tabel `sewa_detail`
--
ALTER TABLE `sewa_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sewa` (`id_sewa`),
  ADD KEY `id_alat` (`id_alat`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alat`
--
ALTER TABLE `alat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `booking_online`
--
ALTER TABLE `booking_online`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT untuk tabel `booking_online_detail`
--
ALTER TABLE `booking_online_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `pengambilan_barang`
--
ALTER TABLE `pengambilan_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `sewa`
--
ALTER TABLE `sewa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `sewa_detail`
--
ALTER TABLE `sewa_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `booking_online_detail`
--
ALTER TABLE `booking_online_detail`
  ADD CONSTRAINT `booking_online_detail_ibfk_1` FOREIGN KEY (`id_booking`) REFERENCES `booking_online` (`id`),
  ADD CONSTRAINT `booking_online_detail_ibfk_2` FOREIGN KEY (`id_alat`) REFERENCES `alat` (`id`);

--
-- Ketidakleluasaan untuk tabel `pengambilan_barang`
--
ALTER TABLE `pengambilan_barang`
  ADD CONSTRAINT `pengambilan_barang_ibfk_1` FOREIGN KEY (`id_sewa`) REFERENCES `sewa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pengambilan_barang_ibfk_2` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`);

--
-- Ketidakleluasaan untuk tabel `sewa`
--
ALTER TABLE `sewa`
  ADD CONSTRAINT `sewa_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sewa_detail`
--
ALTER TABLE `sewa_detail`
  ADD CONSTRAINT `sewa_detail_ibfk_1` FOREIGN KEY (`id_sewa`) REFERENCES `sewa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sewa_detail_ibfk_2` FOREIGN KEY (`id_alat`) REFERENCES `alat` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
