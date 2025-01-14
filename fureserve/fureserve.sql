-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2025 at 02:33 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fureserve`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_accounts`
--

CREATE TABLE `admin_accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_accounts`
--

INSERT INTO `admin_accounts` (`id`, `username`, `password`) VALUES
(1, 'test', '12345678'),
(2, 'admin', '12341234');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `owner_email` varchar(255) NOT NULL,
  `owner_phone` varchar(20) NOT NULL,
  `owner_address` varchar(255) DEFAULT NULL,
  `pet_name` varchar(255) NOT NULL,
  `pet_breed` varchar(255) NOT NULL,
  `pet_age` int(11) NOT NULL,
  `pet_gender` enum('male','female') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `selected_services` varchar(255) NOT NULL,
  `selected_date` date NOT NULL,
  `selected_time` time NOT NULL,
  `status` enum('pending','confirmed','cancelled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `owner_name`, `owner_email`, `owner_phone`, `owner_address`, `pet_name`, `pet_breed`, `pet_age`, `pet_gender`, `created_at`, `selected_services`, `selected_date`, `selected_time`, `status`) VALUES
(21, 'Ambrad', 'ambrad144@gmail.com', '09516031975', 'Purok 2 Brgy. Dita, Sta. Rosa City, Laguna', 'Paswe', '', 5, 'male', '2025-01-10 11:41:12', 'Full Package', '2025-01-13', '09:00:00', 'cancelled'),
(22, 'Neil Dan', 'ambrad@gmail.com', '09516031975', 'Purok 2 Brgy. Dita, Sta. Rosa City, Laguna', 'Paswey', 'Domestic', 3, 'female', '2025-01-10 11:55:51', 'Hair Cut, Fur Brushing, Paw Cleaning, Ear Cleaning, Nail Cutting', '2025-01-13', '10:00:00', 'cancelled'),
(24, 'Neil Daniel', 'neildanielads@gmail.com', '09516031975', 'Purok 2 Brgy. Dita, Sta. Rosa City, Laguna', 'Bebe', 'Domestic cat', 3, 'female', '2025-01-10 18:23:52', 'Full Package', '2025-01-13', '12:00:00', 'confirmed'),
(38, 'Neil Daniel', 'neildanielads@gmail.com', '09516031975', 'Purok 2 Brgy. Dita, Sta. Rosa City, Laguna', 'Bambi', 'Qwe', 5, 'male', '2025-01-11 13:19:12', 'Full Package', '2025-01-13', '14:00:00', 'confirmed'),
(39, 'Nathaniel Ambrad', 'neildanielads@gmail.com', '09516031975', 'Purok 2 Brgy. Dita, Sta. Rosa City, Laguna', 'Brownie', 'Puskal', 2, 'male', '2025-01-12 04:04:48', 'Full Package', '2025-01-14', '09:00:00', 'confirmed'),
(41, 'Juan Dela Cruz', 'neildaniel144@gmail.com', '09516031975', 'Purok 2 Brgy. Dita, Sta. Rosa City', 'Blackie', 'Aspin', 2, 'male', '2025-01-12 07:17:18', 'Hair Cut, Fur Brushing', '2025-01-14', '13:00:00', 'pending'),
(44, 'Neil Dan', 'neildanielads@gmail.com', '09516031975', 'Purok 2 Brgy. Dita, Sta. Rosa City, Laguna', 'Paswey', 'Puspin', 2, 'female', '2025-01-13 01:49:40', 'Full Package', '2025-01-13', '13:00:00', 'confirmed'),
(45, 'Juan Dela Cruz', 'parasyte144@gmail.com', '09516031975', 'Purok 2 Brgy. Dita, Sta. Rosa City, Laguna', 'Ming', 'Puspin', 5, 'male', '2025-01-13 02:27:03', 'Hair Cut, Fur Brushing, Paw Cleaning, Ear Cleaning, Nail Cutting', '2025-01-13', '11:00:00', 'confirmed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_appointment_slot` (`selected_date`,`selected_time`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
