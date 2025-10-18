-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Oct 18, 2025 at 12:34 PM
-- Server version: 10.5.29-MariaDB-ubu2004
-- PHP Version: 8.2.28

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
-- Table structure for table `kategori_tugas`
--

CREATE TABLE `kategori_tugas` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` enum('praktikum','teori','proyek') NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `warna` varchar(7) DEFAULT '#6c757d'
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
  `id_komentar` int(11) NOT NULL,
  `id_tugas` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `isi_komentar` text NOT NULL,
  `tanggal_komentar` datetime DEFAULT current_timestamp(),
  `id_balasan_untuk` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id_notifikasi` int(11) NOT NULL,
  `id_penerima` int(11) NOT NULL,
  `judul_notifikasi` varchar(150) NOT NULL,
  `isi_notifikasi` text DEFAULT NULL,
  `tipe_notifikasi` enum('tugas_baru','deadline_dekat','tugas_dinilai','komentar_baru') NOT NULL,
  `sudah_dibaca` tinyint(1) DEFAULT 0,
  `tanggal_notifikasi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengumpulan_tugas`
--

CREATE TABLE `pengumpulan_tugas` (
  `id_pengumpulan` int(11) NOT NULL,
  `id_tugas` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `isi_jawaban` text DEFAULT NULL,
  `lampiran_siswa` varchar(255) DEFAULT NULL,
  `tanggal_kirim` datetime DEFAULT current_timestamp(),
  `status_pengumpulan` enum('terkirim','diperbarui','terlambat') DEFAULT 'terkirim',
  `catatan_siswa` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penilaian`
--

CREATE TABLE `penilaian` (
  `id_penilaian` int(11) NOT NULL,
  `id_pengumpulan` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `nilai` int(11) DEFAULT NULL CHECK (`nilai` between 0 and 100),
  `komentar_guru` text DEFAULT NULL,
  `tanggal_penilaian` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id_tugas` int(11) NOT NULL,
  `judul_tugas` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `id_guru` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `tanggal_mulai` datetime DEFAULT current_timestamp(),
  `tanggal_deadline` datetime NOT NULL,
  `durasi_estimasi` int(11) DEFAULT NULL,
  `poin_nilai` int(11) DEFAULT 100,
  `instruksi_pengumpulan` text DEFAULT NULL,
  `lampiran_guru` varchar(255) DEFAULT NULL,
  `status_tugas` enum('aktif','ditutup','dibatalkan') DEFAULT 'aktif',
  `tanggal_dibuat` datetime DEFAULT current_timestamp(),
  `tanggal_diperbarui` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`id_tugas`, `judul_tugas`, `deskripsi`, `id_guru`, `id_kategori`, `tanggal_mulai`, `tanggal_deadline`, `durasi_estimasi`, `poin_nilai`, `instruksi_pengumpulan`, `lampiran_guru`, `status_tugas`, `tanggal_dibuat`, `tanggal_diperbarui`) VALUES
(19, 'Menulis 10 Soal Essay PAI', 'HALAMAN 139 - 140', 6, 2, '2025-10-18 06:15:00', '2025-10-18 12:21:00', 10, 100, 'JAM 10 HARUS DIKUMPUL', 'lampiran_68f3309fa944e_Screenshot_25-Sep_16-01-01_522.png', 'aktif', '2025-10-18 06:15:59', '2025-10-18 06:15:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `peran` enum('siswa','guru','admin') NOT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `nip_nis` varchar(30) DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `tanggal_dibuat` datetime DEFAULT current_timestamp(),
  `tanggal_diperbarui` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `email`, `password_hash`, `nomor_telepon`, `foto_profil`, `peran`, `kelas`, `nip_nis`, `status_aktif`, `tanggal_dibuat`, `tanggal_diperbarui`) VALUES
(4, 'kapin h', 'kapin@gmail.com', '$2y$12$J3FrEtVqu.pM8xFJKkyVvu/NNr88q0BAxsSKNjHEOayx/rTycmiJq', NULL, NULL, 'siswa', 'XI PPLG', '623678216', 1, '2025-10-18 05:33:20', '2025-10-18 05:33:20'),
(5, 'Kevin Hermansyah', 'kevinh@gmail.com', '$2y$12$C0h3ph/.5c1WQ1pKbT15D.nfFKevVfI3ZgMs7q/tONN.plDtRmaqi', NULL, NULL, 'siswa', 'XI PPLG', '00092134', 1, '2025-10-18 06:10:28', '2025-10-18 06:10:28'),
(6, 'Kevin Guru', 'kevinguru@gmail.com', '$2y$12$auULkSoQ7lJ7ZNE2HL0BIeaDs4jXnl4rMWYokdXpiLchFgrj4ljqu', NULL, NULL, 'guru', '', '202304', 1, '2025-10-18 06:11:41', '2025-10-18 06:12:04');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `kategori_tugas`
--
ALTER TABLE `kategori_tugas`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `komentar_tugas`
--
ALTER TABLE `komentar_tugas`
  MODIFY `id_komentar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notifikasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  MODIFY `id_pengumpulan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id_tugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

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
