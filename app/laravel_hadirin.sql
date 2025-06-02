-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2025 at 05:22 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel_coba`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` bigint(20) UNSIGNED NOT NULL,
  `id_siswa` bigint(20) UNSIGNED NOT NULL,
  `id_jadwal` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','alpa','sakit','izin') NOT NULL,
  `catatan` text DEFAULT NULL,
  `dibuat_pada` timestamp NULL DEFAULT NULL,
  `dibuat_oleh` varchar(255) DEFAULT NULL,
  `diperbarui_pada` timestamp NULL DEFAULT NULL,
  `diperbarui_oleh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id_guru` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `nomor_telepon` varchar(255) DEFAULT NULL,
  `bidang_studi` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `dibuat_pada` timestamp NULL DEFAULT NULL,
  `dibuat_oleh` varchar(255) DEFAULT NULL,
  `diperbarui_pada` timestamp NULL DEFAULT NULL,
  `diperbarui_oleh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id_guru`, `id_user`, `nama_lengkap`, `nip`, `nomor_telepon`, `bidang_studi`, `status`, `dibuat_pada`, `dibuat_oleh`, `diperbarui_pada`, `diperbarui_oleh`) VALUES
(1, 4, 'Ahmad Wijaya', '198601202008011002', '081234567891', 'Matematika', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(2, 5, 'Dewi Susanti', '198703152009012003', '081234567892', 'Bahasa Indonesia', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(3, 6, 'Sarah Johnson', '198805102010012004', '081234567893', 'Bahasa Inggris', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(4, 7, 'Rini Wulandari', '199002252012012005', '081234567894', 'Ilmu Pengetahuan Alam', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(5, 8, 'Budi Santoso', '198904182011011006', '081234567895', 'Pendidikan Pancasila dan Kewarganegaraan', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(6, 9, 'Siti Nurhaliza', '199106302013012007', '081234567896', 'Ilmu Pengetahuan Sosial', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(7, 10, 'Indira Sari', '199208152014012008', '081234567897', 'Seni Budaya', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(8, 11, 'Yoga Pratama', '199310202015011009', '081234567898', 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(9, 12, 'Andi Kurniawan', '199405122016011010', '081234567899', 'Prakarya', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(10, 20, 'Sari Matematika', '199507152017012010', '081234567900', 'Matematika', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(11, 21, 'Rina Indonesia', '199608202018012011', '081234567901', 'Bahasa Indonesia', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(12, 22, 'Maya English', '199709102019012012', '081234567902', 'Bahasa Inggris', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(13, 23, 'Doni Sains', '199810252020011013', '081234567903', 'Ilmu Pengetahuan Alam', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(14, 24, 'Lina Civics', '199911152021012014', '081234567904', 'PPKn', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(15, 25, 'Eko Sosial', '200012302022011015', '081234567905', 'Ilmu Pengetahuan Sosial', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(16, 26, 'Fitri Seni', '200101182023012016', '081234567906', 'Seni Budaya', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(17, 27, 'Agus Sport', '200202052024011017', '081234567907', 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(18, 28, 'Nina Craft', '200303122025012018', '081234567908', 'Prakarya', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(19, 30, 'Hendra Matematika', '200404152026011019', '081234567910', 'Matematika', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(20, 31, 'Lisa Bahasa', '200505202027012020', '081234567911', 'Bahasa Indonesia', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(21, 32, 'David English', '200606102028011021', '081234567912', 'Bahasa Inggris', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(22, 33, 'Ratna Science', '200707252029012022', '081234567913', 'Ilmu Pengetahuan Alam', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(23, 34, 'Bambang PKN', '200808182030011023', '081234567914', 'Pendidikan Pancasila dan Kewarganegaraan', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(24, 35, 'Wati IPS', '200909302031012024', '081234567915', 'Ilmu Pengetahuan Sosial', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(25, 36, 'Andi Art', '201010152032011025', '081234567916', 'Seni Budaya', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(26, 37, 'Rudi Sport', '201111052033011026', '081234567917', 'PJOK', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(27, 38, 'Sinta Craft', '201212122034012027', '081234567918', 'Prakarya', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system');

-- --------------------------------------------------------

--
-- Table structure for table `guru_mata_pelajaran`
--

CREATE TABLE `guru_mata_pelajaran` (
  `id_guru_mata_pelajaran` bigint(20) UNSIGNED NOT NULL,
  `id_guru` bigint(20) UNSIGNED NOT NULL,
  `id_mata_pelajaran` bigint(20) UNSIGNED NOT NULL,
  `dibuat_pada` timestamp NULL DEFAULT NULL,
  `dibuat_oleh` varchar(255) DEFAULT NULL,
  `diperbarui_pada` timestamp NULL DEFAULT NULL,
  `diperbarui_oleh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guru_mata_pelajaran`
--

INSERT INTO `guru_mata_pelajaran` (`id_guru_mata_pelajaran`, `id_guru`, `id_mata_pelajaran`, `dibuat_pada`, `dibuat_oleh`, `diperbarui_pada`, `diperbarui_oleh`) VALUES
(1, 1, 1, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(2, 2, 2, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(3, 3, 3, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(4, 4, 4, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(5, 5, 5, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(6, 6, 6, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(7, 7, 7, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(8, 8, 8, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(9, 9, 9, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(10, 10, 1, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(11, 11, 2, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(12, 12, 3, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(13, 13, 4, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(14, 14, 5, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(15, 15, 6, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(16, 16, 7, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(17, 17, 8, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(18, 18, 9, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(19, 19, 1, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(20, 20, 2, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(21, 21, 3, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(22, 22, 4, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(23, 23, 5, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(24, 24, 6, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(25, 25, 7, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(26, 26, 8, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(27, 27, 9, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` bigint(20) UNSIGNED NOT NULL,
  `id_kelas` bigint(20) UNSIGNED NOT NULL,
  `id_mata_pelajaran` bigint(20) UNSIGNED NOT NULL,
  `id_guru` bigint(20) UNSIGNED NOT NULL,
  `id_tahun_ajaran` bigint(20) UNSIGNED NOT NULL,
  `hari` enum('senin','selasa','rabu','kamis','jumat','sabtu','minggu') NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `dibuat_pada` timestamp NULL DEFAULT NULL,
  `dibuat_oleh` varchar(255) DEFAULT NULL,
  `diperbarui_pada` timestamp NULL DEFAULT NULL,
  `diperbarui_oleh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id_jadwal`, `id_kelas`, `id_mata_pelajaran`, `id_guru`, `id_tahun_ajaran`, `hari`, `waktu_mulai`, `waktu_selesai`, `status`, `dibuat_pada`, `dibuat_oleh`, `diperbarui_pada`, `diperbarui_oleh`) VALUES
(1, 1, 2, 2, 1, 'senin', '07:45:00', '08:30:00', 'aktif', '2025-06-02 02:22:40', 'admin', '2025-06-02 03:14:29', 'admin'),
(2, 1, 2, 2, 1, 'senin', '08:30:00', '09:15:00', 'aktif', '2025-06-02 02:22:40', 'admin', '2025-06-02 03:14:29', 'admin'),
(3, 1, 3, 21, 1, 'senin', '09:15:00', '10:00:00', 'aktif', '2025-06-02 02:22:40', 'admin', '2025-06-02 03:14:29', 'admin'),
(4, 1, 3, 21, 1, 'senin', '10:15:00', '11:00:00', 'aktif', '2025-06-02 02:22:40', 'admin', '2025-06-02 03:14:29', 'admin'),
(5, 1, 4, 13, 1, 'senin', '11:00:00', '11:45:00', 'aktif', '2025-06-02 02:22:40', 'admin', '2025-06-02 03:14:29', 'admin'),
(6, 1, 4, 13, 1, 'senin', '11:45:00', '12:30:00', 'aktif', '2025-06-02 02:22:40', 'admin', '2025-06-02 03:14:29', 'admin'),
(7, 1, 4, 13, 1, 'selasa', '07:45:00', '08:30:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(8, 1, 4, 13, 1, 'selasa', '08:30:00', '09:15:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(9, 1, 6, 15, 1, 'selasa', '09:15:00', '10:00:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(10, 1, 6, 15, 1, 'selasa', '10:15:00', '11:00:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(11, 1, 1, 10, 1, 'selasa', '11:00:00', '11:45:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(12, 1, 1, 10, 1, 'selasa', '11:45:00', '12:30:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(13, 1, 8, 17, 1, 'rabu', '07:45:00', '08:30:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(14, 1, 8, 17, 1, 'rabu', '08:30:00', '09:15:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(15, 1, 5, 23, 1, 'rabu', '09:15:00', '10:00:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(16, 1, 5, 23, 1, 'rabu', '10:15:00', '11:00:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(17, 1, 9, 9, 1, 'rabu', '11:00:00', '11:45:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(18, 1, 9, 9, 1, 'rabu', '11:45:00', '12:30:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(19, 1, 7, 25, 1, 'kamis', '07:45:00', '08:30:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(20, 1, 7, 25, 1, 'kamis', '08:30:00', '09:15:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(21, 1, 2, 2, 1, 'kamis', '09:15:00', '10:00:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(22, 1, 2, 2, 1, 'kamis', '10:15:00', '11:00:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(23, 1, 3, 21, 1, 'kamis', '11:00:00', '11:45:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(24, 1, 3, 21, 1, 'kamis', '11:45:00', '12:30:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(25, 1, 4, 13, 1, 'jumat', '07:45:00', '08:30:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(26, 1, 4, 13, 1, 'jumat', '08:30:00', '09:15:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(27, 1, 1, 10, 1, 'jumat', '09:15:00', '10:00:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(28, 1, 1, 10, 1, 'jumat', '10:15:00', '11:00:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(29, 1, 6, 15, 1, 'jumat', '11:00:00', '11:45:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(30, 1, 6, 15, 1, 'jumat', '11:45:00', '12:30:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(31, 1, 8, 17, 1, 'sabtu', '07:45:00', '08:30:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(32, 1, 8, 17, 1, 'sabtu', '08:30:00', '09:15:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(33, 1, 9, 9, 1, 'sabtu', '09:15:00', '10:00:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(34, 1, 5, 23, 1, 'sabtu', '10:15:00', '11:00:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(35, 1, 5, 23, 1, 'sabtu', '11:00:00', '11:45:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(36, 1, 7, 25, 1, 'sabtu', '11:45:00', '12:30:00', 'aktif', '2025-06-02 02:32:59', 'admin', '2025-06-02 03:14:29', 'admin'),
(37, 2, 7, 16, 1, 'senin', '07:45:00', '08:30:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(38, 2, 7, 16, 1, 'senin', '08:30:00', '09:15:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(39, 2, 2, 20, 1, 'senin', '09:15:00', '10:00:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(40, 2, 2, 20, 1, 'senin', '10:15:00', '11:00:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(41, 2, 3, 12, 1, 'senin', '11:00:00', '11:45:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(42, 2, 3, 12, 1, 'senin', '11:45:00', '12:30:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(43, 2, 1, 1, 1, 'selasa', '07:45:00', '08:30:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(44, 2, 1, 1, 1, 'selasa', '08:30:00', '09:15:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(45, 2, 8, 26, 1, 'selasa', '09:15:00', '10:00:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(46, 2, 8, 26, 1, 'selasa', '10:15:00', '11:00:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(47, 2, 9, 18, 1, 'selasa', '11:00:00', '11:45:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(48, 2, 9, 18, 1, 'selasa', '11:45:00', '12:30:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(49, 2, 7, 16, 1, 'rabu', '07:45:00', '08:30:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(50, 2, 7, 16, 1, 'rabu', '08:30:00', '09:15:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(51, 2, 9, 18, 1, 'rabu', '09:15:00', '10:00:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(52, 2, 9, 18, 1, 'rabu', '10:15:00', '11:00:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(53, 2, 6, 6, 1, 'rabu', '11:00:00', '11:45:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(54, 2, 6, 6, 1, 'rabu', '11:45:00', '12:30:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(55, 2, 4, 22, 1, 'kamis', '07:45:00', '08:30:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(56, 2, 4, 22, 1, 'kamis', '08:30:00', '09:15:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(57, 2, 1, 1, 1, 'kamis', '09:15:00', '10:00:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(58, 2, 1, 1, 1, 'kamis', '10:15:00', '11:00:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(59, 2, 3, 12, 1, 'kamis', '11:00:00', '11:45:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(60, 2, 3, 12, 1, 'kamis', '11:45:00', '12:30:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(61, 2, 8, 26, 1, 'jumat', '07:45:00', '08:30:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(62, 2, 8, 26, 1, 'jumat', '08:30:00', '09:15:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(63, 2, 5, 5, 1, 'jumat', '09:15:00', '10:00:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(64, 2, 5, 5, 1, 'jumat', '10:15:00', '11:00:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(65, 2, 2, 20, 1, 'jumat', '11:00:00', '11:45:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(66, 2, 2, 20, 1, 'jumat', '11:45:00', '12:30:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(67, 2, 5, 5, 1, 'sabtu', '07:45:00', '08:30:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(68, 2, 5, 5, 1, 'sabtu', '08:30:00', '09:15:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(69, 2, 2, 20, 1, 'sabtu', '09:15:00', '10:00:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(70, 2, 4, 22, 1, 'sabtu', '10:15:00', '11:00:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(71, 2, 4, 22, 1, 'sabtu', '11:00:00', '11:45:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(72, 2, 6, 6, 1, 'sabtu', '11:45:00', '12:30:00', 'aktif', '2025-06-02 02:46:56', 'admin', '2025-06-02 02:46:56', NULL),
(107, 3, 3, 21, 1, 'senin', '07:45:00', '08:30:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(108, 3, 3, 21, 1, 'senin', '08:30:00', '09:15:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(109, 3, 8, 17, 1, 'senin', '09:15:00', '10:00:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(110, 3, 8, 17, 1, 'senin', '10:15:00', '11:00:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(111, 3, 9, 9, 1, 'senin', '11:00:00', '11:45:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(112, 3, 9, 9, 1, 'senin', '11:45:00', '12:30:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(113, 3, 6, 15, 1, 'selasa', '07:45:00', '08:30:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(114, 3, 6, 15, 1, 'selasa', '08:30:00', '09:15:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(115, 3, 4, 13, 1, 'selasa', '09:15:00', '10:00:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(116, 3, 4, 13, 1, 'selasa', '10:15:00', '11:00:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(117, 3, 1, 1, 1, 'selasa', '11:00:00', '11:45:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(118, 3, 1, 1, 1, 'selasa', '11:45:00', '12:30:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(119, 3, 7, 25, 1, 'rabu', '07:45:00', '08:30:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(120, 3, 7, 25, 1, 'rabu', '08:30:00', '09:15:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(121, 3, 3, 21, 1, 'rabu', '09:15:00', '10:00:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(122, 3, 3, 21, 1, 'rabu', '10:15:00', '11:00:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(123, 3, 5, 23, 1, 'rabu', '11:00:00', '11:45:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(124, 3, 5, 23, 1, 'rabu', '11:45:00', '12:30:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(125, 3, 9, 9, 1, 'kamis', '07:45:00', '08:30:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(126, 3, 9, 9, 1, 'kamis', '08:30:00', '09:15:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(127, 3, 8, 17, 1, 'kamis', '09:15:00', '10:00:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(128, 3, 8, 17, 1, 'kamis', '10:15:00', '11:00:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(129, 3, 1, 10, 1, 'kamis', '11:00:00', '11:45:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(130, 3, 1, 10, 1, 'kamis', '11:45:00', '12:30:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(131, 3, 5, 23, 1, 'jumat', '07:45:00', '08:30:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(132, 3, 5, 23, 1, 'jumat', '08:30:00', '09:15:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(133, 3, 7, 25, 1, 'jumat', '09:15:00', '10:00:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(134, 3, 7, 25, 1, 'jumat', '10:15:00', '11:00:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(135, 3, 2, 2, 1, 'jumat', '11:00:00', '11:45:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(136, 3, 2, 2, 1, 'jumat', '11:45:00', '12:30:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(137, 3, 2, 2, 1, 'sabtu', '07:45:00', '08:30:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(138, 3, 2, 2, 1, 'sabtu', '08:30:00', '09:15:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(139, 3, 1, 10, 1, 'sabtu', '09:15:00', '10:00:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(140, 3, 1, 10, 1, 'sabtu', '10:15:00', '11:00:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(141, 3, 4, 13, 1, 'sabtu', '11:00:00', '11:45:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(142, 3, 4, 13, 1, 'sabtu', '11:45:00', '12:30:00', 'aktif', '2025-06-02 03:09:04', 'admin', '2025-06-02 03:09:04', NULL),
(143, 4, 7, 7, 1, 'sabtu', '11:45:00', '12:30:00', 'aktif', '2025-06-02 03:15:55', 'admin', '2025-06-02 03:15:55', NULL),
(144, 5, 7, 16, 1, 'sabtu', '11:45:00', '12:30:00', 'aktif', '2025-06-02 03:16:10', 'admin', '2025-06-02 03:16:10', NULL),
(145, 6, 9, 18, 1, 'sabtu', '11:45:00', '12:30:00', 'aktif', '2025-06-02 03:16:35', 'admin', '2025-06-02 03:16:35', NULL),
(146, 7, 9, 27, 1, 'sabtu', '11:45:00', '12:30:00', 'aktif', '2025-06-02 03:16:49', 'admin', '2025-06-02 03:16:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` bigint(20) UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) NOT NULL,
  `tingkat` varchar(255) NOT NULL,
  `id_guru` bigint(20) UNSIGNED NOT NULL,
  `id_tahun_ajaran` bigint(20) UNSIGNED DEFAULT NULL,
  `dibuat_pada` timestamp NULL DEFAULT NULL,
  `dibuat_oleh` varchar(255) DEFAULT NULL,
  `diperbarui_pada` timestamp NULL DEFAULT NULL,
  `diperbarui_oleh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `tingkat`, `id_guru`, `id_tahun_ajaran`, `dibuat_pada`, `dibuat_oleh`, `diperbarui_pada`, `diperbarui_oleh`) VALUES
(1, '7A', '1', 1, 1, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(2, '7B', '1', 2, 1, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(3, '7C', '1', 3, 1, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(4, '8A', '2', 4, 1, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(5, '8B', '2', 5, 1, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(6, '9A', '3', 6, 1, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(7, '9B', '3', 7, 1, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system');

-- --------------------------------------------------------

--
-- Table structure for table `mata_pelajaran`
--

CREATE TABLE `mata_pelajaran` (
  `id_mata_pelajaran` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kode` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `dibuat_pada` timestamp NULL DEFAULT NULL,
  `dibuat_oleh` varchar(255) DEFAULT NULL,
  `diperbarui_pada` timestamp NULL DEFAULT NULL,
  `diperbarui_oleh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_pelajaran`
--

INSERT INTO `mata_pelajaran` (`id_mata_pelajaran`, `nama`, `kode`, `deskripsi`, `dibuat_pada`, `dibuat_oleh`, `diperbarui_pada`, `diperbarui_oleh`) VALUES
(1, 'Matematika', 'MTK', 'Pelajaran tentang ilmu hitung dan logika', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(2, 'Bahasa Indonesia', 'BIN', 'Pelajaran tentang bahasa dan sastra Indonesia', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(3, 'Bahasa Inggris', 'BIG', 'Pelajaran tentang bahasa Inggris', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(4, 'Ilmu Pengetahuan Alam', 'IPA', 'Pelajaran tentang ilmu alam dan sains', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(5, 'Pendidikan Pancasila dan Kewarganegaraan', 'PKN', 'Pelajaran tentang nilai-nilai Pancasila dan kewarganegaraan', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(6, 'Ilmu Pengetahuan Sosial', 'IPS', 'Pelajaran tentang ilmu sosial dan masyarakat', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(7, 'Seni Budaya', 'SBD', 'Pelajaran tentang seni dan budaya Indonesia', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(8, 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'PJK', 'Pelajaran tentang pendidikan jasmani, olahraga dan kesehatan', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(9, 'Prakarya', 'PKY', 'Pelajaran tentang keterampilan dan prakarya', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0000_01_20_082618_create_role_table', 1),
(2, '0000_03_20_082618_create_tahun_ajaran_table', 1),
(3, '0001_01_01_000000_create_users_table', 1),
(4, '0003_03_19_011601_create_mata_pelajaran_table', 1),
(5, '0004_03_19_011600_create_guru_table', 1),
(6, '0005_03_19_011602_create_kelas_table', 1),
(7, '0006_03_19_011602_create_orang_tua_table', 1),
(8, '0007_03_19_011602_create_siswa_table', 1),
(9, '0008_03_19_011603_create_surat_izin_table', 1),
(10, '0009_03_19_011602_create_jadwal_pelajaran_table', 1),
(11, '0010_03_19_011602_create_absensi_table', 1),
(12, '0011_03_19_120411_create_staf_table', 1),
(13, '2025_03_24_095245_create_rekap_table', 1),
(14, '2025_03_24_095305_create_notif_table', 1),
(15, '2025_03_24_095521_create_guru_mata_pelajaran_table', 1),
(16, '2025_03_26_000000_create_sessions_table', 1),
(17, '2025_04_15_024951_create_personal_access_tokens_table', 1),
(18, '2025_04_15_074741_add_id_tahun_ajaran_to_kelas_table', 1),
(19, '2025_04_15_075600_add_id_tahun_ajaran_to_siswa_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id_notifikasi` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `tipe` varchar(255) NOT NULL DEFAULT 'info',
  `dibaca` tinyint(1) NOT NULL DEFAULT 0,
  `waktu_dibaca` timestamp NULL DEFAULT NULL,
  `dibuat_pada` timestamp NULL DEFAULT NULL,
  `dibuat_oleh` varchar(255) DEFAULT NULL,
  `diperbarui_pada` timestamp NULL DEFAULT NULL,
  `diperbarui_oleh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id_notifikasi`, `id_user`, `judul`, `pesan`, `tipe`, `dibaca`, `waktu_dibaca`, `dibuat_pada`, `dibuat_oleh`, `diperbarui_pada`, `diperbarui_oleh`) VALUES
(1, 1, 'Selamat Datang', 'Selamat datang di sistem absensi sekolah.', 'info', 1, '2025-05-28 02:20:08', '2025-05-27 02:20:08', 'system', '2025-05-28 02:20:08', 'system'),
(2, 2, 'Surat Izin Disetujui', 'Surat izin untuk Andi Pratama telah disetujui.', 'success', 1, '2025-05-30 02:20:08', '2025-05-29 02:20:08', 'system', '2025-05-30 02:20:08', 'system'),
(3, 3, 'Surat Izin Menunggu Persetujuan', 'Surat izin untuk Rizki Ramadhan sedang menunggu persetujuan.', 'warning', 0, NULL, '2025-06-01 02:20:08', 'system', '2025-06-01 02:20:08', 'system'),
(4, 4, 'Jadwal Mengajar Baru', 'Anda memiliki jadwal mengajar baru untuk kelas 7A pada hari Senin.', 'info', 1, '2025-05-31 02:20:08', '2025-05-30 02:20:08', 'system', '2025-05-31 02:20:08', 'system'),
(5, 5, 'Pengumuman Rapat Guru', 'Rapat guru akan diadakan pada tanggal 25 Mei 2025 pukul 14:00.', 'info', 0, NULL, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system');

-- --------------------------------------------------------

--
-- Table structure for table `orangtua`
--

CREATE TABLE `orangtua` (
  `id_orangtua` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `nomor_telepon` varchar(255) DEFAULT NULL,
  `pekerjaan` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif','pending') NOT NULL DEFAULT 'pending' COMMENT 'Status akun pengguna',
  `dibuat_pada` timestamp NULL DEFAULT NULL,
  `dibuat_oleh` varchar(255) DEFAULT NULL,
  `diperbarui_pada` timestamp NULL DEFAULT NULL,
  `diperbarui_oleh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orangtua`
--

INSERT INTO `orangtua` (`id_orangtua`, `id_user`, `nama_lengkap`, `alamat`, `nomor_telepon`, `pekerjaan`, `status`, `dibuat_pada`, `dibuat_oleh`, `diperbarui_pada`, `diperbarui_oleh`) VALUES
(1, 2, 'Budi Santoso', 'Jl. Merdeka No. 123, Jakarta Selatan', '081234567894', 'Wiraswasta', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(2, 3, 'Siti Rahayu', 'Jl. Pahlawan No. 45, Jakarta Timur', '081234567895', 'Guru', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rekap_absensi`
--

CREATE TABLE `rekap_absensi` (
  `id_rekap` bigint(20) UNSIGNED NOT NULL,
  `id_siswa` bigint(20) UNSIGNED NOT NULL,
  `id_kelas` bigint(20) UNSIGNED NOT NULL,
  `bulan` varchar(2) NOT NULL,
  `tahun` year(4) NOT NULL,
  `jumlah_hadir` int(11) NOT NULL DEFAULT 0,
  `jumlah_sakit` int(11) NOT NULL DEFAULT 0,
  `jumlah_izin` int(11) NOT NULL DEFAULT 0,
  `jumlah_alpa` int(11) NOT NULL DEFAULT 0,
  `dibuat_pada` timestamp NULL DEFAULT NULL,
  `dibuat_oleh` varchar(255) DEFAULT NULL,
  `diperbarui_pada` timestamp NULL DEFAULT NULL,
  `diperbarui_oleh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id_role` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id_role`, `role`, `deskripsi`) VALUES
(1, 'staf', 'Administrator sistem'),
(2, 'orangtua', 'Orangtua atau wali siswa'),
(3, 'guru', 'Guru atau pengajar');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1Tg9PF297Zjfk7o1MxdgvjEqxtM57mtfySRihRLi', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUXhQNHhHVjZFU2JDRVhxem1CMlZLUUZqOWVZTzF6SGk3WjhXYzZLTSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2phZHdhbC1wZWxhamFyYW4iO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2tlbGFzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1748834518);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nis` varchar(255) NOT NULL,
  `id_orangtua` bigint(20) UNSIGNED DEFAULT NULL,
  `id_kelas` bigint(20) UNSIGNED NOT NULL,
  `id_tahun_ajaran` bigint(20) UNSIGNED DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('laki-laki','perempuan') NOT NULL,
  `alamat` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `dibuat_pada` timestamp NULL DEFAULT NULL,
  `dibuat_oleh` varchar(255) DEFAULT NULL,
  `diperbarui_pada` timestamp NULL DEFAULT NULL,
  `diperbarui_oleh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama`, `nis`, `id_orangtua`, `id_kelas`, `id_tahun_ajaran`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `status`, `dibuat_pada`, `dibuat_oleh`, `diperbarui_pada`, `diperbarui_oleh`) VALUES
(1, 'Andi Pratama', '2024001', 1, 1, 1, 'Jakarta', '2012-05-15', 'laki-laki', 'Jl. Merdeka No. 123, Jakarta Selatan', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(2, 'Dina Fitriani', '2024002', 1, 1, 1, 'Bandung', '2012-08-20', 'perempuan', 'Jl. Merdeka No. 123, Jakarta Selatan', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(3, 'Rizki Ramadhan', '2024003', 2, 2, 1, 'Surabaya', '2012-03-10', 'laki-laki', 'Jl. Pahlawan No. 45, Jakarta Timur', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(4, 'Maya Sari', '2024004', 2, 3, 1, 'Yogyakarta', '2011-11-25', 'perempuan', 'Jl. Pahlawan No. 45, Jakarta Timur', 'aktif', '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system');

-- --------------------------------------------------------

--
-- Table structure for table `staf`
--

CREATE TABLE `staf` (
  `id_staf` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `nomor_telepon` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `dibuat_pada` timestamp NULL DEFAULT NULL,
  `dibuat_oleh` varchar(255) DEFAULT NULL,
  `diperbarui_pada` timestamp NULL DEFAULT NULL,
  `diperbarui_oleh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `surat_izin`
--

CREATE TABLE `surat_izin` (
  `id_surat_izin` bigint(20) UNSIGNED NOT NULL,
  `id_siswa` bigint(20) UNSIGNED NOT NULL,
  `id_orangtua` bigint(20) UNSIGNED NOT NULL,
  `jenis` enum('sakit','izin') NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `alasan` text NOT NULL,
  `file_lampiran` varchar(255) DEFAULT NULL,
  `status` enum('menunggu','disetujui','ditolak') NOT NULL DEFAULT 'menunggu',
  `dibuat_pada` timestamp NULL DEFAULT NULL,
  `dibuat_oleh` varchar(255) DEFAULT NULL,
  `diperbarui_pada` timestamp NULL DEFAULT NULL,
  `diperbarui_oleh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surat_izin`
--

INSERT INTO `surat_izin` (`id_surat_izin`, `id_siswa`, `id_orangtua`, `jenis`, `tanggal_mulai`, `tanggal_selesai`, `alasan`, `file_lampiran`, `status`, `dibuat_pada`, `dibuat_oleh`, `diperbarui_pada`, `diperbarui_oleh`) VALUES
(1, 1, 1, 'sakit', '2025-05-05', '2025-05-06', 'Sakit demam dan flu', 'surat_dokter_1.pdf', 'disetujui', '2025-05-28 02:20:08', 'Budi Santoso', '2025-05-29 02:20:08', 'Ahmad Wijaya'),
(2, 2, 1, 'izin', '2025-05-10', '2025-05-10', 'Acara keluarga', NULL, 'disetujui', '2025-05-30 02:20:08', 'Budi Santoso', '2025-05-31 02:20:08', 'Ahmad Wijaya'),
(3, 3, 2, 'sakit', '2025-05-15', '2025-05-17', 'Sakit perut', 'surat_dokter_2.pdf', 'menunggu', '2025-06-01 02:20:08', 'Siti Rahayu', '2025-06-01 02:20:08', 'Siti Rahayu');

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `id_tahun_ajaran` bigint(20) UNSIGNED NOT NULL,
  `nama_tahun_ajaran` varchar(255) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 0,
  `dibuat_pada` timestamp NULL DEFAULT NULL,
  `dibuat_oleh` varchar(255) DEFAULT NULL,
  `diperbarui_pada` timestamp NULL DEFAULT NULL,
  `diperbarui_oleh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`id_tahun_ajaran`, `nama_tahun_ajaran`, `tanggal_mulai`, `tanggal_selesai`, `aktif`, `dibuat_pada`, `dibuat_oleh`, `diperbarui_pada`, `diperbarui_oleh`) VALUES
(1, 'Tahun Ajaran 2024/2025', '2024-07-15', '2025-06-30', 1, '2025-06-02 02:20:02', 'system', '2025-06-02 02:20:02', 'system'),
(2, 'Tahun Ajaran 2025/2026', '2025-07-15', '2026-06-30', 0, '2025-06-02 02:20:02', 'system', '2025-06-02 02:20:02', 'system');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_role` bigint(20) UNSIGNED NOT NULL,
  `fcm_token` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `dibuat_pada` timestamp NULL DEFAULT NULL,
  `dibuat_oleh` varchar(255) DEFAULT NULL,
  `diperbarui_pada` timestamp NULL DEFAULT NULL,
  `diperbarui_oleh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `id_role`, `fcm_token`, `remember_token`, `last_login_at`, `dibuat_pada`, `dibuat_oleh`, `diperbarui_pada`, `diperbarui_oleh`) VALUES
(1, 'admin', '$2y$12$TJpw5x6jfGxProXD2Q9S5eGi4ahUs8C9lQzTtSWrx.h.i9qINC2h6', 1, NULL, NULL, '2025-06-02 02:20:23', '2025-06-02 02:20:02', 'system', '2025-06-02 02:20:23', 'system'),
(2, 'budi_santoso', '$2y$12$RX/o6/zk.z7nT4gynwrCBuvJswNrg5YE5i4iu3o9l.xCChV7b04je', 2, NULL, NULL, NULL, '2025-06-02 02:20:02', 'system', '2025-06-02 02:20:02', 'system'),
(3, 'siti_rahayu', '$2y$12$Lb8/OVEp3lzU8Y6734bAvOwnzpSQAi7/MwrRmRA/exCsxdb6dqprm', 2, NULL, NULL, NULL, '2025-06-02 02:20:03', 'system', '2025-06-02 02:20:03', 'system'),
(4, 'ahmad_wijaya', '$2y$12$STKJlPY5gPF6MeLbwZuQZ.kZQfOjkFUbzA8eJ/lXFoSedoDfdwTCy', 3, NULL, NULL, NULL, '2025-06-02 02:20:03', 'system', '2025-06-02 02:20:03', 'system'),
(5, 'dewi_susanti', '$2y$12$60g8UbHkc0s32xqx97xscO751rCkVYPmbZAPoZVIfLeHaJhZHOQv2', 3, NULL, NULL, NULL, '2025-06-02 02:20:03', 'system', '2025-06-02 02:20:03', 'system'),
(6, 'sarah_johnson', '$2y$12$Hd4rm69DaQ/ZURZ7vPNpKuph/M5Jp0TtS/jTxYvFjfSoHGRxjjpvK', 3, NULL, NULL, NULL, '2025-06-02 02:20:03', 'system', '2025-06-02 02:20:03', 'system'),
(7, 'rini_wulandari', '$2y$12$VJ1Ovi.rxp77XwQBdexq2OvIRN/VSChu2mMhVEYJ4CdsLKAH9//Mu', 3, NULL, NULL, NULL, '2025-06-02 02:20:03', 'system', '2025-06-02 02:20:03', 'system'),
(8, 'budi_santoso_guru', '$2y$12$FJWO2KrzRtILCa.BlUcXNOgqdbOcW51Y2fJUeQcHwSmvYHroTu/im', 3, NULL, NULL, NULL, '2025-06-02 02:20:04', 'system', '2025-06-02 02:20:04', 'system'),
(9, 'siti_nurhaliza', '$2y$12$sMIvmfMEwhPhSGjGicuXmO7GsUC8eVCj1gIX3fGClvIF1arfdNV52', 3, NULL, NULL, NULL, '2025-06-02 02:20:04', 'system', '2025-06-02 02:20:04', 'system'),
(10, 'indira_sari', '$2y$12$IvxEBEZS6oHf9DQM7YcQA.6HU3qrC2NTh8HD65Mv7x7c55y2NYZoS', 3, NULL, NULL, NULL, '2025-06-02 02:20:04', 'system', '2025-06-02 02:20:04', 'system'),
(11, 'yoga_pratama', '$2y$12$LJspMZ6kdg9/gni86Amp2.XSjfmxciat36aKpwE7REMjfsDxydNlq', 3, NULL, NULL, NULL, '2025-06-02 02:20:04', 'system', '2025-06-02 02:20:04', 'system'),
(12, 'andi_kurniawan', '$2y$12$LTny/0.amuH.3AORyPdk..d9cmC82KtWO6wIrow.yoJObzuc/9vtu', 3, NULL, NULL, NULL, '2025-06-02 02:20:04', 'system', '2025-06-02 02:20:04', 'system'),
(20, 'sari_matematika', '$2y$12$TnxEjYbg3/KKobUISlJT.umLJ5T.GTAX8FtfLAdTW3NkYlBdIuxea', 3, NULL, NULL, NULL, '2025-06-02 02:20:05', 'system', '2025-06-02 02:20:05', 'system'),
(21, 'rina_indonesia', '$2y$12$Mmkx005xoW89osWEYBbegu.SDkWgz6a/xnT6QUhTKG.cf7EnaaLne', 3, NULL, NULL, NULL, '2025-06-02 02:20:05', 'system', '2025-06-02 02:20:05', 'system'),
(22, 'maya_english', '$2y$12$f03CPIP83M.BN7v5P.NsqeZDFSTaeIMhE6t23mF/JZs8CrVb4YLoO', 3, NULL, NULL, NULL, '2025-06-02 02:20:05', 'system', '2025-06-02 02:20:05', 'system'),
(23, 'doni_sains', '$2y$12$MJFRNX/u5Wttpn81vTblyuewHRC0lusYxwZddh4EX/aCApqb1ykZ2', 3, NULL, NULL, NULL, '2025-06-02 02:20:05', 'system', '2025-06-02 02:20:05', 'system'),
(24, 'lina_civics', '$2y$12$SOz71bH0DrSTt6wVABCnYuidi0WgWIZF/.OPjEV.2Qp3KAbJ6HgKW', 3, NULL, NULL, NULL, '2025-06-02 02:20:05', 'system', '2025-06-02 02:20:05', 'system'),
(25, 'eko_sosial', '$2y$12$19snuMw9f3dDr0hYLwQVteEPI.COXatCRVnS0a1Ek7fZCtHsSfTcG', 3, NULL, NULL, NULL, '2025-06-02 02:20:06', 'system', '2025-06-02 02:20:06', 'system'),
(26, 'fitri_seni', '$2y$12$uLVL3nqRa3VXIguwVN.JIenJV4jjPTCtoBgJwkuiKi99oXfp6uHRG', 3, NULL, NULL, NULL, '2025-06-02 02:20:06', 'system', '2025-06-02 02:20:06', 'system'),
(27, 'agus_sport', '$2y$12$8oDx0JeGk/9cNIVXl735Ge95i6kE1tFn.LeZbaZCanv9XheAY549q', 3, NULL, NULL, NULL, '2025-06-02 02:20:06', 'system', '2025-06-02 02:20:06', 'system'),
(28, 'nina_craft', '$2y$12$i/KnkgrRuRu1m1IefSMBhOAUPbkU4IbHONP0/xvPI.CE1IBtqgPY6', 3, NULL, NULL, NULL, '2025-06-02 02:20:06', 'system', '2025-06-02 02:20:06', 'system'),
(30, 'hendra_math', '$2y$12$76NFMPvhlJV620F0mpFBdeS6XneYOect9B2mlkWel2pUTDZkBm8k2', 3, NULL, NULL, NULL, '2025-06-02 02:20:06', 'system', '2025-06-02 02:20:06', 'system'),
(31, 'lisa_bahasa', '$2y$12$MqWaEufNqthHEI0qyU3fbOdOAvhdBsElyUPdWw3pz6rhBUioD./pS', 3, NULL, NULL, NULL, '2025-06-02 02:20:06', 'system', '2025-06-02 02:20:06', 'system'),
(32, 'david_english', '$2y$12$ek/7QSTJIWLjgc99Dr7sw.g1TiOcnD4e6sJrMbpHMh3Vk8zBxhjBm', 3, NULL, NULL, NULL, '2025-06-02 02:20:07', 'system', '2025-06-02 02:20:07', 'system'),
(33, 'ratna_science', '$2y$12$yY6kjDL0rPb6RjBb8O7MKOjrghBQdu3kYCZOhoe7dr6JkbguZFY6y', 3, NULL, NULL, NULL, '2025-06-02 02:20:07', 'system', '2025-06-02 02:20:07', 'system'),
(34, 'bambang_pkn', '$2y$12$68029N9aXuZXk6.J14x5Ke5X9QmVXBkBvoWkj.CTNjPLv2e1h828G', 3, NULL, NULL, NULL, '2025-06-02 02:20:07', 'system', '2025-06-02 02:20:07', 'system'),
(35, 'wati_ips', '$2y$12$IWvU2zKInkgtoav0XRMfCONN3LgpnxRXYTiceDuCwBGNQuvh.kEz2', 3, NULL, NULL, NULL, '2025-06-02 02:20:07', 'system', '2025-06-02 02:20:07', 'system'),
(36, 'andi_art', '$2y$12$tLtL0lUAjLnjez3A3VghPew4jpO4/fgNmgMWJ0pz2GO.4yiDvpUya', 3, NULL, NULL, NULL, '2025-06-02 02:20:07', 'system', '2025-06-02 02:20:07', 'system'),
(37, 'rudi_sport', '$2y$12$uOR.tXwpG3En55owQTBTFuYK2zVgdjlKUWdTsois6HNElbnW9Ea5q', 3, NULL, NULL, NULL, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system'),
(38, 'sinta_craft', '$2y$12$ubx/pmJGEkPwxDxnmHiWMOQj5iOhxKHkDM/882kWeA8KK.BtmyfcK', 3, NULL, NULL, NULL, '2025-06-02 02:20:08', 'system', '2025-06-02 02:20:08', 'system');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD UNIQUE KEY `absensi_id_siswa_id_jadwal_tanggal_unique` (`id_siswa`,`id_jadwal`,`tanggal`),
  ADD KEY `absensi_id_jadwal_foreign` (`id_jadwal`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`),
  ADD UNIQUE KEY `guru_id_user_unique` (`id_user`),
  ADD UNIQUE KEY `guru_nip_unique` (`nip`);

--
-- Indexes for table `guru_mata_pelajaran`
--
ALTER TABLE `guru_mata_pelajaran`
  ADD PRIMARY KEY (`id_guru_mata_pelajaran`),
  ADD UNIQUE KEY `guru_mata_pelajaran_id_guru_id_mata_pelajaran_unique` (`id_guru`,`id_mata_pelajaran`),
  ADD KEY `guru_mata_pelajaran_id_mata_pelajaran_foreign` (`id_mata_pelajaran`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `jadwal_id_kelas_foreign` (`id_kelas`),
  ADD KEY `jadwal_id_mata_pelajaran_foreign` (`id_mata_pelajaran`),
  ADD KEY `jadwal_id_guru_foreign` (`id_guru`),
  ADD KEY `jadwal_id_tahun_ajaran_foreign` (`id_tahun_ajaran`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `kelas_id_guru_foreign` (`id_guru`),
  ADD KEY `kelas_id_tahun_ajaran_foreign` (`id_tahun_ajaran`);

--
-- Indexes for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  ADD PRIMARY KEY (`id_mata_pelajaran`),
  ADD UNIQUE KEY `mata_pelajaran_kode_unique` (`kode`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id_notifikasi`),
  ADD KEY `notifikasi_id_user_foreign` (`id_user`);

--
-- Indexes for table `orangtua`
--
ALTER TABLE `orangtua`
  ADD PRIMARY KEY (`id_orangtua`),
  ADD KEY `orangtua_id_user_foreign` (`id_user`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `rekap_absensi`
--
ALTER TABLE `rekap_absensi`
  ADD PRIMARY KEY (`id_rekap`),
  ADD UNIQUE KEY `rekap_absensi_id_siswa_id_kelas_bulan_tahun_unique` (`id_siswa`,`id_kelas`,`bulan`,`tahun`),
  ADD KEY `rekap_absensi_id_kelas_foreign` (`id_kelas`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `siswa_nis_unique` (`nis`),
  ADD KEY `siswa_id_orangtua_foreign` (`id_orangtua`),
  ADD KEY `siswa_id_kelas_foreign` (`id_kelas`),
  ADD KEY `siswa_id_tahun_ajaran_foreign` (`id_tahun_ajaran`);

--
-- Indexes for table `staf`
--
ALTER TABLE `staf`
  ADD PRIMARY KEY (`id_staf`),
  ADD UNIQUE KEY `staf_nip_unique` (`nip`),
  ADD KEY `staf_id_user_foreign` (`id_user`);

--
-- Indexes for table `surat_izin`
--
ALTER TABLE `surat_izin`
  ADD PRIMARY KEY (`id_surat_izin`),
  ADD KEY `surat_izin_id_siswa_foreign` (`id_siswa`),
  ADD KEY `surat_izin_id_orangtua_foreign` (`id_orangtua`);

--
-- Indexes for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`id_tahun_ajaran`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_id_role_foreign` (`id_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `guru_mata_pelajaran`
--
ALTER TABLE `guru_mata_pelajaran`
  MODIFY `id_guru_mata_pelajaran` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `id_mata_pelajaran` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notifikasi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orangtua`
--
ALTER TABLE `orangtua`
  MODIFY `id_orangtua` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rekap_absensi`
--
ALTER TABLE `rekap_absensi`
  MODIFY `id_rekap` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `staf`
--
ALTER TABLE `staf`
  MODIFY `id_staf` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surat_izin`
--
ALTER TABLE `surat_izin`
  MODIFY `id_surat_izin` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  MODIFY `id_tahun_ajaran` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_id_jadwal_foreign` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id_jadwal`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_id_siswa_foreign` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE;

--
-- Constraints for table `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `guru_mata_pelajaran`
--
ALTER TABLE `guru_mata_pelajaran`
  ADD CONSTRAINT `guru_mata_pelajaran_id_guru_foreign` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE,
  ADD CONSTRAINT `guru_mata_pelajaran_id_mata_pelajaran_foreign` FOREIGN KEY (`id_mata_pelajaran`) REFERENCES `mata_pelajaran` (`id_mata_pelajaran`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_id_guru_foreign` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_id_kelas_foreign` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_id_mata_pelajaran_foreign` FOREIGN KEY (`id_mata_pelajaran`) REFERENCES `mata_pelajaran` (`id_mata_pelajaran`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_id_tahun_ajaran_foreign` FOREIGN KEY (`id_tahun_ajaran`) REFERENCES `tahun_ajaran` (`id_tahun_ajaran`) ON DELETE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_id_guru_foreign` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`),
  ADD CONSTRAINT `kelas_id_tahun_ajaran_foreign` FOREIGN KEY (`id_tahun_ajaran`) REFERENCES `tahun_ajaran` (`id_tahun_ajaran`) ON DELETE SET NULL;

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `orangtua`
--
ALTER TABLE `orangtua`
  ADD CONSTRAINT `orangtua_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `rekap_absensi`
--
ALTER TABLE `rekap_absensi`
  ADD CONSTRAINT `rekap_absensi_id_kelas_foreign` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE,
  ADD CONSTRAINT `rekap_absensi_id_siswa_foreign` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_id_kelas_foreign` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE,
  ADD CONSTRAINT `siswa_id_orangtua_foreign` FOREIGN KEY (`id_orangtua`) REFERENCES `orangtua` (`id_orangtua`) ON DELETE CASCADE,
  ADD CONSTRAINT `siswa_id_tahun_ajaran_foreign` FOREIGN KEY (`id_tahun_ajaran`) REFERENCES `tahun_ajaran` (`id_tahun_ajaran`) ON DELETE SET NULL;

--
-- Constraints for table `staf`
--
ALTER TABLE `staf`
  ADD CONSTRAINT `staf_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `surat_izin`
--
ALTER TABLE `surat_izin`
  ADD CONSTRAINT `surat_izin_id_orangtua_foreign` FOREIGN KEY (`id_orangtua`) REFERENCES `orangtua` (`id_orangtua`) ON DELETE CASCADE,
  ADD CONSTRAINT `surat_izin_id_siswa_foreign` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_id_role_foreign` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
