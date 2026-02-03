-- phpMyAdmin SQL Dump
-- version 5.2.3-1.el10_2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 03, 2026 at 05:17 AM
-- Server version: 10.11.15-MariaDB
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `NP03CS4A240201`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `studentId` int(11) NOT NULL,
  `attendance_date` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`studentId`, `attendance_date`) VALUES
(2, '2026-02-02'),
(2, '2026-02-03'),
(3, '2026-02-03'),
(4, '2026-02-03'),
(5, '2026-02-03'),
(7, '2026-02-03'),
(9, '2026-02-03'),
(10, '2026-02-03'),
(11, '2026-02-03'),
(12, '2026-02-03'),
(13, '2026-02-03');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `recordId` int(11) NOT NULL,
  `studentId` int(11) DEFAULT NULL,
  `fullname` varchar(100) NOT NULL,
  `completion_date` date NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`recordId`, `studentId`, `fullname`, `completion_date`, `course_name`, `grade`, `status`) VALUES
(1, 2, 'Sriyog Dhital', '2026-02-02', 'Biology', '96', 'Verified'),
(2, 2, 'Sriyog Dhital', '2026-02-02', 'Computer', 'A+', 'Verified'),
(3, 7, 'Siku Magar', '2026-02-02', 'Computer', 'A+', 'Rejected'),
(4, 9, 'DUDU', '2025-02-10', 'Biology', 'A+', 'Verified'),
(5, 12, 'Chandra Magar', '2026-02-02', 'Computer', 'B+', 'Rejected'),
(6, 2, 'Sriyog Dhital', '2026-02-03', 'Nepali', 'A+', 'Verified');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `item_description` text NOT NULL,
  `added_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `supplier_name`, `item_description`, `added_at`) VALUES
(1, 'eraser', 'pendealer', 'sadads ad wa ds daw asd was d was dwa sd aw sd awsdw d', '2026-01-13 09:09:51'),
(2, 'copy', 'copydelear', 'uioahwdiahowddwoihdwioh wdiaisd ia dhia diah wih ai hwiah dih awihd aw hiw dhi', '2026-01-13 09:15:43'),
(3, 'Badgedcasdf', 'cxscas', 'wqjndlkqwmd;lasdmsnd;lw,;ld,qwl,lxmasmnx;lqwldqw', '2026-01-29 04:49:55');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `studentId` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `program` varchar(50) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'student',
  `joined_at` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`studentId`, `fullname`, `email`, `password`, `program`, `role`, `joined_at`) VALUES
(1, 'Principal', 'admin@namaste.edu', '$2y$10$8r2x4kqlP6B0CqXdiKW0dOF7HGTX1WCOUDduePV/HGncNPgua3UWS', 'Administration', 'admin', '2026-02-03'),
(2, 'Sriyog Dhital', 'sriyog@gmail.com', '$2y$10$Zr0uEu1LzguuaFUE6V3VzOjZ960V5445YJuYRm7WV8cPz0gt30Mwq', 'Grade 11 - Science', 'student', '2026-02-03'),
(3, 'Safal Joshi', 'safal@gmail.com', '$2y$10$gsXWhppsVw7mg9pmsUcsTeSDd6X46i4frC2pfIWcmm7wdi7KCs0CO', 'Grade 11 - Management', 'student', '2026-02-03'),
(4, 'Bishwas siku', 'siku@gmail.com', '$2y$10$qiqLvN5.i4GRjReUSrKzpeKahbD4OqYP9mC5a.5jwKNpXr/0T3.Xe', 'Grade 11 - Science', 'student', '2026-02-03'),
(6, 'anmol magar', 'anmol@gmail.com', '$2y$10$lNM2H5lmSlFLmL60L3QiY.64z1Qv8WE26GEj2SVze3SR4jWbYtI9u', 'Grade 11 - Science', 'student', '2026-02-03'),
(8, 'raja ramlal', 'raja@gmail.com', '$2y$10$7NGi0I.USbqC/Yra80KitO9f4cNM9qV/kg.u15rjzoSVKo3r20cPS', 'Grade 11 - Science', 'student', '2026-02-03'),
(11, 'Anil Sharma', 'anil@gmail.com', '$2y$10$otAxciMQ44tzPNRtg2qsPO9gSZ.eUjXH2Uf81wPZ29rrHi72wXBCW', 'Grade 11 - Science', 'student', '2026-02-03'),
(12, 'Chandra Magar', 'chandra@gmail.com', '$2y$10$/Zfa1ld.KQoAXL3EewJ6wulc8xzwxzpSsooxNVY/vX50pI0dW1lIO', 'Grade 11 - Science', 'student', '2026-02-03'),
(13, 'Fiona', 'raelikesdynamite@gmail.com', '$2y$10$u6vzF2n6yy2BoCXzbX2UQOcLxs/pVTorD0zlIkHPUme9bA98nyyYi', 'Grade 11 - Science', 'student', '2026-02-03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`studentId`,`attendance_date`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`recordId`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`studentId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `recordId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `studentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
