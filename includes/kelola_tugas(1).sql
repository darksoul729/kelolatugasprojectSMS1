-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 24, 2025 at 01:55 AM
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
  `jam_olahraga_selesai` time DEFAULT NULL,
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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `nama_lengkap` varchar(100) NOT NULL,
  `kelas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `anak_kebiasaan`
--

INSERT INTO `anak_kebiasaan` (`id`, `id_user`, `bangun_pagi`, `jam_bangun`, `beribadah`, `agama`, `sholat_subuh`, `sholat_dzuhur`, `sholat_ashar`, `sholat_maghrib`, `sholat_isya`, `ibadah_lainnya`, `berolahraga`, `jam_olahraga_mulai`, `jam_olahraga_selesai`, `foto_olahraga`, `makan_sehat`, `makan_pagi`, `foto_makan_pagi`, `makan_siang`, `foto_makan_siang`, `makan_malam`, `foto_makan_malam`, `gemar_belajar`, `jam_belajar_mulai`, `jam_belajar_selesai`, `materi_belajar`, `bermasyarakat`, `kegiatan_masyarakat`, `ket_masyarakat`, `foto_masyarakat`, `tidur_cepat`, `jam_tidur`, `ket_tidur`, `created_at`, `nama_lengkap`, `kelas`) VALUES
(30, 16, 1, '05:00:00', 0, 'Islam', 0, 0, 0, 1, 0, '', 0, '00:00:00', '00:00:00', NULL, 1, 'Nasi Kuning😋', NULL, 'Ayam Katsu', NULL, 'Rawon', NULL, 1, '19:00:00', '22:00:00', 'Matematika Lanjutan', 1, NULL, 'Kerja Bakti Bersihin banjir', NULL, 1, '10:00:00', NULL, '2025-10-23 22:52:32', 'Fadlan Firdaus', '8 A'),
(31, 5, 0, '00:00:00', 0, NULL, 0, 0, 0, 0, 0, '', 0, '00:00:00', '00:00:00', NULL, 0, '', NULL, '', NULL, '', NULL, 0, '00:00:00', '00:00:00', '', 0, NULL, '', NULL, 0, '00:00:00', NULL, '2025-10-23 23:00:35', 'Kevin Hermansyah', 'XI PPLG'),
(32, 19, 1, '23:20:00', 1, NULL, 0, 0, 0, 0, 0, '', 0, '00:00:00', '00:00:00', NULL, 0, '', NULL, '', NULL, '', NULL, 0, '00:00:00', '00:00:00', '', 0, NULL, '', NULL, 0, '00:00:00', NULL, '2025-10-24 00:20:31', 'test2', '8 A'),
(33, 16, 1, '05:30:00', 1, 'Islam', 0, 1, 0, 1, 0, '', 1, '06:00:00', '06:30:00', NULL, 1, '', NULL, 'Ayam Geprek', NULL, 'Nasi Goreng', NULL, 1, '21:00:00', '22:00:00', 'Fisika', 0, NULL, '', NULL, 0, '00:00:00', NULL, '2025-10-24 01:02:19', 'Fadlan Firdaus', '8 A'),
(34, 24, 1, '00:16:00', 1, 'Hindu', 0, 0, 0, 0, 0, 'wnzndjd', 1, '00:16:00', '00:16:00', NULL, 1, 'djdjdjd', NULL, 'xhdjdjdd', NULL, 'dfgvvg', NULL, 1, '00:17:00', '00:17:00', 'ffgvvvv', 1, NULL, 'dfvv', NULL, 1, '00:17:00', NULL, '2025-10-24 01:17:20', 'test6', ''),
(36, 27, 1, '09:24:00', 1, 'Islam', 1, 0, 1, 0, 1, '', 1, '00:56:00', '00:56:00', NULL, 1, 'djdjdjd', NULL, 'xhdjdjdd', NULL, 'djjdjddjd', NULL, 0, '00:57:00', '14:57:00', '', 0, NULL, '', NULL, 0, '00:00:00', NULL, '2025-10-23 01:58:01', 'test8', '8 C'),
(37, 27, 0, '00:00:00', 0, NULL, 0, 0, 0, 0, 0, '', 0, '00:00:00', '00:00:00', NULL, 0, '', NULL, '', NULL, '', NULL, 0, '00:00:00', '00:00:00', '', 0, NULL, '', NULL, 0, '00:00:00', NULL, '2025-10-22 01:59:30', 'test8', '8 C'),
(38, 27, 1, '00:00:00', 1, NULL, 0, 0, 0, 0, 0, '', 1, '00:00:00', '00:00:00', NULL, 1, '', NULL, '', NULL, '', NULL, 1, '00:00:00', '00:00:00', '', 1, NULL, '', NULL, 1, '00:00:00', NULL, '2025-10-24 02:00:29', 'test8', '8 C'),
(39, 19, 1, '00:00:00', 1, NULL, 0, 0, 0, 0, 0, '', 1, '00:00:00', '00:00:00', NULL, 1, '', NULL, '', NULL, '', NULL, 1, '00:00:00', '00:00:00', '', 1, NULL, '', NULL, 1, '00:00:00', NULL, '2025-10-22 02:07:13', 'test2', '8 A'),
(40, 19, 1, '00:00:00', 1, NULL, 0, 0, 0, 0, 0, '', 1, '00:00:00', '00:00:00', NULL, 1, '', NULL, '', NULL, '', NULL, 1, '00:00:00', '00:00:00', '', 1, NULL, '', NULL, 1, '00:00:00', NULL, '2025-10-21 02:07:48', 'test2', '8 A'),
(41, 19, 1, '00:00:00', 1, NULL, 0, 0, 0, 0, 0, '', 1, '00:00:00', '00:00:00', NULL, 1, '', NULL, '', NULL, '', NULL, 1, '00:00:00', '00:00:00', '', 1, NULL, '', NULL, 1, '00:00:00', NULL, '2025-10-20 02:10:02', 'test2', '8 A'),
(42, 19, 1, '00:00:00', 1, NULL, 0, 0, 0, 0, 0, '', 1, '00:00:00', '00:00:00', NULL, 1, '', NULL, '', NULL, '', NULL, 1, '00:00:00', '00:00:00', '', 1, NULL, '', NULL, 1, '00:00:00', NULL, '2025-10-19 02:10:30', 'test2', '8 A'),
(43, 19, 1, '00:00:00', 1, NULL, 0, 0, 0, 0, 0, '', 1, '00:00:00', '00:00:00', NULL, 1, '', NULL, '', NULL, '', NULL, 1, '00:00:00', '00:00:00', '', 1, NULL, '', NULL, 1, '00:00:00', NULL, '2025-10-18 02:11:05', 'test2', '8 A'),
(44, 19, 1, '00:00:00', 1, NULL, 0, 0, 0, 0, 0, '', 1, '00:00:00', '00:00:00', NULL, 1, '', NULL, '', NULL, '', NULL, 1, '00:00:00', '00:00:00', '', 1, NULL, '', NULL, 1, '00:00:00', NULL, '2025-10-17 02:11:37', 'test2', '8 A'),
(45, 19, 1, '00:00:00', 1, NULL, 0, 0, 0, 0, 0, '', 1, '00:00:00', '00:00:00', NULL, 1, '', NULL, '', NULL, '', NULL, 1, '00:00:00', '00:00:00', '', 1, NULL, '', NULL, 1, '00:00:00', NULL, '2025-10-16 02:12:15', 'test2', '8 A'),
(46, 19, 1, '00:00:00', 1, NULL, 0, 0, 0, 0, 0, '', 1, '00:00:00', '00:00:00', NULL, 1, '', NULL, '', NULL, '', NULL, 1, '00:00:00', '00:00:00', '', 1, NULL, '', NULL, 1, '00:00:00', NULL, '2025-10-15 02:12:54', 'test2', '8 A'),
(47, 19, 1, '00:00:00', 1, NULL, 0, 0, 0, 0, 0, '', 1, '00:00:00', '00:00:00', NULL, 1, '', NULL, '', NULL, '', NULL, 1, '00:00:00', '00:00:00', '', 1, NULL, '', NULL, 1, '00:00:00', NULL, '2025-10-14 02:13:20', 'test2', '8 A'),
(48, 19, 1, '00:00:00', 1, NULL, 0, 0, 0, 0, 0, '', 1, '00:00:00', '00:00:00', NULL, 1, '', NULL, '', NULL, '', NULL, 1, '00:00:00', '00:00:00', '', 1, NULL, '', NULL, 1, '00:00:00', NULL, '2025-10-13 02:13:48', 'test2', '8 A'),
(49, 19, 1, '00:00:00', 1, NULL, 0, 0, 0, 0, 0, '', 1, '00:00:00', '00:00:00', NULL, 1, '', NULL, '', NULL, '', NULL, 1, '00:00:00', '00:00:00', '', 1, NULL, '', NULL, 1, '00:00:00', NULL, '2025-10-24 02:16:06', 'test2', '8 A'),
(50, 5, 0, '00:00:00', 0, NULL, 0, 0, 0, 0, 0, '', 0, '00:00:00', '00:00:00', NULL, 0, '', NULL, '', NULL, '', NULL, 0, '00:00:00', '00:00:00', '', 0, NULL, '', NULL, 0, '00:00:00', NULL, '2025-10-24 02:32:18', 'Kevin Hermansyah', 'XI PPLG'),
(51, 28, 0, '00:00:00', 0, NULL, 0, 0, 0, 0, 0, '', 0, '00:00:00', '00:00:00', NULL, 0, '', NULL, '', NULL, '', NULL, 0, '00:00:00', '00:00:00', '', 0, NULL, '', NULL, 0, '00:00:00', NULL, '2025-10-24 02:45:54', 'test9', '8 C');

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
(26, 'Hapalan AL baqaarah', 'Raka wajib ngumupul 😡🤬', 6, 1, '2025-10-20 05:30:00', '2025-10-20 11:01:00', 100, 100, 'raka nda kumpul dihukum', 'lampiran_68f5d00333c0b_php-elephant-fart.png', 'aktif', '2025-10-20 04:30:58', '2025-10-22 16:40:40', 'XII DKV'),
(27, 'tugas b.indo', 'asjhdjhsajkdhjkshajkdhsjakhas', 6, 2, '2025-10-20 05:31:00', '1000-10-01 00:00:00', 100, 100, 'raka harus kumpul', 'lampiran_68f5cd0b1027e_api.jpg', 'aktif', '2025-10-20 04:31:45', '2025-10-22 14:29:37', 'XII PPLG'),
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
  `peran` enum('belum_verifikasi','siswa','guru','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kelas` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `wali_kelas` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nip_nis` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT '1',
  `tanggal_dibuat` datetime DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diperbarui` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `email`, `password_hash`, `nomor_telepon`, `foto_profil`, `peran`, `kelas`, `wali_kelas`, `nip_nis`, `status_aktif`, `tanggal_dibuat`, `tanggal_diperbarui`) VALUES
