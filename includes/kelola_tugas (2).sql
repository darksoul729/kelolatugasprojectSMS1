-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 21, 2025 at 01:15 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kelola_tugas`
--

-- --------------------------------------------------------

--
-- Table structure for table `anak_kebiasaan`
--

CREATE TABLE `anak_kebiasaan` (
  `id` int UNSIGNED NOT NULL,
  `id_user` int NOT NULL,
  `bangun_pagi` tinyint(1) DEFAULT '0',
  `jam_bangun` time DEFAULT NULL,
  `beribadah` tinyint(1) DEFAULT '0',
  `agama` enum('Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya') DEFAULT NULL,
  `sholat_subuh` tinyint(1) DEFAULT '0',
  `sholat_dzuhur` tinyint(1) DEFAULT '0',
  `sholat_ashar` tinyint(1) DEFAULT '0',
  `sholat_maghrib` tinyint(1) DEFAULT '0',
  `sholat_isya` tinyint(1) DEFAULT '0',
  `ibadah_lainnya` varchar(255) DEFAULT NULL,
  `berolahraga` tinyint(1) DEFAULT '0',
  `jam_olahraga_mulai` time DEFAULT NULL,
   `foto_olahraga` varchar(255) DEFAULT NULL,
  `makan_sehat` tinyint(1) DEFAULT '0',
  `makan_pagi` varchar(150) DEFAULT NULL,
  `foto_makan_pagi` varchar(255) DEFAULT NULL,
  `makan_siang` varchar(150) DEFAULT NULL,
  `foto_makan_siang` varchar(255) DEFAULT NULL,
  `makan_malam` varchar(150) DEFAULT NULL,
  `foto_makan_malam` varchar(255) DEFAULT NULL,
  `gemar_belajar` tinyint(1) DEFAULT '0',
  `jam_belajar_mulai` time DEFAULT NULL,
  `jam_belajar_selesai` time DEFAULT NULL,
  `materi_belajar` varchar(255) DEFAULT NULL,
  `bermasyarakat` tinyint(1) DEFAULT '0',
  `kegiatan_masyarakat` varchar(255) DEFAULT NULL,
  `ket_masyarakat` text,
  `foto_masyarakat` varchar(255) DEFAULT NULL,
  `tidur_cepat` tinyint(1) DEFAULT '0',
  `jam_tidur` time DEFAULT NULL,
  `ket_tidur` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori_tugas`
--

