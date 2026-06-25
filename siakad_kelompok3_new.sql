-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 24 Jun 2026 pada 20.47
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siakad_kelompok3_new`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_approve_krs` (IN `p_id_krs` INT)   BEGIN

    UPDATE krs
    SET status_krs='approved'
    WHERE id=p_id_krs;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_lihat_krs` (IN `p_id_mahasiswa` INT)   BEGIN

    SELECT
        m.nama,
        mk.nama_mk,
        mk.sks
    FROM mahasiswa m
    JOIN krs k
        ON m.id = k.id_mahasiswa
    JOIN detail_krs dk
        ON k.id = dk.id_krs
    JOIN jadwal_kuliah jk
        ON dk.id_jadwal = jk.id
    JOIN mata_kuliah mk
        ON jk.id_mk = mk.id
    WHERE m.id = p_id_mahasiswa;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_tambah_mahasiswa` (IN `p_nim` VARCHAR(20), IN `p_nama` VARCHAR(100), IN `p_id_prodi` INT, IN `p_id_user` INT)   BEGIN

    INSERT INTO mahasiswa(
        nim,
        nama,
        id_prodi,
        id_user,
        angkatan
    )
    VALUES(
        p_nim,
        p_nama,
        p_id_prodi,
        p_id_user,
        YEAR(CURDATE())
    );

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_krs`
--

CREATE TABLE `detail_krs` (
  `id` int(11) NOT NULL,
  `id_krs` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_krs`
--

INSERT INTO `detail_krs` (`id`, `id_krs`, `id_jadwal`) VALUES
(41, 21, 41),
(42, 21, 42),
(43, 22, 42),
(44, 22, 43),
(45, 23, 43),
(46, 23, 44),
(47, 24, 44),
(48, 24, 45),
(49, 25, 45),
(50, 25, 46),
(51, 26, 46),
(52, 26, 47),
(53, 27, 47),
(54, 27, 48),
(55, 28, 48),
(56, 28, 49),
(57, 29, 49),
(58, 29, 50),
(59, 30, 50),
(60, 30, 51),
(61, 31, 51),
(62, 31, 52),
(63, 32, 52),
(64, 32, 53),
(65, 33, 53),
(66, 33, 54),
(67, 34, 54),
(68, 34, 55),
(69, 35, 55),
(70, 35, 56),
(71, 36, 56),
(72, 36, 57),
(73, 37, 57),
(74, 37, 58),
(75, 38, 58),
(76, 38, 59),
(77, 39, 59),
(78, 39, 60),
(80, 40, 41),
(79, 40, 60),
(82, 41, 63),
(81, 41, 64);

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen`
--

CREATE TABLE `dosen` (
  `id` int(11) NOT NULL,
  `nidn` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `gelar` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `id_prodi` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dosen`
--

