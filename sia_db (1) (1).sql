-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Jun 2026 pada 09.51
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
-- Database: `sia_db`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ambil_krs` (IN `p_id_mahasiswa` INT, IN `p_id_jadwal` INT, OUT `p_status` VARCHAR(100))   sp_ambil_krs: BEGIN
    DECLARE v_id_krs    INT;
    DECLARE v_id_ta     INT;
    DECLARE v_kapasitas INT;
    DECLARE v_terisi    INT;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET p_status = 'ERROR: Terjadi kesalahan sistem.';
    END;

    START TRANSACTION;

    -- Ambil tahun akademik aktif
    SELECT id_ta INTO v_id_ta
    FROM tahun_akademik
    WHERE is_aktif = TRUE
    LIMIT 1;

    IF v_id_ta IS NULL THEN
        SET p_status = 'GAGAL: Tidak ada tahun akademik aktif.';
        ROLLBACK;
        LEAVE sp_ambil_krs;
    END IF;

    -- Cek kapasitas kelas
    SELECT kapasitas INTO v_kapasitas
    FROM jadwal_kuliah
    WHERE id_jadwal = p_id_jadwal;

    SELECT COUNT(*) INTO v_terisi
    FROM detail_krs dk
    JOIN krs k ON dk.id_krs = k.id_krs
    WHERE dk.id_jadwal = p_id_jadwal
      AND k.id_ta      = v_id_ta;

    IF v_terisi >= v_kapasitas THEN
        SET p_status = 'GAGAL: Kelas sudah penuh.';
        ROLLBACK;
        LEAVE sp_ambil_krs;
    END IF;

    -- Buat KRS baru jika belum ada
    SELECT id_krs INTO v_id_krs
    FROM krs
    WHERE id_mahasiswa = p_id_mahasiswa
      AND id_ta        = v_id_ta
    LIMIT 1;

    IF v_id_krs IS NULL THEN
        INSERT INTO krs(id_mahasiswa, id_ta, status, tanggal_pengajuan)
        VALUES(p_id_mahasiswa, v_id_ta, 'draft', NOW());
        SET v_id_krs = LAST_INSERT_ID();
    END IF;

    -- Cek apakah MK sudah diambil
    IF EXISTS (
        SELECT 1 FROM detail_krs
        WHERE id_krs    = v_id_krs
          AND id_jadwal = p_id_jadwal
    ) THEN
        SET p_status = 'GAGAL: Mata kuliah ini sudah diambil.';
        ROLLBACK;
        LEAVE sp_ambil_krs;
    END IF;

    INSERT INTO detail_krs(id_krs, id_jadwal)
    VALUES(v_id_krs, p_id_jadwal);

    COMMIT;
    SET p_status = 'BERHASIL: Mata kuliah berhasil ditambahkan ke KRS.';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_hitung_ips` (IN `p_id_mahasiswa` INT, IN `p_id_ta` INT, OUT `p_ips` DECIMAL(3,2), OUT `p_total_sks` INT)   BEGIN
    SELECT
        ROUND(
            SUM(mk.sks * CASE n.grade
                WHEN 'A' THEN 4.0
                WHEN 'B' THEN 3.0
                WHEN 'C' THEN 2.0
                WHEN 'D' THEN 1.0
                ELSE 0.0 END
            ) / SUM(mk.sks), 2
        ),
        SUM(mk.sks)
    INTO p_ips, p_total_sks
    FROM nilai n
    JOIN detail_krs    dk ON n.id_detail    = dk.id_detail
    JOIN krs            k ON dk.id_krs      = k.id_krs
    JOIN jadwal_kuliah  j ON dk.id_jadwal   = j.id_jadwal
    JOIN mata_kuliah   mk ON j.id_mk        = mk.id_mk
    WHERE k.id_mahasiswa = p_id_mahasiswa
      AND k.id_ta        = p_id_ta;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_input_nilai` (IN `p_id_detail` INT, IN `p_nilai_tugas` DECIMAL(5,2), IN `p_nilai_uts` DECIMAL(5,2), IN `p_nilai_uas` DECIMAL(5,2), OUT `p_status` VARCHAR(100))   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        SET p_status = 'ERROR: Gagal menyimpan nilai.';
    END;

    IF p_nilai_tugas NOT BETWEEN 0 AND 100
    OR p_nilai_uts   NOT BETWEEN 0 AND 100
    OR p_nilai_uas   NOT BETWEEN 0 AND 100 THEN
        SET p_status = 'GAGAL: Nilai harus antara 0-100.';
    ELSE
        INSERT INTO nilai(id_detail, nilai_tugas, nilai_uts, nilai_uas)
            VALUES(p_id_detail, p_nilai_tugas, p_nilai_uts, p_nilai_uas)
            ON DUPLICATE KEY UPDATE
                nilai_tugas = p_nilai_tugas,
                nilai_uts   = p_nilai_uts,
                nilai_uas   = p_nilai_uas;
        SET p_status = 'BERHASIL: Nilai tersimpan.';
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_krs`
--

