-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 30, 2026 at 01:26 PM
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
-- Database: `booking_application`
--

-- --------------------------------------------------------

--
-- Table structure for table `barberfavs`
--

CREATE TABLE `barberfavs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barberfavs`
--

INSERT INTO `barberfavs` (`id`, `user_id`, `staff_id`, `created_at`, `updated_at`) VALUES
(1, 39, 1, '2026-04-22 01:06:33', '2026-04-22 01:06:33');

-- --------------------------------------------------------

--
-- Table structure for table `barberratings`
--

CREATE TABLE `barberratings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `rate` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `staff_service_id` int(11) NOT NULL,
  `amount` varchar(191) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` varchar(255) NOT NULL,
  `booking_timestamp` varchar(255) NOT NULL,
  `timestamp` varchar(255) NOT NULL,
  `reschedule` enum('No','Yes') NOT NULL DEFAULT 'No',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `staff_id`, `staff_service_id`, `amount`, `booking_date`, `booking_time`, `booking_timestamp`, `timestamp`, `reschedule`, `status`, `created_at`, `updated_at`) VALUES
(1, 44, 6, 2, '100', '2026-04-28', '11:00:00', '1777374000', '1777288154', 'No', 'completed', '2026-04-27 05:09:14', '2026-04-27 05:10:46');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `latitude` varchar(191) DEFAULT NULL,
  `longitude` varchar(191) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `address`, `email`, `phone`, `latitude`, `longitude`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Farmgate', 'Quae neque non susci', 'cecilolat@mailinator.com', '+1 (316) 501-5261', '23.44', '45.44', 'Active', '2026-02-02 11:11:02', '2026-02-07 06:55:33');

-- --------------------------------------------------------

--
-- Table structure for table `durations`
--

CREATE TABLE `durations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `time_duration` varchar(191) DEFAULT NULL,
  `time_unit` varchar(191) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `durations`
--

INSERT INTO `durations` (`id`, `time_duration`, `time_unit`, `status`, `created_at`, `updated_at`) VALUES
(1, '30', 'Minutes', 'Active', '2026-02-01 11:35:20', '2026-02-02 11:29:32'),
(3, '45', 'Minutes', 'Active', '2026-02-07 06:37:28', '2026-04-04 05:56:35');

-- --------------------------------------------------------

--
-- Table structure for table `earnings`
--

CREATE TABLE `earnings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` varchar(191) DEFAULT NULL,
  `date` date NOT NULL,
  `time` varchar(255) NOT NULL,
  `timestamp` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `earnings`
--

INSERT INTO `earnings` (`id`, `staff_id`, `booking_id`, `amount`, `date`, `time`, `timestamp`, `created_at`, `updated_at`) VALUES
(1, 6, 1, '100.00', '2026-04-27', '11:09:35 am', '1777288175', '2026-04-27 05:09:35', '2026-04-27 05:09:35');

-- --------------------------------------------------------

--
-- Table structure for table `experiences`
--

CREATE TABLE `experiences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `year_of_exp` varchar(20) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `experiences`
--

INSERT INTO `experiences` (`id`, `year_of_exp`, `status`, `created_at`, `updated_at`) VALUES
(1, '1', 'Active', '2026-02-03 05:24:25', '2026-02-07 06:10:48'),
(3, '2', 'Active', '2026-02-07 06:10:55', '2026-02-07 06:10:55');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_11_122640_create_user_types_table', 1),
(2, '2014_10_12_000000_create_users_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2026_02_01_143438_create_services_table', 2),
(7, '2026_02_01_160418_create_durations_table', 3),
(8, '2026_02_01_175607_create_branches_table', 4),
(9, '2026_02_03_104947_create_experiences_table', 5),
(10, '2026_02_03_115215_create_specialities_table', 6),
(11, '2026_02_03_134953_create_working_days_table', 7),
(12, '2026_02_03_152453_add_columns_to_working_days_table', 8),
(13, '2026_02_03_164508_create_working_time_ranges_table', 9),
(15, '2026_02_07_104805_create_staff_services_table', 10),
(16, '2026_02_05_133902_create_staff_table', 11),
(17, '2026_02_07_130631_create_staff_working_days_table', 12),
(18, '2026_04_13_053622_create_bookings_table', 13),
(19, '2026_04_22_064551_create_barberfavs_table', 14),
(20, '2026_04_22_073234_create_barberratings_table', 15),
(21, '2026_04_23_054426_create_earnings_table', 16),
(22, '2026_04_23_055003_create_withdraws_table', 16),
(23, '2026_04_23_061957_create_paymentmethods_table', 17),
(24, '2026_04_27_111712_create_withdrawsettings_table', 18);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paymentmethods`
--

CREATE TABLE `paymentmethods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `paymentmethods`
--

