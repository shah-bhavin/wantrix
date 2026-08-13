-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 13, 2026 at 07:33 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wantrix`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `group_id` bigint UNSIGNED NOT NULL,
  `template_id` bigint UNSIGNED NOT NULL,
  `message_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'template',
  `media_library_id` bigint UNSIGNED DEFAULT NULL,
  `whatsapp_account_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','scheduled','processing','completed','paused','cancelled','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `messages_generated_at` timestamp NULL DEFAULT NULL,
  `message_delay_seconds` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `vendor_id`, `group_id`, `template_id`, `message_type`, `media_library_id`, `whatsapp_account_id`, `name`, `status`, `scheduled_at`, `started_at`, `completed_at`, `cancelled_at`, `messages_generated_at`, `message_delay_seconds`, `created_at`, `updated_at`) VALUES
(11, 1, 7, 3, 'template', NULL, NULL, 'Test', 'processing', NULL, NULL, NULL, NULL, NULL, 0, '2026-07-16 02:30:28', '2026-07-20 11:39:56'),
(12, 1, 7, 3, 'template', NULL, NULL, 'Test 2', 'completed', NULL, '2026-07-28 05:44:39', '2026-07-28 05:44:42', NULL, '2026-07-28 05:44:29', 0, '2026-07-27 09:24:39', '2026-07-28 05:44:42');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_activities`
--

CREATE TABLE `campaign_activities` (
  `id` bigint UNSIGNED NOT NULL,
  `campaign_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `campaign_activities`
--

INSERT INTO `campaign_activities` (`id`, `campaign_id`, `type`, `description`, `created_at`, `updated_at`) VALUES
(123, 12, 'messages_generated', '5 messages generated.', '2026-07-28 05:28:43', '2026-07-28 05:28:43'),
(124, 12, 'messages_generated', '5 messages generated.', '2026-07-28 05:44:29', '2026-07-28 05:44:29'),
(125, 12, 'started', 'Campaign processing started.', '2026-07-28 05:44:39', '2026-07-28 05:44:39'),
(126, 12, 'completed', 'Campaign finished successfully.', '2026-07-28 05:44:42', '2026-07-28 05:44:42');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `last_contacted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `vendor_id`, `name`, `phone_number`, `email`, `company`, `city`, `state`, `country`, `notes`, `status`, `last_contacted_at`, `created_at`, `updated_at`) VALUES
(13, 1, 'Dummy 1', '9876543211', 'a@b.com', 'Dummy 1', NULL, NULL, NULL, NULL, 'active', NULL, '2026-07-04 00:35:15', '2026-07-04 00:35:15'),
(14, 1, 'Dummy 2', '9876543212', 'a@b.com', 'Dummy 1', NULL, NULL, NULL, NULL, 'active', NULL, '2026-07-04 00:35:15', '2026-07-04 00:35:15'),
(15, 1, 'Dummy 3', '9876543213', 'a@b.com', 'Dummy 3', NULL, NULL, NULL, NULL, 'blocked', NULL, '2026-07-04 00:35:15', '2026-07-04 00:35:15'),
(16, 1, 'Dummy 4', '9876543214', 'a@b.com', 'Dummy 4', NULL, NULL, NULL, NULL, 'unsubscribed', NULL, '2026-07-04 00:35:15', '2026-07-04 00:35:15'),
(17, 1, 'Dummy 5', '9876543215', 'a@b.com', 'Dummy 5', NULL, NULL, NULL, NULL, 'active', NULL, '2026-07-04 00:35:15', '2026-07-04 00:35:15'),
(18, 1, 'Bhavin Shah', '917405217574', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, '2026-08-07 10:39:17', '2026-08-07 10:39:17');

-- --------------------------------------------------------

--
-- Table structure for table `contact_group`
--