INSERT INTO `dosen` (`id`, `nidn`, `nama`, `gelar`, `email`, `id_user`, `id_prodi`, `foto`) VALUES
(21, '1001', 'Dr. Ahmad Fauzi', 'S.Kom.,M.Kom', 'ahmad@kampus.ac.id', 22, 2, NULL),
(22, '1002', 'Dr. Budi Santoso', 'S.Kom.,M.Kom', 'budi@kampus.ac.id', 23, 1, NULL),
(23, '1003', 'Dr. Citra Dewi', 'S.Kom.,M.Kom', 'citra@kampus.ac.id', 24, 3, NULL),
(24, '1004', 'Dr. Dedi Pratama', 'S.Kom.,M.Kom', 'dedi@kampus.ac.id', 25, 2, NULL),
(26, '123456', 'CUMAY', 'SMA', 'CUMAY@sttnf.ac.id', 29, 5, 'foto-dosen/01KVXGVENBHY0H6FBY0AXKKN0Y.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` varchar(191) NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_kuliah`
--

CREATE TABLE `jadwal_kuliah` (
  `id` int(11) NOT NULL,
  `kode_kelas` varchar(10) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruang` varchar(20) NOT NULL,
  `kuota` int(11) DEFAULT 40,
  `id_mk` int(11) NOT NULL,
  `id_dosen` int(11) NOT NULL,
  `id_ta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal_kuliah`
--

INSERT INTO `jadwal_kuliah` (`id`, `kode_kelas`, `hari`, `jam_mulai`, `jam_selesai`, `ruang`, `kuota`, `id_mk`, `id_dosen`, `id_ta`) VALUES
(41, 'A', 'Senin', '08:00:00', '10:00:00', 'R101', 40, 41, 21, 1),
(42, 'B', 'Selasa', '08:00:00', '10:00:00', 'R102', 40, 42, 22, 1),
(43, 'C', 'Rabu', '08:00:00', '10:00:00', 'R103', 40, 43, 23, 1),
(44, 'D', 'Kamis', '08:00:00', '10:00:00', 'R104', 40, 44, 24, 1),
(45, 'E', 'Jumat', '08:00:00', '10:00:00', 'R105', 40, 45, 21, 1),
(46, 'F', 'Senin', '10:00:00', '12:00:00', 'R106', 40, 46, 22, 2),
(47, 'G', 'Selasa', '10:00:00', '12:00:00', 'R107', 40, 47, 23, 2),
(48, 'H', 'Rabu', '10:00:00', '12:00:00', 'R108', 40, 48, 24, 2),
(49, 'I', 'Kamis', '10:00:00', '12:00:00', 'R109', 40, 49, 21, 2),
(50, 'J', 'Jumat', '10:00:00', '12:00:00', 'R110', 40, 50, 22, 2),
(51, 'K', 'Senin', '13:00:00', '15:00:00', 'R111', 40, 51, 23, 3),
(52, 'L', 'Selasa', '13:00:00', '15:00:00', 'R112', 40, 52, 24, 3),
(53, 'M', 'Rabu', '13:00:00', '15:00:00', 'R113', 40, 53, 21, 3),
(54, 'N', 'Kamis', '13:00:00', '15:00:00', 'R114', 40, 54, 22, 3),
(55, 'O', 'Jumat', '13:00:00', '15:00:00', 'R115', 40, 55, 23, 3),
(56, 'P', 'Senin', '15:00:00', '17:00:00', 'R116', 40, 56, 24, 4),
(57, 'Q', 'Selasa', '15:00:00', '17:00:00', 'R117', 40, 57, 21, 4),
(58, 'R', 'Rabu', '15:00:00', '17:00:00', 'R118', 40, 58, 22, 4),
(59, 'S', 'Kamis', '15:00:00', '17:00:00', 'R119', 40, 59, 23, 4),
(60, 'T', 'Jumat', '15:00:00', '17:00:00', 'R120', 40, 60, 24, 4),
(63, 'AB', 'Senin', '08:00:00', '10:00:00', 'R121', 40, 63, 26, 22),
(64, 'AC', 'Senin', '12:00:00', '03:00:00', 'R122', 40, 64, 26, 22);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `krs`
--

CREATE TABLE `krs` (
  `id` int(11) NOT NULL,
  `tanggal_krs` datetime DEFAULT current_timestamp(),
  `status_krs` enum('draft','submitted','approved','rejected') DEFAULT 'draft',
  `total_sks` int(11) DEFAULT 0,
  `id_mahasiswa` int(11) NOT NULL,
  `id_ta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `krs`
--

INSERT INTO `krs` (`id`, `tanggal_krs`, `status_krs`, `total_sks`, `id_mahasiswa`, `id_ta`) VALUES
(21, '2025-08-01 00:00:00', 'approved', 0, 41, 1),
(22, '2025-08-01 00:00:00', 'approved', 0, 42, 1),
(23, '2025-08-01 00:00:00', 'approved', 0, 43, 1),
(24, '2025-08-01 00:00:00', 'approved', 0, 44, 1),
(25, '2025-08-01 00:00:00', 'approved', 0, 45, 1),
(26, '2025-08-01 00:00:00', 'approved', 0, 46, 1),
(27, '2025-08-01 00:00:00', 'approved', 0, 47, 1),
(28, '2025-08-01 00:00:00', 'approved', 0, 48, 1),
(29, '2025-08-01 00:00:00', 'approved', 0, 49, 1),
(30, '2025-08-01 00:00:00', 'approved', 0, 50, 1),
(31, '2025-08-01 00:00:00', 'approved', 0, 51, 1),
(32, '2025-08-01 00:00:00', 'approved', 0, 52, 1),
(33, '2025-08-01 00:00:00', 'approved', 0, 53, 1),
(34, '2025-08-01 00:00:00', 'approved', 0, 54, 1),
(35, '2025-08-01 00:00:00', 'approved', 0, 55, 1),
(36, '2025-08-01 00:00:00', 'approved', 0, 56, 1),
(37, '2025-08-01 00:00:00', 'approved', 0, 57, 1),
(38, '2025-08-01 00:00:00', 'approved', 0, 58, 1),
(39, '2025-08-01 00:00:00', 'approved', 0, 59, 1),
(40, '2025-08-01 00:00:00', 'approved', 0, 60, 1),
(41, '2026-06-24 19:48:17', 'approved', 6, 62, 22);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `angkatan` year(4) NOT NULL,
  `id_prodi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `jenis_kelamin`, `alamat`, `no_hp`, `angkatan`, `id_prodi`, `id_user`, `foto`) VALUES
(41, '231011400001', 'Andi Saputra', 'L', 'Jakarta', '0811111111', '2023', 1, 2, NULL),
(42, '231011400002', 'Budi Hartono', 'L', 'Bogor', '0811111112', '2023', 1, 3, NULL),
(43, '231011400003', 'Citra Dewi', 'P', 'Depok', '0811111113', '2023', 1, 4, NULL),
(44, '231011400004', 'Dian Putri', 'P', 'Tangerang', '0811111114', '2023', 1, 5, NULL),
(45, '231011400005', 'Eko Prasetyo', 'L', 'Bekasi', '0811111115', '2023', 1, 6, NULL),
(46, '231011400006', 'Fajar Nugroho', 'L', 'Jakarta', '0811111116', '2023', 2, 7, NULL),
(47, '231011400007', 'Gina Lestari', 'P', 'Bogor', '0811111117', '2023', 2, 8, NULL),
(48, '231011400008', 'Hadi Wijaya', 'L', 'Depok', '0811111118', '2023', 2, 9, NULL),
(49, '231011400009', 'Indah Permata', 'P', 'Tangerang', '0811111119', '2023', 2, 10, NULL),
(50, '231011400010', 'Joko Susilo', 'L', 'Bekasi', '0811111120', '2023', 2, 11, NULL),
(51, '231011400011', 'Kiki Amelia', 'P', 'Jakarta', '0811111121', '2023', 3, 12, NULL),
(52, '231011400012', 'Lukman Hakim', 'L', 'Bogor', '0811111122', '2023', 3, 13, NULL),
(53, '231011400013', 'Maya Sari', 'P', 'Depok', '0811111123', '2023', 3, 14, NULL),
(54, '231011400014', 'Nanda Putra', 'L', 'Bekasi', '0811111124', '2023', 3, 15, NULL),
(55, '231011400015', 'Oki Setiawan', 'L', 'Jakarta', '0811111125', '2023', 3, 16, NULL),
(56, '231011400016', 'Putri Ayu', 'P', 'Bogor', '0811111126', '2023', 1, 17, NULL),
(57, '231011400017', 'Qori Ahmad', 'L', 'Depok', '0811111127', '2023', 1, 18, NULL),
(58, '231011400018', 'Rina Melati', 'P', 'Bekasi', '0811111128', '2023', 2, 19, NULL),
(59, '231011400019', 'Sandi Prakoso', 'L', 'Jakarta', '0811111129', '2023', 2, 20, NULL),
(60, '231011400020', 'Tina Maharani', 'P', 'Bogor', '0811111130', '2023', 3, 21, NULL),
(62, '01102250', 'ICAK', 'L', 'DEPOK', '0812345678', '2025', 5, 28, 'foto-mahasiswa/01KVXGS7M8BVAJ2H53GQDNR104.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id` int(11) NOT NULL,
  `kode_mk` varchar(15) NOT NULL,
  `nama_mk` varchar(100) NOT NULL,
  `sks` int(11) NOT NULL CHECK (`sks` > 0),
  `semester` tinyint(4) NOT NULL CHECK (`semester` between 1 and 14),
  `id_prodi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id`, `kode_mk`, `nama_mk`, `sks`, `semester`, `id_prodi`) VALUES
(41, 'BD101', 'Pengantar Bisnis Digital', 3, 1, 1),
(42, 'BD102', 'Dasar Manajemen', 3, 1, 1),
(43, 'BD103', 'E-Commerce', 3, 2, 1),
(44, 'BD104', 'Digital Marketing', 3, 2, 1),
(45, 'BD105', 'Business Intelligence', 3, 3, 1),
(46, 'BD106', 'Startup Digital', 3, 4, 1),
(47, 'BD107', 'Analisis Data Bisnis', 3, 5, 1),
(48, 'SI101', 'Pengantar Sistem Informasi', 3, 1, 2),
(49, 'SI102', 'Analisis dan Perancangan SI', 3, 2, 2),
(50, 'SI103', 'Basis Data', 3, 2, 2),
(51, 'SI104', 'Pemrograman Web', 3, 3, 2),
(52, 'SI105', 'ERP', 3, 4, 2),
(53, 'SI106', 'Manajemen Proyek TI', 3, 5, 2),
(54, 'SI107', 'Audit Sistem Informasi', 3, 6, 2),
(55, 'TI101', 'Algoritma dan Pemrograman', 3, 1, 3),
(56, 'TI102', 'Struktur Data', 3, 2, 3),
(57, 'TI103', 'Jaringan Komputer', 3, 3, 3),
(58, 'TI104', 'Sistem Operasi', 3, 3, 3),
(59, 'TI105', 'Kecerdasan Buatan', 3, 5, 3),
(60, 'TI106', 'Machine Learning', 3, 6, 3),
(63, 'AK101', 'FAKTUR PAJAK', 3, 1, 5),
(64, 'AK102', 'ACCOUNTING PAYABLE', 3, 1, 5);

--
-- Trigger `mata_kuliah`
--
DELIMITER $$
CREATE TRIGGER `trg_cek_mk_dihapus` BEFORE DELETE ON `mata_kuliah` FOR EACH ROW BEGIN

    IF EXISTS(
        SELECT 1
        FROM jadwal_kuliah
        WHERE id_mk = OLD.id
    ) THEN

        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT =
        'Mata kuliah masih digunakan pada jadwal kuliah';

    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_14_120248_create_permission_tables', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 22),
(2, 'App\\Models\\User', 23),
(2, 'App\\Models\\User', 24),
(2, 'App\\Models\\User', 25),
(2, 'App\\Models\\User', 29),
(3, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 6),
(3, 'App\\Models\\User', 7),
(3, 'App\\Models\\User', 8),
(3, 'App\\Models\\User', 9),
(3, 'App\\Models\\User', 10),
(3, 'App\\Models\\User', 11),
(3, 'App\\Models\\User', 12),
(3, 'App\\Models\\User', 13),
(3, 'App\\Models\\User', 14),
(3, 'App\\Models\\User', 15),
(3, 'App\\Models\\User', 16),
(3, 'App\\Models\\User', 17),
(3, 'App\\Models\\User', 18),
(3, 'App\\Models\\User', 19),
(3, 'App\\Models\\User', 20),
(3, 'App\\Models\\User', 21),
(3, 'App\\Models\\User', 26),
(3, 'App\\Models\\User', 28);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai`
--

CREATE TABLE `nilai` (
  `id` int(11) NOT NULL,
  `id_detail_krs` int(11) NOT NULL,
  `nilai_angka` decimal(5,2) DEFAULT NULL,
  `nilai_huruf` char(2) DEFAULT NULL,
  `tanggal_input` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nilai`
--

INSERT INTO `nilai` (`id`, `id_detail_krs`, `nilai_angka`, `nilai_huruf`, `tanggal_input`) VALUES
(41, 41, 90.00, 'A', '2026-06-10 21:56:23'),
(42, 42, 85.00, 'A', '2026-06-10 21:56:23'),
(43, 43, 78.00, 'B', '2026-06-10 21:56:23'),
(44, 44, 88.00, 'A', '2026-06-10 21:56:23'),
(45, 45, 75.00, 'B', '2026-06-10 21:56:23'),
(46, 46, 80.00, 'B', '2026-06-10 21:56:23'),
(47, 47, 72.00, 'B', '2026-06-10 21:56:23'),
(48, 48, 68.00, 'C', '2026-06-10 21:56:23'),
(49, 49, 95.00, 'A', '2026-06-10 21:56:23'),
(50, 50, 82.00, 'B', '2026-06-10 21:56:23'),
(51, 51, 77.00, 'B', '2026-06-10 21:56:23'),
(52, 52, 86.00, 'A', '2026-06-10 21:56:23'),
(53, 53, 90.00, 'A', '2026-06-10 21:56:23'),
(54, 54, 74.00, 'B', '2026-06-10 21:56:23'),
(55, 55, 65.00, 'C', '2026-06-10 21:56:23'),
(56, 56, 84.00, 'B', '2026-06-10 21:56:23'),
(57, 57, 79.00, 'B', '2026-06-10 21:56:23'),
(58, 58, 87.00, 'A', '2026-06-10 21:56:23'),
(59, 59, 76.00, 'B', '2026-06-10 21:56:23'),
(60, 60, 81.00, 'B', '2026-06-10 21:56:23'),
(61, 61, 88.00, 'A', '2026-06-10 21:56:23'),
(62, 62, 73.00, 'B', '2026-06-10 21:56:23'),
(63, 63, 92.00, 'A', '2026-06-10 21:56:23'),
(64, 64, 69.00, 'C', '2026-06-10 21:56:23'),
(65, 65, 80.00, 'B', '2026-06-10 21:56:23'),
(66, 66, 78.00, 'B', '2026-06-10 21:56:23'),
(67, 67, 85.00, 'A', '2026-06-10 21:56:23'),
(68, 68, 70.00, 'B', '2026-06-10 21:56:23'),
(69, 69, 83.00, 'B', '2026-06-10 21:56:23'),
(70, 70, 75.00, 'B', '2026-06-10 21:56:23'),
(71, 71, 91.00, 'A', '2026-06-10 21:56:23'),
(72, 72, 72.00, 'B', '2026-06-10 21:56:23'),
(73, 73, 88.00, 'A', '2026-06-10 21:56:23'),
(74, 74, 77.00, 'B', '2026-06-10 21:56:23'),
(75, 75, 79.00, 'B', '2026-06-10 21:56:23'),
(76, 76, 94.00, 'A', '2026-06-10 21:56:23'),
(77, 77, 81.00, 'B', '2026-06-10 21:56:23'),
(78, 78, 74.00, 'B', '2026-06-10 21:56:23'),
(79, 79, 86.00, 'A', '2026-06-10 21:56:23'),
(80, 80, 89.00, 'A', '2026-06-10 21:56:23'),
(83, 82, 90.00, 'A', '2026-06-24 20:23:59'),
(84, 81, 70.00, 'B', '2026-06-24 20:24:15');

--
-- Trigger `nilai`
--
DELIMITER $$
CREATE TRIGGER `trg_huruf_nilai` BEFORE INSERT ON `nilai` FOR EACH ROW BEGIN
    SET NEW.nilai_huruf = CASE
        WHEN NEW.nilai_angka >= 85 THEN 'A'
        WHEN NEW.nilai_angka >= 70 THEN 'B'
        WHEN NEW.nilai_angka >= 60 THEN 'C'
        WHEN NEW.nilai_angka >= 50 THEN 'D'
        ELSE 'E'
    END;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_huruf_nilai_update` BEFORE UPDATE ON `nilai` FOR EACH ROW BEGIN
    IF NEW.nilai_angka < 0 OR NEW.nilai_angka > 100 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Nilai harus antara 0 sampai 100';
    END IF;

    SET NEW.nilai_huruf =
    CASE
        WHEN NEW.nilai_angka >= 85 THEN 'A'
        WHEN NEW.nilai_angka >= 70 THEN 'B'
        WHEN NEW.nilai_angka >= 60 THEN 'C'
        WHEN NEW.nilai_angka >= 50 THEN 'D'
        ELSE 'E'
    END;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_validasi_nilai` BEFORE INSERT ON `nilai` FOR EACH ROW BEGIN

    IF NEW.nilai_angka < 0
    OR NEW.nilai_angka > 100 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT =
        'Nilai harus antara 0 sampai 100';
    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `prodi`
--

CREATE TABLE `prodi` (
  `id` int(11) NOT NULL,
  `kode_prodi` varchar(10) NOT NULL,
  `nama_prodi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `prodi`
--

INSERT INTO `prodi` (`id`, `kode_prodi`, `nama_prodi`) VALUES
(1, 'BD', 'Bisnis Digital'),
(2, 'SI', 'Sistem Informasi'),
(3, 'TI', 'Teknik Informatika'),
(5, 'AK', 'AKUNTAN');

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2026-06-24 11:10:24', '2026-06-24 11:10:24'),
(2, 'dosen', 'web', '2026-06-24 11:10:24', '2026-06-24 11:10:24'),
(3, 'mahasiswa', 'web', '2026-06-24 11:10:24', '2026-06-24 11:10:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tahun_akademik`
--

CREATE TABLE `tahun_akademik` (
  `id` int(11) NOT NULL,
  `tahun` varchar(9) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `status_aktif` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tahun_akademik`
--

INSERT INTO `tahun_akademik` (`id`, `tahun`, `semester`, `status_aktif`) VALUES
(1, '2024/2025', 'Ganjil', 1),
(2, '2024/2025', 'Genap', 0),
(3, '2023/2024', 'Ganjil', 0),
(4, '2023/2024', 'Genap', 0),
(5, '2022/2023', 'Ganjil', 0),
(6, '2022/2023', 'Genap', 0),
(7, '2021/2022', 'Ganjil', 0),
(8, '2021/2022', 'Genap', 0),
(9, '2020/2021', 'Ganjil', 0),
(10, '2020/2021', 'Genap', 0),
(11, '2019/2020', 'Ganjil', 0),
(12, '2019/2020', 'Genap', 0),
(13, '2018/2019', 'Ganjil', 0),
(14, '2018/2019', 'Genap', 0),
(15, '2017/2018', 'Ganjil', 0),
(16, '2017/2018', 'Genap', 0),
(17, '2016/2017', 'Ganjil', 0),
(18, '2016/2017', 'Genap', 0),
(19, '2015/2016', 'Ganjil', 0),
(20, '2015/2016', 'Genap', 0),
(22, '2025/2026', 'Genap', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','dosen','mahasiswa') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `role`, `created_at`) VALUES
(1, 'admin1', '$2y$12$ydFp8YxcW3F9nbBEgbzLh.cQatJ8c8P8QIT62PydqRUaok07AExoW', 'admin1@kampus.ac.id', 'admin', '2026-06-10 14:08:55'),
(2, 'mhs01', '$2y$12$qzLbmvuucbf4tN8CXK8SE.B93DfuQ8DLbAG53Me6cSGS.txNysRKi', 'mhs01@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(3, 'mhs02', '$2y$12$yixMPXF/h.2mlR1/PCp6Ee0ubEBpdHp0XQGLy9bO2okN14I.8CJuy', 'mhs02@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(4, 'mhs03', '$2y$12$fm9ZlDYybd2n2cKqaE1dguX8PXS5yNLdqOBQegquu7nvMtjQzcZuO', 'mhs03@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(5, 'mhs04', '$2y$12$t99pLX2lLkk6hMGR6u6viukcwCl32bSmJ5ivJ1MeOH5AE/G/KUSJW', 'mhs04@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(6, 'mhs05', '$2y$12$fearmrQEAt2brI6nm4Ksxut.px.Hp35EaevAL7USi41aDBJdoUmJe', 'mhs05@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(7, 'mhs06', '$2y$12$680qqxbaoMo/J02kuHeiGudd1QY7hL57W0dI8M7P8TI.GrE9YOYXq', 'mhs06@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(8, 'mhs07', '$2y$12$9SKR1rseP4Z0fu3BuSvkGeMO4E9lMxY0eDd/mvK2tl/w9Np2VdDLu', 'mhs07@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(9, 'mhs08', '$2y$12$1mUZh88OblmBQQDZk5fuYuK25TZ09EzhE5jOZxDAc4lfHU10g9XGy', 'mhs08@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(10, 'mhs09', '$2y$12$WZu2DSM0MQeAne6U6fCwUuvaA6xuhrOQtKH7A0xGDHhDOFOcKaYKu', 'mhs09@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(11, 'mhs10', '$2y$12$d7k.1SLzeko.1bhBjAlbZ.6YV8T5Sv/ulXokpt/eW0xZwM1C2PSYu', 'mhs10@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(12, 'mhs11', '$2y$12$LODZkF7jIA4H1lOWH3Gv5O//wvm96EuOgizDuMMjhX6jYOzyGliM.', 'mhs11@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(13, 'mhs12', '$2y$12$ZSueKVxV3daXZvLmoI3Yu.TSEY8yoab9Lt92qAD1Zhx4x79OFfSvi', 'mhs12@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(14, 'mhs13', '$2y$12$RxcCyX2vlYqmUfqQnSJwVeHLWg5C8VSZMAsfsAOdOWGk6YyrVwFXi', 'mhs13@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(15, 'mhs14', '$2y$12$lWXAlKTySc6GF/DVzQwDges1Spw./4HN7V2i2Mib.z.S44SSv3lxu', 'mhs14@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(16, 'mhs15', '$2y$12$uSmZ6syVZ3BPyXWqQRnr8OIa1MQW2sg1lWm9BWKBA0VLZsjadJ2Vm', 'mhs15@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(17, 'mhs16', '$2y$12$Svxgwim6UC/0OH/TpNVn8usZrU.89TZHv7BXzjAQ35BBBVdY1cX.W', 'mhs16@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(18, 'mhs17', '$2y$12$zkUdbR8GgtdDhVojdxgCa.IQAZPRgsNhczLMIJno8Jczus.Ovoi9W', 'mhs17@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(19, 'mhs18', '$2y$12$yD9SfVsuhf0vOQoB6tsWP.9PrxEynGtx1GnzputZa8yOjH.9EVdke', 'mhs18@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(20, 'mhs19', '$2y$12$ddSlxenE/u6yeETTTvfQnuJqAzo3I4GDjrFhVonV.jxymvL6Hh1GK', 'mhs19@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(21, 'mhs20', '$2y$12$zl0PWtesl3i13mVJ7VbkFeouXyfh0EKadG06b1WOx/giA1AlrJc6u', 'mhs20@mail.com', 'mahasiswa', '2026-06-10 14:08:55'),
(22, 'dsn01', '$2y$12$qnbhnJ2IqqcCHUAk0L5JUOoGpU8WXQCDPFpfgC9avbypYOztYa5wm', 'dsn01@kampus.ac.id', 'dosen', '2026-06-10 14:08:55'),
(23, 'dsn02', '$2y$12$T1TV4KsrVK8wFfHVgasnmO66ft0jkIX/oziZVvRWTvMgjJjFraabi', 'dsn02@kampus.ac.id', 'dosen', '2026-06-10 14:08:55'),
(24, 'dsn03', '$2y$12$cFy060kZevJopuyHByvR0uXRopIuCd/xz3OmLRKBgSUUbZuOZtDMe', 'dsn03@kampus.ac.id', 'dosen', '2026-06-10 14:08:55'),
(25, 'dsn04', '$2y$12$RbFFFiZm..Y6yT7SIpNx7.7zWpmlP9GqzYXTsF2g1FZNu4WgJnziK', 'dsn04@kampus.ac.id', 'dosen', '2026-06-10 14:08:55'),
(26, 'ICAK', '$2y$12$MYQsnyThrqZB/3FRBzNXJOMgROhNQ9PWMQ7DD.QU7kVJSXHWeVBDK', 'ICAK@sttnf.ac.id', 'mahasiswa', '2026-06-24 18:20:11'),
(28, 'ICAK13', '$2y$12$V7zJ7w.wbgJSnFMi9koEQu.osgevQEaqNa01.7gGX7pJbd37awdfm', 'ICAK13@sttnf.ac.id', 'mahasiswa', '2026-06-24 19:12:49'),
(29, 'CUMAY', '$2y$12$YhlKU2EvIcUZQKISFqxf7eWVqd3Y7tfiVkmSKmDzWplGTfI.ewsmu', 'CUMAY@sttnf.ac.id', 'dosen', '2026-06-24 19:14:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_detail_mahasiswa`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_detail_mahasiswa` (
`mahasiswa_id` int(11)
,`nim` varchar(20)
,`nama_mahasiswa` varchar(100)
,`jenis_kelamin` enum('L','P')
,`alamat` text
,`no_hp` varchar(20)
,`angkatan` year(4)
,`kode_prodi` varchar(10)
,`nama_prodi` varchar(100)
,`username` varchar(50)
,`email` varchar(100)
,`role` enum('admin','dosen','mahasiswa')
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_jadwal_mahasiswa`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_jadwal_mahasiswa` (
`nim` varchar(20)
,`nama_mahasiswa` varchar(100)
,`kode_mk` varchar(15)
,`nama_mk` varchar(100)
,`sks` int(11)
,`kode_kelas` varchar(10)
,`hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')
,`jam_mulai` time
,`jam_selesai` time
,`ruang` varchar(20)
,`nama_dosen` varchar(100)
,`tahun` varchar(9)
,`semester` enum('Ganjil','Genap')
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_nilai_mahasiswa`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_nilai_mahasiswa` (
`nim` varchar(20)
,`nama_mahasiswa` varchar(100)
,`kode_mk` varchar(15)
,`nama_mk` varchar(100)
,`nilai_angka` decimal(5,2)
,`nilai_huruf` char(2)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_transkrip_nilai`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_transkrip_nilai` (
`mahasiswa_id` int(11)
,`nim` varchar(20)
,`nama_mahasiswa` varchar(100)
,`kode_mk` varchar(15)
,`nama_mk` varchar(100)
,`sks` int(11)
,`nilai_angka` decimal(5,2)
,`nilai_huruf` varchar(1)
,`nilai_mutu` int(1)
,`tahun` varchar(9)
,`ta_semester` enum('Ganjil','Genap')
);

-- --------------------------------------------------------

--
-- Struktur untuk view `v_detail_mahasiswa`
--
DROP TABLE IF EXISTS `v_detail_mahasiswa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_detail_mahasiswa`  AS SELECT `m`.`id` AS `mahasiswa_id`, `m`.`nim` AS `nim`, `m`.`nama` AS `nama_mahasiswa`, `m`.`jenis_kelamin` AS `jenis_kelamin`, `m`.`alamat` AS `alamat`, `m`.`no_hp` AS `no_hp`, `m`.`angkatan` AS `angkatan`, `p`.`kode_prodi` AS `kode_prodi`, `p`.`nama_prodi` AS `nama_prodi`, `u`.`username` AS `username`, `u`.`email` AS `email`, `u`.`role` AS `role` FROM ((`mahasiswa` `m` join `prodi` `p` on(`p`.`id` = `m`.`id_prodi`)) join `user` `u` on(`u`.`id` = `m`.`id_user`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_jadwal_mahasiswa`
--
DROP TABLE IF EXISTS `v_jadwal_mahasiswa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_jadwal_mahasiswa`  AS SELECT `m`.`nim` AS `nim`, `m`.`nama` AS `nama_mahasiswa`, `mk`.`kode_mk` AS `kode_mk`, `mk`.`nama_mk` AS `nama_mk`, `mk`.`sks` AS `sks`, `jk`.`kode_kelas` AS `kode_kelas`, `jk`.`hari` AS `hari`, `jk`.`jam_mulai` AS `jam_mulai`, `jk`.`jam_selesai` AS `jam_selesai`, `jk`.`ruang` AS `ruang`, `d`.`nama` AS `nama_dosen`, `ta`.`tahun` AS `tahun`, `ta`.`semester` AS `semester` FROM ((((((`mahasiswa` `m` join `krs` `k` on(`m`.`id` = `k`.`id_mahasiswa`)) join `detail_krs` `dk` on(`k`.`id` = `dk`.`id_krs`)) join `jadwal_kuliah` `jk` on(`dk`.`id_jadwal` = `jk`.`id`)) join `mata_kuliah` `mk` on(`jk`.`id_mk` = `mk`.`id`)) join `dosen` `d` on(`jk`.`id_dosen` = `d`.`id`)) join `tahun_akademik` `ta` on(`k`.`id_ta` = `ta`.`id`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_nilai_mahasiswa`
--
DROP TABLE IF EXISTS `v_nilai_mahasiswa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_nilai_mahasiswa`  AS SELECT `m`.`nim` AS `nim`, `m`.`nama` AS `nama_mahasiswa`, `mk`.`kode_mk` AS `kode_mk`, `mk`.`nama_mk` AS `nama_mk`, `n`.`nilai_angka` AS `nilai_angka`, `n`.`nilai_huruf` AS `nilai_huruf` FROM (((((`mahasiswa` `m` join `krs` `k` on(`m`.`id` = `k`.`id_mahasiswa`)) join `detail_krs` `dk` on(`k`.`id` = `dk`.`id_krs`)) join `nilai` `n` on(`dk`.`id` = `n`.`id_detail_krs`)) join `jadwal_kuliah` `jk` on(`dk`.`id_jadwal` = `jk`.`id`)) join `mata_kuliah` `mk` on(`jk`.`id_mk` = `mk`.`id`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_transkrip_nilai`
--
DROP TABLE IF EXISTS `v_transkrip_nilai`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_transkrip_nilai`  AS SELECT `m`.`id` AS `mahasiswa_id`, `m`.`nim` AS `nim`, `m`.`nama` AS `nama_mahasiswa`, `mk`.`kode_mk` AS `kode_mk`, `mk`.`nama_mk` AS `nama_mk`, `mk`.`sks` AS `sks`, `n`.`nilai_angka` AS `nilai_angka`, CASE WHEN `n`.`nilai_angka` >= 85 THEN 'A' WHEN `n`.`nilai_angka` >= 70 THEN 'B' WHEN `n`.`nilai_angka` >= 60 THEN 'C' WHEN `n`.`nilai_angka` >= 50 THEN 'D' ELSE 'E' END AS `nilai_huruf`, CASE WHEN `n`.`nilai_angka` >= 85 THEN 4 WHEN `n`.`nilai_angka` >= 70 THEN 3 WHEN `n`.`nilai_angka` >= 60 THEN 2 WHEN `n`.`nilai_angka` >= 50 THEN 1 ELSE 0 END AS `nilai_mutu`, `ta`.`tahun` AS `tahun`, `ta`.`semester` AS `ta_semester` FROM ((((((`nilai` `n` join `detail_krs` `dk` on(`dk`.`id` = `n`.`id_detail_krs`)) join `krs` `k` on(`k`.`id` = `dk`.`id_krs`)) join `mahasiswa` `m` on(`m`.`id` = `k`.`id_mahasiswa`)) join `jadwal_kuliah` `j` on(`j`.`id` = `dk`.`id_jadwal`)) join `mata_kuliah` `mk` on(`mk`.`id` = `j`.`id_mk`)) join `tahun_akademik` `ta` on(`ta`.`id` = `k`.`id_ta`)) ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `detail_krs`
--
ALTER TABLE `detail_krs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_krs` (`id_krs`,`id_jadwal`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indeks untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nidn` (`nidn`),
  ADD UNIQUE KEY `id_user` (`id_user`),
  ADD KEY `id_prodi` (`id_prodi`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indeks untuk tabel `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mk` (`id_mk`),
  ADD KEY `id_ta` (`id_ta`),
  ADD KEY `idx_jadwal_dosen` (`id_dosen`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ta` (`id_ta`),
  ADD KEY `idx_krs_mahasiswa_ta` (`id_mahasiswa`,`id_ta`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD UNIQUE KEY `id_user` (`id_user`),
  ADD KEY `id_prodi` (`id_prodi`),
  ADD KEY `idx_mahasiswa_nim` (`nim`);

--
-- Indeks untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_mk` (`kode_mk`),
  ADD KEY `id_prodi` (`id_prodi`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indeks untuk tabel `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indeks untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_detail_krs` (`id_detail_krs`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indeks untuk tabel `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_prodi` (`kode_prodi`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indeks untuk tabel `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `tahun_akademik`
--
ALTER TABLE `tahun_akademik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tahun` (`tahun`,`semester`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_krs`
--
ALTER TABLE `detail_krs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT untuk tabel `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `krs`
--
ALTER TABLE `krs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT untuk tabel `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `prodi`
--
ALTER TABLE `prodi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tahun_akademik`
--
ALTER TABLE `tahun_akademik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_krs`
--
ALTER TABLE `detail_krs`
  ADD CONSTRAINT `detail_krs_ibfk_1` FOREIGN KEY (`id_krs`) REFERENCES `krs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_krs_ibfk_2` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_kuliah` (`id`);

--
-- Ketidakleluasaan untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD CONSTRAINT `dosen_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dosen_ibfk_2` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD CONSTRAINT `jadwal_kuliah_ibfk_1` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id`),
  ADD CONSTRAINT `jadwal_kuliah_ibfk_2` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id`),
  ADD CONSTRAINT `jadwal_kuliah_ibfk_3` FOREIGN KEY (`id_ta`) REFERENCES `tahun_akademik` (`id`);

--
-- Ketidakleluasaan untuk tabel `krs`
--
ALTER TABLE `krs`
  ADD CONSTRAINT `krs_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `krs_ibfk_2` FOREIGN KEY (`id_ta`) REFERENCES `tahun_akademik` (`id`);

--
-- Ketidakleluasaan untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `mahasiswa_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD CONSTRAINT `mata_kuliah_ibfk_1` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`id_detail_krs`) REFERENCES `detail_krs` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