INSERT INTO `paymentmethods` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Paypal', 'Active', NULL, '2026-04-30 04:15:19'),
(2, 'Stripe', 'Active', NULL, NULL),
(3, 'Bkash', 'Active', NULL, NULL),
(4, 'Rocket', 'Active', NULL, NULL),
(5, 'Nagad', 'Active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(2, 'App\\Models\\User', 37, 'MyApp', 'd13bfbc66475b17fcbf089d7e61c2a268485d6586f7bb44927f2bfcb72e9058f', '[\"*\"]', '2026-04-13 00:00:29', NULL, '2026-04-12 23:59:54', '2026-04-13 00:00:29'),
(7, 'App\\Models\\User', 39, 'MyApp', 'e730619fbb486be3c576577adbaff6ed739e4eac6553a9290717484e7fdd5abc', '[\"*\"]', NULL, NULL, '2026-04-13 00:25:59', '2026-04-13 00:25:59'),
(8, 'App\\Models\\User', 39, 'MyApp', '2e4fd527fc59555aabdfcd84a024452a626cbe06c508006d8706e0c354e7aa79', '[\"*\"]', '2026-04-13 00:33:51', NULL, '2026-04-13 00:32:32', '2026-04-13 00:33:51'),
(9, 'App\\Models\\User', 37, 'MyApp', 'e4e251c48813ae7c0f371af9d20f2a68e95e7b5500f5d2c762d17bcf9d86124d', '[\"*\"]', '2026-04-13 00:39:10', NULL, '2026-04-13 00:34:54', '2026-04-13 00:39:10'),
(10, 'App\\Models\\User', 39, 'MyApp', 'b38bf482be8e8da1ea89ec4154d77b7a4e78a1ac379dfcf2caa594c2a63926eb', '[\"*\"]', '2026-04-13 00:42:43', NULL, '2026-04-13 00:41:18', '2026-04-13 00:42:43'),
(18, 'App\\Models\\User', 44, 'MyApp', 'd85572df7b38a94603358ffae2b3b72870ba6a89c1d8f7193008aaf5229cf9dd', '[\"*\"]', '2026-04-19 06:31:28', NULL, '2026-04-19 02:17:52', '2026-04-19 06:31:28'),
(19, 'App\\Models\\User', 44, 'MyApp', 'e586380568767d07b4d1c8deaa73793ee312d1277a56f6d6f7c7639ec4500c12', '[\"*\"]', '2026-04-20 00:10:05', NULL, '2026-04-20 00:07:47', '2026-04-20 00:10:05'),
(22, 'App\\Models\\User', 38, 'MyApp', '3e68c013af3a3d94fa2cb34296f00425c63517286e1791faa7707765acd2874d', '[\"*\"]', '2026-04-21 02:31:01', NULL, '2026-04-21 01:06:05', '2026-04-21 02:31:01'),
(23, 'App\\Models\\User', 37, 'MyApp', '2eb22fa8ffe0a4c9c33bf52df193324301b71c1e642cd90f9534775066ce02f2', '[\"*\"]', '2026-04-21 23:55:35', NULL, '2026-04-21 23:49:08', '2026-04-21 23:55:35'),
(25, 'App\\Models\\User', 39, 'MyApp', '48c0ceb55a4a63f6eb86dbc04a605031d517641e6455145cf67b5c4f73d564e2', '[\"*\"]', '2026-04-27 00:43:43', NULL, '2026-04-22 01:03:37', '2026-04-27 00:43:43'),
(31, 'App\\Models\\User', 46, 'MyApp', '9c7db7bb3fdbee6ea7f5e8c51d8aec9319424ffc24664acb4add6e0ae43441e7', '[\"*\"]', '2026-04-26 23:20:36', NULL, '2026-04-26 01:36:28', '2026-04-26 23:20:36'),
(32, 'App\\Models\\User', 42, 'MyApp', 'b92f55fed1407bfc3729944e19db3fe1243c547262bbfe73918ca42cb10ee603', '[\"*\"]', NULL, NULL, '2026-04-27 04:26:51', '2026-04-27 04:26:51'),
(33, 'App\\Models\\User', 44, 'MyApp', '8f6489cc280f7665cb053a229d0085aee5ab060faf15549447558607e66691fb', '[\"*\"]', '2026-04-27 05:09:13', NULL, '2026-04-27 04:28:05', '2026-04-27 05:09:13'),
(34, 'App\\Models\\User', 43, 'MyApp', '2bfa855bae92ae7b354f8b75e48bcf6ef4a9ee19f3adb2880bfafda426235f74', '[\"*\"]', '2026-04-27 05:10:45', NULL, '2026-04-27 04:29:56', '2026-04-27 05:10:45');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `hit_count` int(11) NOT NULL DEFAULT 0,
  `image` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `status`, `hit_count`, `image`, `created_at`, `updated_at`) VALUES
(2, 'Salons', 'Active', 0, 'uploads/services/default.png', '2026-02-01 09:29:31', '2026-02-01 09:29:31'),
(3, 'Spas', 'Active', 0, 'uploads/services/default.png', '2026-02-01 09:31:55', '2026-02-02 11:24:17');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
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
-- Table structure for table `specialities`
--

CREATE TABLE `specialities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `specialities`
--

INSERT INTO `specialities` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Barber', 'barber', 'Active', '2026-02-03 06:32:27', '2026-02-03 06:32:27');

-- --------------------------------------------------------

--
-- Table structure for table `staffs`
--

CREATE TABLE `staffs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `specialty_id` bigint(20) UNSIGNED DEFAULT NULL,
  `experience_id` bigint(20) UNSIGNED DEFAULT NULL,
  `working_time_range_id` bigint(20) UNSIGNED DEFAULT NULL,
  `slot_duration_minutes` int(11) DEFAULT 15,
  `balance` decimal(12,2) DEFAULT 0.00,
  `current_status` varchar(191) DEFAULT 'Available',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staffs`
--

INSERT INTO `staffs` (`id`, `user_id`, `branch_id`, `specialty_id`, `experience_id`, `working_time_range_id`, `slot_duration_minutes`, `balance`, `current_status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 37, NULL, NULL, NULL, 2, 20, 0.00, 'Available', NULL, NULL, '2026-04-12 23:57:09', '2026-04-12 23:57:09'),
(2, 38, NULL, NULL, NULL, 3, 0, 0.00, 'Available', NULL, NULL, '2026-04-13 00:01:18', '2026-04-13 00:01:48'),
(5, 42, NULL, NULL, NULL, 2, 20, 0.00, 'Available', NULL, NULL, '2026-04-14 23:13:43', '2026-04-14 23:13:43'),
(6, 43, NULL, NULL, NULL, 2, 20, 100.00, 'Available', NULL, NULL, '2026-04-14 23:14:22', '2026-04-27 05:09:35');

