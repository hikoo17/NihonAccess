-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2026 at 08:02 AM
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
-- Database: `nihonaccess`
--

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
-- Table structure for table `character_attempts`
--

CREATE TABLE `character_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `character_exercise_id` bigint(20) UNSIGNED NOT NULL,
  `answer` varchar(255) NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `character_exercises`
--

CREATE TABLE `character_exercises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `lesson_id` bigint(20) UNSIGNED DEFAULT NULL,
  `character_type` enum('hiragana','katakana','kanji') NOT NULL,
  `character` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `hint` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `character_exercises`
--

INSERT INTO `character_exercises` (`id`, `course_id`, `lesson_id`, `character_type`, `character`, `answer`, `hint`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'hiragana', 'あ', 'a', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(2, 1, NULL, 'hiragana', 'い', 'i', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(3, 1, NULL, 'hiragana', 'う', 'u', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(4, 1, NULL, 'katakana', 'ア', 'a', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(5, 1, NULL, 'katakana', 'イ', 'i', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(6, 2, NULL, 'kanji', '日', 'hi/nichi', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(7, 2, NULL, 'kanji', '本', 'hon', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(8, 3, NULL, 'kanji', '一', 'ichi', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(9, 3, NULL, 'kanji', '二', 'ni', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(10, 3, NULL, 'kanji', '三', 'san', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `teacher_id`, `title`, `slug`, `description`, `level`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Hiragana & Katakana Dasar', 'hiragana-katakana-dasar', 'Pelajari 46 huruf hiragana dan 46 huruf katakana dari nol.', 'Pemula', 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(2, NULL, 'Grammar JLPT N5', 'grammar-jlpt-n5', 'Pola kalimat dasar untuk persiapan JLPT N5.', 'N5', 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(3, NULL, 'Kanji Dasar', 'kanji-dasar', '200+ kanji dasar yang sering muncul dalam JLPT N5.', 'N5', 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34');

-- --------------------------------------------------------

--
-- Table structure for table `course_package`
--

CREATE TABLE `course_package` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_package`
--

INSERT INTO `course_package` (`id`, `course_id`, `package_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 5, NULL, NULL),
(3, 1, 2, NULL, NULL),
(4, 1, 3, NULL, NULL),
(5, 1, 4, NULL, NULL),
(6, 2, 5, NULL, NULL),
(7, 2, 2, NULL, NULL),
(8, 2, 3, NULL, NULL),
(9, 3, 5, NULL, NULL),
(10, 3, 2, NULL, NULL),
(11, 3, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','expired','suspended') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `order_id`, `package_id`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, '2026-06-29', '2026-07-29', 'active', '2026-06-28 21:09:02', '2026-06-28 21:09:02'),
(2, 4, 2, 1, '2026-06-29', '2026-07-29', 'active', '2026-06-28 21:15:51', '2026-06-28 21:15:51'),
(3, 5, 3, 3, '2026-06-29', '2026-12-26', 'active', '2026-06-28 21:19:58', '2026-06-28 21:19:58'),
(4, 6, 4, 3, '2026-06-29', '2026-12-26', 'active', '2026-06-28 21:22:47', '2026-06-28 21:22:47'),
(5, 7, 5, 1, '2026-06-29', '2026-07-29', 'active', '2026-06-28 21:31:24', '2026-06-28 21:31:24'),
(6, 8, 6, 3, '2026-06-29', '2026-12-26', 'active', '2026-06-28 21:40:57', '2026-06-28 21:40:57'),
(7, 9, 7, 1, '2026-06-29', '2026-07-29', 'active', '2026-06-28 22:48:09', '2026-06-28 22:48:09'),
(8, 10, 8, 5, '2026-06-30', '2026-07-30', 'active', '2026-06-29 22:56:35', '2026-06-29 22:56:35');

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
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `course_id`, `title`, `slug`, `content`, `video_url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pengenalan Hiragana', 'pengenalan-hiragana', 'Mengenal 46 huruf hiragana dasar.', 'https://youtu.be/pvPJARjqAa8?si=s05vi51t-RSZU7gl', 0, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(2, 1, 'Pengenalan Katakana', 'pengenalan-katakana', 'Mengenal 46 huruf katakana dasar.', 'https://youtu.be/pvPJARjqAa8?si=s05vi51t-RSZU7gl', 1, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(3, 1, 'Kosakata Dasar', 'kosakata-dasar', '300+ kata umum bahasa Jepang.', NULL, 2, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(4, 2, 'Pola Kalimat Dasar', 'pola-kalimat-dasar', 'Subjek + objek + kata kerja (SOV).', NULL, 0, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(5, 2, 'Partikel wa & ga', 'partikel-wa-ga', 'Penggunaan partikel は dan が.', NULL, 1, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(6, 2, 'Bentuk Lampau', 'bentuk-lampau', 'Menggunakan bentuk lampau ~た.', NULL, 2, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(7, 3, 'Kanji Angka', 'kanji-angka', '一二三四五六七八九十.', NULL, 0, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(8, 3, 'Kanji Waktu', 'kanji-waktu', '日月年時分.', NULL, 1, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_progress`
--

CREATE TABLE `lesson_progress` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `lesson_id` bigint(20) UNSIGNED NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `listening_attempts`
--

CREATE TABLE `listening_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `listening_exercise_id` bigint(20) UNSIGNED NOT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`answers`)),
  `score` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_passed` tinyint(1) NOT NULL DEFAULT 0,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `listening_exercises`
--

CREATE TABLE `listening_exercises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `lesson_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `audio_url` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `listening_exercises`
--

INSERT INTO `listening_exercises` (`id`, `course_id`, `lesson_id`, `title`, `description`, `audio_url`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Listening Hiragana', 'Dengarkan dan tebak huruf.', '/audio/hiragana-1.mp3', 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(2, 2, NULL, 'Listening Grammar N5', 'Dengarkan kalimat sederhana.', '/audio/grammar-n5-1.mp3', 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34');

-- --------------------------------------------------------

--
-- Table structure for table `listening_questions`
--

CREATE TABLE `listening_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `listening_exercise_id` bigint(20) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `correct_answer` varchar(255) NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(4, '2026_05_28_000003_create_personal_access_tokens_table', 1),
(5, '2026_06_26_000001_create_kursus_jepang_tables', 1),
(6, '2026_06_29_000005_add_details_to_packages_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('pending','paid','failed','expired','cancelled') NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `package_id`, `order_number`, `amount`, `status`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'NA-20260629-DRRNN4', 99000.00, 'paid', '2026-06-28 21:09:01', '2026-06-28 21:08:49', '2026-06-28 21:09:01'),
(2, 4, 1, 'NA-20260629-Q54SVY', 99000.00, 'paid', '2026-06-28 21:15:51', '2026-06-28 21:15:31', '2026-06-28 21:15:51'),
(3, 5, 3, 'NA-20260629-YMY6GK', 499000.00, 'paid', '2026-06-28 21:19:58', '2026-06-28 21:19:47', '2026-06-28 21:19:58'),
(4, 6, 3, 'NA-20260629-2Q0IQH', 499000.00, 'paid', '2026-06-28 21:22:47', '2026-06-28 21:22:39', '2026-06-28 21:22:47'),
(5, 7, 1, 'NA-20260629-OLHLN4', 99000.00, 'paid', '2026-06-28 21:31:24', '2026-06-28 21:29:46', '2026-06-28 21:31:24'),
(6, 8, 3, 'NA-20260629-YCJBHM', 499000.00, 'paid', '2026-06-28 21:40:57', '2026-06-28 21:40:49', '2026-06-28 21:40:57'),
(7, 9, 1, 'NA-20260629-BMCJX1', 98000.00, 'paid', '2026-06-28 22:48:09', '2026-06-28 22:47:58', '2026-06-28 22:48:09'),
(8, 10, 5, 'NA-20260630-7Q26C0', 599000.00, 'paid', '2026-06-29 22:56:35', '2026-06-29 19:15:53', '2026-06-29 22:56:35');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` enum('online','onsite') NOT NULL DEFAULT 'online',
  `icon` varchar(255) DEFAULT NULL,
  `badge` varchar(255) DEFAULT NULL,
  `format` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `highlights` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`highlights`)),
  `modules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`modules`)),
  `suitable_for` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`suitable_for`)),
  `price` decimal(12,2) NOT NULL,
  `price_note` varchar(255) DEFAULT NULL,
  `duration_days` int(10) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `slug`, `type`, `icon`, `badge`, `format`, `level`, `description`, `highlights`, `modules`, `suitable_for`, `price`, `price_note`, `duration_days`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Paket Basic Online', 'basic', 'online', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'Online Starter', 'Belajar Mandiri', 'Pemula', 'Belajar melalui video pembelajaran berkualitas tinggi dengan akses selamanya. Cocok untuk yang mandiri.', '[{\"label\":\"Materi\",\"value\":\"Huruf & kosakata\"},{\"label\":\"Akses\",\"value\":\"Web learning\"},{\"label\":\"Latihan\",\"value\":\"500+ soal\"}]', '[{\"name\":\"Pengenalan Hiragana\",\"description\":\"Belajar 46 huruf dasar dengan audio native speaker\"},{\"name\":\"Pengenalan Katakana\",\"description\":\"Pelajari 46 huruf katakana dengan contoh penggunaan\"},{\"name\":\"Kosakata Dasar\",\"description\":\"Kumpulan 300+ kata umum dalam bahasa Jepang\"}]', '[\"Siswa yang baru mulai dari nol dan ingin mengenal huruf Jepang.\",\"Pembelajar mandiri yang butuh materi ringkas dan bisa diulang kapan saja.\",\"Peserta yang ingin mencoba kursus sebelum masuk ke program mentor.\",\"Orang sibuk yang membutuhkan jadwal belajar fleksibel.\"]', 99000.00, 'Sekali bayar untuk akses 1 bulan', 30, 1, '2026-06-28 21:04:33', '2026-06-28 21:04:33'),
(2, 'Paket Premium Online', 'premium', 'online', 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z', 'Online Mentoring', 'Zoom + Materi Web', 'N5 Dasar', 'Program intensif dengan mentor melalui Zoom. Cocok untuk yang ingin belajar secara langsung.', '[{\"label\":\"Mentoring\",\"value\":\"Live Zoom\"},{\"label\":\"Target\",\"value\":\"JLPT N5\"},{\"label\":\"Akses\",\"value\":\"3 bulan\"}]', '[{\"name\":\"Grammar Dasar\",\"description\":\"Pola kalimat dasar hingga lanjutan JLPT N5\"},{\"name\":\"Kanji Pilihan\",\"description\":\"200 kanji dasar yang sering digunakan\"},{\"name\":\"Simulasi Ujian\",\"description\":\"Tryout rutin untuk persiapan JLPT N5\"}]', '[\"Peserta yang ingin belajar dengan arahan mentor, bukan hanya video.\",\"Siswa yang menargetkan kemampuan setara JLPT N5.\",\"Pembelajar yang membutuhkan jadwal dan progres yang lebih terstruktur.\",\"Orang yang ingin rutin bertanya langsung saat menemukan materi sulit.\"]', 199000.00, 'Termasuk sesi live mingguan', 90, 1, '2026-06-28 21:04:34', '2026-06-29 19:39:21'),
(3, 'Paket Private Online', 'private', 'online', 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm14 10v-2a4 4 0 0 0-3-3.87m-4-12a4 4 0 0 1 0 7.75', '1-on-1 Online', 'Kelas Private', 'Pemula - N5', 'Kelas online personal dengan tutor untuk menyesuaikan tempo, target, dan fokus belajar peserta.', '[{\"label\":\"Kelas\",\"value\":\"Private Zoom\"},{\"label\":\"Jadwal\",\"value\":\"Fleksibel\"},{\"label\":\"Review\",\"value\":\"Personal\"}]', '[{\"name\":\"Konsultasi Pribadi\",\"description\":\"Sesi belajar individu dengan tutor berpengalaman\"},{\"name\":\"Review Materi\",\"description\":\"Evaluasi dan perbaikan pada materi yang sulit\"},{\"name\":\"Persiapan Ujian\",\"description\":\"Strategi khusus untuk menghadapi JLPT\"}]', '[\"Peserta yang ingin perhatian penuh dari tutor.\",\"Pembelajar dengan target khusus seperti ujian, kerja, atau wawancara.\",\"Siswa yang membutuhkan jadwal belajar lebih fleksibel.\",\"Orang yang ingin progresnya dievaluasi secara personal.\"]', 499000.00, 'Program personal dengan jadwal fleksibel', 180, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(4, 'Paket Basic On-Site', 'reguler', 'onsite', 'M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5', 'Tatap Muka', 'Kelas Kelompok', 'Pemula', 'Belajar interaktif langsung tatap muka di ruang kelas bersama teman kelompok.', '[{\"label\":\"Pertemuan\",\"value\":\"2x\\/minggu\"},{\"label\":\"Modul\",\"value\":\"Cetak\"},{\"label\":\"Evaluasi\",\"value\":\"Bulanan\"}]', '[{\"name\":\"Kelas Tatap Muka\",\"description\":\"Belajar langsung di tempat dengan metode interaktif\"},{\"name\":\"Praktik Langsung\",\"description\":\"Pelajaran praktik dengan tutor profesional\"},{\"name\":\"Evaluasi Rutin\",\"description\":\"Penilaian mingguan untuk melacak kemajuan\"}]', '[\"Siswa yang lebih nyaman belajar langsung di kelas.\",\"Peserta yang suka diskusi bersama teman kelompok.\",\"Pembelajar yang membutuhkan rutinitas pertemuan tetap.\",\"Orang yang ingin latihan pelafalan secara langsung.\"]', 350000.00, 'Termasuk modul cetak dan evaluasi', 30, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(5, 'Paket Premium On-Site', 'intensive', 'onsite', 'M13 10V3L4 14h7v7l9-11h-7z', 'Kelas Intensif', 'Tatap Muka Harian', 'N5 Terarah', 'Akselerasi materi terstruktur padat dengan bimbingan harian yang dipandu penuh.', '[{\"label\":\"Pertemuan\",\"value\":\"Setiap hari\"},{\"label\":\"Fasilitas\",\"value\":\"Ruang belajar\"},{\"label\":\"Tryout\",\"value\":\"Offline\"}]', '[{\"name\":\"Belajar Harian\",\"description\":\"Pertemuan rutin 5x seminggu\"},{\"name\":\"Simulasi Ujian\",\"description\":\"Tryout intensif selama program\"},{\"name\":\"Ruang Belajar\",\"description\":\"Fasilitas belajar gratis selama program\"}]', '[\"Peserta yang ingin mengejar target dalam waktu singkat.\",\"Siswa yang siap belajar dengan ritme intensif.\",\"Pembelajar yang membutuhkan lingkungan belajar fokus.\",\"Orang yang ingin persiapan lebih serius untuk ujian dasar.\"]', 599000.00, 'Untuk progres cepat dan jadwal padat', 30, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(6, 'Paket Private Corporate', 'corporate', 'onsite', 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'Corporate Training', 'Program Custom', 'Sesuai kebutuhan', 'Pelatihan kurikulum khusus bisnis skala korporat yang jadwalnya fleksibel.', '[{\"label\":\"Kurikulum\",\"value\":\"Custom\"},{\"label\":\"Lokasi\",\"value\":\"Fleksibel\"},{\"label\":\"Sertifikat\",\"value\":\"Resmi\"}]', '[{\"name\":\"Kurikulum Custom\",\"description\":\"Materi disesuaikan dengan kebutuhan korporat\"},{\"name\":\"Lokasi Fleksibel\",\"description\":\"Bisa di kantor atau kami datang ke lokasi\"},{\"name\":\"Sertifikat Resmi\",\"description\":\"Sertifikat pelatihan untuk peserta\"}]', '[\"Perusahaan yang membutuhkan pelatihan bahasa Jepang untuk tim.\",\"Universitas atau komunitas yang ingin program belajar khusus.\",\"Tim HR yang membutuhkan jadwal dan materi fleksibel.\",\"Organisasi yang ingin pelatihan dengan laporan progres peserta.\"]', 0.00, 'Harga menyesuaikan jumlah peserta dan kebutuhan', 0, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34');

-- --------------------------------------------------------

--
-- Table structure for table `package_features`
--

CREATE TABLE `package_features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `package_features`
--

INSERT INTO `package_features` (`id`, `package_id`, `name`, `description`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Akses video Hiragana & Katakana lengkap', NULL, 0, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(2, 1, 'Materi PDF interaktif untuk latihan', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(3, 1, 'Bank soal latihan 500+ pertanyaan', NULL, 2, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(4, 1, 'Akses materi selama 1 bulan', NULL, 3, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(5, 1, 'Grup komunitas belajar via Discord', NULL, 4, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(6, 2, 'Semua fitur Online Course', NULL, 0, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(7, 2, 'Kanji dasar 200+ karakter', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(8, 2, 'Grammar JLPT N5 lengkap', NULL, 2, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(9, 2, 'Latihan soal JLPT N5 siap ujian', NULL, 3, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(10, 2, 'Akses materi selama 3 bulan', NULL, 4, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(11, 2, 'Sesi tanya jawab mingguan via Zoom', NULL, 5, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(12, 3, 'Semua fitur Bootcamp', NULL, 0, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(13, 3, 'Kelas private via Zoom', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(14, 3, 'Konsultasi belajar 1-on-1', NULL, 2, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(15, 3, 'Akses materi selama 6 bulan', NULL, 3, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(16, 3, 'Jadwal fleksibel sesuai keinginan', NULL, 4, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(17, 4, 'Kelas tatap muka (seminggu 2x)', NULL, 0, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(18, 4, 'Modul cetak dan merchandise', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(19, 4, 'Grup diskusi offline', NULL, 2, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(20, 4, 'Evaluasi bulanan', NULL, 3, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(21, 5, 'Semua fitur Reguler On-Site', NULL, 0, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(22, 5, 'Kelas tatap muka setiap hari', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(23, 5, 'Free-flow coffee & ruang belajar', NULL, 2, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(24, 5, 'Simulasi JLPT offline', NULL, 3, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(25, 5, 'Prioritas jadwal', NULL, 4, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(26, 6, 'Jadwal fleksibel', NULL, 0, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(27, 6, 'Lokasi bisa disesuaikan', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(28, 6, 'Kurikulum khusus bisnis', NULL, 2, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(29, 6, 'Sertifikat resmi pelatihan', NULL, 3, '2026-06-28 21:04:34', '2026-06-28 21:04:34');

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `provider` varchar(255) NOT NULL DEFAULT 'midtrans',
  `snap_token` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `status` enum('pending','settlement','capture','deny','cancel','expire','failure') NOT NULL DEFAULT 'pending',
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `provider`, `snap_token`, `transaction_id`, `payment_type`, `status`, `payload`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'midtrans', '9a30126f-f2dd-4da0-980a-957f6fb72dd1', 'A120260629042743p6SyHFDaxYID', 'dana', 'settlement', '{\"token\":\"9a30126f-f2dd-4da0-980a-957f6fb72dd1\",\"redirect_url\":\"https:\\/\\/app.sandbox.midtrans.com\\/snap\\/v4\\/redirection\\/9a30126f-f2dd-4da0-980a-957f6fb72dd1\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"transaction_id\":\"A120260629042743p6SyHFDaxYID\",\"payment_type\":\"dana\"}', '2026-06-28 21:09:01', '2026-06-28 21:08:50', '2026-06-28 21:09:01'),
(2, 2, 'midtrans', '575c2f82-0bc3-46b8-b9e8-65d9136b809a', 'A120260629043431WcYHDGhgQYID', 'dana', 'settlement', '{\"token\":\"575c2f82-0bc3-46b8-b9e8-65d9136b809a\",\"redirect_url\":\"https:\\/\\/app.sandbox.midtrans.com\\/snap\\/v4\\/redirection\\/575c2f82-0bc3-46b8-b9e8-65d9136b809a\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"transaction_id\":\"A120260629043431WcYHDGhgQYID\",\"payment_type\":\"dana\"}', '2026-06-28 21:15:51', '2026-06-28 21:15:31', '2026-06-28 21:15:51'),
(3, 3, 'midtrans', 'cae2d528-2fee-4197-a485-be8cd492c06a', 'A120260629043838KX9EWWtOXUID', 'dana', 'settlement', '{\"token\":\"cae2d528-2fee-4197-a485-be8cd492c06a\",\"redirect_url\":\"https:\\/\\/app.sandbox.midtrans.com\\/snap\\/v4\\/redirection\\/cae2d528-2fee-4197-a485-be8cd492c06a\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"transaction_id\":\"A120260629043838KX9EWWtOXUID\",\"payment_type\":\"dana\"}', '2026-06-28 21:19:58', '2026-06-28 21:19:48', '2026-06-28 21:19:58'),
(4, 4, 'midtrans', 'a0429b99-a1cf-4ea2-b5e5-5c3405afe8a8', 'A120260629044129n8gEzdfHBeID', 'dana', 'settlement', '{\"token\":\"a0429b99-a1cf-4ea2-b5e5-5c3405afe8a8\",\"redirect_url\":\"https:\\/\\/app.sandbox.midtrans.com\\/snap\\/v4\\/redirection\\/a0429b99-a1cf-4ea2-b5e5-5c3405afe8a8\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"transaction_id\":\"A120260629044129n8gEzdfHBeID\",\"payment_type\":\"dana\"}', '2026-06-28 21:22:47', '2026-06-28 21:22:39', '2026-06-28 21:22:47'),
(5, 5, 'midtrans', 'aab338d3-07ad-44d9-b677-fe1a95929534', 'SIM-TXN-003', 'credit_card', 'settlement', '{\"token\":\"aab338d3-07ad-44d9-b677-fe1a95929534\",\"redirect_url\":\"https:\\/\\/app.sandbox.midtrans.com\\/snap\\/v4\\/redirection\\/aab338d3-07ad-44d9-b677-fe1a95929534\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"transaction_id\":\"SIM-TXN-003\",\"payment_type\":\"credit_card\"}', '2026-06-28 21:31:24', '2026-06-28 21:29:46', '2026-06-28 21:31:24'),
(6, 6, 'midtrans', '0f8e2204-c44c-4aeb-a9b2-f94ba3f38d07', 'A120260629045939bqyEgMe0w7ID', 'dana', 'settlement', '{\"token\":\"0f8e2204-c44c-4aeb-a9b2-f94ba3f38d07\",\"redirect_url\":\"https:\\/\\/app.sandbox.midtrans.com\\/snap\\/v4\\/redirection\\/0f8e2204-c44c-4aeb-a9b2-f94ba3f38d07\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"transaction_id\":\"A120260629045939bqyEgMe0w7ID\",\"payment_type\":\"dana\"}', '2026-06-28 21:40:57', '2026-06-28 21:40:49', '2026-06-28 21:40:57'),
(7, 7, 'midtrans', '161ad4e2-0e28-4991-8dcb-9b7fc994e643', 'A120260629060652vpPt3YyEyhID', 'dana', 'settlement', '{\"token\":\"161ad4e2-0e28-4991-8dcb-9b7fc994e643\",\"redirect_url\":\"https:\\/\\/app.sandbox.midtrans.com\\/snap\\/v4\\/redirection\\/161ad4e2-0e28-4991-8dcb-9b7fc994e643\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"transaction_id\":\"A120260629060652vpPt3YyEyhID\",\"payment_type\":\"dana\"}', '2026-06-28 22:48:09', '2026-06-28 22:47:58', '2026-06-28 22:48:09'),
(8, 8, 'midtrans', 'd5b716b8-c72d-4893-a4e8-a735c3609c47', 'A120260630061515JVY1nU8CMPID', 'dana', 'settlement', '{\"token\":\"d5b716b8-c72d-4893-a4e8-a735c3609c47\",\"redirect_url\":\"https:\\/\\/app.sandbox.midtrans.com\\/snap\\/v4\\/redirection\\/d5b716b8-c72d-4893-a4e8-a735c3609c47\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"transaction_id\":\"A120260630061515JVY1nU8CMPID\",\"payment_type\":\"dana\"}', '2026-06-29 22:56:35', '2026-06-29 19:15:57', '2026-06-29 22:56:35');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
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
(1, 'App\\Models\\User', 1, 'api-token', '2a4c39afdb2e214c0e77c27e9212934930bcfd6e70f1a626b4eb11bac1e86531', '[\"*\"]', NULL, NULL, '2026-06-29 00:16:36', '2026-06-29 00:16:36'),
(2, 'App\\Models\\User', 1, 'api-token', '652a2a3c24f7f1a0e7e4cf190b964907d8c4c00dca1f16c585d00d840521555c', '[\"*\"]', NULL, NULL, '2026-06-29 00:17:29', '2026-06-29 00:17:29'),
(3, 'App\\Models\\User', 2, 'api-token', '6e252eac20b42b76e8dbdd0287cb8cf2d274f39c9a6175539f9ee18864f744fc', '[\"*\"]', NULL, NULL, '2026-06-29 00:21:45', '2026-06-29 00:21:45'),
(4, 'App\\Models\\User', 9, 'api-token', '571d56b645c68d5832f404b61c5e8b2b2d55f3c5e259946db3477100fc593a11', '[\"*\"]', NULL, NULL, '2026-06-29 00:23:15', '2026-06-29 00:23:15'),
(5, 'App\\Models\\User', 1, 'api-token', '4da3a67160ce9f8465ef7596965c844335481ab2992162035ff88fb9c460a7c9', '[\"*\"]', NULL, NULL, '2026-06-29 00:24:38', '2026-06-29 00:24:38'),
(6, 'App\\Models\\User', 1, 'api-token', '2f7cad36bb7857433b7a61cd6dcd9629fb757b85b173e0c486ba516c8f2a0856', '[\"*\"]', NULL, NULL, '2026-06-29 00:24:52', '2026-06-29 00:24:52'),
(7, 'App\\Models\\User', 1, 'api-token', '75a14f202ed1eb4bda3e455cfb1b8ee957fa73ec81f029e6381a872951c0da53', '[\"*\"]', '2026-06-29 00:45:14', NULL, '2026-06-29 00:45:13', '2026-06-29 00:45:14'),
(8, 'App\\Models\\User', 1, 'api-token', '31e95bf11e3cdb405d5449d645b26aaf8c6597fa7058954f4fb2a8db00a4bfce', '[\"*\"]', '2026-06-29 01:33:47', NULL, '2026-06-29 00:51:23', '2026-06-29 01:33:47'),
(9, 'App\\Models\\User', 1, 'api-token', '52bc9a6e81ca7f76e29595418d9328685672009ee9188043ef38d9565a8f5d79', '[\"*\"]', '2026-06-29 18:10:46', NULL, '2026-06-29 18:10:44', '2026-06-29 18:10:46'),
(10, 'App\\Models\\User', 2, 'api-token', 'd158da2f44d40818007fde25f872ae1b2d6f5e430af8055c271781c82c422e5c', '[\"*\"]', '2026-06-29 18:49:01', NULL, '2026-06-29 18:25:17', '2026-06-29 18:49:01'),
(11, 'App\\Models\\User', 1, 'api-token', '20abf1f79a531e96bbbd414b08d6764266a601b3a19f12dad1bf2ea7d5bfcc31', '[\"*\"]', NULL, NULL, '2026-06-29 18:49:51', '2026-06-29 18:49:51'),
(12, 'App\\Models\\User', 1, 'api-token', '81b59991944af1ac7e549abe443d4372bd1b26e53163b43177c56726f209cae8', '[\"*\"]', '2026-06-29 21:28:34', NULL, '2026-06-29 18:50:29', '2026-06-29 21:28:34'),
(13, 'App\\Models\\User', 1, 'admin-test', '25459bf179b982eda5cb91d47e9d7d788a627bbb9db23d279bed5ef50f706137', '[\"*\"]', '2026-06-29 20:48:43', NULL, '2026-06-29 20:48:13', '2026-06-29 20:48:43'),
(14, 'App\\Models\\User', 9, 'api-token', '4ba621bb5ffffe5ea1a004493850c7914fe8b683a5770c6f4c6afcb80b641576', '[\"*\"]', '2026-06-29 21:33:58', NULL, '2026-06-29 21:30:34', '2026-06-29 21:33:58'),
(15, 'App\\Models\\User', 1, 'api-token', '3950ccd05f767470ac10a26a46ce271866c76cf75a11b9d78253ccf314510a40', '[\"*\"]', '2026-06-29 23:00:47', NULL, '2026-06-29 22:57:39', '2026-06-29 23:00:47');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `lesson_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `passing_score` int(10) UNSIGNED NOT NULL DEFAULT 70,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `course_id`, `lesson_id`, `title`, `description`, `passing_score`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Kuis Hiragana', 'Uji kemampuan membaca huruf hiragana.', 70, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(2, 2, NULL, 'Kuis Grammar N5', 'Uji pemahaman grammar JLPT N5.', 70, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(3, 3, NULL, 'Kuis Kanji', 'Tebak bacaan kanji.', 70, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`answers`)),
  `score` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_passed` tinyint(1) NOT NULL DEFAULT 0,
  `started_at` timestamp NULL DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`options`)),
  `correct_answer` varchar(255) NOT NULL,
  `explanation` text DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question`, `options`, `correct_answer`, `explanation`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Huruf hiragana untuk \"a\" adalah?', '[\"\\u3042\",\"\\u3044\",\"\\u3046\",\"\\u3048\"]', 'あ', NULL, 0, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(2, 1, 'Huruf hiragana untuk \"ka\" adalah?', '[\"\\u304b\",\"\\u304d\",\"\\u304f\",\"\\u3051\"]', 'か', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(3, 1, 'Huruf hiragana untuk \"sa\" adalah?', '[\"\\u3055\",\"\\u3057\",\"\\u3059\",\"\\u305b\"]', 'さ', NULL, 2, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(4, 2, 'Partikel untuk topik kalimat adalah?', '[\"\\u306f\",\"\\u304c\",\"\\u3092\",\"\\u306b\"]', 'は', NULL, 0, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(5, 2, 'Bentuk lampau dari 食べる adalah?', '[\"\\u98df\\u3079\\u305f\",\"\\u98df\\u3079\\u307e\\u3059\",\"\\u98df\\u3079\\u306a\\u3044\",\"\\u98df\\u3079\\u308b\"]', '食べた', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(6, 3, 'Kanji untuk angka \"satu\" adalah?', '[\"\\u4e00\",\"\\u4e8c\",\"\\u4e09\",\"\\u56db\"]', '一', NULL, 0, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(7, 3, 'Kanji untuk \"hari\" adalah?', '[\"\\u65e5\",\"\\u6708\",\"\\u5e74\",\"\\u6642\"]', '日', NULL, 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `status` enum('pending','active','cancelled') NOT NULL DEFAULT 'pending',
  `account_created_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `full_name`, `email`, `phone`, `status`, `account_created_at`, `created_at`, `updated_at`) VALUES
(1, 'keyza zaki', 'keyzazaki054@gmail.com', '089524398977', 'active', '2026-06-28 21:09:01', '2026-06-28 21:08:49', '2026-06-28 21:09:01'),
(2, 'muhammad boengg', 'boeng283@gmail.com', '08211278664', 'active', '2026-06-28 21:15:51', '2026-06-28 21:15:31', '2026-06-28 21:15:51'),
(3, 'afdhal', 'm.afdhalrasidt01@gmail.com', '083841454673', 'active', '2026-06-28 21:19:58', '2026-06-28 21:19:47', '2026-06-28 21:19:58'),
(4, 'zaki', 'thespikekeyza@gmail.com', '089524398977', 'active', '2026-06-28 21:22:47', '2026-06-28 21:22:39', '2026-06-28 21:22:47'),
(5, 'Wa Test', 'watest.na@gmail.com', '081234567890', 'active', '2026-06-28 21:31:24', '2026-06-28 21:29:46', '2026-06-28 21:31:24'),
(6, 'afdhal', 'gksjdksjd@gmail.com', '089524398977', 'active', '2026-06-28 21:40:57', '2026-06-28 21:40:49', '2026-06-28 21:40:57'),
(7, 'keyza', 'ksjdksjd@gmail.com', '089524398977', 'active', '2026-06-28 22:48:09', '2026-06-28 22:47:57', '2026-06-28 22:48:09'),
(8, 'tttt', 'ff@gmail.com', '082119278664', 'active', '2026-06-29 22:56:35', '2026-06-29 19:15:53', '2026-06-29 22:56:35');

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
('d5iUh4yZ8f2AMiJTrhLIA1nt1jqTWrxA6AZehnkR', NULL, '127.0.0.1', 'WhatsApp/2.2623.103 W', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid0dtWVVRSDNkeUJmRkdLeDNjMFhZQjIwTjRydDFpdHhpYjhuSk1POSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9zY2hlZHVsZS10d2lybC1hZmZpcm0ubmdyb2stZnJlZS5kZXYiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1782788207),
('HP1Rx8Q5EiaLa1Ytv9rUFbrz5FKp9kJlbFC51Ez8', NULL, '127.0.0.1', 'WhatsApp/2.2623.103 W', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic2t1anp5dmw5MVhoMHBQcFFOMFdyQlZlMWVrRlJHbFFWUmxOTjFQcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9zY2hlZHVsZS10d2lybC1hZmZpcm0ubmdyb2stZnJlZS5kZXYiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1782788178),
('kQZQGcNCKD16WHX12sTsWo1p6CgWoTMKrtCzFveG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRlRrQjdOMjdQbnIzQnpWaW5iNjBLZXVBUnBPd0VWRG5KS0lIcVlvZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9zY2hlZHVsZS10d2lybC1hZmZpcm0ubmdyb2stZnJlZS5kZXYiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1782712763),
('mDFTwb3E2NiZ6NzYP6ZNXCxRjgp3NavlBHxjNES8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWEY2aklyOG1GTzdOSGJZZHZZT1pxNGF6TkUzU282QkNFVktzUG9HbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9zY2hlZHVsZS10d2lybC1hZmZpcm0ubmdyb2stZnJlZS5kZXYiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1782788221),
('oEINJiVcLpsdJOAMQPDZz2CvMLJZmYfqZNawwlp5', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibHlqSHFubnB0bnhmU1lESTJzQ2hLV3hWeUx2ZHB0ME9iUW53Z3c2dCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9zY2hlZHVsZS10d2lybC1hZmZpcm0ubmdyb2stZnJlZS5kZXYiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1782788400),
('QDM0MS2Vk4DInZWwh1POjCWuCmfu97NGy1TnIKp0', NULL, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTFJSaEFNeDlITmJEMlRsSlhDWlFPR005WFowT24yalY4dUd5aVZDYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9zY2hlZHVsZS10d2lybC1hZmZpcm0ubmdyb2stZnJlZS5kZXYiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1782790956),
('T16n7vP1hbpFUyKOmldvH0rqjoa0trTL8rKGQEGR', NULL, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ1VqTnhUOEl1RVdQQkg3aDhFcUd0Sm16bXcyR1h0TERGaWt1OExUMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9zY2hlZHVsZS10d2lybC1hZmZpcm0ubmdyb2stZnJlZS5kZXYiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1782790957);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `registration_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','teacher','client') NOT NULL DEFAULT 'client',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `registration_id`, `name`, `email`, `whatsapp`, `username`, `password`, `role`, `is_active`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Admin NihonAccess', 'admin@nihonaccess.test', NULL, 'admin', '$2y$12$qvvxSNuTuhCACTXF2Um9jeAPDtkffBI8CRp6HPG6Ai5Ch9aDOx/Vm', 'admin', 1, '2026-06-28 21:04:33', NULL, '2026-06-28 21:04:33', '2026-06-28 21:04:33'),
(2, NULL, 'Sensei Tanaka', 'sensei@nihonaccess.test', NULL, 'sensei', '$2y$12$3t2Xfk4d4oECKSUIBuWy7.EUKCSpTnRXzsAlG6rgF8xF.be56eSg2', 'teacher', 1, '2026-06-28 21:04:33', NULL, '2026-06-28 21:04:33', '2026-06-29 19:39:31'),
(3, 1, 'keyza zaki', 'dfdfdf@gmail.com', '32434', 'nihon_n8vip', '$2y$12$wg2i7wFrTZgceLrwMBvWkeML54NwYYXqbKUcfGrCjw1QifvZwwMHm', 'client', 1, '2026-06-28 21:09:01', NULL, '2026-06-28 21:08:49', '2026-06-28 21:09:01'),
(4, 2, 'muhammad boengg', 'boeng283@gmail.com', '08211278664', 'nihon_m7zxb', '$2y$12$VxNuqFKrnKN1MDqgkl6qeuQH.KDcWc/U86zqBbQuqWnSF6Nv/vZcW', 'client', 1, '2026-06-28 21:15:50', NULL, '2026-06-28 21:15:31', '2026-06-28 21:15:51'),
(5, 3, 'afdhal', 'm.afdhalrasidt01@gmail.com', '083841454673', 'nihon_xmmj0', '$2y$12$a.hWOYaZLeiQVKHSr5RsbeSaHVy70Il8Z5hfw69yqq2a4fJcYPy2y', 'client', 1, '2026-06-28 21:19:58', NULL, '2026-06-28 21:19:47', '2026-06-28 21:19:58'),
(6, 4, 'zaki', 'thespikekeyza@gmail.com', '089524398977', 'nihon_k0qij', '$2y$12$G42WDkJ5KS7.gzRE81UVF.qP4R3w3.qvCsE35Lpu6S2yXJoWlea/O', 'client', 1, '2026-06-28 21:22:47', NULL, '2026-06-28 21:22:39', '2026-06-28 21:22:47'),
(7, 5, 'Wa Test', 'watest.na@gmail.com', '081234567890', 'nihon_5crbp', '$2y$12$rwgvTO2BOALFhCZ4GgvpFukCcLo2R8Byei.MfxmvIrGmieJpNrtUS', 'client', 1, '2026-06-28 21:31:23', NULL, '2026-06-28 21:29:46', '2026-06-28 21:31:24'),
(8, 6, 'afdhal', 'gksjdksjd@gmail.com', '089524398977', 'nihon_atqx7', '$2y$12$d4YhsYrzy9kwK47yOFGFF..BUe4UJA2rnABGRrjNzbjAUusKUxP4K', 'client', 1, '2026-06-28 21:40:57', NULL, '2026-06-28 21:40:49', '2026-06-28 21:40:57'),
(9, 7, 'keyza', 'ksjdksjd@gmail.com', '089524398977', 'nihon_vizg7', '$2y$12$01k8875OcQJmQ/wxKap3xejHx8Wxp9diDth7r4IxQEEmxNrDrNFIK', 'client', 1, '2026-06-28 22:48:09', NULL, '2026-06-28 22:47:58', '2026-06-28 22:48:09'),
(10, 8, 'tttt', 'ff@gmail.com', '082119278664', 'nihon_r9icp', '$2y$12$YbRJyy6k/0y0huvPNyyQYuqmrh/PGbRizDm4Uu6kkCNC.gyk/QA4K', 'client', 1, '2026-06-29 22:56:35', NULL, '2026-06-29 19:15:53', '2026-06-29 22:56:35');

-- --------------------------------------------------------

--
-- Table structure for table `writing_attempts`
--

CREATE TABLE `writing_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `writing_exercise_id` bigint(20) UNSIGNED NOT NULL,
  `submission_image` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `score` int(10) UNSIGNED DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `writing_exercises`
--

CREATE TABLE `writing_exercises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `lesson_id` bigint(20) UNSIGNED DEFAULT NULL,
  `character_type` enum('hiragana','katakana','kanji') NOT NULL,
  `character` varchar(255) NOT NULL,
  `stroke_order_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `writing_exercises`
--

INSERT INTO `writing_exercises` (`id`, `course_id`, `lesson_id`, `character_type`, `character`, `stroke_order_image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'hiragana', 'あ', '/img/stroke/hiragana-a.png', 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(2, 1, NULL, 'hiragana', 'い', '/img/stroke/hiragana-i.png', 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(3, 2, NULL, 'kanji', '日', '/img/stroke/kanji-hi.png', 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34'),
(4, 3, NULL, 'kanji', '一', '/img/stroke/kanji-ichi.png', 1, '2026-06-28 21:04:34', '2026-06-28 21:04:34');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `character_attempts`
--
ALTER TABLE `character_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `character_attempts_user_id_foreign` (`user_id`),
  ADD KEY `character_attempts_character_exercise_id_foreign` (`character_exercise_id`);

--
-- Indexes for table `character_exercises`
--
ALTER TABLE `character_exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `character_exercises_course_id_foreign` (`course_id`),
  ADD KEY `character_exercises_lesson_id_foreign` (`lesson_id`),
  ADD KEY `character_exercises_is_active_index` (`is_active`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_slug_unique` (`slug`),
  ADD KEY `courses_teacher_id_foreign` (`teacher_id`),
  ADD KEY `courses_is_active_index` (`is_active`);

--
-- Indexes for table `course_package`
--
ALTER TABLE `course_package`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_package_course_id_package_id_unique` (`course_id`,`package_id`),
  ADD KEY `course_package_package_id_foreign` (`package_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollments_user_id_foreign` (`user_id`),
  ADD KEY `enrollments_order_id_foreign` (`order_id`),
  ADD KEY `enrollments_package_id_foreign` (`package_id`),
  ADD KEY `enrollments_end_date_index` (`end_date`),
  ADD KEY `enrollments_status_index` (`status`);

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
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lessons_course_id_slug_unique` (`course_id`,`slug`),
  ADD KEY `lessons_is_active_index` (`is_active`);

--
-- Indexes for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lesson_progress_user_id_lesson_id_unique` (`user_id`,`lesson_id`),
  ADD KEY `lesson_progress_lesson_id_foreign` (`lesson_id`);

--
-- Indexes for table `listening_attempts`
--
ALTER TABLE `listening_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `listening_attempts_user_id_foreign` (`user_id`),
  ADD KEY `listening_attempts_listening_exercise_id_foreign` (`listening_exercise_id`);

--
-- Indexes for table `listening_exercises`
--
ALTER TABLE `listening_exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `listening_exercises_course_id_foreign` (`course_id`),
  ADD KEY `listening_exercises_lesson_id_foreign` (`lesson_id`),
  ADD KEY `listening_exercises_is_active_index` (`is_active`);

--
-- Indexes for table `listening_questions`
--
ALTER TABLE `listening_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `listening_questions_listening_exercise_id_foreign` (`listening_exercise_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_package_id_foreign` (`package_id`),
  ADD KEY `orders_status_index` (`status`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `packages_slug_unique` (`slug`);

--
-- Indexes for table `package_features`
--
ALTER TABLE `package_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_features_package_id_foreign` (`package_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`),
  ADD KEY `payments_transaction_id_index` (`transaction_id`),
  ADD KEY `payments_status_index` (`status`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizzes_course_id_foreign` (`course_id`),
  ADD KEY `quizzes_lesson_id_foreign` (`lesson_id`),
  ADD KEY `quizzes_is_active_index` (`is_active`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_attempts_user_id_foreign` (`user_id`),
  ADD KEY `quiz_attempts_quiz_id_foreign` (`quiz_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_questions_quiz_id_foreign` (`quiz_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `registrations_email_index` (`email`),
  ADD KEY `registrations_status_index` (`status`);

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
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_registration_id_foreign` (`registration_id`);

--
-- Indexes for table `writing_attempts`
--
ALTER TABLE `writing_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `writing_attempts_user_id_foreign` (`user_id`),
  ADD KEY `writing_attempts_writing_exercise_id_foreign` (`writing_exercise_id`);

--
-- Indexes for table `writing_exercises`
--
ALTER TABLE `writing_exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `writing_exercises_course_id_foreign` (`course_id`),
  ADD KEY `writing_exercises_lesson_id_foreign` (`lesson_id`),
  ADD KEY `writing_exercises_is_active_index` (`is_active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `character_attempts`
--
ALTER TABLE `character_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `character_exercises`
--
ALTER TABLE `character_exercises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `course_package`
--
ALTER TABLE `course_package`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
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
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `listening_attempts`
--
ALTER TABLE `listening_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `listening_exercises`
--
ALTER TABLE `listening_exercises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `listening_questions`
--
ALTER TABLE `listening_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `package_features`
--
ALTER TABLE `package_features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `writing_attempts`
--
ALTER TABLE `writing_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `writing_exercises`
--
ALTER TABLE `writing_exercises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `character_attempts`
--
ALTER TABLE `character_attempts`
  ADD CONSTRAINT `character_attempts_character_exercise_id_foreign` FOREIGN KEY (`character_exercise_id`) REFERENCES `character_exercises` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `character_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `character_exercises`
--
ALTER TABLE `character_exercises`
  ADD CONSTRAINT `character_exercises_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `character_exercises_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `course_package`
--
ALTER TABLE `course_package`
  ADD CONSTRAINT `course_package_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_package_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`),
  ADD CONSTRAINT `enrollments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD CONSTRAINT `lesson_progress_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lesson_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `listening_attempts`
--
ALTER TABLE `listening_attempts`
  ADD CONSTRAINT `listening_attempts_listening_exercise_id_foreign` FOREIGN KEY (`listening_exercise_id`) REFERENCES `listening_exercises` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `listening_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `listening_exercises`
--
ALTER TABLE `listening_exercises`
  ADD CONSTRAINT `listening_exercises_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `listening_exercises_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `listening_questions`
--
ALTER TABLE `listening_questions`
  ADD CONSTRAINT `listening_questions_listening_exercise_id_foreign` FOREIGN KEY (`listening_exercise_id`) REFERENCES `listening_exercises` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`),
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `package_features`
--
ALTER TABLE `package_features`
  ADD CONSTRAINT `package_features_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quizzes_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `quiz_attempts_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_registration_id_foreign` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `writing_attempts`
--
ALTER TABLE `writing_attempts`
  ADD CONSTRAINT `writing_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `writing_attempts_writing_exercise_id_foreign` FOREIGN KEY (`writing_exercise_id`) REFERENCES `writing_exercises` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `writing_exercises`
--
ALTER TABLE `writing_exercises`
  ADD CONSTRAINT `writing_exercises_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `writing_exercises_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