CREATE TABLE `kategori_tugas` (
  `id_kategori` int NOT NULL,
  `nama_kategori` enum('praktikum','teori','proyek') COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `warna` varchar(7) COLLATE utf8mb4_general_ci DEFAULT '#6c757d'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_tugas`
--

INSERT INTO `kategori_tugas` (`id_kategori`, `nama_kategori`, `deskripsi`, `warna`) VALUES
(1, 'praktikum', 'Tugas berupa kegiatan praktik langsung di laboratorium atau lapangan.', '#28a745'),
(2, 'teori', 'Tugas berupa soal, esai, atau bacaan teoritis.', '#17a2b8'),
(3, 'proyek', 'Tugas berupa proyek kelompok atau individu jangka panjang.', '#ffc107');

-- --------------------------------------------------------

--
-- Table structure for table `komentar_tugas`
--

CREATE TABLE `komentar_tugas` (
  `id_komentar` int NOT NULL,
  `id_tugas` int NOT NULL,
  `id_user` int NOT NULL,
  `isi_komentar` text COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_komentar` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_balasan_untuk` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id_notifikasi` int NOT NULL,
  `id_penerima` int NOT NULL,
  `judul_notifikasi` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `isi_notifikasi` text COLLATE utf8mb4_general_ci,
  `tipe_notifikasi` enum('tugas_baru','deadline_dekat','tugas_dinilai','komentar_baru') COLLATE utf8mb4_general_ci NOT NULL,
  `sudah_dibaca` tinyint(1) DEFAULT '0',
  `tanggal_notifikasi` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengumpulan_tugas`
--

CREATE TABLE `pengumpulan_tugas` (
  `id_pengumpulan` int NOT NULL,
  `id_tugas` int NOT NULL,
  `id_siswa` int NOT NULL,
  `isi_jawaban` text COLLATE utf8mb4_general_ci,
  `lampiran_siswa` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_kirim` datetime DEFAULT CURRENT_TIMESTAMP,
  `status_pengumpulan` enum('terkirim','diperbarui','terlambat') COLLATE utf8mb4_general_ci DEFAULT 'terkirim',
  `catatan_siswa` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penilaian`
--

CREATE TABLE `penilaian` (
  `id_penilaian` int NOT NULL,
  `id_pengumpulan` int NOT NULL,
  `id_guru` int NOT NULL,
  `nilai` int DEFAULT NULL,
  `komentar_guru` text COLLATE utf8mb4_general_ci,
  `tanggal_penilaian` datetime DEFAULT CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id_tugas` int NOT NULL,
  `judul_tugas` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `id_guru` int NOT NULL,
  `id_kategori` int NOT NULL,
  `tanggal_mulai` datetime DEFAULT CURRENT_TIMESTAMP,
  `tanggal_deadline` datetime NOT NULL,
  `durasi_estimasi` int DEFAULT NULL,
  `poin_nilai` int DEFAULT '100',
  `instruksi_pengumpulan` text COLLATE utf8mb4_general_ci,
  `lampiran_guru` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_tugas` enum('aktif','ditutup','dibatalkan') COLLATE utf8mb4_general_ci DEFAULT 'aktif',
  `tanggal_dibuat` datetime DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diperbarui` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `kelas` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`id_tugas`, `judul_tugas`, `deskripsi`, `id_guru`, `id_kategori`, `tanggal_mulai`, `tanggal_deadline`, `durasi_estimasi`, `poin_nilai`, `instruksi_pengumpulan`, `lampiran_guru`, `status_tugas`, `tanggal_dibuat`, `tanggal_diperbarui`, `kelas`) VALUES
(26, 'Hapalam AL baqaarah', 'Raka Harus ngumupul', 6, 1, '2025-10-20 05:30:00', '2025-10-20 11:01:00', 100, 100, 'raka nda kumpul dihukum', 'lampiran_68f5d00333c0b_php-elephant-fart.png', 'aktif', '2025-10-20 04:30:58', '2025-10-20 05:00:35', NULL),
(27, 'tugas b.indo', 'asjhdjhsajkdhjkshajkdhsjakhas', 6, 2, '2025-10-20 05:31:00', '1000-10-01 00:00:00', 100, 100, 'raka harus kumpul', 'lampiran_68f5cd0b1027e_api.jpg', 'aktif', '2025-10-20 04:31:45', '2025-10-20 04:47:55', NULL),
(28, 'tugas pjok voli', 'selesaikan hari ini', 6, 2, '2025-10-20 05:49:00', '0202-02-23 05:55:00', 100, 100, 'kepin passing atas', 'lampiran_68f5cde8caa7e_php-elephant-fart.png', 'aktif', '2025-10-20 04:51:36', '2025-10-20 04:51:36', 'XI PPLG');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nomor_telepon` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto_profil` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `peran` enum('siswa','guru','admin') COLLATE utf8mb4_general_ci NOT NULL,
  `kelas` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nip_nis` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT '1',
  `tanggal_dibuat` datetime DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diperbarui` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `email`, `password_hash`, `nomor_telepon`, `foto_profil`, `peran`, `kelas`, `nip_nis`, `status_aktif`, `tanggal_dibuat`, `tanggal_diperbarui`) VALUES