-- --------------------------------------------------------

--
-- Table structure for table `staff_services`
--

CREATE TABLE `staff_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `duration_id` bigint(20) UNSIGNED DEFAULT NULL,
  `duration` varchar(191) DEFAULT NULL,
  `is_special` tinyint(4) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff_services`
--

INSERT INTO `staff_services` (`id`, `user_id`, `staff_id`, `service_id`, `duration_id`, `duration`, `is_special`, `price`, `created_at`, `updated_at`) VALUES
(1, 37, 1, 2, NULL, NULL, 1, 50.00, '2026-04-12 23:57:09', '2026-04-12 23:57:09'),
(2, 37, 1, 3, NULL, NULL, 0, 100.00, '2026-04-12 23:57:09', '2026-04-12 23:57:09'),
(11, 42, 5, 2, NULL, NULL, 0, 50.00, '2026-04-14 23:13:43', '2026-04-14 23:13:43'),
(12, 42, 5, 3, NULL, NULL, 0, 100.00, '2026-04-14 23:13:43', '2026-04-14 23:13:43'),
(13, 43, 6, 2, NULL, NULL, 1, 50.00, '2026-04-14 23:14:22', '2026-04-14 23:14:22'),
(14, 43, 6, 3, NULL, NULL, 1, 100.00, '2026-04-14 23:14:23', '2026-04-14 23:14:23'),
(15, 38, 2, 2, NULL, '15', 0, 599.00, '2026-04-21 00:58:16', '2026-04-21 00:58:16');

