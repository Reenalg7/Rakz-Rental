-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 23, 2026 at 08:28 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rakz_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `replacement_vehicle_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `pickup_date` date NOT NULL,
  `return_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `vehicle_id`, `replacement_vehicle_id`, `customer_name`, `pickup_date`, `return_date`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 26, 'Ren Gordorn', '2026-09-20', '2026-09-22', 500.00, 'confirmed', '2026-09-17 02:05:14', '2026-09-17 07:32:30'),
(2, 3, 2, NULL, 'Test Customer', '2026-09-19', '2026-09-20', 220.00, 'cancelled', '2026-09-17 06:46:44', '2026-09-17 06:46:57'),
(3, 3, 1, 6, 'Test Customer', '2026-09-20', '2026-09-22', 300.00, 'confirmed', '2026-09-17 07:35:12', '2026-09-17 07:37:26'),
(4, 4, 1, 26, 'test customer', '2026-09-23', '2026-09-25', 300.00, 'confirmed', '2026-09-17 08:15:35', '2026-09-17 08:16:57'),
(5, 4, 1, NULL, 'test customer', '2026-09-17', '2026-09-18', 150.00, 'confirmed', '2026-09-17 10:06:24', '2026-09-17 10:06:24'),
(6, 4, 1, NULL, 'test customer', '2026-09-19', '2026-09-20', 150.00, 'confirmed', '2026-09-17 10:07:10', '2026-09-17 10:07:10'),
(7, 5, 2, NULL, 'esha singh', '2026-09-17', '2026-09-18', 220.00, 'confirmed', '2026-09-17 10:11:21', '2026-09-17 10:11:21'),
(8, 5, 1, NULL, 'esha singh', '2026-10-02', '2026-10-03', 150.00, 'confirmed', '2026-09-17 10:36:26', '2026-09-17 10:36:26');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_30_105146_create_vehicles_table', 1),
(5, '2026_08_30_113707_create_bookings_table', 1),
(6, '2026_09_01_102557_add_role_to_users_table', 1),
(7, '2026_09_01_124345_add_user_id_to_bookings_table', 1),
(8, '2026_09_02_101920_add_registration_to_vehicles_table', 1),
(9, '2026_09_17_131327_create_vehicle_replacements_table', 1),
(10, '2026_09_17_135615_add_missing_registration_to_vehicles_table', 2),
(11, '2026_09_17_191158_add_replacement_vehicle_id_to_bookings_table', 3),
(12, '2026_09_17_194607_create_notifications_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `booking_id`, `title`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 4, 4, 'Vehicle Replacement Notice', 'Your vehicle for Booking #4 has been replaced from LR3180 - Toyota Wish to LR6000 - Toyota Corolla. Your booking dates and total amount remain unchanged.', 0, '2026-09-17 08:16:57', '2026-09-17 08:16:57');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('sLZVxCZU2oEtfSpQPMpm9HHUO0EHmjJxK4yu2AAn', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.135.0 Chrome/148.0.7778.280 Electron/42.8.1 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUERVS0FtV0dTeWowdnNKMXcwcmhRQzBsYnJtY0d4dXZXMW5yUUFyNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1789684792);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Rakz Admin', 'admin@rakzrental.com', 'admin', NULL, '$2y$12$l.wZ7iOcTdsTEGK9K09f8.pUcISxoIRb.wDHHcRtzXGAa.xy.qIca', NULL, '2026-09-17 01:32:12', '2026-09-17 01:32:12'),
(2, 'Ren Gordorn', 'RenG@gmail.com', 'customer', NULL, '$2y$12$Xe7o9sNbD9unv64/dku4OOTFzyGDuzWr4Y7OHX5sDbkhsD6bnUYG.', NULL, '2026-09-17 02:03:24', '2026-09-17 02:03:24'),
(3, 'Test Customer', 'customer@test.gmail.com', 'customer', NULL, '$2y$12$gjLCqgkxOcCfNMzPAFzYze7i.kYTkcoJ4MLfxTX5sJsz0.OVHR65q', NULL, '2026-09-17 06:46:03', '2026-09-17 06:46:03'),
(4, 'test customer', 'testcus@test.com', 'customer', NULL, '$2y$12$.9HQlawcdN8r.cR2.AhcEO4ekciiDyDnaoiXkOWP6d84bOYTwYRRG', NULL, '2026-09-17 08:15:07', '2026-09-17 08:15:07'),
(5, 'esha singh', 'eshasingh@gmail.com', 'customer', NULL, '$2y$12$TJ7WLYpuzDriLh1KJunXUemif3mhgTuGOxlOTnICNj.NJkUUWNOzm', NULL, '2026-09-17 10:10:44', '2026-09-17 10:10:44');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `registration` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `daily_rate` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `registration`, `name`, `category`, `daily_rate`, `status`, `created_at`, `updated_at`) VALUES
(1, 'LR3180', 'Toyota Wish', 'MPV', 150.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(2, 'LR3181', 'Hyundai Tucson', 'SUV', 220.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(3, 'LR3182', 'Toyota Hiace', 'Van', 250.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(4, 'LR3183', 'Nissan NV150', 'Van', 100.00, 'unavailable', '2026-09-17 01:58:27', '2026-09-17 07:03:08'),
(5, 'LR3184', 'Ford Ranger', 'Pickup', 300.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(6, 'LR4038', 'Nissan NV150', 'Van', 100.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(7, 'LR4039', 'Toyota Wish', 'MPV', 150.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(8, 'LR4040', 'Toyota Wish', 'MPV', 150.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(9, 'LR4041', 'Hyundai Tucson', 'SUV', 300.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(10, 'LR4042', 'Nissan NV150', 'Van', 100.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(11, 'LR4250', 'Nissan NV150', 'Van', 100.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(12, 'LR4251', 'Nissan Urvan', 'Van', 300.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(13, 'LR4252', 'Toyota Vitz', 'Hatchback', 100.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(14, 'LR4270', 'Nissan X-Trail', 'SUV', 220.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(15, 'LR4287', 'Toyota Hiace', 'Van', 450.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(16, 'LR4725', 'Toyota Fielder', 'Wagon', 100.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(17, 'LR4726', 'Toyota Fielder', 'Wagon', 100.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(18, 'LR4727', 'Mazda BT50', 'Pickup', 300.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(19, 'LR4728', 'Isuzu Dmax', 'Pickup', 250.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(20, 'LR4729', 'Nissan Urvan', 'Van', 300.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(21, 'LR5355', 'Toyota Voxy', 'MPV', 220.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(22, 'LR5356', 'Nissan NV150', 'Van', 100.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(23, 'LR5357', 'Toyota Wish', 'MPV', 150.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(24, 'LR5358', 'Toyota Hiace', 'Van', 250.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(25, 'LR5359', 'Toyota Hiace', 'Van', 250.00, 'available', '2026-09-17 01:58:27', '2026-09-17 01:58:27'),
(26, 'LR6000', 'Toyota Corolla', 'Sedan', 180.00, 'available', '2026-09-17 07:03:08', '2026-09-17 07:03:08');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_replacements`
--

CREATE TABLE `vehicle_replacements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `old_vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `new_vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_replacements`
--

INSERT INTO `vehicle_replacements` (`id`, `old_vehicle_id`, `new_vehicle_id`, `admin_id`, `created_at`, `updated_at`) VALUES
(1, 4, 26, 1, '2026-09-17 07:03:08', '2026-09-17 07:03:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookings_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `bookings_user_id_foreign` (`user_id`),
  ADD KEY `bookings_replacement_vehicle_id_foreign` (`replacement_vehicle_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`),
  ADD KEY `notifications_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicles_registration_unique` (`registration`);

--
-- Indexes for table `vehicle_replacements`
--
ALTER TABLE `vehicle_replacements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_replacements_old_vehicle_id_foreign` (`old_vehicle_id`),
  ADD KEY `vehicle_replacements_new_vehicle_id_foreign` (`new_vehicle_id`),
  ADD KEY `vehicle_replacements_admin_id_foreign` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `vehicle_replacements`
--
ALTER TABLE `vehicle_replacements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_replacement_vehicle_id_foreign` FOREIGN KEY (`replacement_vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicle_replacements`
--
ALTER TABLE `vehicle_replacements`
  ADD CONSTRAINT `vehicle_replacements_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicle_replacements_new_vehicle_id_foreign` FOREIGN KEY (`new_vehicle_id`) REFERENCES `vehicles` (`id`),
  ADD CONSTRAINT `vehicle_replacements_old_vehicle_id_foreign` FOREIGN KEY (`old_vehicle_id`) REFERENCES `vehicles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
