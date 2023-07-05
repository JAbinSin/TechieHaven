-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 05, 2023 at 11:19 AM
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
  `category_picture` varchar(255) NOT NULL DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`id`, `category_name`, `category_picture`) VALUES
(1, 'All', 'default.png'),
(5, 'CPU', '5_picture.jpg'),
(8, 'Motherboard', '8_picture.jpg'),
(9, 'RAM', '9_picture.jpg'),
(10, 'Storage', '10_picture.jpg'),
(11, 'PSU', '11_picture.jpg');

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

--
-- Dumping data for table `tbl_history`
--

INSERT INTO `tbl_history` (`id`, `user_id`, `item_picture`, `item_name`, `item_id`, `item_quantity`, `item_price`, `time`, `status`) VALUES
(7, 6, '7_picture.jpg', 'Intel Core i5-7400', 7, 12, 3768.00, '11:11:43', 'processing'),
(8, 6, '7_picture.jpg', 'Intel Core i5-7400', 7, 1, 3768.00, '11:47:07', 'pending');

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
(7, 'Intel Core i5-7400', 'Total Cores: 4\r\n\r\nTotal Threads: 4\r\n\r\nMax Turbo Frequency: 3.50 GHz\r\n\r\nIntel Turbo Boost Technology 2.0 Frequency: 3.50 GHz\r\n\r\nProcessor Base Frequency: 3.00 GHz\r\n\r\nCache: 6 MB Intel Smart Cache\r\n\r\nBus Speed: 8 GT/s\r\n\r\n# of QPI Links: 0\r\n\r\nTDP: 65 W', 3768.00, '7_picture.jpg', 'CPU'),
(8, 'B450 TOMAHAWK', 'Supports 1st, 2nd and 3rd Gen AMDÂ® Ryzenâ„¢/ Ryzenâ„¢ with Radeonâ„¢ Vega Graphics / 2nd Gen AMDÂ® Ryzenâ„¢ with Radeonâ„¢ Graphics and Athlonâ„¢ with Radeonâ„¢ Vega Graphics Desktop Processors for Socket AM4\r\nSupports DDR4 Memory, up to 3466(OC) MHz\r\nExtended Heatsink Design: MSI extended PWM and enhanced circuit design ensures even high-end processors to run in full speed.\r\nLightning Fast Game experience: 1x TURBO M.2, AMD Turbo USB 3.1 GEN2, Store MI technology\r\nCore Boost: With premium layout and fully digital power design to support more cores and provide better performance.\r\nDDR4 Boost:Advanced technology to deliver pure data signals for the best gaming performance and stability.\r\nMULTI-GPU: With STEEL ARMOR PCI-E slots. Supports 2-Way AMD Crossfireâ„¢\r\nAudio Boost:Reward your ears with studio grade sound quality for the most immersive audio experience.\r\nMystic Light and Mystic Light Sync: 16.8 million colors / 10 effects in one click. Synchronize RGB strips and other RGB solutions for customization. \r\nFlash BIOS Button: Simply use a USB key to flash any BIOS within seconds, without installing a CPU, memory or graphics card.\r\nIn-Game Weapons: Game Boost, GAMING Hotkey, X-Boost\r\nEZ Debug LED: Easiest way to troubleshoot\r\nGAMING CERTIFIED: 24-hour on- and offline game and motherboard testing by eSports players', 7500.00, '8_picture.png', 'Motherboard'),
(9, 'Intel Core i9 13900k', 'Total Cores 24\r\n\r\n# of Performance-cores8\r\n\r\n# of Efficient-cores16\r\n\r\nTotal Threads 32\r\n\r\nMax Turbo Frequency 5.80 GHz\r\n\r\nIntelÂ® Thermal Velocity Boost Frequency 5.80 GHz\r\n\r\nIntelÂ® Turbo Boost Max Technology 3.0 Frequency â€¡ 5.70 GHz\r\n\r\nPerformance-core Max Turbo Frequency 5.40 GHz\r\n\r\nEfficient-core Max Turbo Frequency 4.30 GHz\r\n\r\nPerformance-core Base Frequency3.00 GHz\r\n\r\nEfficient-core Base Frequency2.20 GHz\r\n\r\nCache 36 MB IntelÂ® Smart Cache\r\n\r\nTotal L2 Cache32 MB\r\n\r\nProcessor Base Power 125 W\r\n\r\nMaximum Turbo Power 253 W', 34950.00, '9_picture.jpg', 'CPU'),
(10, 'Hyper Ekis Ekis 8GB', 'https://www.youtube.com/watch?v=O2zANofnr4Y', 1429.00, '10_picture.jpg', 'RAM'),
(11, 'CRUCIAL MX500 500GB', 'SSD series - MX500\r\nInterface - SATA (6Gb/s)\r\nCapacity - 500GB\r\nForm factor - 2.5-inch (7mm)\r\nSequential Read - 560 MB/s\r\nSequential Write - 510 MB/s\r\nSSD Endurance (TBW) - 180TB', 2230.00, '11_picture.jpg', 'Solid State Drive');

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
(5, 'Carlo Jan Harry', 'S', 'Anonuevo', 'Espana', 'Recto', 'Manila', 1006, '63ccda7175f528493bd1f6d85eb30f8ac54fda7c', 'carlojan@techiehaven.com', '09683758438', 'default.png', 'admin'),
(6, 'Carlo', 'S', 'AÃ±onuevo', 'Quezon St. Zone III, Oriental Mindoro', 'Pinamalayan', 'IV-B', 1003, '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '123@email.com', '09452438183', '6_profile.png', 'customer');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_history`
--
ALTER TABLE `tbl_history`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_items`
--
ALTER TABLE `tbl_items`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