-- --------------------------------------------------------

--
-- Table structure for table `staff_working_days`
--

CREATE TABLE `staff_working_days` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `working_day_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff_working_days`
--

INSERT INTO `staff_working_days` (`id`, `user_id`, `staff_id`, `working_day_id`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 4, NULL, NULL),
(2, NULL, 1, 5, NULL, NULL),
(3, NULL, 2, 4, NULL, NULL),
(4, NULL, 2, 5, NULL, NULL),
(9, NULL, 5, 4, NULL, NULL),
(10, NULL, 5, 5, NULL, NULL),
(11, NULL, 6, 4, NULL, NULL),
(12, NULL, 6, 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `user_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `image` varchar(191) DEFAULT 'defaults/profile.png',
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `home_service` enum('no','yes') DEFAULT NULL,
  `total_rating` varchar(191) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `activation_status` enum('offline','online') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `user_type_id`, `role`, `email`, `phone`, `email_verified_at`, `password`, `image`, `status`, `home_service`, `total_rating`, `remember_token`, `expires_at`, `activation_status`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 1, 'admin', 'admin@gmail.com', '01712345678', NULL, '$2y$10$VY6EEJgoR8IU0wXUmxSC.e8DjXlkD/Wuwq7RdW8VXl97i.UxLmKJi', 'defaults/profile.png', 'Active', NULL, NULL, 'HrrMGbavTUt1ZRl1eqc8mm95kn9fCp80TCeHh0wy8fTxlN5UhzuDay9edynU', NULL, 'offline', '2026-02-01 06:51:05', '2026-02-01 07:22:11'),
(37, 'consultD', 3, 'service_provider', 'consult-d@gmail.com', '01676099104', NULL, '$2y$10$JjIelDsLgvLXxDLfAaUzae5HgzcamDDBfrBNmB9R2EDSDheEFooA6', 'uploads/users/17760598282profile.jpg', 'Active', NULL, NULL, NULL, NULL, 'offline', '2026-04-12 23:57:08', '2026-04-13 00:00:30'),
(38, 'Nahidul Islam Shakin', 3, 'service_provider', 'shakin@gmail.com', '01954841508', NULL, '$2y$10$3/FklBKZel6G1Tg4okxhs.oHNqqFy4ZKl2S0fSLNZPVwTj1YO0FXC', 'uploads/users/1776060150381000037092.jpg', 'Active', NULL, NULL, NULL, NULL, 'online', '2026-04-13 00:01:16', '2026-04-21 02:31:01'),
(39, 'Md. Ali Ahmed', 2, 'user', 'ali@gmail.com', '01536203121', NULL, '$2y$10$OPig0QRAi4wQGwwLLfr3wOzCHq0lR6c2kP..z3z5PbQoTnvLVN49e', 'uploads/users/177684291639profile.jpg', 'Active', NULL, NULL, NULL, NULL, 'offline', '2026-04-13 00:25:40', '2026-04-22 01:28:36'),
(42, 'Jal', 3, 'service_provider', 'jal-d@gmail.com', '0938737464', NULL, '$2y$10$NiBasamowfU3sL0eWC0ikOJQF1RtV.Si49KQ06EuOmdMLDcKcoyUC', 'defaults/profile.png', 'Active', 'yes', NULL, NULL, NULL, 'offline', '2026-04-14 23:13:43', '2026-04-14 23:13:43'),
(43, 'Strings', 3, 'service_provider', 'string-d@gmail.com', '0938737453', NULL, '$2y$10$jHqK3JQwoWLnena4iZXSwOuFxRRIJuKWq.zCic55daNTTdmoGZNVa', 'defaults/profile.png', 'Active', 'yes', NULL, NULL, NULL, 'offline', '2026-04-14 23:14:22', '2026-04-14 23:14:22'),
(44, 'Nahidul Islam Shakin', 2, 'user', 'shakin@email.com', '01954841509', NULL, '$2y$10$24G0I0S8wcXZMzOhoh5YouNm7X/CCiBw.VWmWr601jCoMesNoV4fC', 'uploads/users/177648868071000000075.jpg', 'Active', NULL, NULL, NULL, NULL, 'offline', '2026-04-17 23:04:40', '2026-04-17 23:04:40'),
(45, 'shakin', 2, 'user', NULL, '01548984536', NULL, '$2y$10$DIsBWn7NwowK5ieDsQ4kMubc50gfUrAtNe1rhF7yMjVipR9hx4gYO', 'uploads/users/177648878081000000075.jpg', 'Active', NULL, NULL, NULL, NULL, 'offline', '2026-04-17 23:06:20', '2026-04-17 23:06:20'),
(46, 'test', 2, 'user', 'test@gmail.com', '01800000000', NULL, '$2y$10$tGbJpLjV0O8ioqtd40LpLus1zUkXtCDq6rrn/O/NGU/9fzJegy5RW', 'uploads/users/17768376329IMG_20260422_115613.jpg', 'Active', NULL, NULL, NULL, NULL, 'offline', '2026-04-22 00:00:32', '2026-04-22 00:00:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `role` varchar(191) DEFAULT NULL,
  `is_showing` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `name`, `role`, `is_showing`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 0, '2026-02-01 06:47:05', '2026-02-01 06:47:05'),
(2, 'User', 'user', 1, '2026-02-01 06:47:05', '2026-02-01 06:47:05'),
(3, 'Service Provider', 'service_provider', 1, '2026-02-01 06:47:05', '2026-02-01 06:47:05');

-- --------------------------------------------------------

--
-- Table structure for table `withdraws`
--

CREATE TABLE `withdraws` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` int(11) NOT NULL,
  `paymentmethod_id` int(11) DEFAULT NULL,
  `amount` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(255) NOT NULL,
  `timestamp` varchar(255) NOT NULL,
  `screenshot` varchar(255) DEFAULT NULL,
  `trx_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdraws`
--

INSERT INTO `withdraws` (`id`, `staff_id`, `paymentmethod_id`, `amount`, `date`, `time`, `timestamp`, `screenshot`, `trx_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 1, '100', '2026-04-30', '02:34:00 PM', '1777523766', NULL, 'ggrr', 'approved', NULL, '2026-04-30 05:24:57');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawsettings`
--

CREATE TABLE `withdrawsettings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `min_withdraw` varchar(255) DEFAULT NULL,
  `max_withdraw` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdrawsettings`
--

INSERT INTO `withdrawsettings` (`id`, `min_withdraw`, `max_withdraw`, `created_at`, `updated_at`) VALUES
(1, '50', '100', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `working_days`
--

CREATE TABLE `working_days` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `working_days`
--

INSERT INTO `working_days` (`id`, `name`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
(4, 'Saturday', 1, 'Active', '2026-02-03 09:48:54', '2026-02-03 09:57:51'),
(5, 'Sunday', 2, 'Active', '2026-02-03 09:56:06', '2026-02-03 09:56:06'),
(6, 'Monday', 3, 'Active', '2026-02-07 07:04:00', '2026-02-07 07:04:00'),
(7, 'Tuesday', 4, 'Active', '2026-02-07 07:04:14', '2026-02-07 07:04:14'),
(8, 'Wednesday', 5, 'Active', '2026-02-07 07:04:29', '2026-02-07 07:04:29'),
(9, 'Thursday', 6, 'Active', '2026-02-07 07:04:43', '2026-02-07 07:04:43'),
(10, 'Friday', 7, 'Active', '2026-02-07 07:04:56', '2026-02-07 07:04:56');

-- --------------------------------------------------------

--
-- Table structure for table `working_time_ranges`
--

CREATE TABLE `working_time_ranges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) DEFAULT NULL,
  `from_time` time DEFAULT NULL,
  `to_time` time DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `working_time_ranges`
--

INSERT INTO `working_time_ranges` (`id`, `title`, `from_time`, `to_time`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Full', '09:00:00', '20:00:00', 'Active', '2026-02-03 12:15:47', '2026-02-03 12:15:47'),
(3, 'Morning', '09:00:00', '18:00:00', 'Active', '2026-02-07 06:39:34', '2026-02-07 06:39:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barberfavs`
--
ALTER TABLE `barberfavs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barberratings`
--
ALTER TABLE `barberratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `durations`
--
ALTER TABLE `durations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `earnings`
--
ALTER TABLE `earnings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `experiences`
--
ALTER TABLE `experiences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `specialities`
--
ALTER TABLE `specialities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staffs`
--
ALTER TABLE `staffs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staffs_user_id_foreign` (`user_id`),
  ADD KEY `staffs_branch_id_foreign` (`branch_id`),
  ADD KEY `staffs_specialty_id_foreign` (`specialty_id`),
  ADD KEY `staffs_experience_id_foreign` (`experience_id`),
  ADD KEY `staffs_working_time_range_id_foreign` (`working_time_range_id`),
  ADD KEY `staffs_created_by_foreign` (`created_by`),
  ADD KEY `staffs_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `staff_services`
--
ALTER TABLE `staff_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_services_user_id_foreign` (`user_id`),
  ADD KEY `staff_services_staff_id_foreign` (`staff_id`),
  ADD KEY `staff_services_service_id_foreign` (`service_id`),
  ADD KEY `staff_services_duration_id_foreign` (`duration_id`);

--
-- Indexes for table `staff_working_days`
--
ALTER TABLE `staff_working_days`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_working_days_user_id_foreign` (`user_id`),
  ADD KEY `staff_working_days_staff_id_foreign` (`staff_id`),
  ADD KEY `staff_working_days_working_day_id_foreign` (`working_day_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraws`
--
ALTER TABLE `withdraws`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawsettings`
--
ALTER TABLE `withdrawsettings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `working_days`
--
ALTER TABLE `working_days`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `working_time_ranges`
--
ALTER TABLE `working_time_ranges`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barberfavs`
--
ALTER TABLE `barberfavs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `barberratings`
--
ALTER TABLE `barberratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `durations`
--
ALTER TABLE `durations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `earnings`
--
ALTER TABLE `earnings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `experiences`
--
ALTER TABLE `experiences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `specialities`
--
ALTER TABLE `specialities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staffs`
--
ALTER TABLE `staffs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `staff_services`
--
ALTER TABLE `staff_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `staff_working_days`
--
ALTER TABLE `staff_working_days`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `withdraws`
--
ALTER TABLE `withdraws`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `withdrawsettings`
--
ALTER TABLE `withdrawsettings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `working_days`
--
ALTER TABLE `working_days`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `working_time_ranges`
--
ALTER TABLE `working_time_ranges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `staffs`
--
ALTER TABLE `staffs`
  ADD CONSTRAINT `staffs_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staffs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staffs_experience_id_foreign` FOREIGN KEY (`experience_id`) REFERENCES `experiences` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staffs_specialty_id_foreign` FOREIGN KEY (`specialty_id`) REFERENCES `specialities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staffs_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staffs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staffs_working_time_range_id_foreign` FOREIGN KEY (`working_time_range_id`) REFERENCES `working_time_ranges` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff_services`
--
ALTER TABLE `staff_services`
  ADD CONSTRAINT `staff_services_duration_id_foreign` FOREIGN KEY (`duration_id`) REFERENCES `durations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staff_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staff_services_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staffs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staff_services_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff_working_days`
--
ALTER TABLE `staff_working_days`
  ADD CONSTRAINT `staff_working_days_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staffs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staff_working_days_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staff_working_days_working_day_id_foreign` FOREIGN KEY (`working_day_id`) REFERENCES `working_days` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
