-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 07 Apr 2026 pada 17.41
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

--
-- Indexes for dumped tables
--

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
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

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
