-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 07 Apr 2026 pada 16.59
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simak`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `arsip`
--

CREATE TABLE `arsip` (
  `id` char(36) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file` varchar(255) NOT NULL,
  `user_id` char(36) NOT NULL,
  `fakultas_id` char(36) DEFAULT NULL,
  `prodi_id` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `arsip`
--

INSERT INTO `arsip` (`id`, `judul`, `deskripsi`, `file`, `user_id`, `fakultas_id`, `prodi_id`, `created_at`, `updated_at`) VALUES
('08207207-9446-45da-a459-4aadc6f333a7', 'lo', 'dasdasd', 'arsip/1769954099_CamScanner 11-01-26 16.03.pdf', 'a0f75377-d016-4459-b804-c9254e2733d4', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'bf9dfeab-f5e0-11f0-8a8e-c018504c5f47', '2026-02-01 06:54:59', '2026-02-01 06:54:59'),
('490a95dd-427a-44a1-9bc2-f107c1c3fac5', 'mesin', 'mesin', 'arsip/connector_1775397856.php56', 'a16e5688-850d-43da-b00b-8a172ca12ec7', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'bf9e0a27-f5e0-11f0-8a8e-c018504c5f47', '2026-03-31 03:25:34', '2026-04-05 07:04:16'),
('55ab2b3a-62c2-401e-990c-9fed6938c239', 'sdasa', 'dsadsa', 'arsip/1774950864_ABSEN AGUSTUS - 2 .pdf', 'a16dfdc1-ac0c-4918-819d-017c8d16b70e', 'bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb', NULL, '2026-03-31 02:54:24', '2026-03-31 02:54:24'),
('f7f423e5-964d-4e55-8ace-11854c960586', 'xxx', 'xxxx', 'arsip/1769910515_S1_informatika_SEMUA_KELAS.xlsx', 'a0f7d880-0013-4041-9a22-6b4f932da631', NULL, NULL, '2026-01-31 18:48:35', '2026-01-31 20:23:22');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `arsip`
--
ALTER TABLE `arsip`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