(5, 'Kevin Hermansyah', 'kevinh@gmail.com', '$2y$12$C0h3ph/.5c1WQ1pKbT15D.nfFKevVfI3ZgMs7q/tONN.plDtRmaqi', NULL, NULL, 'siswa', 'XI PPLG', NULL, '00092134', 1, '2025-10-18 06:10:28', '2025-10-18 06:10:28'),
(6, 'Kevin Guru', 'kevinguru@gmail.com', '$2y$12$auULkSoQ7lJ7ZNE2HL0BIeaDs4jXnl4rMWYokdXpiLchFgrj4ljqu', NULL, NULL, 'guru', '', '7A', '202304', 1, '2025-10-18 06:11:41', '2025-10-23 16:26:10'),
(7, 'kevinadmin', 'kevina@gmail.com', '$2y$10$uYu4ZQHyPvhdGm/05FO.leZQ2BE3SMWvf0ENxO.ikOWYoLMGgoRTS', NULL, NULL, 'admin', '', '', '', 1, '2025-10-19 12:50:04', '2025-10-23 16:19:57'),
(10, 'jaskia monyet', 'zskirahma1210@gmail.com', '$2y$10$2r8.DMpCr1nGznlUtaXpiO4C.B5ecFPyzjc915p5D/IiA3vlRcZ/m', NULL, NULL, 'siswa', 'XII PPLG', '', '12345678', 1, '2025-10-22 12:44:44', '2025-10-23 13:17:20'),
(16, 'Fadlan Firdaus', 'Fadlan@gmail.com', '$2y$10$4TU2kKNNdey9Cnl9pz3u3u8Gf81zkK0UwrMdU2co8iEtPpcX4b1ze', NULL, NULL, 'siswa', '8 A', NULL, '8099213', 1, '2025-10-23 13:25:59', '2025-10-23 13:25:59'),
(17, 'syahid hussein', 'syahidshun7@gmail.com', '$2y$10$zFblZblCe7j1.fug2d240.Qm8EUAiAIIc.8TPbGKOaX8J828zoj.a', NULL, NULL, 'guru', '7 A', '8C', '1234123123', 0, '2025-10-23 13:34:19', '2025-10-23 17:22:31'),
(18, 'test1', 'test1@gmail.com', '$2y$10$bJVvv9MboPEBnDdVvCwGRuDOu4Rco0r.NS3NpAq6BOQj1lCi1w9Va', NULL, NULL, 'siswa', '9 A', NULL, '1212123232', 1, '2025-10-23 13:35:40', '2025-10-23 13:35:40'),
(19, 'test2', 'test2@gmail.com', '$2y$10$iqAdCX.YMlQGa9CLSDzA2OK94QcvAWuglJkNdvhKS80dM9jRW8ERO', NULL, NULL, 'siswa', '8 A', NULL, '12345678', 1, '2025-10-23 13:36:13', '2025-10-23 13:36:13'),
(20, 'test3', 'test3@gmail.com', '$2y$10$0lrnJGNm.OFgKmugY7aicONveMtkWhhZ2ddXJ1Y63jJ.IGjDCoBKK', NULL, NULL, 'siswa', '8 C', NULL, '12345678', 1, '2025-10-23 13:36:57', '2025-10-23 13:36:57'),
(21, 'test4', 'test4@gmail.com', '$2y$10$RGElPae2ueALSK5i2L7sheFJK5wqssM2KSV/d/vL9VY466AouGJvK', NULL, NULL, 'siswa', '8 B', NULL, '12345', 1, '2025-10-23 13:37:56', '2025-10-23 13:37:56'),
(22, 'test5', 'test5@gmail.com', '$2y$10$oS.Imdl4Up7CWUsb6cMbTe/ZRL0ocTuI.aTVBURdzGwASv1cVj/sq', NULL, NULL, 'siswa', '', NULL, '12345', 1, '2025-10-23 13:38:34', '2025-10-23 13:38:34'),
(23, 'no', 'no@gmail.com', '$2y$10$7l9PQ9joAhVDsQp4jeQuoO/WbQvPoYOwmvzMMhEcUYu6WgyCiREBm', NULL, NULL, 'siswa', '8 A', NULL, '000912341', 1, '2025-10-23 14:03:28', '2025-10-23 16:05:30'),
(24, 'test6', 'test6@gmail.com', '$2y$10$RFKVu0pGk7OovROvOh.f/eX3Y3tUITwwLxFBxh4v/V//s93ldCsHa', NULL, NULL, 'siswa', '', '', '2342342', 1, '2025-10-23 14:14:55', '2025-10-23 16:12:13'),
(25, 'KevinHHH', 'kevinpp@gmail.com', '$2y$10$pSUSZtjtvOH5lSQhxMhV8OljrWbhaasDZEQlWy0xJzMXEZfhpDvcO', NULL, NULL, 'guru', '', '7A', '00091231412', 1, '2025-10-23 14:21:13', '2025-10-23 16:40:31'),
(26, 'test7', 'test7@gmail.cm', '$2y$10$w2dzGBeRoB/nF2zDby/g0uJ6ad590QtViDdnw9T/cdDs4UPIDCLqO', NULL, NULL, 'guru', '9 A', '8D', '2727373ffvvvvb', 1, '2025-10-23 16:44:34', '2025-10-23 17:19:11'),
(27, 'test8', 'test8@gmail.com', '$2y$10$gIBQBVenXIBL/7342/G7ReO3K442N2Y/40nmzXuTCmHdicsHT58bO', NULL, NULL, 'guru', '8 C', '8 A', '2727373ffvvvvb', 1, '2025-10-23 16:48:23', '2025-10-23 17:19:36'),
(28, 'test9', 'test9@gmail.com', '$2y$10$zLQs3TTak8PfQxXUo6Zupe4srpEynSOpb5v7swPNzCO4QHrnzpJ/C', NULL, NULL, 'siswa', '8 C', '8C', 'djdjdjd', 1, '2025-10-23 17:35:04', '2025-10-23 17:37:54'),
(29, 'KevinHsadddddd', 'kevinp@gmail.com', '$2y$10$wvL57i.MesHImhWMXBXRL.w50pnCxV/GEpunfBZPF2RyqALfrFbPS', NULL, NULL, 'siswa', '8 C', NULL, '000932432', 1, '2025-10-23 17:40:59', '2025-10-23 17:41:56'),
(30, 'keprieoroeepjwr', 'kepinin@gmail.com', '$2y$10$ebfL5yRaFhhxdwIrQSyYoeyug7t6KUSV77jsUfWDeIIUJGidReryy', NULL, NULL, 'siswa', '7 A', NULL, '000293123', 1, '2025-10-23 17:42:45', '2025-10-23 17:43:04'),
(31, 'syahid', 'syaid@gmail.com', '$2y$10$QTbf5LIKGb2ECZFtjasI2ej007rXA9lHHNsYgcQdzHlztpz2/ltHS', NULL, NULL, 'belum_verifikasi', '8 D', NULL, '2727373ffvvvvb', 1, '2025-10-23 17:48:10', '2025-10-23 17:48:10');

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

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
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
