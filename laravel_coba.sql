-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 07:34 PM
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

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `id_siswa`, `id_jadwal`, `tanggal`, `status`, `catatan`, `dibuat_pada`, `dibuat_oleh`, `diperbarui_pada`, `diperbarui_oleh`) VALUES
(1, 1, 1, '2025-05-01', 'sakit', 'Sakit flu', '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(2, 2, 1, '2025-05-01', 'izin', 'Izin keluarga', '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(3, 1, 2, '2025-05-01', 'sakit', 'Sakit flu', '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(4, 2, 2, '2025-05-01', 'izin', 'Izin keluarga', '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(5, 1, 3, '2025-05-01', 'sakit', 'Sakit flu', '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(6, 2, 3, '2025-05-01', 'izin', 'Izin keluarga', '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(7, 3, 4, '2025-05-01', 'alpa', 'Tidak ada keterangan', '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(8, 4, 5, '2025-05-01', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(9, 1, 1, '2025-05-02', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(10, 2, 1, '2025-05-02', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(11, 1, 2, '2025-05-02', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(12, 2, 2, '2025-05-02', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(13, 1, 3, '2025-05-02', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(14, 2, 3, '2025-05-02', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(15, 3, 4, '2025-05-02', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(16, 4, 5, '2025-05-02', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(17, 1, 1, '2025-05-05', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(18, 2, 1, '2025-05-05', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(19, 1, 2, '2025-05-05', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(20, 2, 2, '2025-05-05', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(21, 1, 3, '2025-05-05', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(22, 2, 3, '2025-05-05', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(23, 3, 4, '2025-05-05', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(24, 4, 5, '2025-05-05', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(25, 1, 1, '2025-05-06', 'sakit', 'Sakit flu', '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(26, 2, 1, '2025-05-06', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(27, 1, 2, '2025-05-06', 'sakit', 'Sakit flu', '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(28, 2, 2, '2025-05-06', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(29, 1, 3, '2025-05-06', 'sakit', 'Sakit flu', '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(30, 2, 3, '2025-05-06', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(31, 3, 4, '2025-05-06', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(32, 4, 5, '2025-05-06', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(33, 1, 1, '2025-05-07', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(34, 2, 1, '2025-05-07', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(35, 1, 2, '2025-05-07', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(36, 2, 2, '2025-05-07', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(37, 1, 3, '2025-05-07', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(38, 2, 3, '2025-05-07', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(39, 3, 4, '2025-05-07', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(40, 4, 5, '2025-05-07', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(41, 1, 1, '2025-05-08', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(42, 2, 1, '2025-05-08', 'izin', 'Izin keluarga', '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(43, 1, 2, '2025-05-08', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(44, 2, 2, '2025-05-08', 'izin', 'Izin keluarga', '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(45, 1, 3, '2025-05-08', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(46, 2, 3, '2025-05-08', 'izin', 'Izin keluarga', '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(47, 3, 4, '2025-05-08', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(48, 4, 5, '2025-05-08', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(49, 1, 1, '2025-05-09', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(50, 2, 1, '2025-05-09', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(51, 1, 2, '2025-05-09', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(52, 2, 2, '2025-05-09', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(53, 1, 3, '2025-05-09', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(54, 2, 3, '2025-05-09', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(55, 3, 4, '2025-05-09', 'alpa', 'Tidak ada keterangan', '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(56, 4, 5, '2025-05-09', 'hadir', NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system');

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
(1, 4, 'Ahmad Wijaya', '198601202008011002', '081234567891', 'Matematika', 'aktif', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(2, 5, 'Dewi Susanti', '198703152009012003', '081234567892', 'Bahasa Indonesia', 'aktif', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(3, 6, 'Rini Wulandari', '198805102010012004', '081234567893', 'IPA', 'aktif', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system');

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
(1, 1, 1, '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(2, 2, 2, '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(3, 3, 3, '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(4, 1, 4, '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(5, 2, 5, '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system');

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
(1, 1, 1, 1, 1, 'senin', '07:30:00', '09:00:00', 'aktif', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(2, 1, 2, 2, 1, 'senin', '09:15:00', '10:45:00', 'aktif', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(3, 1, 3, 3, 1, 'selasa', '07:30:00', '09:00:00', 'aktif', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(4, 2, 1, 1, 1, 'rabu', '07:30:00', '09:00:00', 'aktif', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(5, 3, 2, 2, 1, 'kamis', '07:30:00', '09:00:00', 'aktif', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system');

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
(1, '7A', '7', 1, 1, '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(2, '7B', '7', 2, 1, '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(3, '8A', '8', 3, 1, '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system');

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
(1, 'Matematika', 'MTK', 'Pelajaran tentang ilmu hitung dan logika', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(2, 'Bahasa Indonesia', 'BIN', 'Pelajaran tentang bahasa dan sastra Indonesia', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(3, 'Ilmu Pengetahuan Alam', 'IPA', 'Pelajaran tentang ilmu alam dan sains', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(4, 'Ilmu Pengetahuan Sosial', 'IPS', 'Pelajaran tentang ilmu sosial dan masyarakat', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(5, 'Bahasa Inggris', 'BIG', 'Pelajaran tentang bahasa Inggris', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system');

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
(1, 1, 'Selamat Datang', 'Selamat datang di sistem absensi sekolah.', 'info', 1, '2025-05-16 17:07:44', '2025-05-15 17:07:44', 'system', '2025-05-16 17:07:44', 'system'),
(2, 2, 'Surat Izin Disetujui', 'Surat izin untuk Andi Pratama telah disetujui.', 'success', 1, '2025-05-18 17:07:44', '2025-05-17 17:07:44', 'system', '2025-05-18 17:07:44', 'system'),
(3, 3, 'Surat Izin Menunggu Persetujuan', 'Surat izin untuk Rizki Ramadhan sedang menunggu persetujuan.', 'warning', 0, NULL, '2025-05-20 17:07:44', 'system', '2025-05-20 17:07:44', 'system'),
(4, 4, 'Jadwal Mengajar Baru', 'Anda memiliki jadwal mengajar baru untuk kelas 7A pada hari Senin.', 'info', 1, '2025-05-19 17:07:44', '2025-05-18 17:07:44', 'system', '2025-05-19 17:07:44', 'system'),
(5, 5, 'Pengumuman Rapat Guru', 'Rapat guru akan diadakan pada tanggal 25 Mei 2025 pukul 14:00.', 'info', 0, NULL, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system');

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
(1, 2, 'Budi Santoso', 'Jl. Merdeka No. 123, Jakarta Selatan', '081234567894', 'Wiraswasta', 'aktif', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(2, 3, 'Siti Rahayu', 'Jl. Pahlawan No. 45, Jakarta Timur', '081234567895', 'Guru', 'aktif', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system');

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

--
-- Dumping data for table `rekap_absensi`
--

INSERT INTO `rekap_absensi` (`id_rekap`, `id_siswa`, `id_kelas`, `bulan`, `tahun`, `jumlah_hadir`, `jumlah_sakit`, `jumlah_izin`, `jumlah_alpa`, `dibuat_pada`, `dibuat_oleh`, `diperbarui_pada`, `diperbarui_oleh`) VALUES
(1, 1, 1, '05', '2025', 18, 2, 0, 0, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(2, 2, 1, '05', '2025', 17, 0, 3, 0, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(3, 3, 2, '05', '2025', 19, 0, 0, 1, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system'),
(4, 4, 3, '05', '2025', 20, 0, 0, 0, '2025-05-21 17:07:44', 'system', '2025-05-21 17:07:44', 'system');

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
('2RqSKzwUF6NcnBwHi8wIRlhyCg9XwjiBtLNLHLtz', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQlFycjlRVjA0WXVYS0hOeVBpN3BzT3ZtZFVSWlFyekNYUVQ2S2VJMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9qYWR3YWwtcGVsYWphcmFuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1747847484),
('xWJSgkLnHOU0dUpDhvcyl7A5yBMYQZ01e4Fm7U5b', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZlVBSU5IOUQ2MlhyTDJ3Y0VGTHlxd0pLNVRrVXVqRjlyWEhCWWNKaiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3RhaHVuLWFqYXJhbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvdGFodW4tYWphcmFuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1747848633);

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
(1, 'Andi Pratama', '2024001', 1, 1, 1, 'Jakarta', '2012-05-15', 'laki-laki', 'Jl. Merdeka No. 123, Jakarta Selatan', 'aktif', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(2, 'Dina Fitriani', '2024002', 1, 1, 1, 'Bandung', '2012-08-20', 'perempuan', 'Jl. Merdeka No. 123, Jakarta Selatan', 'aktif', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(3, 'Rizki Ramadhan', '2024003', 2, 2, 1, 'Surabaya', '2012-03-10', 'laki-laki', 'Jl. Pahlawan No. 45, Jakarta Timur', 'aktif', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(4, 'Maya Sari', '2024004', 2, 3, 1, 'Yogyakarta', '2011-11-25', 'perempuan', 'Jl. Pahlawan No. 45, Jakarta Timur', 'aktif', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system');

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

--
-- Dumping data for table `staf`
--

INSERT INTO `staf` (`id_staf`, `id_user`, `nama_lengkap`, `nip`, `nomor_telepon`, `jabatan`, `dibuat_pada`, `dibuat_oleh`, `diperbarui_pada`, `diperbarui_oleh`) VALUES
(1, 1, 'Bambang Suryanto', '198501152010011001', '081234567890', 'Administrator Sistem', '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system');

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
(1, 1, 1, 'sakit', '2025-05-05', '2025-05-06', 'Sakit demam dan flu', 'surat_dokter_1.pdf', 'disetujui', '2025-05-16 17:07:44', 'Budi Santoso', '2025-05-17 17:07:44', 'Ahmad Wijaya'),
(2, 2, 1, 'izin', '2025-05-10', '2025-05-10', 'Acara keluarga', NULL, 'disetujui', '2025-05-18 17:07:44', 'Budi Santoso', '2025-05-19 17:07:44', 'Ahmad Wijaya'),
(3, 3, 2, 'sakit', '2025-05-15', '2025-05-17', 'Sakit perut', 'surat_dokter_2.pdf', 'menunggu', '2025-05-20 17:07:44', 'Siti Rahayu', '2025-05-20 17:07:44', 'Siti Rahayu');

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
(1, 'Tahun Ajaran 2024/2025', '2024-07-15', '2025-06-30', 1, '2025-05-21 17:07:42', 'system', '2025-05-21 17:07:42', 'system'),
(2, 'Tahun Ajaran 2025/2026', '2025-07-15', '2026-06-30', 0, '2025-05-21 17:07:42', 'system', '2025-05-21 17:07:42', 'system');

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
(1, 'admin', '$2y$12$013xT6x6Zf0kxTV7K4R2heBzhw4vQQcPM8XAZuSxGHfQ12vcJ0YZm', 1, NULL, NULL, '2025-05-21 17:09:09', '2025-05-21 17:07:42', 'system', '2025-05-21 17:09:09', 'system'),
(2, 'budi_santoso', '$2y$12$Mp31xAp4I8ZXk0soOiBvneikG/hqqhvQQeOVsrwrjgyXNboNpNns6', 2, NULL, NULL, NULL, '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(3, 'siti_rahayu', '$2y$12$o6.IavJKUZpqefgennCYi.U8hg5UFV0O7ZmokA06QS6N9M01x/NX.', 2, NULL, NULL, NULL, '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(4, 'ahmad_wijaya', '$2y$12$3EUoOZOsezVqr7uM5dXYvuHskG2TmjGWpBkkgEjjSMHfVZ5ttoKqG', 3, NULL, NULL, NULL, '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(5, 'dewi_susanti', '$2y$12$/bNZRcq1NgshONk6o/iinOUwvfFpnh90GPZtq8ZYHd36a78k0nOOi', 3, NULL, NULL, NULL, '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system'),
(6, 'rini_wulandari', '$2y$12$P9qovVFN8VQIeFMj8pUkLujdtruCUcjD8xBmz27iiAKiYvuHOYiFK', 3, NULL, NULL, NULL, '2025-05-21 17:07:43', 'system', '2025-05-21 17:07:43', 'system');

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
  MODIFY `id_absensi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `guru_mata_pelajaran`
--
ALTER TABLE `guru_mata_pelajaran`
  MODIFY `id_guru_mata_pelajaran` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `id_mata_pelajaran` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id_rekap` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `staf`
--
ALTER TABLE `staf`
  MODIFY `id_staf` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
