-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 17, 2024 at 02:04 PM
-- Server version: 8.0.39-0ubuntu0.22.04.1
-- PHP Version: 8.1.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aceshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `f_name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `l_name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `u_email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `u_phone` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `address_1` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `address_2` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `country` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `state` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `zip` varchar(10) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `order_id` int NOT NULL,
  `card_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `card_number` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `card_cvv` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `card_date` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `user_id`, `order_id`, `card_name`, `card_number`, `card_cvv`, `card_date`, `created_date`) VALUES
(1, 6, 25, 'visa', '4242', '', '2025', '2024-09-17 09:31:32'),
(2, 8, 26, 'visa', '1111', '', '2024', '2024-09-17 10:00:29');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `discount_type` enum('fixed','percentage') COLLATE utf8mb4_general_ci NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `expiry_date` datetime NOT NULL,
  `usage_limit` int NOT NULL,
  `used_count` int DEFAULT '0',
  `status` enum('active','expired') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_type`, `discount_value`, `expiry_date`, `usage_limit`, `used_count`, `status`, `created_at`) VALUES
(6, '4ZZGXC3ZR9J', 'fixed', '7.00', '2024-09-18 00:00:00', 5, 0, 'active', '2024-09-17 10:33:41'),
(8, '0OCAKQYQ1DAM', 'fixed', '7.00', '2024-09-18 00:00:00', 2, 0, 'expired', '2024-09-17 11:28:29'),
(9, 'KHX93S9XFO', 'fixed', '2.00', '2025-09-20 00:00:00', 2, 0, 'active', '2024-09-17 11:35:23'),
(10, 'V10CMKKFO2M', 'fixed', '10.00', '2024-10-16 00:00:00', 80, 0, 'active', '2024-09-17 11:46:47');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `address` text COLLATE utf8mb4_general_ci NOT NULL,
  `items` mediumtext COLLATE utf8mb4_general_ci NOT NULL,
  `total` float NOT NULL,
  `payment_method` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `payment_id` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_status` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `address`, `items`, `total`, `payment_method`, `payment_id`, `payment_status`, `status`, `created_date`) VALUES
