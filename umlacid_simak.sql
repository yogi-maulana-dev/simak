-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 07 Apr 2026 pada 21.34
-- Versi server: 11.4.10-MariaDB-cll-lve
-- Versi PHP: 8.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `umlacid_simak`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `asesor_akses`
--

CREATE TABLE `asesor_akses` (
  `id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `fakultas_id` char(36) DEFAULT NULL,
  `prodi_id` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_fakultas`
--

CREATE TABLE `data_fakultas` (
  `id_data_fakultas` char(36) NOT NULL,
  `arsip_id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `fakultas_id` char(36) DEFAULT NULL,
  `role_id` char(36) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `data_fakultas`
--

INSERT INTO `data_fakultas` (`id_data_fakultas`, `arsip_id`, `user_id`, `fakultas_id`, `role_id`, `created_at`, `updated_at`) VALUES
('74072c5d-9286-416a-8b26-640d18fc27ea', '490a95dd-427a-44a1-9bc2-f107c1c3fac5', 'a16e5688-850d-43da-b00b-8a172ca12ec7', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', '44444444-4444-4444-4444-444444444444', '2026-04-05 07:04:16', '2026-04-05 07:04:16'),
('b3d50878-9d50-4f69-b7f4-1c964f3932f8', 'd273bc53-3047-430c-9516-0db8844938ad', 'a0e177ca-842c-4b5d-97c4-da172e68e3df', 'cccccccc-cccc-cccc-cccc-cccccccccccc', '33333333-3333-3333-3333-333333333333', '2026-01-30 23:47:27', '2026-01-30 23:47:27'),
('b670bcfa-92e2-4d81-bdf9-94465da8df4b', '55ab2b3a-62c2-401e-990c-9fed6938c239', 'a16dfdc1-ac0c-4918-819d-017c8d16b70e', 'bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb', '33333333-3333-3333-3333-333333333333', '2026-03-31 02:54:24', '2026-03-31 02:54:24'),
('ca273731-0c3b-4a90-b167-916189cfe050', 'b511afbc-583e-4bf7-8ba5-4d588ca7e917', 'a0e177ca-842c-4b5d-97c4-da172e68e3df', 'cccccccc-cccc-cccc-cccc-cccccccccccc', '33333333-3333-3333-3333-333333333333', '2026-01-26 21:39:21', '2026-01-26 21:39:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_prodis`
--

CREATE TABLE `data_prodis` (
  `id_data_prodis` char(36) NOT NULL,
  `arsip_id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `fakultas_id` char(36) DEFAULT NULL,
  `prodi_id` char(36) DEFAULT NULL,
  `role_id` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `data_prodis`
--

INSERT INTO `data_prodis` (`id_data_prodis`, `arsip_id`, `user_id`, `fakultas_id`, `prodi_id`, `role_id`, `created_at`, `updated_at`) VALUES
('1ec360dc-1aa2-4f9e-86ee-91598b18215e', '5ac0ae73-a99f-4085-8acc-ba1d39015e06', 'a0f75377-d016-4459-b804-c9254e2733d4', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'bf9dfeab-f5e0-11f0-8a8e-c018504c5f47', '44444444-4444-4444-4444-444444444444', '2026-02-01 06:52:51', '2026-02-01 06:52:51'),
('3203e299-21eb-4010-af2d-5f15bd6245a5', 'd5cef2ba-5553-4e02-a28f-898b764cf776', 'a0f75377-d016-4459-b804-c9254e2733d4', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'bf9dfeab-f5e0-11f0-8a8e-c018504c5f47', '44444444-4444-4444-4444-444444444444', '2026-02-01 06:09:14', '2026-02-01 06:09:14'),
('8de8bea4-b654-461c-baa6-be976b4d3d38', 'd006fdb1-d683-4293-8ed5-62912b9e6f6e', 'a0f75377-d016-4459-b804-c9254e2733d4', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'bf9dfeab-f5e0-11f0-8a8e-c018504c5f47', '44444444-4444-4444-4444-444444444444', '2026-01-31 23:10:48', '2026-01-31 23:10:48'),
('bc262c67-8b44-45cd-a235-815917001bf5', '08207207-9446-45da-a459-4aadc6f333a7', 'a0f75377-d016-4459-b804-c9254e2733d4', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'bf9dfeab-f5e0-11f0-8a8e-c018504c5f47', '44444444-4444-4444-4444-444444444444', '2026-02-01 06:54:59', '2026-02-01 06:54:59'),
('cf9a091c-ef01-48ff-8df1-0ead7d904bf0', '490a95dd-427a-44a1-9bc2-f107c1c3fac5', 'a16e5688-850d-43da-b00b-8a172ca12ec7', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'bf9e0a27-f5e0-11f0-8a8e-c018504c5f47', '44444444-4444-4444-4444-444444444444', '2026-03-31 03:25:34', '2026-03-31 03:25:34'),
('f5a380e4-073f-407c-b7f0-c0a0e1bff9e1', '34610b9d-69b9-4d01-983b-b61790237455', 'a0f75377-d016-4459-b804-c9254e2733d4', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'bf9dfeab-f5e0-11f0-8a8e-c018504c5f47', '44444444-4444-4444-4444-444444444444', '2026-01-30 23:45:35', '2026-01-30 23:45:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` char(36) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `fakultas`
--

CREATE TABLE `fakultas` (
  `id` char(36) NOT NULL,
  `nama_fakultas` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `fakultas`
--

INSERT INTO `fakultas` (`id`, `nama_fakultas`, `created_at`, `updated_at`) VALUES
('aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'Fakultas Teknik', '2026-01-20 08:33:26', '2026-01-20 08:33:26'),
('bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb', 'Fakultas Ekonomi', '2026-01-20 08:33:26', '2026-01-20 08:33:26'),
('cccccccc-cccc-cccc-cccc-cccccccccccc', 'Fakultas Hukum', '2026-01-20 08:33:26', '2026-01-20 08:33:26'),
('dddddddd-dddd-dddd-dddd-dddddddddddd', 'Fakultas Kedokteran', '2026-01-20 08:33:26', '2026-01-20 08:33:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategoris`
--

CREATE TABLE `kategoris` (
  `id` char(36) NOT NULL,
  `nama_kategori` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` char(36) NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` char(36) NOT NULL,
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
-- Struktur dari tabel `prodi`
--

CREATE TABLE `prodi` (
  `id` char(36) NOT NULL,
  `fakultas_id` char(36) NOT NULL,
  `nama_prodi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `prodi`
--

INSERT INTO `prodi` (`id`, `fakultas_id`, `nama_prodi`, `created_at`, `updated_at`) VALUES
('bf9dfeab-f5e0-11f0-8a8e-c018504c5f47', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'Teknik Informatika', '2026-01-20 09:16:48', '2026-01-20 09:16:48'),
('bf9e08ca-f5e0-11f0-8a8e-c018504c5f47', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'Teknik Sipil', '2026-01-20 09:16:48', '2026-01-20 09:16:48'),
('bf9e0a27-f5e0-11f0-8a8e-c018504c5f47', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'Teknik Mesin', '2026-01-20 09:16:48', '2026-01-20 09:16:48'),
('bf9e0b7a-f5e0-11f0-8a8e-c018504c5f47', 'bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb', 'Manajemen', '2026-01-20 09:16:48', '2026-01-20 09:16:48'),
('bf9e0cd9-f5e0-11f0-8a8e-c018504c5f47', 'bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb', 'Akuntansi', '2026-01-20 09:16:48', '2026-01-20 09:16:48'),
('bf9e0e29-f5e0-11f0-8a8e-c018504c5f47', 'bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb', 'Ekonomi Pembangunan', '2026-01-20 09:16:48', '2026-01-20 09:16:48'),
('bf9e0f56-f5e0-11f0-8a8e-c018504c5f47', 'cccccccc-cccc-cccc-cccc-cccccccccccc', 'Ilmu Hukum', '2026-01-20 09:16:48', '2026-01-20 09:16:48'),
('bf9e108f-f5e0-11f0-8a8e-c018504c5f47', 'dddddddd-dddd-dddd-dddd-dddddddddddd', 'Pendidikan Dokter', '2026-01-20 09:16:48', '2026-01-20 09:16:48'),
('bf9e11cb-f5e0-11f0-8a8e-c018504c5f47', 'dddddddd-dddd-dddd-dddd-dddddddddddd', 'Kedokteran Gigi', '2026-01-20 09:16:48', '2026-01-20 09:16:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `prodis`
--

CREATE TABLE `prodis` (
  `id` char(36) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
('11111111-1111-1111-1111-111111111111', 'superadmin', '2026-01-20 07:56:01', '2026-01-20 07:56:01'),
('22222222-2222-2222-2222-222222222222', 'admin_univ', '2026-01-20 07:56:01', '2026-01-20 07:56:01'),
('33333333-3333-3333-3333-333333333333', 'admin_fakultas', '2026-01-20 07:56:01', '2026-01-20 07:56:01'),
('44444444-4444-4444-4444-444444444444', 'admin_prodi', '2026-01-20 07:56:01', '2026-01-20 07:56:01'),
('55555555-5555-5555-5555-555555555555', 'asesor_fakultas', '2026-01-20 07:56:01', '2026-01-20 07:56:01'),
('66666666-6666-6666-6666-666666666666', 'asesor_prodi', '2026-01-20 07:56:01', '2026-01-20 07:56:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` char(36) NOT NULL,
  `fakultas_id` char(36) DEFAULT NULL,
  `prodi_id` char(36) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`, `fakultas_id`, `prodi_id`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
('a0e151d0-5e20-4c76-94ef-5bfcf850070f', 'Superadmin', 'yogimaulana100@gmail.com', '$2y$12$pQGYuaMbJASvHah3v96zoeGUmqTa7hTmB7wJ9sBkaXK7SmmF.gF8K', '11111111-1111-1111-1111-111111111111', NULL, NULL, NULL, NULL, '2026-01-20 01:00:06', '2026-01-20 01:00:06'),
('a0e177ca-842c-4b5d-97c4-da172e68e3df', 'Hukum', 'yogimaulana60@gmail.com', '$2y$12$pQGYuaMbJASvHah3v96zoeGUmqTa7hTmB7wJ9sBkaXK7SmmF.gF8K', '33333333-3333-3333-3333-333333333333', 'cccccccc-cccc-cccc-cccc-cccccccccccc', NULL, NULL, NULL, '2026-01-20 02:46:18', '2026-01-29 09:03:59'),
('a0f75377-d016-4459-b804-c9254e2733d4', 'Prodi', 'filesgajah22@gmail.com', '$2y$12$fTq7N7YicRGcIEYrSC8azun6JB7TBDGV9CVq1aukFGOV9VTD5ZiIO', '44444444-4444-4444-4444-444444444444', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'bf9dfeab-f5e0-11f0-8a8e-c018504c5f47', NULL, NULL, '2026-01-30 23:32:57', '2026-02-26 04:41:40'),
('a0f7d880-0013-4041-9a22-6b4f932da631', 'v', 'yogimaulana88@gmail.com', '$2y$12$LMvx1bz9B7DW95rwOMF1h.vbkEB4e9wyIhhyCqg4j0WJQ4Tu9z8le', '22222222-2222-2222-2222-222222222222', NULL, NULL, NULL, NULL, '2026-01-31 05:44:56', '2026-01-31 05:44:56'),
('a0f9b292-78a6-419b-afd1-88b197aa4d50', 'Asesor Prodi TEknik Informatika', 'yogimaulana@gmail.com', '$2y$12$mZ1xaZKw6fIp6v1ipAOqPuwBTkkXKUCbmNV38IIUFB7f961tF32zS', '66666666-6666-6666-6666-666666666666', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'bf9dfeab-f5e0-11f0-8a8e-c018504c5f47', NULL, NULL, '2026-02-01 03:50:32', '2026-02-25 10:32:54'),
('a0fa0066-73fe-4b7e-b1cf-5654680f4c72', 'fakultas teknik', 'yogimaulana170@gmail.com', '$2y$12$iIoXq066Yd/TCymYcN/vrOFSR/NSAPxwhzYbXOE7X2Dga0MAAf..K', '55555555-5555-5555-5555-555555555555', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', NULL, NULL, NULL, '2026-02-01 07:28:09', '2026-02-01 07:28:09'),
('a16dfdc1-ac0c-4918-819d-017c8d16b70e', 'lolkxd', 'yogimaulana601@gmail.com', '$2y$12$bCJWyR2fuR57egDfWmL4ZOiGWGAE5K.itwwdQqORFh5hy7aQ6.qLi', '33333333-3333-3333-3333-333333333333', 'bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb', NULL, NULL, NULL, '2026-03-30 23:16:48', '2026-03-31 00:14:58'),
('a16e4be9-6adc-4f57-a2eb-af16bf08c35a', 'assor ekonom', 'ekonom@gmail.com', '$2y$12$8bFk/OzP68eEwqkZwVttFuLPqIuLyPaPlThH9sJzu5AuKTOaA2h3u', '66666666-6666-6666-6666-666666666666', 'bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb', 'bf9e0cd9-f5e0-11f0-8a8e-c018504c5f47', NULL, NULL, '2026-03-31 02:55:20', '2026-03-31 02:55:20'),
('a16e4cb9-edc5-4b56-8af0-8cd2c8949a7f', 'ekonom fakultas ', 'ekonom12@gmail.com', '$2y$12$0WOQ5NUI3fy4UB0zb01yT.J1oVb3hcYTfr4TR4cu6OK1nukoe4GKC', '55555555-5555-5555-5555-555555555555', 'bbbbbbbb-bbbb-bbbb-bbbb-bbbbbbbbbbbb', NULL, NULL, NULL, '2026-03-31 02:57:37', '2026-03-31 02:57:37'),
('a16e5688-850d-43da-b00b-8a172ca12ec7', 'dasdsada', 'adminpmat@uml.ac.id', '$2y$12$sVc.jDf5jDQIY9c33eDHKO8fjlpahTeyoy5E8IoLEhdNiV6tJ24sm', '44444444-4444-4444-4444-444444444444', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'bf9e0a27-f5e0-11f0-8a8e-c018504c5f47', NULL, NULL, '2026-03-31 03:25:02', '2026-03-31 03:25:02'),
('a16e573a-5779-43ab-95f3-751a5a2a8c19', 'mesin', 'yogimaulana123@gmail.com', '$2y$12$Hrg5Nzlm3JkRxsAMoSIh..wEEtlJh9WKhcbR/6V2TM4k2I.FRZW.i', '66666666-6666-6666-6666-666666666666', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'bf9e0a27-f5e0-11f0-8a8e-c018504c5f47', NULL, NULL, '2026-03-31 03:26:59', '2026-03-31 03:26:59'),
('a178b295-8ae4-45b6-a1f3-24dc8bc1d5ed', 'asdasd fwb', 'fanssonly701@gmail.com', '$2y$12$DUoYCDOnnbDpKuviWbeGeOMYQKz6ltDse7SLHPtic1vsZ9jBP/6K6', '11111111-1111-1111-1111-111111111111', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa', 'bf9dfeab-f5e0-11f0-8a8e-c018504c5f47', NULL, NULL, '2026-04-05 07:00:42', '2026-04-05 07:00:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users_backup`
--

CREATE TABLE `users_backup` (
  `id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `fakultas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `prodi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `arsip`
--
ALTER TABLE `arsip`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `asesor_akses`
--
ALTER TABLE `asesor_akses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `asesor_akses_user_id_unique` (`user_id`),
  ADD KEY `asesor_akses_fakultas_id_foreign` (`fakultas_id`),
  ADD KEY `asesor_akses_prodi_id_foreign` (`prodi_id`);

--
-- Indeks untuk tabel `data_fakultas`
--
ALTER TABLE `data_fakultas`
  ADD PRIMARY KEY (`id_data_fakultas`);

--
-- Indeks untuk tabel `data_prodis`
--
ALTER TABLE `data_prodis`
  ADD PRIMARY KEY (`id_data_prodis`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategoris`
--
ALTER TABLE `kategoris`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prodi_fakultas_id_foreign` (`fakultas_id`);

--
-- Indeks untuk tabel `prodis`
--
ALTER TABLE `prodis`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_fakultas_id_foreign` (`fakultas_id`),
  ADD KEY `users_prodi_id_foreign` (`prodi_id`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `asesor_akses`
--
ALTER TABLE `asesor_akses`
  ADD CONSTRAINT `asesor_akses_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `asesor_akses_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `asesor_akses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `prodi`
--
ALTER TABLE `prodi`
  ADD CONSTRAINT `prodi_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
