-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2023 at 04:35 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `techiehaven`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cart`
--

CREATE TABLE `tbl_cart` (
  `id` int(11) NOT NULL,
  `user_id` int(25) NOT NULL,
  `item_id` int(25) NOT NULL,
  `quantity` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `id` int(25) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`id`, `category_name`, `category_picture`) VALUES
(1, 'All', 'default.png'),
(5, 'CPU', '5_picture.jpg'),
(8, 'Motherboard', '8_picture.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_history`
--

CREATE TABLE `tbl_history` (
  `id` int(25) NOT NULL,
  `user_id` int(25) NOT NULL,
  `item_picture` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_id` int(25) NOT NULL,
  `item_quantity` int(25) NOT NULL,
  `item_price` decimal(11,2) NOT NULL,
  `time` time NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_items`
--

CREATE TABLE `tbl_items` (
  `id` int(25) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_description` text NOT NULL,
  `item_price` decimal(11,2) NOT NULL,
  `item_picture` varchar(255) NOT NULL DEFAULT 'default.png',
  `item_category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_items`
--

INSERT INTO `tbl_items` (`id`, `item_name`, `item_description`, `item_price`, `item_picture`, `item_category`) VALUES
(6, 'GA-H110M-DS2 (rev. 1.x)', 'Supports 7th / 6th Generation Intel® Core™ Processors\r\nDual Channel DDR4, 2 DIMMs\r\n8-channel HD Audio with High Quality Audio Capacitors\r\nAudio Noise Guard with LED Trace Path Lighting\r\nRealtek® GbE LAN with cFosSpeed Internet Accelerator Software\r\nAll new GIGABYTE™ APP Center, simple and easy use\r\nGIGABYTE UEFI DualBIOS™ Technology\r\nSupport Intel® Small Business Basics', 3200.00, '6_picture.png', 'Motherboard'),
(7, 'i5-7400', 'Total Cores: 4\r\nTotal Threads: 4\r\nMax Turbo Frequency: 3.50 GHz\r\nIntel® Turbo Boost Technology 2.0 Frequency: 3.50 GHz\r\nProcessor Base Frequency: 3.00 GHz\r\nCache: 6 MB Intel® Smart Cache\r\nBus Speed: 8 GT/s\r\n# of QPI Links: 0\r\nTDP: 65 W', 3768.00, '7_picture.jpg', 'CPU');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(25) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `region` varchar(255) NOT NULL,
  `zip_code` int(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL DEFAULT 'default.png',
  `user_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `first_name`, `middle_name`, `last_name`, `address`, `city`, `region`, `zip_code`, `password`, `email`, `phone_number`, `profile_picture`, `user_type`) VALUES
(4, 'John Alvin', 'Reyes', 'Cruz', '8 Cruz Compound', 'Taytay', 'Rizal', 1920, '6f7473436b4bf3905ecca90210ed36e8c6372b94', 'johnalvin@techiehaven.com', '09582346582', 'default.png', 'admin'),
(5, 'Carlo Jan Harry', 'S', 'Anonuevo', 'Espana', 'Recto', 'Manila', 1006, '63ccda7175f528493bd1f6d85eb30f8ac54fda7c', 'carlojan@techiehaven.com', '09683758438', 'default.png', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_history`
--
ALTER TABLE `tbl_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_items`
--
ALTER TABLE `tbl_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_history`
--
ALTER TABLE `tbl_history`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_items`
--
ALTER TABLE `tbl_items`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