(17, 8, '{\"f_name\":\"admin\",\"l_name\":\"test\",\"u_email\":\"nirefigex@mailinator.com\",\"address_1\":\"69 Rocky Clarendon Court\",\"address_2\":\"Consequatur ut dicta\",\"country\":\"Veritatis officia ad\",\"state\":\"Sunt obcaecati qui i\",\"zip\":\"96546\"}', '{\"7\":{\"id\":\"7\",\"quantity\":1,\"price\":\"100\",\"hash\":\"8eba97c469eb763d7ad751c4a1f1ea69\"}}', 135, 'cod', ' ', '', '', '2024-09-17 08:37:43'),
(18, 8, '{\"f_name\":\"Zelda\",\"l_name\":\"Carney\",\"u_email\":\"qunykebef@mailinator.com\",\"address_1\":\"83 East Milton Drive\",\"address_2\":\"Corporis ea eius dol\",\"country\":\"Earum harum quisquam\",\"state\":\"Maxime veritatis nul\",\"zip\":\"55133\"}', '{\"7\":{\"id\":\"7\",\"quantity\":1,\"price\":\"100\",\"hash\":\"d75ca2459b0a0fd523f16de49ea51c52\"}}', 135, 'cod', ' ', '', '', '2024-09-17 09:08:16'),
(19, 8, '{\"f_name\":\"Camden\",\"l_name\":\"Hull\",\"u_email\":\"xawa@mailinator.com\",\"address_1\":\"61 West Clarendon Lane\",\"address_2\":\"Nam unde ipsa non o\",\"country\":\"Est officia minim no\",\"state\":\"Ut dolorum in nostru\",\"zip\":\"58717\"}', '{\"8\":{\"id\":\"8\",\"quantity\":1,\"price\":\"400\",\"hash\":\"14c308c0497314929fe10f3f6e5c270f\"}}', 435, 'cod', ' ', '', '', '2024-09-17 09:12:16'),
(20, 8, '{\"f_name\":\"Lucian\",\"l_name\":\"Salazar\",\"u_email\":\"xuferydiq@mailinator.com\",\"address_1\":\"480 West First Road\",\"address_2\":\"Explicabo Accusamus\",\"country\":\"Accusamus dolorum es\",\"state\":\"Quaerat quidem ut ex\",\"zip\":\"24228\"}', '{\"7\":{\"id\":\"7\",\"quantity\":1,\"price\":\"100\",\"hash\":\"2cc42f05ba922b57cfd33d1b7409c6ad\"}}', 135, 'cod', ' ', '', '', '2024-09-17 09:20:18'),
(21, 8, '{\"f_name\":\"Raymond\",\"l_name\":\"Conrad\",\"u_email\":\"fedufysag@mailinator.com\",\"address_1\":\"27 White New Drive\",\"address_2\":\"Quia itaque ad odit \",\"country\":\"Ratione incidunt in\",\"state\":\"Ipsum qui amet quo \",\"zip\":\"11283\"}', '{\"6\":{\"id\":\"6\",\"quantity\":1,\"price\":\"400\",\"hash\":\"087e6de73553bac93a8538742e430d89\"}}', 435, 'cod', ' ', '', '', '2024-09-17 09:20:44'),
(22, 8, '{\"f_name\":\"Jameson\",\"l_name\":\"Jenkins\",\"u_email\":\"qiji@mailinator.com\",\"address_1\":\"51 East Hague Freeway\",\"address_2\":\"Adipisci commodo min\",\"country\":\"Sed ad proident et \",\"state\":\"Similique non non co\",\"zip\":\"47156\"}', '{\"8\":{\"id\":\"8\",\"quantity\":2,\"price\":\"400\",\"hash\":\"192698d9816a1248453e50c27581792e\"},\"7\":{\"id\":\"7\",\"quantity\":1,\"price\":\"100\",\"hash\":\"e75b47d9e71c8a1809faf8fef4ad074a\"}}', 935, 'cod', ' ', '', '', '2024-09-17 09:24:10'),
(23, 8, '{\"f_name\":\"Teegan\",\"l_name\":\"Buck\",\"u_email\":\"sinu@mailinator.com\",\"address_1\":\"37 West Old Extension\",\"address_2\":\"Cillum veritatis qui\",\"country\":\"Deleniti impedit la\",\"state\":\"Aperiam velit fugiat\",\"zip\":\"52237\"}', '{\"6\":{\"id\":\"6\",\"quantity\":1,\"price\":\"400\",\"hash\":\"4fe13643dd54f772beb69a9d3a01c5b3\"}}', 435, 'cod', ' ', '', '', '2024-09-17 09:24:38'),
(24, 6, '{\"f_name\":\"Yoko\",\"l_name\":\"Mathews\",\"u_email\":\"musabuwyd@mailinator.com\",\"address_1\":\"600 West Fabien Parkway\",\"address_2\":\"Rerum optio nesciun\",\"country\":\"Ad duis velit moles\",\"state\":\"Aliqua Eligendi et \",\"zip\":\"84129\"}', '{\"8\":{\"id\":\"8\",\"quantity\":1,\"price\":\"400\",\"hash\":\"4ca6eb5271a72be7b711228daeff41ad\"}}', 435, 'cod', ' ', '', '', '2024-09-17 09:28:13'),
(25, 6, '{\"f_name\":\"Ignacia\",\"l_name\":\"Flynn\",\"u_email\":\"dymefyhyju@mailinator.com\",\"address_1\":\"241 East Fabien Lane\",\"address_2\":\"Porro similique quia\",\"country\":\"Quia iste nulla corr\",\"state\":\"Do earum sapiente du\",\"zip\":\"37509\"}', '{\"5\":{\"id\":\"5\",\"quantity\":1,\"price\":\"400\",\"hash\":\"cf49ea6ec21ae60e0da86fdbe096dca1\"}}', 435, 'card', ' ch_3PzxktH6iAIa5QEF02j5fvdR', 'Payment complete.', 'succeeded', '2024-09-17 09:31:32'),
(26, 8, '{\"f_name\":\"Demo\",\"l_name\":\"Name\",\"u_email\":\"admin@gmail.com\",\"address_1\":\"some\",\"address_2\":\"address\",\"country\":\"India\",\"state\":\"Punjab\",\"zip\":\"101010\"}', '{\"6\":{\"id\":\"6\",\"quantity\":1,\"price\":\"400\",\"hash\":\"c77d594ad9cc609ce9ce204ed7efe522\"}}', 435, 'card', ' ch_3PzyCuH6iAIa5QEF0jUnemPV', 'Payment complete.', 'succeeded', '2024-09-17 10:00:29'),
(27, 8, '{\"f_name\":\"Jocelyn\",\"l_name\":\"Malone\",\"u_email\":\"kovecujuju@mailinator.com\",\"address_1\":\"14 South Oak Street\",\"address_2\":\"Consequatur Ea quae\",\"country\":\"Autem adipisci sed s\",\"state\":\"Quod beatae dolore n\",\"zip\":\"95956\"}', '{\"6\":{\"id\":\"6\",\"quantity\":1,\"price\":\"400\",\"hash\":\"eca42d77dd092cbf1107038c873257e2\"}}', 435, 'cod', ' ', '', '', '2024-09-17 10:47:05');

