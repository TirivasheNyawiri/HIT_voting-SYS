-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2024 at 03:22 PM
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
-- Database: `voting_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int(30) NOT NULL,
  `category` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `category`) VALUES
(1, 'President'),
(2, 'Vice President'),
(3, 'Minister of Legal Affairs'),
(6, 'Minister of Sports');

-- --------------------------------------------------------

--
-- Table structure for table `dates`
--

CREATE TABLE `dates` (
  `start_voting` datetime DEFAULT current_timestamp(),
  `end_voting` datetime DEFAULT current_timestamp(),
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dates`
--

INSERT INTO `dates` (`start_voting`, `end_voting`, `id`) VALUES
('2024-04-24 16:55:52', '2024-04-25 16:55:52', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(30) NOT NULL,
  `name` varchar(200) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1+admin , 2 = users',
  `email` varchar(225) NOT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime NOT NULL,
  `password_changed` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`, `email`, `otp`, `otp_expiry`, `password_changed`) VALUES
('2003', 'Stuli', 'Upenyu', 'admin2003', 2, '', NULL, '0000-00-00 00:00:00', NULL),
('H000000V', 'pana', 'Pana', '$2y$10$Ht8Dwk0H3hGoIPDEQHu0PutSZTsB3sD.Pg1D4AY.zTqNtzxebq3Jy', 2, 'blessingmuneri1@gmail.com', NULL, '0000-00-00 00:00:00', NULL),
('H220000V', 'Simba', 'Simba', '$2y$10$Ksc8AESORotFgTnADKRRwetDPahwK18ZVS99lNogeuKY9j6Yh7mZm', 2, 'tinayedebwe214@gmail.com', NULL, '0000-00-00 00:00:00', NULL),
('h220087e', 'Tamuka', 'Tamuka', '$2y$10$Ksc8AESORotFgTnADKRRwetDPahwK18ZVS99lNogeuKY9j6Yh7mZm', 2, 'h220087e@hit.ac.zw', '162882', '2024-04-26 08:26:21', NULL),
('H220290G', 'Nyawiri', 'Trizz', '$2y$10$Ksc8AESORotFgTnADKRRwetDPahwK18ZVS99lNogeuKY9j6Yh7mZm', 2, 'nyawiritirivashe02@gmail.com', '990800', '2024-04-26 14:30:53', NULL),
('H220315H', 'Edward ', 'Mandishe', '$2y$10$Ksc8AESORotFgTnADKRRwetDPahwK18ZVS99lNogeuKY9j6Yh7mZm', 1, '', NULL, '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(30) NOT NULL,
  `voting_id` int(30) NOT NULL,
  `category_id` int(30) NOT NULL,
  `voting_opt_id` int(30) NOT NULL,
  `user_id` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `voting_id`, `category_id`, `voting_opt_id`, `user_id`) VALUES
(58, 4, 1, 13, 'H220000V'),
(59, 4, 2, 15, 'H220000V'),
(60, 4, 3, 12, 'H220000V'),
(61, 4, 6, 8, 'H220000V'),
(66, 4, 1, 13, 'H220290G'),
(67, 4, 2, 15, 'H220290G'),
(68, 4, 3, 6, 'H220290G'),
(69, 4, 6, 7, 'H220290G');

-- --------------------------------------------------------

--
-- Table structure for table `voting_cat_settings`
--

CREATE TABLE `voting_cat_settings` (
  `id` int(30) NOT NULL,
  `voting_id` int(30) NOT NULL,
  `category_id` int(30) NOT NULL,
  `max_selection` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voting_cat_settings`
--

INSERT INTO `voting_cat_settings` (`id`, `voting_id`, `category_id`, `max_selection`) VALUES
(2, 1, 3, 1),
(3, 1, 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `voting_list`
--

CREATE TABLE `voting_list` (
  `id` int(30) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voting_list`
--

INSERT INTO `voting_list` (`id`, `title`, `description`, `is_default`) VALUES
(4, 'president', '0', 1),
(5, 'vice president', '0\r\n', 0),
(8, 'president', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `voting_opt`
--

CREATE TABLE `voting_opt` (
  `id` int(30) NOT NULL,
  `voting_id` int(30) NOT NULL,
  `category_id` int(30) NOT NULL,
  `image_path` text NOT NULL,
  `opt_txt` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voting_opt`
--

INSERT INTO `voting_opt` (`id`, `voting_id`, `category_id`, `image_path`, `opt_txt`) VALUES
(1, 1, 1, 'avatar', 'EDWARD'),
(3, 1, 1, '1614206100_jude.jpg', 'TERRENCE'),
(5, 1, 3, '1600415520_avatar', 'TIRIVASHE'),
(6, 4, 3, '1600415520_avatar.jpg', 'MONICA'),
(7, 4, 6, '1600415520_avatar.jpg', 'TAFARA'),
(8, 4, 6, '1600415520_avatar.jpg', 'TAKUNDA'),
(9, 4, 4, '1600415520_avatar.jpg', 'MELODY'),
(10, 4, 4, '1600415520_avatar.jpg', 'BLESSING'),
(11, 4, 6, '1600415520_avatar.jpg', 'SIMON'),
(12, 4, 3, '1600418640_avatar.jpg', 'UPENYU'),
(13, 4, 1, '1600418640_avatar.jpg', 'EDDY MANDISHE'),
(14, 4, 1, '1600418640_avatar.jpg', 'STULI'),
(15, 4, 2, '1600418640_avatar.jpg', 'MERCY'),
(16, 4, 2, '1600418640_avatar.jpg', 'HAMANDISHE'),
(17, 4, 3, '1600418640_avatar.jpg', 'NELSON');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category_list`
--
ALTER TABLE `category_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dates`
--
ALTER TABLE `dates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voting_cat_settings`
--
ALTER TABLE `voting_cat_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voting_list`
--
ALTER TABLE `voting_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voting_opt`
--
ALTER TABLE `voting_opt`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category_list`
--
ALTER TABLE `category_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `dates`
--
ALTER TABLE `dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `voting_cat_settings`
--
ALTER TABLE `voting_cat_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `voting_list`
--
ALTER TABLE `voting_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `voting_opt`
--
ALTER TABLE `voting_opt`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