CREATE TABLE `contact_group` (
  `id` bigint UNSIGNED NOT NULL,
  `contact_id` bigint UNSIGNED NOT NULL,
  `group_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_group`
--

INSERT INTO `contact_group` (`id`, `contact_id`, `group_id`, `created_at`, `updated_at`) VALUES
(5, 13, 7, NULL, NULL),
(6, 15, 7, NULL, NULL),
(7, 17, 7, NULL, NULL),
(8, 14, 7, NULL, NULL),
(9, 16, 7, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contact_tag`
--

CREATE TABLE `contact_tag` (
  `id` bigint UNSIGNED NOT NULL,
  `contact_id` bigint UNSIGNED NOT NULL,
  `tag_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `contact_id` bigint UNSIGNED NOT NULL,
  `whatsapp_account_id` bigint UNSIGNED DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `last_incoming_at` timestamp NULL DEFAULT NULL,
  `last_outgoing_at` timestamp NULL DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT '0',
  `is_ai_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `unread_count` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `vendor_id`, `contact_id`, `whatsapp_account_id`, `last_message_at`, `last_incoming_at`, `last_outgoing_at`, `is_closed`, `is_ai_enabled`, `unread_count`, `created_at`, `updated_at`) VALUES
(1, 1, 13, NULL, '2026-08-07 09:53:53', '2026-08-07 09:53:53', '2026-08-07 09:36:45', 0, 0, 0, '2026-08-07 05:44:41', '2026-08-07 09:54:06'),
(4, 1, 18, NULL, '2026-08-12 12:14:26', '2026-08-10 07:27:01', '2026-08-12 12:14:26', 0, 0, 0, '2026-08-07 10:39:17', '2026-08-12 12:14:26'),
(5, 1, 18, 15, '2026-08-13 06:49:26', '2026-08-12 12:37:50', '2026-08-13 06:49:26', 0, 0, 0, '2026-08-11 12:45:16', '2026-08-13 06:49:26');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(102, '6976d265-5912-4d99-acb8-3ba2b37ff2ba', 'database', 'whatsapp-sending', '{\"uuid\":\"6976d265-5912-4d99-acb8-3ba2b37ff2ba\",\"displayName\":\"App\\\\Jobs\\\\SendMessageJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMessageJob\",\"command\":\"O:23:\\\"App\\\\Jobs\\\\SendMessageJob\\\":2:{s:7:\\\"message\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Message\\\";s:2:\\\"id\\\";i:818;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:5:\\\"queue\\\";s:16:\\\"whatsapp-sending\\\";}\",\"batchId\":null},\"createdAt\":1786172081,\"delay\":null}', 'TypeError: App\\Jobs\\SendMessageJob::markFailed(): Argument #2 ($reason) must be of type string, array given, called in C:\\laragon\\www\\wantrix\\app\\Jobs\\SendMessageJob.php on line 63 and defined in C:\\laragon\\www\\wantrix\\app\\Jobs\\SendMessageJob.php:95\nStack trace:\n#0 C:\\laragon\\www\\wantrix\\app\\Jobs\\SendMessageJob.php(63): App\\Jobs\\SendMessageJob->markFailed(Object(App\\Models\\Message), Array)\n#1 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\SendMessageJob->handle(Object(App\\Services\\Whatsapp\\WhatsappManager))\n#2 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#3 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#4 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#5 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#6 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Container\\Container->call(Array)\n#7 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendMessageJob))\n#8 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMessageJob))\n#9 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(140): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#10 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(153): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendMessageJob), false)\n#11 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendMessageJob))\n#12 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendMessageJob))\n#13 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(146): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#14 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(84): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendMessageJob))\n#15 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#16 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(553): Illuminate\\Queue\\Jobs\\Job->fire()\n#17 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(499): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#18 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(245): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#19 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'whatsapp-sendin...\', Object(Illuminate\\Queue\\WorkerOptions))\n#20 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'whatsapp-sendin...\')\n#21 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#22 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#23 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#24 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#25 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#26 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(280): Illuminate\\Container\\Container->call(Array)\n#27 C:\\laragon\\www\\wantrix\\vendor\\symfony\\console\\Command\\Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#28 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(249): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#29 C:\\laragon\\www\\wantrix\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#30 C:\\laragon\\www\\wantrix\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#31 C:\\laragon\\www\\wantrix\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#32 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#33 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 C:\\laragon\\www\\wantrix\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#35 {main}', '2026-08-08 06:54:45'),
(103, '111e0128-b60e-4928-b23a-b63f3fea96c3', 'database', 'whatsapp-sending', '{\"uuid\":\"111e0128-b60e-4928-b23a-b63f3fea96c3\",\"displayName\":\"App\\\\Jobs\\\\SendMessageJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMessageJob\",\"command\":\"O:23:\\\"App\\\\Jobs\\\\SendMessageJob\\\":2:{s:7:\\\"message\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Message\\\";s:2:\\\"id\\\";i:829;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:5:\\\"queue\\\";s:16:\\\"whatsapp-sending\\\";}\",\"batchId\":null},\"createdAt\":1786434987,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\Message]. in C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:786\nStack trace:\n#0 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(112): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Jobs\\SendMessageJob->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(98): App\\Jobs\\SendMessageJob->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Jobs\\SendMessageJob->__unserialize(Array)\n#4 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(114): unserialize(\'O:23:\"App\\\\Jobs\\\\...\')\n#5 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(71): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(553): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(499): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(245): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'whatsapp-sendin...\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'whatsapp-sendin...\')\n#12 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(280): Illuminate\\Container\\Container->call(Array)\n#18 C:\\laragon\\www\\wantrix\\vendor\\symfony\\console\\Command\\Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(249): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 C:\\laragon\\www\\wantrix\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\laragon\\www\\wantrix\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\laragon\\www\\wantrix\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\laragon\\www\\wantrix\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2026-08-11 09:08:52'),
(104, '24993318-73a4-4357-8946-a04e370a1652', 'database', 'whatsapp-sending', '{\"uuid\":\"24993318-73a4-4357-8946-a04e370a1652\",\"displayName\":\"App\\\\Jobs\\\\SendMessageJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMessageJob\",\"command\":\"O:23:\\\"App\\\\Jobs\\\\SendMessageJob\\\":2:{s:7:\\\"message\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Message\\\";s:2:\\\"id\\\";i:830;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:5:\\\"queue\\\";s:16:\\\"whatsapp-sending\\\";}\",\"batchId\":null},\"createdAt\":1786435049,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\Message]. in C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:786\nStack trace:\n#0 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(112): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Jobs\\SendMessageJob->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(98): App\\Jobs\\SendMessageJob->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Jobs\\SendMessageJob->__unserialize(Array)\n#4 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(114): unserialize(\'O:23:\"App\\\\Jobs\\\\...\')\n#5 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(71): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(553): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(499): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(245): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'whatsapp-sendin...\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'whatsapp-sendin...\')\n#12 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(280): Illuminate\\Container\\Container->call(Array)\n#18 C:\\laragon\\www\\wantrix\\vendor\\symfony\\console\\Command\\Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(249): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 C:\\laragon\\www\\wantrix\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\laragon\\www\\wantrix\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\laragon\\www\\wantrix\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\laragon\\www\\wantrix\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2026-08-11 09:08:52'),
(105, 'dfbd2bca-bcb5-402a-89b5-98bd2dd288f8', 'database', 'whatsapp-sending', '{\"uuid\":\"dfbd2bca-bcb5-402a-89b5-98bd2dd288f8\",\"displayName\":\"App\\\\Jobs\\\\SendMessageJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendMessageJob\",\"command\":\"O:23:\\\"App\\\\Jobs\\\\SendMessageJob\\\":2:{s:7:\\\"message\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Message\\\";s:2:\\\"id\\\";i:831;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:5:\\\"queue\\\";s:16:\\\"whatsapp-sending\\\";}\",\"batchId\":null},\"createdAt\":1786435671,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\Message]. in C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php:786\nStack trace:\n#0 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(112): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesAndRestoresModelIdentifiers.php(63): App\\Jobs\\SendMessageJob->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\SerializesModels.php(98): App\\Jobs\\SendMessageJob->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Jobs\\SendMessageJob->__unserialize(Array)\n#4 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(114): unserialize(\'O:23:\"App\\\\Jobs\\\\...\')\n#5 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(71): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(553): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(499): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(245): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'whatsapp-sendin...\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'whatsapp-sendin...\')\n#12 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#14 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(280): Illuminate\\Container\\Container->call(Array)\n#18 C:\\laragon\\www\\wantrix\\vendor\\symfony\\console\\Command\\Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(249): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 C:\\laragon\\www\\wantrix\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 C:\\laragon\\www\\wantrix\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 C:\\laragon\\www\\wantrix\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 C:\\laragon\\www\\wantrix\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 C:\\laragon\\www\\wantrix\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2026-08-11 09:08:52');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `vendor_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(7, 1, 'Test Group', 'Test Group', '2026-07-04 00:34:46', '2026-07-04 00:34:46');

-- --------------------------------------------------------

--
-- Table structure for table `incoming_messages`
--

CREATE TABLE `incoming_messages` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `contact_id` bigint UNSIGNED DEFAULT NULL,
  `whatsapp_account_id` bigint UNSIGNED NOT NULL,
  `conversation_id` bigint UNSIGNED DEFAULT NULL,
  `media_library_id` bigint UNSIGNED DEFAULT NULL,
  `provider_message_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `type` enum('text','image','document','video','audio','voice','sticker','location','contact','button','interactive','reaction','unknown') COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` json DEFAULT NULL,
  `received_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incoming_messages`
--

INSERT INTO `incoming_messages` (`id`, `vendor_id`, `contact_id`, `whatsapp_account_id`, `conversation_id`, `media_library_id`, `provider_message_id`, `from_phone`, `body`, `type`, `payload`, `received_at`, `created_at`, `updated_at`) VALUES
(31, 1, 18, 15, 5, NULL, 'wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhgWM0VCMDQ1NEJCNThDMDIxOUFCRjMwOAA=', '917405217574', 'hello', 'text', '{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhgWM0VCMDQ1NEJCNThDMDIxOUFCRjMwOAA=\", \"from\": \"917405217574\", \"text\": {\"body\": \"hello\"}, \"type\": \"text\", \"timestamp\": \"1786532281\", \"from_user_id\": \"IN.1697250741455688\"}', '2026-08-12 10:58:03', '2026-08-12 10:58:03', '2026-08-12 10:58:03'),
(32, 1, 18, 15, 5, NULL, 'wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhgWM0VCMDA3NzNCRUQwNjlEMzMxRTYzMQA=', '917405217574', 'hi', 'text', '{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhgWM0VCMDA3NzNCRUQwNjlEMzMxRTYzMQA=\", \"from\": \"917405217574\", \"text\": {\"body\": \"hi\"}, \"type\": \"text\", \"timestamp\": \"1786534624\", \"from_user_id\": \"IN.1697250741455688\"}', '2026-08-12 11:37:05', '2026-08-12 11:37:05', '2026-08-12 11:37:05'),
(33, 1, 18, 15, 5, NULL, 'wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhgWM0VCMDVDOUZBRjMyQ0NFNzExOTVCQQA=', '917405217574', 'hi', 'text', '{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhgWM0VCMDVDOUZBRjMyQ0NFNzExOTVCQQA=\", \"from\": \"917405217574\", \"text\": {\"body\": \"hi\"}, \"type\": \"text\", \"timestamp\": \"1786534876\", \"from_user_id\": \"IN.1697250741455688\"}', '2026-08-12 11:41:17', '2026-08-12 11:41:17', '2026-08-12 11:41:17'),
(34, 1, 18, 15, 5, NULL, 'wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhgWM0VCMEZGNkJBMjZDNEFDQUU4MzA1RQA=', '917405217574', 'b', 'text', '{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhgWM0VCMEZGNkJBMjZDNEFDQUU4MzA1RQA=\", \"from\": \"917405217574\", \"text\": {\"body\": \"b\"}, \"type\": \"text\", \"timestamp\": \"1786536880\", \"from_user_id\": \"IN.1697250741455688\"}', '2026-08-12 12:14:41', '2026-08-12 12:14:41', '2026-08-12 12:14:41'),
(35, 1, 18, 15, 5, NULL, 'wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTVGQ0Q4MEI5QjBFQThFRjFFQzNEMDlFN0VCOUI4NEYA', '917405217574', 'Aa to kevu thayu', 'text', '{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTVGQ0Q4MEI5QjBFQThFRjFFQzNEMDlFN0VCOUI4NEYA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Aa to kevu thayu\"}, \"type\": \"text\", \"timestamp\": \"1786461552\", \"from_user_id\": \"IN.1697250741455688\"}', '2026-08-12 12:19:54', '2026-08-12 12:19:54', '2026-08-12 12:19:54'),
(36, 1, 18, 15, 5, NULL, 'wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTU3NTQyM0VBRjJDMEU0NDE5RkJFODMyOTQwNDQzNEIA', '917405217574', 'Zvvxgxzv', 'text', '{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTU3NTQyM0VBRjJDMEU0NDE5RkJFODMyOTQwNDQzNEIA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Zvvxgxzv\"}, \"type\": \"text\", \"timestamp\": \"1786538156\", \"from_user_id\": \"IN.1697250741455688\"}', '2026-08-12 12:35:56', '2026-08-12 12:35:56', '2026-08-12 12:35:56'),
(37, 1, 18, 15, 5, NULL, 'wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTU3NkNGODc1QUFEQjY3RTc4OUExQkQ4QURDMDMwNDkA', '917405217574', 'Gsgsgs', 'text', '{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTU3NkNGODc1QUFEQjY3RTc4OUExQkQ4QURDMDMwNDkA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Gsgsgs\"}, \"type\": \"text\", \"timestamp\": \"1786538244\", \"from_user_id\": \"IN.1697250741455688\"}', '2026-08-12 12:37:24', '2026-08-12 12:37:24', '2026-08-12 12:37:24'),
(38, 1, 18, 15, 5, NULL, 'wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTU3MzU1QUMxN0YzM0ZBQUU5MkJFQjM5QUI4QkVCQUIA', '917405217574', 'Gdgxgd', 'text', '{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTU3MzU1QUMxN0YzM0ZBQUU5MkJFQjM5QUI4QkVCQUIA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Gdgxgd\"}, \"type\": \"text\", \"timestamp\": \"1786538269\", \"from_user_id\": \"IN.1697250741455688\"}', '2026-08-12 12:37:50', '2026-08-12 12:37:50', '2026-08-12 12:37:50');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INR',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `issued_at` timestamp NOT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `vendor_id`, `subscription_id`, `invoice_number`, `amount`, `currency`, `status`, `issued_at`, `paid_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 7, '#ABCD2345', 450.00, 'INR', 'pending', '2026-06-18 09:34:17', NULL, NULL, NULL, NULL),
(2, 1, 7, '#DEF2345', 900.00, 'INR', 'pending', '2026-06-18 09:34:33', NULL, NULL, NULL, NULL),
(3, 1, 7, 'INV-20260618111729', 999.00, 'INR', 'pending', '2026-06-18 05:47:29', NULL, '2026-06-18 05:47:29', '2026-06-18 05:47:29', NULL),
(4, 1, 7, 'INV-20260618111801', 999.00, 'INR', 'pending', '2026-06-18 05:48:01', NULL, '2026-06-18 05:48:01', '2026-06-18 05:48:01', NULL),
(5, 1, 7, 'INV-20260618112806', 999.00, 'INR', 'pending', '2026-06-18 05:58:06', NULL, '2026-06-18 05:58:06', '2026-06-18 05:58:06', NULL),
(6, 1, 7, 'INV-20260618114526', 999.00, 'INR', 'pending', '2026-06-18 06:15:26', NULL, '2026-06-18 06:15:26', '2026-06-18 06:15:26', NULL),
(7, 1, 7, 'INV-20260618114537', 999.00, 'INR', 'pending', '2026-06-18 06:15:37', NULL, '2026-06-18 06:15:37', '2026-06-18 06:15:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_batches`
--

INSERT INTO `job_batches` (`id`, `name`, `total_jobs`, `pending_jobs`, `failed_jobs`, `failed_job_ids`, `options`, `cancelled_at`, `created_at`, `finished_at`) VALUES
('a25d4994-e085-4262-bcb7-2320e823dd21', '', 1, 0, 0, '[]', 'a:3:{s:5:\"queue\";s:19:\"campaign-generation\";s:4:\"then\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:772:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:8:\"campaign\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:19:\"App\\Models\\Campaign\";s:2:\"id\";i:12;s:9:\"relations\";a:2:{i:0;s:5:\"group\";i:1;s:8:\"template\";}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}}s:8:\"function\";s:317:\"function (\\Illuminate\\Bus\\Batch $batch) use ($campaign) {\n\n                $campaign->update([\n                    \'messages_generated_at\' => now(),\n                ]);\n\n                event(new \\App\\Events\\Campaigns\\CampaignMessagesGenerated(\n                    $campaign->fresh()\n                ));\n            }\";s:5:\"scope\";s:36:\"App\\Jobs\\GenerateCampaignMessagesJob\";s:4:\"this\";N;s:4:\"self\";s:32:\"0000000000000a730000000000000000\";}\";s:4:\"hash\";s:44:\"4ZctUSi6+gAmyYBXDx7JAF5MPmJCx2zc6cJkNnFrDrw=\";}}}s:5:\"catch\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:570:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:8:\"campaign\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:19:\"App\\Models\\Campaign\";s:2:\"id\";i:12;s:9:\"relations\";a:2:{i:0;s:5:\"group\";i:1;s:8:\"template\";}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}}s:8:\"function\";s:115:\"function (\\Illuminate\\Bus\\Batch $batch, \\Throwable $e) use ($campaign) {\n\n                report($e);\n            }\";s:5:\"scope\";s:36:\"App\\Jobs\\GenerateCampaignMessagesJob\";s:4:\"this\";N;s:4:\"self\";s:32:\"0000000000000a800000000000000000\";}\";s:4:\"hash\";s:44:\"0NyFUiKhaB+ycImLfyEUcogM+KVdeQZQLVY7S8yzmh0=\";}}}}', NULL, 1785215501, 1785215501),
('a25d49f0-b0d6-4b4c-b6b9-fd29594bd80b', '', 1, 0, 0, '[]', 'a:3:{s:5:\"queue\";s:19:\"campaign-generation\";s:4:\"then\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:772:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:8:\"campaign\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:19:\"App\\Models\\Campaign\";s:2:\"id\";i:12;s:9:\"relations\";a:2:{i:0;s:5:\"group\";i:1;s:8:\"template\";}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}}s:8:\"function\";s:317:\"function (\\Illuminate\\Bus\\Batch $batch) use ($campaign) {\n\n                $campaign->update([\n                    \'messages_generated_at\' => now(),\n                ]);\n\n                event(new \\App\\Events\\Campaigns\\CampaignMessagesGenerated(\n                    $campaign->fresh()\n                ));\n            }\";s:5:\"scope\";s:36:\"App\\Jobs\\GenerateCampaignMessagesJob\";s:4:\"this\";N;s:4:\"self\";s:32:\"0000000000000a730000000000000000\";}\";s:4:\"hash\";s:44:\"4ZctUSi6+gAmyYBXDx7JAF5MPmJCx2zc6cJkNnFrDrw=\";}}}s:5:\"catch\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:570:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:8:\"campaign\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:19:\"App\\Models\\Campaign\";s:2:\"id\";i:12;s:9:\"relations\";a:2:{i:0;s:5:\"group\";i:1;s:8:\"template\";}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}}s:8:\"function\";s:115:\"function (\\Illuminate\\Bus\\Batch $batch, \\Throwable $e) use ($campaign) {\n\n                report($e);\n            }\";s:5:\"scope\";s:36:\"App\\Jobs\\GenerateCampaignMessagesJob\";s:4:\"this\";N;s:4:\"self\";s:32:\"0000000000000a800000000000000000\";}\";s:4:\"hash\";s:44:\"0NyFUiKhaB+ycImLfyEUcogM+KVdeQZQLVY7S8yzmh0=\";}}}}', NULL, 1785215561, 1785215561),
('a25d4bb8-745c-4ccb-a4a2-b5a70d788384', '', 1, 0, 0, '[]', 'a:3:{s:5:\"queue\";s:19:\"campaign-generation\";s:4:\"then\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:772:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:8:\"campaign\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:19:\"App\\Models\\Campaign\";s:2:\"id\";i:12;s:9:\"relations\";a:2:{i:0;s:5:\"group\";i:1;s:8:\"template\";}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}}s:8:\"function\";s:317:\"function (\\Illuminate\\Bus\\Batch $batch) use ($campaign) {\n\n                $campaign->update([\n                    \'messages_generated_at\' => now(),\n                ]);\n\n                event(new \\App\\Events\\Campaigns\\CampaignMessagesGenerated(\n                    $campaign->fresh()\n                ));\n            }\";s:5:\"scope\";s:36:\"App\\Jobs\\GenerateCampaignMessagesJob\";s:4:\"this\";N;s:4:\"self\";s:32:\"0000000000000a730000000000000000\";}\";s:4:\"hash\";s:44:\"4ZctUSi6+gAmyYBXDx7JAF5MPmJCx2zc6cJkNnFrDrw=\";}}}s:5:\"catch\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:570:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:8:\"campaign\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:19:\"App\\Models\\Campaign\";s:2:\"id\";i:12;s:9:\"relations\";a:2:{i:0;s:5:\"group\";i:1;s:8:\"template\";}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}}s:8:\"function\";s:115:\"function (\\Illuminate\\Bus\\Batch $batch, \\Throwable $e) use ($campaign) {\n\n                report($e);\n            }\";s:5:\"scope\";s:36:\"App\\Jobs\\GenerateCampaignMessagesJob\";s:4:\"this\";N;s:4:\"self\";s:32:\"0000000000000a800000000000000000\";}\";s:4:\"hash\";s:44:\"0NyFUiKhaB+ycImLfyEUcogM+KVdeQZQLVY7S8yzmh0=\";}}}}', NULL, 1785215860, 1785215860),
('a25d4ddf-f674-4b54-9ed4-0c29b302eb71', '', 1, 0, 0, '[]', 'a:3:{s:5:\"queue\";s:19:\"campaign-generation\";s:4:\"then\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:772:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:8:\"campaign\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:19:\"App\\Models\\Campaign\";s:2:\"id\";i:12;s:9:\"relations\";a:2:{i:0;s:5:\"group\";i:1;s:8:\"template\";}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}}s:8:\"function\";s:317:\"function (\\Illuminate\\Bus\\Batch $batch) use ($campaign) {\n\n                $campaign->update([\n                    \'messages_generated_at\' => now(),\n                ]);\n\n                event(new \\App\\Events\\Campaigns\\CampaignMessagesGenerated(\n                    $campaign->fresh()\n                ));\n            }\";s:5:\"scope\";s:36:\"App\\Jobs\\GenerateCampaignMessagesJob\";s:4:\"this\";N;s:4:\"self\";s:32:\"0000000000000a730000000000000000\";}\";s:4:\"hash\";s:44:\"4ZctUSi6+gAmyYBXDx7JAF5MPmJCx2zc6cJkNnFrDrw=\";}}}s:5:\"catch\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:570:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:8:\"campaign\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:19:\"App\\Models\\Campaign\";s:2:\"id\";i:12;s:9:\"relations\";a:2:{i:0;s:5:\"group\";i:1;s:8:\"template\";}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}}s:8:\"function\";s:115:\"function (\\Illuminate\\Bus\\Batch $batch, \\Throwable $e) use ($campaign) {\n\n                report($e);\n            }\";s:5:\"scope\";s:36:\"App\\Jobs\\GenerateCampaignMessagesJob\";s:4:\"this\";N;s:4:\"self\";s:32:\"0000000000000a800000000000000000\";}\";s:4:\"hash\";s:44:\"0NyFUiKhaB+ycImLfyEUcogM+KVdeQZQLVY7S8yzmh0=\";}}}}', NULL, 1785216221, 1785216222),
('a25d4fac-048b-4e69-9749-07ae1e3e0a8d', '', 1, 0, 0, '[]', 'a:3:{s:5:\"queue\";s:19:\"campaign-generation\";s:4:\"then\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:772:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:8:\"campaign\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:19:\"App\\Models\\Campaign\";s:2:\"id\";i:12;s:9:\"relations\";a:2:{i:0;s:5:\"group\";i:1;s:8:\"template\";}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}}s:8:\"function\";s:317:\"function (\\Illuminate\\Bus\\Batch $batch) use ($campaign) {\n\n                $campaign->update([\n                    \'messages_generated_at\' => now(),\n                ]);\n\n                event(new \\App\\Events\\Campaigns\\CampaignMessagesGenerated(\n                    $campaign->fresh()\n                ));\n            }\";s:5:\"scope\";s:36:\"App\\Jobs\\GenerateCampaignMessagesJob\";s:4:\"this\";N;s:4:\"self\";s:32:\"000000000000090e0000000000000000\";}\";s:4:\"hash\";s:44:\"WgIe3GEzyLfkDCHghHebpnR2Xjp6bgav8fBTjffMjik=\";}}}s:5:\"catch\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:570:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:8:\"campaign\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:19:\"App\\Models\\Campaign\";s:2:\"id\";i:12;s:9:\"relations\";a:2:{i:0;s:5:\"group\";i:1;s:8:\"template\";}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}}s:8:\"function\";s:115:\"function (\\Illuminate\\Bus\\Batch $batch, \\Throwable $e) use ($campaign) {\n\n                report($e);\n            }\";s:5:\"scope\";s:36:\"App\\Jobs\\GenerateCampaignMessagesJob\";s:4:\"this\";N;s:4:\"self\";s:32:\"00000000000008aa0000000000000000\";}\";s:4:\"hash\";s:44:\"9AjkH3QBDTBfXIXG2ieWx9CSdI/UGpOQ2SYP9ke4KIA=\";}}}}', NULL, 1785216523, 1785216523),
('a25d554f-b963-49ec-900e-d23d0b01eeec', '', 1, 0, 0, '[]', 'a:3:{s:5:\"queue\";s:19:\"campaign-generation\";s:4:\"then\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:772:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:8:\"campaign\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:19:\"App\\Models\\Campaign\";s:2:\"id\";i:12;s:9:\"relations\";a:2:{i:0;s:5:\"group\";i:1;s:8:\"template\";}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}}s:8:\"function\";s:317:\"function (\\Illuminate\\Bus\\Batch $batch) use ($campaign) {\n\n                $campaign->update([\n                    \'messages_generated_at\' => now(),\n                ]);\n\n                event(new \\App\\Events\\Campaigns\\CampaignMessagesGenerated(\n                    $campaign->fresh()\n                ));\n            }\";s:5:\"scope\";s:36:\"App\\Jobs\\GenerateCampaignMessagesJob\";s:4:\"this\";N;s:4:\"self\";s:32:\"0000000000000a730000000000000000\";}\";s:4:\"hash\";s:44:\"4ZctUSi6+gAmyYBXDx7JAF5MPmJCx2zc6cJkNnFrDrw=\";}}}s:5:\"catch\";a:1:{i:0;O:47:\"Laravel\\SerializableClosure\\SerializableClosure\":1:{s:12:\"serializable\";O:46:\"Laravel\\SerializableClosure\\Serializers\\Signed\":2:{s:12:\"serializable\";s:570:\"O:46:\"Laravel\\SerializableClosure\\Serializers\\Native\":5:{s:3:\"use\";a:1:{s:8:\"campaign\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:19:\"App\\Models\\Campaign\";s:2:\"id\";i:12;s:9:\"relations\";a:2:{i:0;s:5:\"group\";i:1;s:8:\"template\";}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}}s:8:\"function\";s:115:\"function (\\Illuminate\\Bus\\Batch $batch, \\Throwable $e) use ($campaign) {\n\n                report($e);\n            }\";s:5:\"scope\";s:36:\"App\\Jobs\\GenerateCampaignMessagesJob\";s:4:\"this\";N;s:4:\"self\";s:32:\"0000000000000a800000000000000000\";}\";s:4:\"hash\";s:44:\"0NyFUiKhaB+ycImLfyEUcogM+KVdeQZQLVY7S8yzmh0=\";}}}}', NULL, 1785217469, 1785217469);

-- --------------------------------------------------------

--
-- Table structure for table `media_libraries`
--

CREATE TABLE `media_libraries` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_media_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_to_meta` tinyint(1) NOT NULL DEFAULT '0',
  `uploaded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media_libraries`
--

INSERT INTO `media_libraries` (`id`, `vendor_id`, `name`, `original_name`, `disk`, `path`, `mime_type`, `size`, `type`, `meta_media_id`, `uploaded_to_meta`, `uploaded_at`, `created_at`, `updated_at`) VALUES
(7, 1, 'logo', 'logo.png', 'public', 'media/vendor_1/fJIKe9k68Oqb4jt1UbsqKUq0m2EW83dgP3QKEvsb.png', 'image/png', 10198, 'image', '1434980551803127', 1, '2026-08-06 09:33:17', '2026-08-06 09:33:15', '2026-08-06 09:33:17'),
(8, 1, 'Mr. Shreyas Quotation', 'Mr. Shreyas Quotation.pdf', 'public', 'media/vendor_1/W5eAGkIfurUfRgQppamQjoKWpHOa92EgnsOil0S8.pdf', 'application/pdf', 79947, 'document', '2058380105069371', 1, '2026-08-06 09:38:56', '2026-08-06 09:38:53', '2026-08-06 09:38:56'),
(9, 1, 'logo', 'logo.png', 'public', 'media/vendor_1/yqV92gbirAbbK9OhrGmnKGxIMjtkkzzKA2izmWY9.png', 'image/png', 10198, 'image', '973364379096514', 1, '2026-08-06 09:39:06', '2026-08-06 09:39:04', '2026-08-06 09:39:06');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `campaign_id` bigint UNSIGNED DEFAULT NULL,
  `conversation_id` bigint UNSIGNED DEFAULT NULL,
  `whatsapp_template_id` bigint UNSIGNED DEFAULT NULL,
  `template_variables` json DEFAULT NULL,
  `contact_id` bigint UNSIGNED NOT NULL,
  `direction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'outgoing',
  `whatsapp_account_id` bigint UNSIGNED DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `status` enum('pending','queued','sending','sent','delivered','read','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `provider_message_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `failure_reason` text COLLATE utf8mb4_unicode_ci,
  `retry_count` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `last_retried_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `media_library_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `vendor_id`, `campaign_id`, `conversation_id`, `whatsapp_template_id`, `template_variables`, `contact_id`, `direction`, `whatsapp_account_id`, `body`, `type`, `status`, `provider_message_id`, `sent_at`, `delivered_at`, `read_at`, `failure_reason`, `retry_count`, `last_retried_at`, `created_at`, `updated_at`, `media_library_id`) VALUES
(851, 1, NULL, 5, NULL, NULL, 18, 'outgoing', 15, 'Bhavin', 'text', 'failed', NULL, NULL, NULL, NULL, 'Unable to decrypt WhatsApp access token.', 0, NULL, '2026-08-12 10:56:20', '2026-08-12 10:56:43', NULL),
(852, 1, NULL, 5, NULL, NULL, 18, 'outgoing', 15, 'fjsdhgfjds', 'text', 'failed', NULL, NULL, NULL, NULL, 'Unable to decrypt WhatsApp access token.', 0, NULL, '2026-08-12 11:22:53', '2026-08-12 11:22:55', NULL),
(853, 1, NULL, 5, NULL, NULL, 18, 'outgoing', 15, 'Vajradham', 'text', 'failed', NULL, NULL, NULL, NULL, 'Unable to decrypt WhatsApp access token.', 0, NULL, '2026-08-12 11:25:13', '2026-08-12 11:25:14', NULL),
(854, 1, NULL, 5, NULL, NULL, 18, 'outgoing', 15, 'Golmaal', 'text', 'failed', NULL, NULL, NULL, NULL, 'Unable to decrypt WhatsApp access token.', 0, NULL, '2026-08-12 11:36:42', '2026-08-12 11:36:43', NULL),
(855, 1, NULL, 5, NULL, NULL, 18, 'outgoing', 15, 'tang', 'text', 'read', 'wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSNUUwQTNDMzMwQzBCMDM2MDFDAA==', '2026-08-12 11:41:44', '2026-08-12 11:41:44', '2026-08-12 11:44:30', NULL, 0, NULL, '2026-08-12 11:41:36', '2026-08-12 11:44:30', NULL),
(857, 1, NULL, 4, NULL, NULL, 18, 'outgoing', NULL, 'a', 'text', 'failed', NULL, NULL, NULL, NULL, 'No WhatsApp account associated with this message.', 0, NULL, '2026-08-12 12:12:39', '2026-08-12 12:12:41', NULL),
(858, 1, NULL, 4, NULL, NULL, 18, 'outgoing', NULL, 'a', 'text', 'failed', NULL, NULL, NULL, NULL, 'No WhatsApp account associated with this message.', 0, NULL, '2026-08-12 12:14:00', '2026-08-12 12:14:03', NULL),
(859, 1, NULL, 4, NULL, NULL, 18, 'outgoing', NULL, 'a', 'text', 'failed', NULL, NULL, NULL, NULL, 'No WhatsApp account associated with this message.', 0, NULL, '2026-08-12 12:14:26', '2026-08-12 12:14:27', NULL),
(860, 1, NULL, 5, NULL, NULL, 18, 'outgoing', 15, 'aajtak', 'text', 'read', 'wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSODVDMjFCMjE3MDA1NUU5NkNGAA==', '2026-08-12 12:15:16', '2026-08-12 12:15:17', '2026-08-12 12:27:01', NULL, 0, NULL, '2026-08-12 12:15:10', '2026-08-12 12:27:01', NULL),
(861, 1, NULL, 5, NULL, NULL, 18, 'outgoing', 15, 'ABP News', 'text', 'read', 'wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRkMyQkI2RDhGRjI1Nzg4RTNCAA==', '2026-08-12 12:16:01', '2026-08-12 12:16:01', '2026-08-12 12:27:01', NULL, 0, NULL, '2026-08-12 12:15:55', '2026-08-12 12:27:01', NULL),
(862, 1, NULL, 5, NULL, NULL, 18, 'outgoing', 15, 'Barabar', 'text', 'read', 'wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRDk3NEI2OEU5OEM1MjU4NERFAA==', '2026-08-12 12:27:42', '2026-08-12 12:27:42', '2026-08-12 12:31:48', NULL, 0, NULL, '2026-08-12 12:27:36', '2026-08-12 12:31:48', NULL),
(863, 1, NULL, 5, NULL, NULL, 18, 'outgoing', 15, 'check', 'text', 'read', 'wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSQjVBNjhBQ0M3QkU4ODZBMDk0AA==', '2026-08-12 12:31:51', '2026-08-12 12:31:51', '2026-08-12 12:31:52', NULL, 0, NULL, '2026-08-12 12:31:44', '2026-08-12 12:31:52', NULL),
(864, 1, NULL, 5, NULL, NULL, 18, 'outgoing', 15, 'fsdgsdags', 'text', 'read', 'wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRDQ5QTVFOTg3MjEyRDZEQUQ4AA==', '2026-08-13 06:49:37', '2026-08-13 06:49:37', '2026-08-13 07:02:14', NULL, 0, NULL, '2026-08-13 06:49:26', '2026-08-13 07:02:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `message_status_histories`
--

CREATE TABLE `message_status_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `message_id` bigint UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_status_histories`
--

INSERT INTO `message_status_histories` (`id`, `message_id`, `status`, `changed_at`, `created_at`, `updated_at`) VALUES
(107, 851, 'pending', '2026-08-12 10:56:20', '2026-08-12 10:56:20', '2026-08-12 10:56:20'),
(108, 851, 'sending', '2026-08-12 10:56:43', '2026-08-12 10:56:43', '2026-08-12 10:56:43'),
(109, 851, 'failed', '2026-08-12 10:56:43', '2026-08-12 10:56:43', '2026-08-12 10:56:43'),
(110, 852, 'pending', '2026-08-12 11:22:53', '2026-08-12 11:22:53', '2026-08-12 11:22:53'),
(111, 852, 'sending', '2026-08-12 11:22:55', '2026-08-12 11:22:55', '2026-08-12 11:22:55'),
(112, 852, 'failed', '2026-08-12 11:22:55', '2026-08-12 11:22:55', '2026-08-12 11:22:55'),
(113, 853, 'pending', '2026-08-12 11:25:14', '2026-08-12 11:25:14', '2026-08-12 11:25:14'),
(114, 853, 'sending', '2026-08-12 11:25:14', '2026-08-12 11:25:14', '2026-08-12 11:25:14'),
(115, 853, 'failed', '2026-08-12 11:25:14', '2026-08-12 11:25:14', '2026-08-12 11:25:14'),
(116, 854, 'pending', '2026-08-12 11:36:42', '2026-08-12 11:36:42', '2026-08-12 11:36:42'),
(117, 854, 'sending', '2026-08-12 11:36:43', '2026-08-12 11:36:43', '2026-08-12 11:36:43'),
(118, 854, 'failed', '2026-08-12 11:36:43', '2026-08-12 11:36:43', '2026-08-12 11:36:43'),
(119, 855, 'pending', '2026-08-12 11:41:36', '2026-08-12 11:41:36', '2026-08-12 11:41:36'),
(120, 855, 'sending', '2026-08-12 11:41:38', '2026-08-12 11:41:38', '2026-08-12 11:41:38'),
(121, 855, 'sent', '2026-08-12 11:41:41', '2026-08-12 11:41:41', '2026-08-12 11:41:41'),
(122, 855, 'delivered', '2026-08-12 11:41:44', '2026-08-12 11:41:44', '2026-08-12 11:41:44'),
(126, 855, 'read', '2026-08-12 11:44:30', '2026-08-12 11:44:30', '2026-08-12 11:44:30'),
(127, 857, 'pending', '2026-08-12 12:12:39', '2026-08-12 12:12:39', '2026-08-12 12:12:39'),
(128, 857, 'sending', '2026-08-12 12:12:41', '2026-08-12 12:12:41', '2026-08-12 12:12:41'),
(129, 857, 'failed', '2026-08-12 12:12:41', '2026-08-12 12:12:41', '2026-08-12 12:12:41'),
(130, 858, 'pending', '2026-08-12 12:14:00', '2026-08-12 12:14:00', '2026-08-12 12:14:00'),
(131, 858, 'sending', '2026-08-12 12:14:03', '2026-08-12 12:14:03', '2026-08-12 12:14:03'),
(132, 858, 'failed', '2026-08-12 12:14:03', '2026-08-12 12:14:03', '2026-08-12 12:14:03'),
(133, 859, 'pending', '2026-08-12 12:14:26', '2026-08-12 12:14:26', '2026-08-12 12:14:26'),
(134, 859, 'sending', '2026-08-12 12:14:27', '2026-08-12 12:14:27', '2026-08-12 12:14:27'),
(135, 859, 'failed', '2026-08-12 12:14:27', '2026-08-12 12:14:27', '2026-08-12 12:14:27'),
(136, 860, 'pending', '2026-08-12 12:15:10', '2026-08-12 12:15:10', '2026-08-12 12:15:10'),
(137, 860, 'sending', '2026-08-12 12:15:12', '2026-08-12 12:15:12', '2026-08-12 12:15:12'),
(138, 860, 'sent', '2026-08-12 12:15:14', '2026-08-12 12:15:14', '2026-08-12 12:15:14'),
(139, 860, 'delivered', '2026-08-12 12:15:17', '2026-08-12 12:15:17', '2026-08-12 12:15:17'),
(140, 861, 'pending', '2026-08-12 12:15:55', '2026-08-12 12:15:55', '2026-08-12 12:15:55'),
(141, 861, 'sending', '2026-08-12 12:15:57', '2026-08-12 12:15:57', '2026-08-12 12:15:57'),
(142, 861, 'sent', '2026-08-12 12:15:59', '2026-08-12 12:15:59', '2026-08-12 12:15:59'),
(143, 861, 'delivered', '2026-08-12 12:16:01', '2026-08-12 12:16:01', '2026-08-12 12:16:01'),
(144, 860, 'read', '2026-08-12 12:27:01', '2026-08-12 12:27:01', '2026-08-12 12:27:01'),
(145, 861, 'read', '2026-08-12 12:27:01', '2026-08-12 12:27:01', '2026-08-12 12:27:01'),
(146, 862, 'pending', '2026-08-12 12:27:36', '2026-08-12 12:27:36', '2026-08-12 12:27:36'),
(147, 862, 'sending', '2026-08-12 12:27:37', '2026-08-12 12:27:37', '2026-08-12 12:27:37'),
(148, 862, 'sent', '2026-08-12 12:27:39', '2026-08-12 12:27:39', '2026-08-12 12:27:39'),
(149, 862, 'delivered', '2026-08-12 12:27:42', '2026-08-12 12:27:42', '2026-08-12 12:27:42'),
(150, 862, 'sent', '2026-08-12 12:27:42', '2026-08-12 12:27:42', '2026-08-12 12:27:42'),
(151, 863, 'pending', '2026-08-12 12:31:44', '2026-08-12 12:31:44', '2026-08-12 12:31:44'),
(152, 863, 'sending', '2026-08-12 12:31:46', '2026-08-12 12:31:46', '2026-08-12 12:31:46'),
(153, 862, 'read', '2026-08-12 12:31:48', '2026-08-12 12:31:48', '2026-08-12 12:31:48'),
(154, 863, 'sent', '2026-08-12 12:31:48', '2026-08-12 12:31:48', '2026-08-12 12:31:48'),
(155, 863, 'delivered', '2026-08-12 12:31:51', '2026-08-12 12:31:51', '2026-08-12 12:31:51'),
(156, 863, 'read', '2026-08-12 12:31:52', '2026-08-12 12:31:52', '2026-08-12 12:31:52'),
(157, 864, 'pending', '2026-08-13 06:49:26', '2026-08-13 06:49:26', '2026-08-13 06:49:26'),
(158, 864, 'sending', '2026-08-13 06:49:29', '2026-08-13 06:49:29', '2026-08-13 06:49:29'),
(159, 864, 'sent', '2026-08-13 06:49:36', '2026-08-13 06:49:36', '2026-08-13 06:49:36'),
(160, 864, 'delivered', '2026-08-13 06:49:37', '2026-08-13 06:49:37', '2026-08-13 06:49:37'),
(161, 864, 'read', '2026-08-13 07:02:14', '2026-08-13 07:02:14', '2026-08-13 07:02:14');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(4, '0001_01_01_000000_create_users_table', 1),
(5, '0001_01_01_000001_create_cache_table', 1),
(6, '0001_01_01_000002_create_jobs_table', 1),
(7, '2026_06_09_114435_create_permission_tables', 2),
(9, '2026_06_09_120537_create_vendors_table', 3),
(10, '2026_06_09_121416_add_vendor_id_to_users_table', 4),
(12, '2026_06_09_124010_add_status_to_users_table', 5),
(13, '2026_06_10_051910_replace_is_active_with_status_in_users_table', 6),
(14, '2026_06_10_053213_add_phone_and_last_login_to_users_table', 7),
(15, '2026_06_10_080837_create_plans_table', 8),
(16, '2026_06_12_105521_add_unlimited_columns_to_plans_table', 9),
(17, '2026_06_13_050546_create_subscriptions_table', 10),
(18, '2026_06_13_053302_add_notes_to_subscriptions_table', 11),
(19, '2026_06_13_082759_create_payments_table', 12),
(20, '2026_06_13_090711_add_is_default_to_plans_table', 13),
(22, '2026_06_13_103344_create_invoices_table', 14),
(25, '2026_06_13_112126_create_subscription_changes_table', 15),
(26, '2026_06_18_125049_add_company_fields_to_vendors_table', 15),
(30, '2026_06_19_071301_create_contacts_table', 17),
(31, '2026_06_24_055500_create_tags_table', 18),
(32, '2026_06_24_055656_create_contact_tag_table', 19),
(33, '2026_06_24_075118_create_groups_table', 20),
(34, '2026_06_24_075152_create_contact_group_table', 20),
(35, '2026_06_24_085621_create_templates_table', 21),
(36, '2026_06_24_093059_create_campaigns_table', 22),
(38, '2026_06_24_095526_create_messages_table', 23),
(40, '2026_06_19_065120_create_whatsapp_accounts_table', 24),
(41, '2026_06_26_060525_add_role_to_users_table', 25),
(42, '2026_07_03_103212_add_whatsapp_account_to_campaigns_table', 26),
(43, '2026_07_14_052507_add_messages_generated_at_to_campaigns_table', 27),
(46, '2026_07_14_101438_create_activities_table', 28),
(47, '2026_07_15_103747_create_campaign_activities_table', 28),
(48, '2026_07_16_111658_add_cancelled_at_to_campaigns_table', 28),
(60, '2026_07_17_071233_add_retry_fields_to_messages_table', 29),
(61, '2026_07_17_080510_add_message_delay_seconds_to_campaigns_table', 29),
(62, '2026_07_20_104518_create_message_status_histories_table', 29),
(63, '2026_07_28_132203_add_meta_fields_to_whatsapp_accounts_table', 29),
(64, '2026_07_28_142116_add_connection_fields_to_whatsapp_accounts_table', 29),
(65, '2026_07_28_170055_create_whatsapp_templates_table', 29),
(66, '2026_07_30_125628_create_webhook_logs_table', 29),
(67, '2026_08_04_162520_add_message_type_to_messages_table', 30),
(68, '2026_08_04_180237_add_whatsapp_template_to_messages_table', 31),
(69, '2026_08_04_181147_add_template_variables_to_messages_table', 32),
(70, '2026_08_06_110328_create_media_libraries_table', 33),
(71, '2026_08_06_120630_add_media_library_id_to_messages_table', 34),
(72, '2026_08_06_144742_add_media_fields_to_campaigns_table', 35),
(74, '2026_08_06_184432_create_incoming_messages_table', 36),
(75, '2026_08_06_190633_create_conversations_table', 37),
(76, '2026_08_06_192148_add_conversation_id_to_messages_table', 37),
(78, '2026_08_07_101006_add_direction_to_messages_table', 38),
(79, '2026_08_07_104601_add_conversation_id_to_incoming_messages_table', 39),
(80, '2026_08_07_113918_make_campaign_id_nullable_in_messages_table', 40),
(81, '2026_08_07_145305_add_unique_provider_message_id_to_incoming_messages_table', 41);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 1),
(4, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INR',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `gateway` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_payment_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `gateway_response` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `vendor_id`, `subscription_id`, `amount`, `currency`, `status`, `gateway`, `gateway_payment_id`, `paid_at`, `gateway_response`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 7, 450.00, 'INR', 'pending', 'razorpay', '', NULL, NULL, NULL, NULL, NULL),
(2, 1, 7, 900.00, 'INR', 'pending', 'razorpay', '', NULL, NULL, NULL, NULL, NULL),
(4, 1, 7, 999.00, 'INR', 'pending', 'razorpay', NULL, NULL, NULL, '2026-06-18 05:48:01', '2026-06-18 05:48:01', NULL),
(5, 1, 7, 999.00, 'INR', 'pending', 'razorpay', NULL, NULL, NULL, '2026-06-18 05:58:06', '2026-06-18 05:58:06', NULL),
(6, 1, 7, 999.00, 'INR', 'pending', 'razorpay', NULL, NULL, NULL, '2026-06-18 06:15:26', '2026-06-18 06:15:26', NULL),
(7, 1, 7, 999.00, 'INR', 'pending', 'razorpay', NULL, NULL, NULL, '2026-06-18 06:15:37', '2026-06-18 06:15:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `monthly_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `yearly_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `trial_days` int NOT NULL DEFAULT '0',
  `max_users` int NOT NULL DEFAULT '1',
  `is_unlimited_users` tinyint(1) NOT NULL DEFAULT '0',
  `max_contacts` int NOT NULL DEFAULT '0',
  `is_unlimited_contacts` tinyint(1) NOT NULL DEFAULT '0',
  `max_whatsapp_numbers` int NOT NULL DEFAULT '0',
  `is_unlimited_whatsapp_numbers` tinyint(1) NOT NULL DEFAULT '0',
  `max_campaigns_per_month` int NOT NULL DEFAULT '0',
  `is_unlimited_campaigns` tinyint(1) NOT NULL DEFAULT '0',
  `is_popular` tinyint(1) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `name`, `slug`, `description`, `monthly_price`, `yearly_price`, `trial_days`, `max_users`, `is_unlimited_users`, `max_contacts`, `is_unlimited_contacts`, `max_whatsapp_numbers`, `is_unlimited_whatsapp_numbers`, `max_campaigns_per_month`, `is_unlimited_campaigns`, `is_popular`, `is_default`, `status`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'Starter', 'starter', 'This is our starter plan', 999.00, 9990.00, 7, 2, 0, 1000, 0, 1, 0, 5, 0, 1, 0, 'active', 1, '2026-06-12 23:18:21', '2026-06-13 00:29:51', NULL),
(3, 'Growth', 'growth', 'This is our growth plan', 2999.00, 29990.00, 14, 5, 0, 10000, 0, 3, 0, 50, 0, 1, 1, 'active', 2, '2026-06-12 23:19:49', '2026-06-13 00:30:09', NULL),
(4, 'Agency', 'agency', 'This is our agency plan', 9999.00, 99990.00, 30, 1, 0, 0, 1, 0, 1, 0, 1, 1, 0, 'active', 3, '2026-06-12 23:20:55', '2026-06-13 00:28:50', NULL),
(5, 'Trial', 'trial', 'Trial', 0.00, 0.00, 7, 2, 0, 100, 0, 0, 0, 10, 0, 0, 0, 'active', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2026-06-09 06:18:46', '2026-06-09 06:18:46'),
(2, 'Vendor Owner', 'web', '2026-06-09 06:18:46', '2026-06-09 06:18:46'),
(3, 'Vendor Manager', 'web', '2026-06-09 06:18:46', '2026-06-09 06:18:46'),
(4, 'Vendor User', 'web', '2026-06-09 06:18:46', '2026-06-09 06:18:46');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('E6sFyMGHqwnma7YegCoXJ8P8R62EuEKXbF6SYwsb', NULL, '2a03:2880:10ff:4::', 'facebookexternalua', 'eyJfdG9rZW4iOiJNQU9xMHlRbHNwTUV0U2YxbkhwZDhUYzNCa2Y2RHFYdEJjaWhsZlQxIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1786603777),
('eCjSyvHCw7YXtEjsDxcQWyiYDu5ZCrcASBwA5ZnA', NULL, '2a03:2880:10ff:4d::', 'facebookexternalua', 'eyJfdG9rZW4iOiJhajVRYjBudkNMMXFPbk5YRGRzb1hOTVduaUFiU09XbFlzVm5KamhzIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1786603777),
('jBebODVeGj80vbHfzMDrkHrF4kI3DsfGANfxVuHJ', 1, '2402:a00:142:b016:3c65:c7f3:840f:7a72', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJtbWZGazY3QU96ekdKNUNpUmZ4eUhuQ1dTOGdZVk52S2E3UGg3SDZUIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC93YW50cml4Lm5ld3ZhY2hocmFqLmNvbVwvYXBwXC9pbmJveCIsInJvdXRlIjoidmVuZG9yLmluYm94In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1786606201),
('pzvkcGdvEvcJjqBqk7yQOg8IBhXom2411UtJUeu5', NULL, '2a03:2880:21ff:4d::', 'facebookexternalua', 'eyJfdG9rZW4iOiJzdFVWck94ak9jUk02Nkw2RTQ4UUROYncyV2RQTGhWOURxeVZ1N1loIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1786604534),
('ZUuzOlpnfIRe4FulRlFuql4BnSbhZZ81vRlxfUVQ', NULL, '2a03:2880:10ff:1c::', 'facebookexternalua', 'eyJfdG9rZW4iOiJBdFFvSWZYRndqMWI3QjFXS09TcWJpMEI0QzlHU2JEbVJPNDgzY05vIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1786603777);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `plan_id` bigint UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'trial',
  `starts_at` timestamp NOT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `vendor_id`, `plan_id`, `status`, `starts_at`, `ends_at`, `trial_ends_at`, `cancelled_at`, `created_by`, `created_at`, `updated_at`, `deleted_at`, `notes`) VALUES
(7, 1, 2, 'active', '2026-06-18 07:35:29', '2026-08-29 07:35:40', NULL, '2026-07-18 07:35:48', 1, '2026-06-18 02:06:02', '2026-06-18 02:06:02', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subscription_changes`
--

CREATE TABLE `subscription_changes` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `old_subscription_id` bigint UNSIGNED DEFAULT NULL,
  `new_subscription_id` bigint UNSIGNED DEFAULT NULL,
  `old_plan_id` bigint UNSIGNED DEFAULT NULL,
  `new_plan_id` bigint UNSIGNED DEFAULT NULL,
  `change_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'amber',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `vendor_id`, `name`, `color`, `created_at`, `updated_at`) VALUES
(1, 1, 'Lead', 'purple', '2026-06-24 00:54:47', '2026-06-24 00:54:47'),
(2, 1, 'Customer', 'green', '2026-06-24 00:55:03', '2026-06-24 00:55:03'),
(3, 1, 'VIP', 'blue', '2026-06-24 01:14:25', '2026-06-24 01:14:25');

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'marketing',
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`id`, `vendor_id`, `name`, `category`, `body`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 1, 'Happy Deewali  2026', 'utility', 'Hello {{name}}, your appointment is scheduled for {{phone}}.', 1, '2026-06-24 05:08:40', '2026-06-24 05:08:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'owner',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `vendor_id`, `role`, `is_active`, `avatar`, `status`, `last_login_at`, `name`, `email`, `phone`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'owner', 1, 'avatars/01KTQYH7WZF2KAJ6RNDWPJGY39.png', 'active', NULL, 'Bhavin Shah', 'info@antrixwebsolution.com', NULL, NULL, '$2y$12$ihTI82xQkOVjW/mmTPO3PeGIbfZeQV5atuRs2UTIMpqz1cq5hcHfu', NULL, '2026-06-09 06:00:10', '2026-06-10 02:25:28'),
(6, 1, 'manager', 1, NULL, 'active', NULL, 'test', 'test@test.com', NULL, NULL, '$2y$12$EpUNFDFvaf0IlwhOsSz2FuD/GGOgdpTGww2KEzRQojrQ9fO9Tfniu', NULL, '2026-06-26 03:14:04', '2026-06-26 03:14:04');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asia/Kolkata'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `slug`, `email`, `phone`, `address`, `city`, `state`, `country`, `logo`, `status`, `created_by`, `created_at`, `updated_at`, `deleted_at`, `website`, `postal_code`, `gst_number`, `timezone`) VALUES
(1, 'Antrix Web Solution', 'antrix-web-solution', 'antrixwebsolution@gmail.com', '07405217574', 'Tarsali', 'Vadodara', 'Gujarat', 'India', 'vendor-logos/HC3pKt9HL4fLTHNOm9QCntvcMokJgog0gSoqobFf.png', 'pending', NULL, '2026-06-09 06:59:31', '2026-06-19 00:30:51', NULL, 'https://antrixwebsolution.com', '390009', 'ABCD1234', 'Asia/Kolkata'),
(16, 'Veeha Industries', 'veeha-industries', 'veehaindustries@gmail.com', '07405217574', 'Tarsali', 'Vadodara', 'Gujarat', 'India', 'vendor-logos/HC3pKt9HL4fLTHNOm9QCntvcMokJgog0gSoqobFf.png', 'pending', NULL, '2026-06-09 06:59:31', '2026-06-19 00:30:51', NULL, 'https://veehaindustries.com', '390009', 'ABCD1234', 'Asia/Kolkata');

-- --------------------------------------------------------

--
-- Table structure for table `webhook_logs`
--

CREATE TABLE `webhook_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'meta',
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` json NOT NULL,
  `processed` tinyint(1) NOT NULL DEFAULT '0',
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `webhook_logs`
--

INSERT INTO `webhook_logs` (`id`, `provider`, `event`, `payload`, `processed`, `processed_at`, `created_at`, `updated_at`) VALUES
(1, 'meta', 'unknown', '{\"test\": true}', 0, NULL, '2026-08-07 10:38:55', '2026-08-07 10:38:55'),
(2, 'meta', 'messages', '{\"entry\": [{\"id\": \"TEST-WABA-ID\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"919999999999\", \"profile\": {\"name\": \"Webhook Test Customer\"}}], \"messages\": [{\"id\": \"wamid.TEST-20260807155344509\", \"from\": \"919999999999\", \"text\": {\"body\": \"Hello from Meta Webhook Test\"}, \"type\": \"text\", \"timestamp\": \"1786098224\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"TEST\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-07 10:39:17', '2026-08-07 10:39:17'),
(3, 'meta', 'messages', '{\"entry\": [{\"id\": \"TEST-WABA-ID\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"919999999999\", \"profile\": {\"name\": \"Webhook Test Customer\"}}], \"messages\": [{\"id\": \"wamid.TEST-20260807155344509\", \"from\": \"919999999999\", \"text\": {\"body\": \"Hello from Meta Webhook Test\"}, \"type\": \"text\", \"timestamp\": \"1786098224\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"TEST\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-07 10:39:53', '2026-08-07 10:39:53'),
(4, 'meta', 'messages', '{\"entry\": [{\"id\": \"TEST-WABA-ID\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"919999999999\", \"profile\": {\"name\": \"Webhook Test Customer\"}}], \"messages\": [{\"id\": \"wamid.TEST-20260807161200851\", \"from\": \"919999999999\", \"text\": {\"body\": \"Hello from Meta Webhook Test\"}, \"type\": \"text\", \"timestamp\": \"1786099320\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"TEST\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-07 10:44:24', '2026-08-07 10:44:24'),
(5, 'meta', 'unknown', '[]', 0, NULL, '2026-08-08 12:35:09', '2026-08-08 12:35:09'),
(6, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTU2MzgzMzZCM0ZBQTVDMDdCNTMxRTcyRDFGQzlDMTcA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Are\"}, \"type\": \"text\", \"timestamp\": \"1786343578\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 06:32:59', '2026-08-10 06:32:59'),
(7, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTVEMDEwNUZCRkFCMjZDQzI0OTBCREZDQUYxNDAxRjAA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Ha bhai\"}, \"type\": \"text\", \"timestamp\": \"1786343637\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 06:33:57', '2026-08-10 06:33:57'),
(8, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTU4RjNCQTMyQkNBOUQ0QzRBN0I4OTRFNTQyNDRBOEQA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Hi\"}, \"type\": \"text\", \"timestamp\": \"1786344394\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 06:46:34', '2026-08-10 06:46:34'),
(9, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTU2MjMzMjg4NUVERTc3RUVBNjA4NzdFMTcyOEQyODEA\", \"from\": \"917405217574\", \"text\": {\"body\": \"A\"}, \"type\": \"text\", \"timestamp\": \"1786344822\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 06:53:42', '2026-08-10 06:53:42'),
(10, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTUyQkVBQzkwREM5NzdCMkFCOTY3MDMzNkQwNzYwRTEA\", \"from\": \"917405217574\", \"text\": {\"body\": \"B\"}, \"type\": \"text\", \"timestamp\": \"1786344917\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 06:55:17', '2026-08-10 06:55:17'),
(11, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTUyQjgxOEQ2NThDNDgzOTA4RDk3OERDMEU4RDNBMDEA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Hi\"}, \"type\": \"text\", \"timestamp\": \"1786345047\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 06:57:26', '2026-08-10 06:57:26'),
(12, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTUyMkU0REYyN0VBRDFEMUQzRDc4RUQxMDJCOEIzMkUA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Hi\"}, \"type\": \"text\", \"timestamp\": \"1786345254\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 07:00:54', '2026-08-10 07:00:54'),
(13, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTUxOUI1MERBRDI3QTYwNEJGOEM3NTQ3NzgxQ0I0QjMA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Hi\"}, \"type\": \"text\", \"timestamp\": \"1786346322\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 07:18:43', '2026-08-10 07:18:43'),
(14, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTU4NTZBMzUzQzkzQUI2ODRFOTZFMTcyRDY1MDY0RDMA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Hello\"}, \"type\": \"text\", \"timestamp\": \"1786346341\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 07:19:01', '2026-08-10 07:19:01'),
(15, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTVCMjc3NDcwMEE3RENERkRGMzQ2OTVCRTAyODRDQjcA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Hi\"}, \"type\": \"text\", \"timestamp\": \"1786346391\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 07:19:51', '2026-08-10 07:19:51'),
(16, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTUyQTNBNzRGN0MwOEZBN0Q4NjYxRjFFNTQwMDMxM0QA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Phi\"}, \"type\": \"text\", \"timestamp\": \"1786346426\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 07:20:25', '2026-08-10 07:20:25'),
(17, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTU2NTVGREUzMTU2MEU0RTc0REQ3NjA5QjkwNkE0RkYA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Hi\"}, \"type\": \"text\", \"timestamp\": \"1786346530\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 07:22:10', '2026-08-10 07:22:10'),
(18, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTVBQkJCQzNDNUVGODNGMEU0MzAxRTlBMTgyMkEyNTAA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Hi again\"}, \"type\": \"text\", \"timestamp\": \"1786346807\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 07:26:47', '2026-08-10 07:26:47'),
(19, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTU4NEJDOTAyNzk5RkE1QjBFQUVDNkY5M0MxNjkzNzEA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Hi again\"}, \"type\": \"text\", \"timestamp\": \"1786346821\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 07:27:01', '2026-08-10 07:27:01'),
(20, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSQ0M1QUY0M0IyREZENEU0Mjk5AA==\", \"status\": \"sent\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786346849\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 07:27:28', '2026-08-10 07:27:28'),
(21, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSQ0M1QUY0M0IyREZENEU0Mjk5AA==\", \"status\": \"delivered\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786346849\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 07:27:29', '2026-08-10 07:27:29'),
(22, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSQ0M1QUY0M0IyREZENEU0Mjk5AA==\", \"status\": \"read\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786346849\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 07:27:29', '2026-08-10 07:27:29'),
(23, 'meta', 'messages', '{\"entry\": [{\"id\": \"309700228901620\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"342651388928989\", \"display_phone_number\": \"15550547578\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSQ0M1QUY0M0IyREZENEU0Mjk5AA==\", \"status\": \"delivered\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786346849\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-10 07:27:30', '2026-08-10 07:27:30'),
(24, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786432916, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"MM_LITE_TERMS_SIGNED\", \"waba_info\": {\"waba_id\": \"2254805258702333\", \"owner_business_id\": \"1046710203176415\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 07:21:58', '2026-08-11 07:21:58'),
(25, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786432916, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"PARTNER_APP_INSTALLED\", \"waba_info\": {\"waba_id\": \"2254805258702333\", \"partner_app_id\": \"1384333156405246\", \"owner_business_id\": \"1046710203176415\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 07:21:58', '2026-08-11 07:21:58'),
(26, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786434280, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"MM_LITE_TERMS_SIGNED\", \"waba_info\": {\"waba_id\": \"2254805258702333\", \"owner_business_id\": \"1046710203176415\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 07:44:41', '2026-08-11 07:44:41'),
(27, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786434280, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"PARTNER_APP_INSTALLED\", \"waba_info\": {\"waba_id\": \"2254805258702333\", \"partner_app_id\": \"1384333156405246\", \"owner_business_id\": \"1046710203176415\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 07:44:41', '2026-08-11 07:44:41'),
(28, 'meta', 'account_update', '{\"entry\": [{\"id\": \"2254805258702333\", \"time\": 1786434343, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"PHONE_NUMBER_REMOVED\", \"phone_number\": \"15553003118\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 07:45:45', '2026-08-11 07:45:45'),
(29, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786434865, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"MM_LITE_TERMS_SIGNED\", \"waba_info\": {\"waba_id\": \"2497979430654027\", \"owner_business_id\": \"586761670830309\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 07:54:26', '2026-08-11 07:54:26'),
(30, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786434865, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"PARTNER_APP_INSTALLED\", \"waba_info\": {\"waba_id\": \"2497979430654027\", \"partner_app_id\": \"1384333156405246\", \"owner_business_id\": \"586761670830309\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 07:54:58', '2026-08-11 07:54:58'),
(31, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786435200, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"PARTNER_APP_INSTALLED\", \"waba_info\": {\"waba_id\": \"2497979430654027\", \"partner_app_id\": \"1384333156405246\", \"owner_business_id\": \"586761670830309\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 08:00:01', '2026-08-11 08:00:01'),
(32, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786435200, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"MM_LITE_TERMS_SIGNED\", \"waba_info\": {\"waba_id\": \"2497979430654027\", \"owner_business_id\": \"586761670830309\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 08:00:01', '2026-08-11 08:00:01'),
(33, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786435638, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"PARTNER_APP_INSTALLED\", \"waba_info\": {\"waba_id\": \"996920489452823\", \"partner_app_id\": \"1384333156405246\", \"owner_business_id\": \"586761670830309\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 08:07:20', '2026-08-11 08:07:20'),
(34, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786435638, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"MM_LITE_TERMS_SIGNED\", \"waba_info\": {\"waba_id\": \"996920489452823\", \"owner_business_id\": \"586761670830309\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 08:07:20', '2026-08-11 08:07:20'),
(35, 'meta', 'account_update', '{\"entry\": [{\"id\": \"2254805258702333\", \"time\": 1786437997, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"ACCOUNT_DELETED\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 08:46:39', '2026-08-11 08:46:39'),
(36, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786438923, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"PARTNER_APP_INSTALLED\", \"waba_info\": {\"waba_id\": \"1328787579337421\", \"partner_app_id\": \"1384333156405246\", \"owner_business_id\": \"1046710203176415\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 09:02:04', '2026-08-11 09:02:04'),
(37, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786438923, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"MM_LITE_TERMS_SIGNED\", \"waba_info\": {\"waba_id\": \"1328787579337421\", \"owner_business_id\": \"1046710203176415\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 09:02:04', '2026-08-11 09:02:04'),
(38, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786440693, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"PARTNER_APP_INSTALLED\", \"waba_info\": {\"waba_id\": \"2497979430654027\", \"partner_app_id\": \"1384333156405246\", \"owner_business_id\": \"586761670830309\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 09:31:34', '2026-08-11 09:31:34'),
(39, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786440693, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"MM_LITE_TERMS_SIGNED\", \"waba_info\": {\"waba_id\": \"2497979430654027\", \"owner_business_id\": \"586761670830309\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 09:31:35', '2026-08-11 09:31:35'),
(40, 'meta', 'messages', '{\"entry\": [{\"id\": \"2497979430654027\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.954310964075376\"}], \"metadata\": {\"phone_number_id\": \"1150708964791540\", \"display_phone_number\": \"15559808477\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSQjc2NDJGODhCODUxNzFFQTcxAA==\", \"errors\": [{\"code\": 131047, \"href\": \"https://developers.facebook.com/docs/whatsapp/cloud-api/support/error-codes/\", \"title\": \"Re-engagement message\", \"message\": \"Re-engagement message\", \"error_data\": {\"details\": \"Message failed to send because more than 24 hours have passed since the customer last replied to this number.\"}}], \"status\": \"failed\", \"timestamp\": \"1786443244\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.954310964075376\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 10:14:05', '2026-08-11 10:14:05'),
(41, 'meta', 'messages', '{\"entry\": [{\"id\": \"2497979430654027\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.954310964075376\"}], \"metadata\": {\"phone_number_id\": \"1150708964791540\", \"display_phone_number\": \"15559808477\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRDg1QTBFMzFCNkY1OUUwNzMyAA==\", \"errors\": [{\"code\": 131047, \"href\": \"https://developers.facebook.com/docs/whatsapp/cloud-api/support/error-codes/\", \"title\": \"Re-engagement message\", \"message\": \"Re-engagement message\", \"error_data\": {\"details\": \"Message failed to send because more than 24 hours have passed since the customer last replied to this number.\"}}], \"status\": \"failed\", \"timestamp\": \"1786443298\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.954310964075376\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 10:14:59', '2026-08-11 10:14:59'),
(42, 'meta', 'messages', '{\"entry\": [{\"id\": \"2497979430654027\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.954310964075376\"}], \"metadata\": {\"phone_number_id\": \"1150708964791540\", \"display_phone_number\": \"15559808477\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSMjAzQTJCOUQwMjZERUQ3NTg3AA==\", \"errors\": [{\"code\": 131047, \"href\": \"https://developers.facebook.com/docs/whatsapp/cloud-api/support/error-codes/\", \"title\": \"Re-engagement message\", \"message\": \"Re-engagement message\", \"error_data\": {\"details\": \"Message failed to send because more than 24 hours have passed since the customer last replied to this number.\"}}], \"status\": \"failed\", \"timestamp\": \"1786444068\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.954310964075376\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 10:27:50', '2026-08-11 10:27:50'),
(43, 'meta', 'messages', '{\"entry\": [{\"id\": \"2497979430654027\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.954310964075376\"}], \"metadata\": {\"phone_number_id\": \"1150708964791540\", \"display_phone_number\": \"15559808477\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSQkE2NTc1N0JDNTNCQUJFMjFEAA==\", \"errors\": [{\"code\": 131047, \"href\": \"https://developers.facebook.com/docs/whatsapp/cloud-api/support/error-codes/\", \"title\": \"Re-engagement message\", \"message\": \"Re-engagement message\", \"error_data\": {\"details\": \"Message failed to send because more than 24 hours have passed since the customer last replied to this number.\"}}], \"status\": \"failed\", \"timestamp\": \"1786444569\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.954310964075376\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 10:36:10', '2026-08-11 10:36:10'),
(44, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786445427, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"PARTNER_APP_INSTALLED\", \"waba_info\": {\"waba_id\": \"1328787579337421\", \"partner_app_id\": \"1384333156405246\", \"owner_business_id\": \"1046710203176415\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 10:50:28', '2026-08-11 10:50:28'),
(45, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786445427, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"MM_LITE_TERMS_SIGNED\", \"waba_info\": {\"waba_id\": \"1328787579337421\", \"owner_business_id\": \"1046710203176415\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 10:50:28', '2026-08-11 10:50:28'),
(46, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSMDk2QTNCOTEzOTYxMzUyOUM5AA==\", \"errors\": [{\"code\": 131047, \"href\": \"https://developers.facebook.com/docs/whatsapp/cloud-api/support/error-codes/\", \"title\": \"Re-engagement message\", \"message\": \"Re-engagement message\", \"error_data\": {\"details\": \"Message failed to send because more than 24 hours have passed since the customer last replied to this number.\"}}], \"status\": \"failed\", \"timestamp\": \"1786451704\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 12:35:05', '2026-08-11 12:35:05'),
(47, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSNzlBRTI5RkRBOEE5MzY5NUY1AA==\", \"errors\": [{\"code\": 131047, \"href\": \"https://developers.facebook.com/docs/whatsapp/cloud-api/support/error-codes/\", \"title\": \"Re-engagement message\", \"message\": \"Re-engagement message\", \"error_data\": {\"details\": \"Message failed to send because more than 24 hours have passed since the customer last replied to this number.\"}}], \"status\": \"failed\", \"timestamp\": \"1786451992\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 12:39:53', '2026-08-11 12:39:53'),
(48, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSNzcxRjE2RkFEQzRDRDIyQjQxAA==\", \"errors\": [{\"code\": 131047, \"href\": \"https://developers.facebook.com/docs/whatsapp/cloud-api/support/error-codes/\", \"title\": \"Re-engagement message\", \"message\": \"Re-engagement message\", \"error_data\": {\"details\": \"Message failed to send because more than 24 hours have passed since the customer last replied to this number.\"}}], \"status\": \"failed\", \"timestamp\": \"1786452043\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 12:40:44', '2026-08-11 12:40:44'),
(49, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhgWM0VCMDM5OEI5MjZBQzVDQTUzQUM3RAA=\", \"from\": \"917405217574\", \"text\": {\"body\": \"Hi\"}, \"type\": \"text\", \"timestamp\": \"1786452315\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 12:45:16', '2026-08-11 12:45:16'),
(50, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRUYxOEIwOUIyOUI1OTA4RkEzAA==\", \"status\": \"sent\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786452349\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 12:45:50', '2026-08-11 12:45:50'),
(51, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRUYxOEIwOUIyOUI1OTA4RkEzAA==\", \"status\": \"delivered\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786452350\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 12:45:51', '2026-08-11 12:45:51'),
(52, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRUYxOEIwOUIyOUI1OTA4RkEzAA==\", \"status\": \"delivered\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786452350\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 12:45:51', '2026-08-11 12:45:51'),
(53, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRUYxOEIwOUIyOUI1OTA4RkEzAA==\", \"status\": \"read\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786452354\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-11 12:45:55', '2026-08-11 12:45:55'),
(54, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTUzRTk5ODZBNjEwN0U2M0ZGMkRFQTEwMUQxMkI1NzUA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Hello\"}, \"type\": \"text\", \"timestamp\": \"1786509259\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 04:34:22', '2026-08-12 04:34:22'),
(55, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhgWM0VCMDI0NjQ1MDUxMkYzN0Q3QTlFRAA=\", \"from\": \"917405217574\", \"text\": {\"body\": \"o\"}, \"type\": \"text\", \"timestamp\": \"1786510540\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 04:55:42', '2026-08-12 04:55:42'),
(56, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhgWM0VCMDYyOTRDQTVCQjE4QzczMEM2MQA=\", \"from\": \"917405217574\", \"text\": {\"body\": \"a\"}, \"type\": \"text\", \"timestamp\": \"1786510647\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 04:57:28', '2026-08-12 04:57:28'),
(57, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTU1QTNGNzE5QzFFQzA2NjQzQUVGMzdEQzNBMEU3NkUA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Hi\"}, \"type\": \"text\", \"timestamp\": \"1786510904\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 05:01:45', '2026-08-12 05:01:45'),
(58, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786531708, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"MM_LITE_TERMS_SIGNED\", \"waba_info\": {\"waba_id\": \"1328787579337421\", \"owner_business_id\": \"1046710203176415\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 10:48:28', '2026-08-12 10:48:28'),
(59, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786531708, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"PARTNER_APP_INSTALLED\", \"waba_info\": {\"waba_id\": \"1328787579337421\", \"partner_app_id\": \"1384333156405246\", \"owner_business_id\": \"1046710203176415\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 10:48:28', '2026-08-12 10:48:28'),
(60, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhgWM0VCMDQ1NEJCNThDMDIxOUFCRjMwOAA=\", \"from\": \"917405217574\", \"text\": {\"body\": \"hello\"}, \"type\": \"text\", \"timestamp\": \"1786532281\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 10:58:03', '2026-08-12 10:58:03'),
(61, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786533853, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"PARTNER_APP_INSTALLED\", \"waba_info\": {\"waba_id\": \"1328787579337421\", \"partner_app_id\": \"1384333156405246\", \"owner_business_id\": \"1046710203176415\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 11:24:13', '2026-08-12 11:24:13'),
(62, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786533853, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"MM_LITE_TERMS_SIGNED\", \"waba_info\": {\"waba_id\": \"1328787579337421\", \"owner_business_id\": \"1046710203176415\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 11:24:13', '2026-08-12 11:24:13'),
(63, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786534412, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"MM_LITE_TERMS_SIGNED\", \"waba_info\": {\"waba_id\": \"1328787579337421\", \"owner_business_id\": \"1046710203176415\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 11:33:33', '2026-08-12 11:33:33'),
(64, 'meta', 'account_update', '{\"entry\": [{\"id\": \"1046710203176415\", \"time\": 1786534412, \"changes\": [{\"field\": \"account_update\", \"value\": {\"event\": \"PARTNER_APP_INSTALLED\", \"waba_info\": {\"waba_id\": \"1328787579337421\", \"partner_app_id\": \"1384333156405246\", \"owner_business_id\": \"1046710203176415\"}}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 11:33:33', '2026-08-12 11:33:33'),
(65, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhgWM0VCMDA3NzNCRUQwNjlEMzMxRTYzMQA=\", \"from\": \"917405217574\", \"text\": {\"body\": \"hi\"}, \"type\": \"text\", \"timestamp\": \"1786534624\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 11:37:05', '2026-08-12 11:37:05'),
(66, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhgWM0VCMDVDOUZBRjMyQ0NFNzExOTVCQQA=\", \"from\": \"917405217574\", \"text\": {\"body\": \"hi\"}, \"type\": \"text\", \"timestamp\": \"1786534876\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 11:41:17', '2026-08-12 11:41:17'),
(67, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSNUUwQTNDMzMwQzBCMDM2MDFDAA==\", \"status\": \"sent\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786534903\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 11:41:44', '2026-08-12 11:41:44'),
(68, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSNUUwQTNDMzMwQzBCMDM2MDFDAA==\", \"status\": \"delivered\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786534904\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 11:41:44', '2026-08-12 11:41:44'),
(69, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSNUUwQTNDMzMwQzBCMDM2MDFDAA==\", \"status\": \"delivered\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786534904\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 11:41:44', '2026-08-12 11:41:44'),
(70, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSNUUwQTNDMzMwQzBCMDM2MDFDAA==\", \"status\": \"read\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786535070\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 11:44:30', '2026-08-12 11:44:30'),
(71, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhgWM0VCMEZGNkJBMjZDNEFDQUU4MzA1RQA=\", \"from\": \"917405217574\", \"text\": {\"body\": \"b\"}, \"type\": \"text\", \"timestamp\": \"1786536880\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:14:41', '2026-08-12 12:14:41'),
(72, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSODVDMjFCMjE3MDA1NUU5NkNGAA==\", \"status\": \"sent\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786536916\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:15:16', '2026-08-12 12:15:16'),
(73, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSODVDMjFCMjE3MDA1NUU5NkNGAA==\", \"status\": \"delivered\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786536917\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:15:17', '2026-08-12 12:15:17'),
(74, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSODVDMjFCMjE3MDA1NUU5NkNGAA==\", \"status\": \"delivered\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786536917\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:15:17', '2026-08-12 12:15:17'),
(75, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRkMyQkI2RDhGRjI1Nzg4RTNCAA==\", \"status\": \"sent\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786536960\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:16:01', '2026-08-12 12:16:01');
INSERT INTO `webhook_logs` (`id`, `provider`, `event`, `payload`, `processed`, `processed_at`, `created_at`, `updated_at`) VALUES
(76, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRkMyQkI2RDhGRjI1Nzg4RTNCAA==\", \"status\": \"delivered\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786536961\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:16:01', '2026-08-12 12:16:01'),
(77, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRkMyQkI2RDhGRjI1Nzg4RTNCAA==\", \"status\": \"delivered\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786536961\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:16:01', '2026-08-12 12:16:01'),
(78, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTVGQ0Q4MEI5QjBFQThFRjFFQzNEMDlFN0VCOUI4NEYA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Aa to kevu thayu\"}, \"type\": \"text\", \"timestamp\": \"1786461552\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:19:54', '2026-08-12 12:19:54'),
(79, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSODVDMjFCMjE3MDA1NUU5NkNGAA==\", \"status\": \"read\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786537620\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:27:01', '2026-08-12 12:27:01'),
(80, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRkMyQkI2RDhGRjI1Nzg4RTNCAA==\", \"status\": \"read\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786537620\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:27:01', '2026-08-12 12:27:01'),
(81, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRDk3NEI2OEU5OEM1MjU4NERFAA==\", \"status\": \"delivered\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786537662\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:27:41', '2026-08-12 12:27:41'),
(82, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRDk3NEI2OEU5OEM1MjU4NERFAA==\", \"status\": \"sent\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786537661\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:27:42', '2026-08-12 12:27:42'),
(83, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRDk3NEI2OEU5OEM1MjU4NERFAA==\", \"status\": \"read\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786537907\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:31:47', '2026-08-12 12:31:47'),
(84, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSQjVBNjhBQ0M3QkU4ODZBMDk0AA==\", \"status\": \"sent\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786537910\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:31:51', '2026-08-12 12:31:51'),
(85, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSQjVBNjhBQ0M3QkU4ODZBMDk0AA==\", \"status\": \"delivered\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786537910\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:31:51', '2026-08-12 12:31:51'),
(86, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSQjVBNjhBQ0M3QkU4ODZBMDk0AA==\", \"status\": \"delivered\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786537910\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:31:51', '2026-08-12 12:31:51'),
(87, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSQjVBNjhBQ0M3QkU4ODZBMDk0AA==\", \"status\": \"delivered\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786537911\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:31:51', '2026-08-12 12:31:51'),
(88, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSQjVBNjhBQ0M3QkU4ODZBMDk0AA==\", \"status\": \"read\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786537910\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:31:51', '2026-08-12 12:31:51'),
(89, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTU3NTQyM0VBRjJDMEU0NDE5RkJFODMyOTQwNDQzNEIA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Zvvxgxzv\"}, \"type\": \"text\", \"timestamp\": \"1786538156\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:35:56', '2026-08-12 12:35:56'),
(90, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTU3NkNGODc1QUFEQjY3RTc4OUExQkQ4QURDMDMwNDkA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Gsgsgs\"}, \"type\": \"text\", \"timestamp\": \"1786538244\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:37:24', '2026-08-12 12:37:24'),
(91, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"profile\": {\"name\": \"Antrix Web Solution\"}, \"user_id\": \"IN.1697250741455688\"}], \"messages\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAEhggQTU3MzU1QUMxN0YzM0ZBQUU5MkJFQjM5QUI4QkVCQUIA\", \"from\": \"917405217574\", \"text\": {\"body\": \"Gdgxgd\"}, \"type\": \"text\", \"timestamp\": \"1786538269\", \"from_user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-12 12:37:50', '2026-08-12 12:37:50'),
(92, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRDQ5QTVFOTg3MjEyRDZEQUQ4AA==\", \"status\": \"sent\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786603776\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-13 06:49:37', '2026-08-13 06:49:37'),
(93, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRDQ5QTVFOTg3MjEyRDZEQUQ4AA==\", \"status\": \"delivered\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786603777\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-13 06:49:37', '2026-08-13 06:49:37'),
(94, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRDQ5QTVFOTg3MjEyRDZEQUQ4AA==\", \"status\": \"delivered\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786603777\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-13 06:49:37', '2026-08-13 06:49:37'),
(95, 'meta', 'messages', '{\"entry\": [{\"id\": \"1328787579337421\", \"changes\": [{\"field\": \"messages\", \"value\": {\"contacts\": [{\"wa_id\": \"917405217574\", \"user_id\": \"IN.1697250741455688\"}], \"metadata\": {\"phone_number_id\": \"1265098370017641\", \"display_phone_number\": \"15554321047\"}, \"statuses\": [{\"id\": \"wamid.HBgMOTE3NDA1MjE3NTc0FQIAERgSRDQ5QTVFOTg3MjEyRDZEQUQ4AA==\", \"status\": \"read\", \"pricing\": {\"type\": \"free_customer_service\", \"billable\": false, \"category\": \"service\", \"pricing_model\": \"PMP\"}, \"timestamp\": \"1786604534\", \"recipient_id\": \"917405217574\", \"recipient_user_id\": \"IN.1697250741455688\"}], \"messaging_product\": \"whatsapp\"}}]}], \"object\": \"whatsapp_business_account\"}', 0, NULL, '2026-08-13 07:02:14', '2026-08-13 07:02:14');

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_accounts`
--

CREATE TABLE `whatsapp_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'meta',
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '91',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `waba_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quality_rating` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_token` longtext COLLATE utf8mb4_unicode_ci,
  `webhook_verify_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `business_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `connected_at` timestamp NULL DEFAULT NULL,
  `last_verified_at` timestamp NULL DEFAULT NULL,
  `last_error` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `whatsapp_accounts`
--

INSERT INTO `whatsapp_accounts` (`id`, `vendor_id`, `name`, `phone_number`, `provider`, `country_code`, `status`, `waba_id`, `phone_number_id`, `display_phone_number`, `verified_name`, `quality_rating`, `verification_status`, `access_token`, `webhook_verify_token`, `metadata`, `is_active`, `business_id`, `connected_at`, `last_verified_at`, `last_error`, `created_at`, `updated_at`) VALUES
(15, 1, 'antrixweb', '+1 555-432-1047', 'meta', '91', 'connected', '1328787579337421', '1265098370017641', '+1 555-432-1047', 'antrixweb', 'GREEN', 'VERIFIED', 'eyJpdiI6Ik14YlkwMWJVTzNVcG9XY1pvVmVKRUE9PSIsInZhbHVlIjoiK3ZuY1hENXBNcVc3QVZZeSsrbW5HT1J2Rzc1alB5am9hV1FiTEFuYTV5b2xuSTU1WHVaMTREVDZZL2szRDhrMDVOVk0reXhZU1NkMTFOU1U4Tm9JdS9HVmt1ZlMzTUpVb3ZhUWt4NlJZbkRhUUVWWkxnTWQwZlduZXFyWmVKMWVldjMwV3UvdFBScGVUalRxbFdHQ3cwd3hzSTc3MnB4Z0FaajR0c3gybSs0RWl2bGoyL2cybUY0bmRGTUJkZEFaUm80dnZPa2xKWlJBRFBZb0pHRnMxT2hjUUE1Sm5ERGp2UXBmY3AwSTBRK1FUVTN1VjdpSWU4WnlCdllCRE0xQlZuWDJ6UnMvQXhSaU9WOEJlYlJ0Q1YrQ0ZaUzBnTFNwQVYrQ25RUDVpNFpZaGNwUlZHUUQ4M3gxU3ovaHBTckxXVGpOWk5qVmtnR1V0NzR6WjdpU3Z4QkhUaFExek11Ri9oOGg0UFRydnRrR29QMFdoblpEeHFCRUVUb0IwdE9jbzR4cXdKbnZSalJ6QUNPeGpET1JzMDZRYlQ5VVM4YlZFWjZUYjZRbTVlUmxybERDejNWRDh6bW5DdlRqeUMvUHlvMCtKZlVTK0RqMkViMERmYXhmelE9PSIsIm1hYyI6ImM3ODg0ZjlkZjYxNjk4MDJmZmVhMDM4ZmU5YjQ4YTc5YmUyMmQxZDlhNGM5OTkxMjgxMGQ1MjQ2NmQ4ZDQ4ZWMiLCJ0YWciOiIifQ==', NULL, NULL, 1, '1046710203176415', NULL, '2026-08-12 11:33:43', NULL, '2026-08-11 10:50:39', '2026-08-12 11:33:43');

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_templates`
--

CREATE TABLE `whatsapp_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `meta_template_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quality_score` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allow_category_change` tinyint(1) NOT NULL DEFAULT '0',
  `components` json DEFAULT NULL,
  `raw_response` json DEFAULT NULL,
  `last_synced_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaigns_vendor_id_foreign` (`vendor_id`),
  ADD KEY `campaigns_group_id_foreign` (`group_id`),
  ADD KEY `campaigns_template_id_foreign` (`template_id`),
  ADD KEY `campaigns_whatsapp_account_id_foreign` (`whatsapp_account_id`),
  ADD KEY `campaigns_media_library_id_foreign` (`media_library_id`);

--
-- Indexes for table `campaign_activities`
--
ALTER TABLE `campaign_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_activities_campaign_id_foreign` (`campaign_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `contact_group`
--
ALTER TABLE `contact_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_group_contact_id_foreign` (`contact_id`),
  ADD KEY `contact_group_group_id_foreign` (`group_id`);

--
-- Indexes for table `contact_tag`
--
ALTER TABLE `contact_tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_tag_contact_id_foreign` (`contact_id`),
  ADD KEY `contact_tag_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `conversations_vendor_id_contact_id_whatsapp_account_id_unique` (`vendor_id`,`contact_id`,`whatsapp_account_id`),
  ADD KEY `conversations_contact_id_foreign` (`contact_id`),
  ADD KEY `conversations_whatsapp_account_id_foreign` (`whatsapp_account_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `groups_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `incoming_messages`
--
ALTER TABLE `incoming_messages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `incoming_messages_provider_message_id_unique` (`provider_message_id`),
  ADD UNIQUE KEY `incoming_messages_account_provider_unique` (`whatsapp_account_id`,`provider_message_id`),
  ADD KEY `incoming_messages_vendor_id_foreign` (`vendor_id`),
  ADD KEY `incoming_messages_contact_id_foreign` (`contact_id`),
  ADD KEY `incoming_messages_media_library_id_foreign` (`media_library_id`),
  ADD KEY `incoming_messages_conversation_id_index` (`conversation_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_vendor_id_foreign` (`vendor_id`),
  ADD KEY `invoices_subscription_id_foreign` (`subscription_id`);

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
-- Indexes for table `media_libraries`
--
ALTER TABLE `media_libraries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_libraries_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_vendor_id_foreign` (`vendor_id`),
  ADD KEY `messages_campaign_id_foreign` (`campaign_id`),
  ADD KEY `messages_contact_id_foreign` (`contact_id`),
  ADD KEY `messages_whatsapp_account_id_foreign` (`whatsapp_account_id`),
  ADD KEY `messages_whatsapp_template_id_foreign` (`whatsapp_template_id`),
  ADD KEY `messages_media_library_id_foreign` (`media_library_id`),
  ADD KEY `messages_conversation_id_foreign` (`conversation_id`);

--
-- Indexes for table `message_status_histories`
--
ALTER TABLE `message_status_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_status_histories_message_id_changed_at_index` (`message_id`,`changed_at`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

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
  ADD KEY `payments_vendor_id_foreign` (`vendor_id`),
  ADD KEY `payments_subscription_id_foreign` (`subscription_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plans_slug_unique` (`slug`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_vendor_id_foreign` (`vendor_id`),
  ADD KEY `subscriptions_plan_id_foreign` (`plan_id`),
  ADD KEY `subscriptions_created_by_foreign` (`created_by`);

--
-- Indexes for table `subscription_changes`
--
ALTER TABLE `subscription_changes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_changes_vendor_id_foreign` (`vendor_id`),
  ADD KEY `subscription_changes_old_subscription_id_foreign` (`old_subscription_id`),
  ADD KEY `subscription_changes_new_subscription_id_foreign` (`new_subscription_id`),
  ADD KEY `subscription_changes_old_plan_id_foreign` (`old_plan_id`),
  ADD KEY `subscription_changes_new_plan_id_foreign` (`new_plan_id`),
  ADD KEY `subscription_changes_created_by_foreign` (`created_by`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tags_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `templates_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendors_slug_unique` (`slug`),
  ADD KEY `vendors_created_by_foreign` (`created_by`);

--
-- Indexes for table `webhook_logs`
--
ALTER TABLE `webhook_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `whatsapp_accounts`
--
ALTER TABLE `whatsapp_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `whatsapp_accounts_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `whatsapp_templates`
--
ALTER TABLE `whatsapp_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `whatsapp_templates_vendor_id_foreign` (`vendor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `campaign_activities`
--
ALTER TABLE `campaign_activities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `contact_group`
--
ALTER TABLE `contact_group`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contact_tag`
--
ALTER TABLE `contact_tag`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `incoming_messages`
--
ALTER TABLE `incoming_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `media_libraries`
--
ALTER TABLE `media_libraries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=865;

--
-- AUTO_INCREMENT for table `message_status_histories`
--
ALTER TABLE `message_status_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `subscription_changes`
--
ALTER TABLE `subscription_changes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `webhook_logs`
--
ALTER TABLE `webhook_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `whatsapp_accounts`
--
ALTER TABLE `whatsapp_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `whatsapp_templates`
--
ALTER TABLE `whatsapp_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD CONSTRAINT `campaigns_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campaigns_media_library_id_foreign` FOREIGN KEY (`media_library_id`) REFERENCES `media_libraries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `campaigns_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `templates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campaigns_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campaigns_whatsapp_account_id_foreign` FOREIGN KEY (`whatsapp_account_id`) REFERENCES `whatsapp_accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `campaign_activities`
--
ALTER TABLE `campaign_activities`
  ADD CONSTRAINT `campaign_activities_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contact_group`
--
ALTER TABLE `contact_group`
  ADD CONSTRAINT `contact_group_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contact_group_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contact_tag`
--
ALTER TABLE `contact_tag`
  ADD CONSTRAINT `contact_tag_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contact_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_whatsapp_account_id_foreign` FOREIGN KEY (`whatsapp_account_id`) REFERENCES `whatsapp_accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incoming_messages`
--
ALTER TABLE `incoming_messages`
  ADD CONSTRAINT `incoming_messages_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `incoming_messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `incoming_messages_media_library_id_foreign` FOREIGN KEY (`media_library_id`) REFERENCES `media_libraries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `incoming_messages_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `incoming_messages_whatsapp_account_id_foreign` FOREIGN KEY (`whatsapp_account_id`) REFERENCES `whatsapp_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `media_libraries`
--
ALTER TABLE `media_libraries`
  ADD CONSTRAINT `media_libraries_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messages_media_library_id_foreign` FOREIGN KEY (`media_library_id`) REFERENCES `media_libraries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messages_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_whatsapp_account_id_foreign` FOREIGN KEY (`whatsapp_account_id`) REFERENCES `whatsapp_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messages_whatsapp_template_id_foreign` FOREIGN KEY (`whatsapp_template_id`) REFERENCES `whatsapp_templates` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `message_status_histories`
--
ALTER TABLE `message_status_histories`
  ADD CONSTRAINT `message_status_histories_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `subscriptions_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscription_changes`
--
ALTER TABLE `subscription_changes`
  ADD CONSTRAINT `subscription_changes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subscription_changes_new_plan_id_foreign` FOREIGN KEY (`new_plan_id`) REFERENCES `plans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subscription_changes_new_subscription_id_foreign` FOREIGN KEY (`new_subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subscription_changes_old_plan_id_foreign` FOREIGN KEY (`old_plan_id`) REFERENCES `plans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subscription_changes_old_subscription_id_foreign` FOREIGN KEY (`old_subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subscription_changes_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `tags_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `templates`
--
ALTER TABLE `templates`
  ADD CONSTRAINT `templates_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `vendors_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `whatsapp_accounts`
--
ALTER TABLE `whatsapp_accounts`
  ADD CONSTRAINT `whatsapp_accounts_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `whatsapp_templates`
--
ALTER TABLE `whatsapp_templates`
  ADD CONSTRAINT `whatsapp_templates_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