-- --------------------------------------------------------

--
-- Table structure for table `order_meta`
--

CREATE TABLE `order_meta` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `meta_key` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `meta_value` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_meta`
--

INSERT INTO `order_meta` (`id`, `order_id`, `meta_key`, `meta_value`) VALUES
(1, 1, 'promocode', 'Quia ut iusto omnis '),
(2, 1, 'promocode_amount', '5'),
(3, 1, 'promocode_type', 'percentage'),
(4, 2, 'promocode', 'Fugiat quis eum ulla'),
(5, 2, 'promocode_amount', '5'),
(6, 2, 'promocode_type', 'percentage'),
(7, 3, 'promocode', 'Quas sed sunt qui eu'),
(8, 3, 'promocode_amount', '5'),
(9, 3, 'promocode_type', 'percentage'),
(10, 4, 'promocode', 'Anim Nam consequatur'),
(11, 4, 'promocode_amount', '5'),
(12, 4, 'promocode_type', 'percentage'),
(13, 5, 'promocode', 'Aute sed error totam'),
(14, 5, 'promocode_amount', '5'),
(15, 5, 'promocode_type', 'percentage'),
(16, 6, 'promocode', 'Nihil mollitia dolor'),
(17, 6, 'promocode_amount', '5'),
(18, 6, 'promocode_type', 'percentage'),
(19, 7, 'promocode', 'Blanditiis autem pra'),
(20, 7, 'promocode_amount', '5'),
(21, 7, 'promocode_type', 'percentage'),
(22, 8, 'promocode', 'Explicabo Quod vel '),
(23, 8, 'promocode_amount', '5'),
(24, 8, 'promocode_type', 'percentage'),
(25, 9, 'promocode', 'Excepturi voluptatum'),
(26, 9, 'promocode_amount', '5'),
(27, 9, 'promocode_type', 'percentage'),
(28, 10, 'promocode', 'Reprehenderit liber'),
(29, 10, 'promocode_amount', '5'),
(30, 10, 'promocode_type', 'percentage'),
(31, 11, 'promocode', 'Est est quidem in nu'),
(32, 11, 'promocode_amount', '5'),
(33, 11, 'promocode_type', 'percentage'),
(34, 12, 'promocode', 'Rerum ex ad in lorem'),
(35, 12, 'promocode_amount', '5'),
(36, 12, 'promocode_type', 'percentage'),
(37, 13, 'promocode', 'Quo tenetur illo cul'),
(38, 13, 'promocode_amount', '5'),
(39, 13, 'promocode_type', 'percentage'),
(40, 14, 'promocode', 'EXAMPLECODE'),
(41, 14, 'promocode_amount', '5'),
(42, 14, 'promocode_type', 'percentage'),
(43, 15, 'promocode', 'Eu at aliquip molest'),
(44, 15, 'promocode_amount', '5'),
(45, 15, 'promocode_type', 'percentage'),
(46, 16, 'promocode', 'Veniam et consequat'),
(47, 16, 'promocode_amount', '5'),
(48, 16, 'promocode_type', 'percentage'),
(49, 17, 'promocode', 'Ut consequatur aliqu'),
(50, 17, 'promocode_amount', '5'),
(51, 17, 'promocode_type', 'percentage'),
(52, 18, 'promocode', 'Dolorem modi ullam f'),
(53, 18, 'promocode_amount', '5'),
(54, 18, 'promocode_type', 'percentage'),
(55, 19, 'promocode', 'Fuga Qui eum rem ip'),
(56, 19, 'promocode_amount', '5'),
(57, 19, 'promocode_type', 'percentage'),
(58, 20, 'promocode', 'Non cupidatat recusa'),
(59, 20, 'promocode_amount', '5'),
(60, 20, 'promocode_type', 'percentage'),
(61, 21, 'promocode', 'Dolor ut quia aute s'),
(62, 21, 'promocode_amount', '5'),
(63, 21, 'promocode_type', 'percentage'),
(64, 22, 'promocode', 'Placeat ducimus es'),
(65, 22, 'promocode_amount', '5'),
(66, 22, 'promocode_type', 'percentage'),
(67, 23, 'promocode', 'Dolorum modi ut eaqu'),
(68, 23, 'promocode_amount', '5'),
(69, 23, 'promocode_type', 'percentage'),
(70, 24, 'promocode', 'Veniam dolore labor'),
(71, 24, 'promocode_amount', '5'),
(72, 24, 'promocode_type', 'percentage'),
(73, 25, 'payment_card_details', '{\"card\":{\"amount_authorized\":43500,\"authorization_code\":null,\"brand\":\"visa\",\"checks\":{\"address_line1_check\":null,\"address_postal_code_check\":\"pass\",\"cvc_check\":\"pass\"},\"country\":\"US\",\"exp_month\":4,\"exp_year\":2025,\"extended_authorization\":{\"status\":\"disabled\"},\"fingerprint\":\"UqYzYoVSHnhmu7W6\",\"funding\":\"credit\",\"incremental_authorization\":{\"status\":\"unavailable\"},\"installments\":null,\"last4\":\"4242\",\"mandate\":null,\"multicapture\":{\"status\":\"unavailable\"},\"network\":\"visa\",\"network_token\":{\"used\":false},\"overcapture\":{\"maximum_amount_capturable\":43500,\"status\":\"unavailable\"},\"three_d_secure\":null,\"wallet\":null},\"type\":\"card\"}'),
(74, 25, 'promocode', 'Ipsam corporis in se'),
(75, 25, 'promocode_amount', '5'),
(76, 25, 'promocode_type', 'percentage'),
(77, 26, 'payment_card_details', '{\"card\":{\"amount_authorized\":43500,\"authorization_code\":null,\"brand\":\"visa\",\"checks\":{\"address_line1_check\":null,\"address_postal_code_check\":\"pass\",\"cvc_check\":\"pass\"},\"country\":\"US\",\"exp_month\":11,\"exp_year\":2024,\"extended_authorization\":{\"status\":\"disabled\"},\"fingerprint\":\"6vtYDCXbIpJEcZtw\",\"funding\":\"credit\",\"incremental_authorization\":{\"status\":\"unavailable\"},\"installments\":null,\"last4\":\"1111\",\"mandate\":null,\"multicapture\":{\"status\":\"unavailable\"},\"network\":\"visa\",\"network_token\":{\"used\":false},\"overcapture\":{\"maximum_amount_capturable\":43500,\"status\":\"unavailable\"},\"three_d_secure\":null,\"wallet\":null},\"type\":\"card\"}'),
(78, 26, 'promocode', 'EXAMPLECODE'),
(79, 26, 'promocode_amount', '5'),
(80, 26, 'promocode_type', 'percentage'),
(81, 27, 'promocode', 'Voluptate ea qui qua'),
(82, 27, 'promocode_amount', '5'),
(83, 27, 'promocode_type', 'percentage');

-- --------------------------------------------------------

--
-- Table structure for table `order_notes`
--

CREATE TABLE `order_notes` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `order_id` int NOT NULL,
  `note` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `note_type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_notes`
--

INSERT INTO `order_notes` (`id`, `user_id`, `order_id`, `note`, `note_type`, `created_date`) VALUES
(1, 5, 1, 'Order placed successfully', 'public', '2024-09-17 05:20:57'),
(2, 5, 1, 'Payment Status  :- ', 'public', '2024-09-17 05:20:57'),
(3, 5, 2, 'Order placed successfully', 'public', '2024-09-17 06:06:04'),
(4, 5, 2, 'Payment Status  :- ', 'public', '2024-09-17 06:06:04'),
(5, 5, 3, 'Order placed successfully', 'public', '2024-09-17 06:08:18'),
(6, 5, 3, 'Payment Status  :- ', 'public', '2024-09-17 06:08:18'),
(7, 5, 4, 'Order placed successfully', 'public', '2024-09-17 06:09:01'),
(8, 5, 4, 'Payment Status  :- ', 'public', '2024-09-17 06:09:01'),
(9, 6, 5, 'Order placed successfully', 'public', '2024-09-17 06:13:53'),
(10, 6, 5, 'Payment Status  :- ', 'public', '2024-09-17 06:13:53'),
(11, 6, 6, 'Order placed successfully', 'public', '2024-09-17 06:24:23'),
(12, 6, 6, 'Payment Status  :- ', 'public', '2024-09-17 06:24:23'),
(13, 8, 7, 'Order placed successfully', 'public', '2024-09-17 06:32:51'),
(14, 8, 7, 'Payment Status  :- ', 'public', '2024-09-17 06:32:51'),
(15, 8, 8, 'Order placed successfully', 'public', '2024-09-17 06:34:56'),
(16, 8, 8, 'Payment Status  :- ', 'public', '2024-09-17 06:34:56'),
(17, 8, 9, 'Order placed successfully', 'public', '2024-09-17 06:37:42'),
(18, 8, 9, 'Payment Status  :- ', 'public', '2024-09-17 06:37:42'),
(19, 8, 10, 'Order placed successfully', 'public', '2024-09-17 06:40:21'),
(20, 8, 10, 'Payment Status  :- ', 'public', '2024-09-17 06:40:21'),
(21, 6, 11, 'Order placed successfully', 'public', '2024-09-17 06:58:35'),
(22, 6, 11, 'Payment Status  :- ', 'public', '2024-09-17 06:58:35'),
(23, 6, 12, 'Order placed successfully', 'public', '2024-09-17 06:59:09'),
(24, 6, 12, 'Payment Status  :- ', 'public', '2024-09-17 06:59:09'),
(25, 6, 13, 'Order placed successfully', 'public', '2024-09-17 07:16:31'),
(26, 6, 13, 'Payment Status  :- ', 'public', '2024-09-17 07:16:31'),
(27, 8, 14, 'Order placed successfully', 'public', '2024-09-17 07:48:38'),
(28, 8, 14, 'Payment Status  :- ', 'public', '2024-09-17 07:48:38'),
(29, 8, 15, 'Order placed successfully', 'public', '2024-09-17 07:50:24'),
(30, 8, 15, 'Payment Status  :- ', 'public', '2024-09-17 07:50:24'),
(31, 8, 16, 'Order placed successfully', 'public', '2024-09-17 08:34:57'),
(32, 8, 16, 'Payment Status  :- ', 'public', '2024-09-17 08:34:57'),
(33, 8, 17, 'Order placed successfully', 'public', '2024-09-17 08:37:43'),
(34, 8, 17, 'Payment Status  :- ', 'public', '2024-09-17 08:37:43'),
(35, 8, 18, 'Order placed successfully', 'public', '2024-09-17 09:08:16'),
(36, 8, 18, 'Payment Status  :- ', 'public', '2024-09-17 09:08:16'),
(37, 8, 19, 'Order placed successfully', 'public', '2024-09-17 09:12:16'),
(38, 8, 19, 'Payment Status  :- ', 'public', '2024-09-17 09:12:16'),
(39, 8, 20, 'Order placed successfully', 'public', '2024-09-17 09:20:18'),
(40, 8, 20, 'Payment Status  :- ', 'public', '2024-09-17 09:20:18'),
(41, 8, 21, 'Order placed successfully', 'public', '2024-09-17 09:20:44'),
(42, 8, 21, 'Payment Status  :- ', 'public', '2024-09-17 09:20:44'),
(43, 8, 22, 'Order placed successfully', 'public', '2024-09-17 09:24:10'),
(44, 8, 22, 'Payment Status  :- ', 'public', '2024-09-17 09:24:10'),
(45, 8, 23, 'Order placed successfully', 'public', '2024-09-17 09:24:38'),
(46, 8, 23, 'Payment Status  :- ', 'public', '2024-09-17 09:24:38'),
(47, 6, 24, 'Order placed successfully', 'public', '2024-09-17 09:28:13'),
(48, 6, 24, 'Payment Status  :- ', 'public', '2024-09-17 09:28:13'),
(49, 6, 25, 'Order placed successfully', 'public', '2024-09-17 09:31:32'),
(50, 6, 25, 'Charge Id:- ch_3PzxktH6iAIa5QEF02j5fvdR', 'public', '2024-09-17 09:31:32'),
(51, 6, 25, 'Payment Status  :-succeeded ', 'public', '2024-09-17 09:31:32'),
(52, 8, 26, 'Order placed successfully', 'public', '2024-09-17 10:00:29'),
(53, 8, 26, 'Charge Id:- ch_3PzyCuH6iAIa5QEF0jUnemPV', 'public', '2024-09-17 10:00:29'),
(54, 8, 26, 'Payment Status  :-succeeded ', 'public', '2024-09-17 10:00:29'),
(55, 8, 27, 'Order placed successfully', 'public', '2024-09-17 10:47:05'),
(56, 8, 27, 'Payment Status  :- ', 'public', '2024-09-17 10:47:05');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'default.png',
  `regular_price` float NOT NULL,
  `sales_price` float NOT NULL,
  `ratings` float NOT NULL,
  `status` enum('active','inactive','') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `sku` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `image`, `regular_price`, `sales_price`, `ratings`, `status`, `created_date`, `updated_date`, `sku`) VALUES
(5, 'Product 4', 'product-4', '<p>Experience superior sound quality and uninterrupted wireless connectivity with our Wireless Bluetooth Headphones. Designed for ultimate comfort and extended listening sessions, these headphones feature noise-cancellation technology, a built-in microphone for hands-free calls, and up to 20 hours of battery life. Ideal for music enthusiasts and professionals on the go.</p>', 'curology-wK0h-mlvfuc-unsplash.jpg', 700, 400, 5, 'active', '2024-09-17 05:35:31', '2024-09-17 07:41:50', 'ESC-4'),
(6, 'Product 3', 'product-3', 'Experience superior sound quality and uninterrupted wireless connectivity with our Wireless Bluetooth Headphones. Designed for ultimate comfort and extended listening sessions, these headphones feature noise-cancellation technology, a built-in microphone for hands-free calls, and up to 20 hours of battery life. Ideal for music enthusiasts and professionals on the go.', 'nataliya-melnychuk-PdzMmdHqN2c-unsplash.jpg', 700, 400, 5, 'active', '2024-09-17 05:39:56', '2024-09-17 07:38:57', 'ESC-3'),
(7, 'Product 2', 'product-2', 'Elevate your wardrobe with our Classic Leather Jacket, crafted from premium full-grain leather for a timeless and durable finish. This jacket features a sleek design with a tailored fit, adjustable cuffs, and multiple pockets for functionality.', 'product2.jpg', 700, 100, 5, 'active', '2024-09-17 05:40:25', '2024-09-17 07:40:05', 'ESC-2'),
(8, 'Product 1', 'product-1', 'Experience superior sound quality and uninterrupted wireless connectivity with our Wireless Bluetooth Headphones. Designed for ultimate comfort and extended listening sessions, these headphones feature noise-cancellation technology, a built-in microphone for hands-free calls, and up to 20 hours of battery life. Ideal for music enthusiasts and professionals on the go.<br>', 'product1.jpg', 700, 400, 5, 'active', '2024-09-17 05:47:28', '2024-09-17 07:42:25', 'ESC-1');

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `id` int NOT NULL,
  `refund_id` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `amount` float NOT NULL,
  `payment_id` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `current_user_id` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_date` varchar(300) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `comment` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rating` decimal(5,0) NOT NULL,
  `status` enum('pending','banned','approved','') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `comment`, `rating`, `status`, `created_date`) VALUES
(1, 7, 8, 'I recently purchased the XYZ , and I have mixed feelings about it. On the plus side, the widget does its job and is reasonably priced. The design is sleek and it arrived on time, which is great', '3', 'approved', '2024-09-17 06:53:20'),
(2, 7, 8, 'I recently purchased the XYZ , and I have mixed feelings about it. On the plus side, the widget does its job and is reasonably priced. The design is sleek and it arrived on time, which is great', '3', 'approved', '2024-09-17 06:53:38'),
(3, 7, 8, 'okay for the price, but there are definitely areas where it could be improved.', '4', 'approved', '2024-09-17 06:54:27'),
(4, 7, 8, 'okay for the price, but there are definitely areas where it could be improved.', '4', 'approved', '2024-09-17 06:56:05'),
(5, 8, 6, 'However, there are a few issues that prevent me from giving it a higher rating. The build quality feels a bit flimsy, and the instructions for assembly were not very clear. I also experienced some minor functionality issues, which, while not severe, were annoying.\r\n\r\n', '1', 'pending', '2024-09-17 07:26:23'),
(6, 7, 6, 'good product', '5', 'approved', '2024-09-17 07:29:44'),
(7, 5, 6, 'In summary, the XYZ Widget is okay for the price, but there are definitely areas where it could be improved. If you’re looking for a budget-friendly option and don’t mind some imperfections, it’s worth considering. Otherwise, you might want to look at more expensive alternatives.', '5', 'approved', '2024-09-17 07:31:35'),
(10, 8, 8, 'I absolutely love this product! It exceeded my expectations in every way. The quality is top-notch, and it was delivered quickly. I would definitely recommend this to anyone looking for a high-quality product at a great price.', '5', 'approved', '2024-09-17 09:51:47'),
(11, 6, 8, 'Best Product', '4', 'banned', '2024-09-17 09:58:25'),
(12, 8, 8, 'I absolutely love this product! It exceeded my expectations in every way. The quality is top-notch, and it was delivered quickly. I would definitely recommend this to anyone looking for a high-quality product at a great price.', '5', 'pending', '2024-09-17 10:02:47'),
(13, 8, 8, 'I absolutely love this product! It exceeded my expectations in every way. The quality is top-notch, and it was delivered quickly. I would definitely recommend this to anyone looking for a high-quality product at a great price.', '5', 'pending', '2024-09-17 10:03:11'),
(14, 6, 8, 'good', '4', 'approved', '2024-09-17 10:46:21'),
(15, 6, 8, 'good', '4', 'pending', '2024-09-17 10:46:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `f_name` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `l_name` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(13) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `f_name`, `l_name`, `image`, `phone`, `role`, `status`, `created_date`, `updated_date`) VALUES
(6, 'user', 'user@gmail.com', '770b219162e52e09fd90faaa68522a36', 'joe', 'dame', '118-canva_480.png', '9876543210', 'user', 'active', NULL, '2024-09-17 10:31:51'),
(8, 'admin', 'admin@gmail.com', 'd7da9f743f75d152b94011ad346cdd6a', 'Spark', 'adem', '1.jpg', '9876543213', 'admin', 'active', NULL, '2024-09-17 12:02:02');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `label` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `privilege` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `name`, `label`, `privilege`) VALUES
(1, 'admin', 'Administrator', 'Full access to all features and settings'),
(2, 'user', 'Regular User', 'Access to user-specific features and content');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `created_date`) VALUES
(1, 5, 6, '2024-09-17 05:50:40'),
(2, 5, 5, '2024-09-17 05:50:42'),
(4, 8, 5, '2024-09-17 07:33:16'),
(6, 8, 7, '2024-09-17 07:43:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_meta`
--
ALTER TABLE `order_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_notes`
--
ALTER TABLE `order_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `order_meta`
--
ALTER TABLE `order_meta`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `order_notes`
--
ALTER TABLE `order_notes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