CREATE TABLE `detail_krs` (
  `id_detail` int(11) NOT NULL,
  `id_krs` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `detail_krs`
--

INSERT INTO `detail_krs` (`id_detail`, `id_krs`, `id_jadwal`) VALUES
(1, 1, 1),
(2, 1, 2),
(5, 1, 3),
(3, 2, 1),
(4, 2, 3);

--
-- Trigger `detail_krs`
--
DELIMITER $$
CREATE TRIGGER `trg_after_delete_detail_krs` AFTER DELETE ON `detail_krs` FOR EACH ROW BEGIN
    DECLARE v_sisa INT;

    SELECT COUNT(*) INTO v_sisa
    FROM detail_krs
    WHERE id_krs = OLD.id_krs;

    IF v_sisa = 0 THEN
        UPDATE krs
        SET status = 'draft'
        WHERE id_krs = OLD.id_krs;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_before_insert_detail_krs` BEFORE INSERT ON `detail_krs` FOR EACH ROW BEGIN
    DECLARE v_id_ta       INT;
    DECLARE v_id_mk       INT;
    DECLARE v_sudah_ambil INT;

    SELECT k.id_ta INTO v_id_ta
    FROM krs k
    WHERE k.id_krs = NEW.id_krs;

    SELECT j.id_mk INTO v_id_mk
    FROM jadwal_kuliah j
    WHERE j.id_jadwal = NEW.id_jadwal;

    SELECT COUNT(*) INTO v_sudah_ambil
    FROM detail_krs    dk
    JOIN krs            k ON dk.id_krs    = k.id_krs
    JOIN jadwal_kuliah  j ON dk.id_jadwal = j.id_jadwal
    WHERE k.id_ta        = v_id_ta
      AND j.id_mk        = v_id_mk
      AND k.id_mahasiswa = (SELECT id_mahasiswa FROM krs WHERE id_krs = NEW.id_krs);

    IF v_sudah_ambil > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Mahasiswa sudah mengambil mata kuliah ini di semester yang sama.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dosen`
--

CREATE TABLE `dosen` (
  `id_dosen` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(50) DEFAULT 'Lektor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dosen`
--

INSERT INTO `dosen` (`id_dosen`, `id_user`, `nip`, `nama`, `jabatan`) VALUES
(1, 4, '198501012010011001', 'Dr. Ahmad Fauzi', 'Lektor Kepala'),
(2, 5, '199002022015012002', 'Rina Kusumawati M.T', 'Lektor');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_kuliah`
--

CREATE TABLE `jadwal_kuliah` (
  `id_jadwal` int(11) NOT NULL,
  `id_mk` int(11) NOT NULL,
  `id_dosen` int(11) NOT NULL,
  `id_ta` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(20) NOT NULL,
  `kapasitas` smallint(6) NOT NULL DEFAULT 40
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jadwal_kuliah`
--

INSERT INTO `jadwal_kuliah` (`id_jadwal`, `id_mk`, `id_dosen`, `id_ta`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `kapasitas`) VALUES
(1, 1, 1, 1, 'Senin', '08:00:00', '10:30:00', 'Lab A', 35),
(2, 2, 2, 1, 'Selasa', '10:00:00', '12:30:00', 'Lab B', 30),
(3, 3, 1, 1, 'Rabu', '13:00:00', '15:30:00', 'Ruang C', 40);

-- --------------------------------------------------------

--
-- Struktur dari tabel `krs`
--

CREATE TABLE `krs` (
  `id_krs` int(11) NOT NULL,
  `id_mahasiswa` int(11) NOT NULL,
  `id_ta` int(11) NOT NULL,
  `status` enum('draft','diajukan','disetujui','ditolak') DEFAULT 'draft',
  `tanggal_pengajuan` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `krs`
--

INSERT INTO `krs` (`id_krs`, `id_mahasiswa`, `id_ta`, `status`, `tanggal_pengajuan`) VALUES
(1, 1, 1, 'disetujui', '2024-09-01 08:00:00'),
(2, 2, 1, 'disetujui', '2024-09-01 09:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_nilai`
--

CREATE TABLE `log_nilai` (
  `id_log` int(11) NOT NULL,
  `id_nilai` int(11) DEFAULT NULL,
  `kolom` varchar(20) DEFAULT NULL,
  `nilai_lama` decimal(5,2) DEFAULT NULL,
  `nilai_baru` decimal(5,2) DEFAULT NULL,
  `diubah_pada` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `log_nilai`
--

INSERT INTO `log_nilai` (`id_log`, `id_nilai`, `kolom`, `nilai_lama`, `nilai_baru`, `diubah_pada`) VALUES
(1, 1, 'nilai_akhir', 81.70, 84.00, '2026-06-05 14:45:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_prodi` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `angkatan` year(4) NOT NULL,
  `status` enum('aktif','cuti','lulus','DO') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `id_user`, `id_prodi`, `nim`, `nama`, `angkatan`, `status`) VALUES
(1, 2, 1, '21001', 'Budi Santoso', '2021', 'aktif'),
(2, 3, 1, '21002', 'Sari Dewi', '2021', 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id_mk` int(11) NOT NULL,
  `id_prodi` int(11) NOT NULL,
  `kode_mk` varchar(10) NOT NULL,
  `nama_mk` varchar(100) NOT NULL,
  `sks` tinyint(4) NOT NULL CHECK (`sks` between 1 and 6),
  `semester` tinyint(4) NOT NULL CHECK (`semester` between 1 and 8)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id_mk`, `id_prodi`, `kode_mk`, `nama_mk`, `sks`, `semester`) VALUES
(1, 1, 'TI301', 'Basis Data', 3, 3),
(2, 1, 'TI302', 'Pemrograman Web', 3, 3),
(3, 1, 'TI401', 'Kecerdasan Buatan', 3, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai`
--

CREATE TABLE `nilai` (
  `id_nilai` int(11) NOT NULL,
  `id_detail` int(11) NOT NULL,
  `nilai_tugas` decimal(5,2) DEFAULT 0.00,
  `nilai_uts` decimal(5,2) DEFAULT 0.00,
  `nilai_uas` decimal(5,2) DEFAULT 0.00,
  `nilai_akhir` decimal(5,2) GENERATED ALWAYS AS (`nilai_tugas` * 0.3 + `nilai_uts` * 0.3 + `nilai_uas` * 0.4) STORED,
  `grade` char(2) GENERATED ALWAYS AS (case when `nilai_tugas` * 0.3 + `nilai_uts` * 0.3 + `nilai_uas` * 0.4 >= 85 then 'A' when `nilai_tugas` * 0.3 + `nilai_uts` * 0.3 + `nilai_uas` * 0.4 >= 70 then 'B' when `nilai_tugas` * 0.3 + `nilai_uts` * 0.3 + `nilai_uas` * 0.4 >= 56 then 'C' when `nilai_tugas` * 0.3 + `nilai_uts` * 0.3 + `nilai_uas` * 0.4 >= 41 then 'D' else 'E' end) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `id_detail`, `nilai_tugas`, `nilai_uts`, `nilai_uas`) VALUES
(1, 1, 82.00, 78.00, 90.00),
(2, 2, 70.00, 65.00, 72.00),
(3, 3, 90.00, 85.00, 92.00),
(4, 4, 60.00, 55.00, 65.00);

--
-- Trigger `nilai`
--
DELIMITER $$
CREATE TRIGGER `trg_after_update_nilai` AFTER UPDATE ON `nilai` FOR EACH ROW BEGIN
    IF OLD.nilai_akhir <> NEW.nilai_akhir THEN
        INSERT INTO log_nilai(id_nilai, kolom, nilai_lama, nilai_baru)
        VALUES(NEW.id_nilai, 'nilai_akhir', OLD.nilai_akhir, NEW.nilai_akhir);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `prodi`
--

CREATE TABLE `prodi` (
  `id_prodi` int(11) NOT NULL,
  `nama_prodi` varchar(100) NOT NULL,
  `jenjang` enum('D3','S1','S2','S3') NOT NULL,
  `akreditasi` char(1) DEFAULT 'B'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `prodi`
--

INSERT INTO `prodi` (`id_prodi`, `nama_prodi`, `jenjang`, `akreditasi`) VALUES
(1, 'Teknik Informatika', 'S1', 'A'),
(2, 'Sistem Informasi', 'S1', 'B');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tahun_akademik`
--

CREATE TABLE `tahun_akademik` (
  `id_ta` int(11) NOT NULL,
  `tahun` varchar(9) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `is_aktif` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tahun_akademik`
--

INSERT INTO `tahun_akademik` (`id_ta`, `tahun`, `semester`, `is_aktif`) VALUES
(1, '2024/2025', 'Ganjil', 1),
(2, '2023/2024', 'Genap', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','mahasiswa','dosen') NOT NULL DEFAULT 'mahasiswa',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'admin', '2026-06-05 14:45:44'),
(2, 'mhs001', '9b8769a4a742959a2d0298c36fb70623f2dfacda8436237df08d8dfd5b37374c', 'mahasiswa', '2026-06-05 14:45:44'),
(3, 'mhs002', '1d4598d1949b47f7f211134b639ec32238ce73086a83c2f745713b3f12f817e5', 'mahasiswa', '2026-06-05 14:45:44'),
(4, 'dosen001', '6a43336baf50915c0042ba1ccecc7c75072763569bf8ad735bd7f6b4419ceb67', 'dosen', '2026-06-05 14:45:44'),
(5, 'dosen002', '0aa7bae759aa8f2a98e14ad3693f707581228ed2c68bf04534ae96d1fdfc07eb', 'dosen', '2026-06-05 14:45:44');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_beban_dosen`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_beban_dosen` (
`nip` varchar(30)
,`nama_dosen` varchar(100)
,`tahun` varchar(9)
,`semester` enum('Ganjil','Genap')
,`jumlah_kelas` bigint(21)
,`total_sks` decimal(25,0)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_statistik_nilai`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_statistik_nilai` (
`kode_mk` varchar(10)
,`nama_mk` varchar(100)
,`tahun` varchar(9)
,`semester` enum('Ganjil','Genap')
,`jumlah_peserta` bigint(21)
,`rata_rata` decimal(6,2)
,`nilai_tertinggi` decimal(5,2)
,`nilai_terendah` decimal(5,2)
,`jumlah_lulus` decimal(22,0)
,`persentase_lulus` decimal(27,1)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_transkrip`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_transkrip` (
`nim` varchar(20)
,`nama_mahasiswa` varchar(100)
,`kode_mk` varchar(10)
,`nama_mk` varchar(100)
,`sks` tinyint(4)
,`tahun` varchar(9)
,`semester` enum('Ganjil','Genap')
,`nilai_tugas` decimal(5,2)
,`nilai_uts` decimal(5,2)
,`nilai_uas` decimal(5,2)
,`nilai_akhir` decimal(5,2)
,`grade` char(2)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `v_beban_dosen`
--
DROP TABLE IF EXISTS `v_beban_dosen`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_beban_dosen`  AS SELECT `d`.`nip` AS `nip`, `d`.`nama` AS `nama_dosen`, `ta`.`tahun` AS `tahun`, `ta`.`semester` AS `semester`, count(`j`.`id_jadwal`) AS `jumlah_kelas`, sum(`mk`.`sks`) AS `total_sks` FROM (((`jadwal_kuliah` `j` join `dosen` `d` on(`j`.`id_dosen` = `d`.`id_dosen`)) join `mata_kuliah` `mk` on(`j`.`id_mk` = `mk`.`id_mk`)) join `tahun_akademik` `ta` on(`j`.`id_ta` = `ta`.`id_ta`)) GROUP BY `d`.`id_dosen`, `ta`.`id_ta` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_statistik_nilai`
--
DROP TABLE IF EXISTS `v_statistik_nilai`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_statistik_nilai`  AS SELECT `mk`.`kode_mk` AS `kode_mk`, `mk`.`nama_mk` AS `nama_mk`, `ta`.`tahun` AS `tahun`, `ta`.`semester` AS `semester`, count(`n`.`id_nilai`) AS `jumlah_peserta`, round(avg(`n`.`nilai_akhir`),2) AS `rata_rata`, max(`n`.`nilai_akhir`) AS `nilai_tertinggi`, min(`n`.`nilai_akhir`) AS `nilai_terendah`, sum(case when `n`.`grade` not in ('D','E') then 1 else 0 end) AS `jumlah_lulus`, round(sum(case when `n`.`grade` not in ('D','E') then 1 else 0 end) / count(`n`.`id_nilai`) * 100,1) AS `persentase_lulus` FROM (((((`nilai` `n` join `detail_krs` `dk` on(`n`.`id_detail` = `dk`.`id_detail`)) join `jadwal_kuliah` `j` on(`dk`.`id_jadwal` = `j`.`id_jadwal`)) join `mata_kuliah` `mk` on(`j`.`id_mk` = `mk`.`id_mk`)) join `krs` `k` on(`dk`.`id_krs` = `k`.`id_krs`)) join `tahun_akademik` `ta` on(`k`.`id_ta` = `ta`.`id_ta`)) GROUP BY `mk`.`id_mk`, `ta`.`id_ta` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_transkrip`
--
DROP TABLE IF EXISTS `v_transkrip`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_transkrip`  AS SELECT `m`.`nim` AS `nim`, `m`.`nama` AS `nama_mahasiswa`, `mk`.`kode_mk` AS `kode_mk`, `mk`.`nama_mk` AS `nama_mk`, `mk`.`sks` AS `sks`, `ta`.`tahun` AS `tahun`, `ta`.`semester` AS `semester`, `n`.`nilai_tugas` AS `nilai_tugas`, `n`.`nilai_uts` AS `nilai_uts`, `n`.`nilai_uas` AS `nilai_uas`, `n`.`nilai_akhir` AS `nilai_akhir`, `n`.`grade` AS `grade` FROM ((((((`nilai` `n` join `detail_krs` `dk` on(`n`.`id_detail` = `dk`.`id_detail`)) join `krs` `k` on(`dk`.`id_krs` = `k`.`id_krs`)) join `mahasiswa` `m` on(`k`.`id_mahasiswa` = `m`.`id_mahasiswa`)) join `jadwal_kuliah` `j` on(`dk`.`id_jadwal` = `j`.`id_jadwal`)) join `mata_kuliah` `mk` on(`j`.`id_mk` = `mk`.`id_mk`)) join `tahun_akademik` `ta` on(`k`.`id_ta` = `ta`.`id_ta`)) ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_krs`
--
ALTER TABLE `detail_krs`
  ADD PRIMARY KEY (`id_detail`),
  ADD UNIQUE KEY `uq_detail` (`id_krs`,`id_jadwal`),
  ADD KEY `idx_detail_jadwal` (`id_jadwal`);

--
-- Indeks untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id_dosen`),
  ADD UNIQUE KEY `id_user` (`id_user`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indeks untuk tabel `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_mk` (`id_mk`),
  ADD KEY `id_dosen` (`id_dosen`),
  ADD KEY `idx_jadwal_ta_hari` (`id_ta`,`hari`);

--
-- Indeks untuk tabel `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id_krs`),
  ADD UNIQUE KEY `uq_krs` (`id_mahasiswa`,`id_ta`),
  ADD KEY `id_ta` (`id_ta`);

--
-- Indeks untuk tabel `log_nilai`
--
ALTER TABLE `log_nilai`
  ADD PRIMARY KEY (`id_log`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD UNIQUE KEY `id_user` (`id_user`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD KEY `id_prodi` (`id_prodi`),
  ADD KEY `idx_mahasiswa_nim` (`nim`);

--
-- Indeks untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id_mk`),
  ADD UNIQUE KEY `kode_mk` (`kode_mk`),
  ADD KEY `id_prodi` (`id_prodi`);

--
-- Indeks untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id_nilai`),
  ADD UNIQUE KEY `id_detail` (`id_detail`),
  ADD KEY `idx_nilai_akhir` (`nilai_akhir`);

--
-- Indeks untuk tabel `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id_prodi`);

--
-- Indeks untuk tabel `tahun_akademik`
--
ALTER TABLE `tahun_akademik`
  ADD PRIMARY KEY (`id_ta`),
  ADD UNIQUE KEY `uq_ta` (`tahun`,`semester`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_krs`
--
ALTER TABLE `detail_krs`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id_dosen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `krs`
--
ALTER TABLE `krs`
  MODIFY `id_krs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `log_nilai`
--
ALTER TABLE `log_nilai`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id_mk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `prodi`
--
ALTER TABLE `prodi`
  MODIFY `id_prodi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tahun_akademik`
--
ALTER TABLE `tahun_akademik`
  MODIFY `id_ta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_krs`
--
ALTER TABLE `detail_krs`
  ADD CONSTRAINT `detail_krs_ibfk_1` FOREIGN KEY (`id_krs`) REFERENCES `krs` (`id_krs`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_krs_ibfk_2` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_kuliah` (`id_jadwal`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `dosen`
--
ALTER TABLE `dosen`
  ADD CONSTRAINT `dosen_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD CONSTRAINT `jadwal_kuliah_ibfk_1` FOREIGN KEY (`id_mk`) REFERENCES `mata_kuliah` (`id_mk`) ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_kuliah_ibfk_2` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id_dosen`) ON UPDATE CASCADE,
  ADD CONSTRAINT `jadwal_kuliah_ibfk_3` FOREIGN KEY (`id_ta`) REFERENCES `tahun_akademik` (`id_ta`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `krs`
--
ALTER TABLE `krs`
  ADD CONSTRAINT `krs_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `krs_ibfk_2` FOREIGN KEY (`id_ta`) REFERENCES `tahun_akademik` (`id_ta`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mahasiswa_ibfk_2` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD CONSTRAINT `mata_kuliah_ibfk_1` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`id_detail`) REFERENCES `detail_krs` (`id_detail`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