(4, 'kapin h', 'kapin@gmail.com', '$2y$12$J3FrEtVqu.pM8xFJKkyVvu/NNr88q0BAxsSKNjHEOayx/rTycmiJq', NULL, NULL, 'siswa', 'XI PPLG', '623678216', 1, '2025-10-18 05:33:20', '2025-10-18 05:33:20'),
(5, 'Kevin Hermansyah', 'kevinh@gmail.com', '$2y$12$C0h3ph/.5c1WQ1pKbT15D.nfFKevVfI3ZgMs7q/tONN.plDtRmaqi', NULL, NULL, 'siswa', 'XI PPLG', '00092134', 1, '2025-10-18 06:10:28', '2025-10-18 06:10:28'),
(6, 'Kevin Guru', 'kevinguru@gmail.com', '$2y$12$auULkSoQ7lJ7ZNE2HL0BIeaDs4jXnl4rMWYokdXpiLchFgrj4ljqu', NULL, NULL, 'guru', '', '202304', 1, '2025-10-18 06:11:41', '2025-10-18 06:12:04'),
(7, 'kevinadmin', 'kevina@gmail.com', '$2y$10$uYu4ZQHyPvhdGm/05FO.leZQ2BE3SMWvf0ENxO.ikOWYoLMGgoRTS', NULL, NULL, 'admin', '', '', 1, '2025-10-19 12:50:04', '2025-10-19 12:51:09'),
(8, 'rakaaws', 'noir1@gmail.com', '$2y$10$a6ialjHbcIukRGLhbiG3De5l0TNo4XrkdAn0vvQEkOo7hAea/Dqo.', NULL, NULL, 'siswa', 'XI PPLG', '', 1, '2025-10-20 03:59:15', '2025-10-20 03:59:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anak_kebiasaan`
--
ALTER TABLE `anak_kebiasaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `kategori_tugas`
--
ALTER TABLE `kategori_tugas`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indexes for table `komentar_tugas`
--
ALTER TABLE `komentar_tugas`
  ADD PRIMARY KEY (`id_komentar`),
  ADD KEY `id_tugas` (`id_tugas`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_balasan_untuk` (`id_balasan_untuk`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id_notifikasi`),
  ADD KEY `id_penerima` (`id_penerima`);

--
-- Indexes for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  ADD PRIMARY KEY (`id_pengumpulan`),
  ADD UNIQUE KEY `unik_siswa_tugas` (`id_tugas`,`id_siswa`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id_penilaian`),
  ADD KEY `id_pengumpulan` (`id_pengumpulan`),
  ADD KEY `id_guru` (`id_guru`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id_tugas`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anak_kebiasaan`
--
ALTER TABLE `anak_kebiasaan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori_tugas`
--
ALTER TABLE `kategori_tugas`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `komentar_tugas`
--
ALTER TABLE `komentar_tugas`
  MODIFY `id_komentar` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notifikasi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  MODIFY `id_pengumpulan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id_tugas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anak_kebiasaan`
--
ALTER TABLE `anak_kebiasaan`
  ADD CONSTRAINT `anak_kebiasaan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `komentar_tugas`
--
ALTER TABLE `komentar_tugas`
  ADD CONSTRAINT `komentar_tugas_ibfk_1` FOREIGN KEY (`id_tugas`) REFERENCES `tugas` (`id_tugas`) ON DELETE CASCADE,
  ADD CONSTRAINT `komentar_tugas_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `komentar_tugas_ibfk_3` FOREIGN KEY (`id_balasan_untuk`) REFERENCES `komentar_tugas` (`id_komentar`) ON DELETE CASCADE;

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`id_penerima`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  ADD CONSTRAINT `pengumpulan_tugas_ibfk_1` FOREIGN KEY (`id_tugas`) REFERENCES `tugas` (`id_tugas`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengumpulan_tugas_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD CONSTRAINT `penilaian_ibfk_1` FOREIGN KEY (`id_pengumpulan`) REFERENCES `pengumpulan_tugas` (`id_pengumpulan`) ON DELETE CASCADE,
  ADD CONSTRAINT `penilaian_ibfk_2` FOREIGN KEY (`id_guru`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `tugas`
--
ALTER TABLE `tugas`
  ADD CONSTRAINT `tugas_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `tugas_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_tugas` (`id_kategori`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
