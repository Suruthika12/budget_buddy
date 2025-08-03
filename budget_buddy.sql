-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 03, 2025 at 12:11 PM
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
-- Database: `budget_buddy`
--

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `type`, `category`, `amount`, `description`, `date`) VALUES
(11, 1, 'expense', 'travel', 4000.00, '', '2025-07-09'),
(13, 1, 'expense', 'travel', 5200.00, '', '2025-07-09'),
(19, 1, 'income', 'Salary', 40000.00, '', '2025-07-01'),
(23, 1, 'expense', 'food', 2000.00, '', '2025-07-09'),
(24, 2, 'income', 'Salary', 50000.00, '', '2025-07-01'),
(25, 2, 'expense', 'food', 2000.00, '', '2025-07-03'),
(26, 2, 'expense', 'travel', 500.00, '', '2025-07-06'),
(27, 2, 'expense', 'travel', 500.00, '', '2025-07-13'),
(28, 2, 'expense', 'shopping', 2500.00, '', '2025-07-12'),
(29, 2, 'expense', 'food', 200.00, '', '2025-07-04'),
(30, 2, 'expense', 'food', 500.00, '', '2025-07-07'),
(31, 2, 'expense', 'rent', 12000.00, '', '2025-07-05'),
(32, 3, 'income', 'Salary', 54600.00, '', '2025-06-30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'Suruthika', 'suruthikacs@gmail.com', '$2y$10$AP5I.7h.EDX2hwpGNzgmi.dDIBg9ZBD.PiMQrf22.eyo6KBwHPaRm'),
(2, 'Sasi', 'sasikrubalani@gmail.com', '$2y$10$ZkieOFraqFhO6pKKc.9f9.WDz3IyafyxKLCzVW.Qiyo0VIvK7xbJS'),
(3, 'selvakumar', 'selvaa052@gmail.com', '$2y$10$Vn/og8xBAxfyk9Kgmy0.n.7ETCwV3aoutUf8vsZIrCj5O2tzFthPK');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
