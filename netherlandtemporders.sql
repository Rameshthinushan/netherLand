-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2022 at 05:18 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `demofinal`
--

-- --------------------------------------------------------

--
-- Table structure for table `netherlandtemporders`
--

CREATE TABLE `netherlandtemporders` (
  `id` int(11) NOT NULL,
  `orderID` text NOT NULL,
  `status` text NOT NULL,
  `date` datetime NOT NULL,
  `channel` text NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `telephone` text NOT NULL,
  `email` text NOT NULL,
  `currency` text NOT NULL,
  `ordertotal` decimal(10,2) NOT NULL,
  `name` text NOT NULL,
  `sku` text NOT NULL,
  `quantity` int(11) NOT NULL,
  `lineitemtotal` decimal(10,2) NOT NULL,
  `flags` text NOT NULL,
  `shippingservice` text NOT NULL,
  `shippingaddresscompany` text NOT NULL,
  `shippingaddressline1` text NOT NULL,
  `shippingaddressline2` text NOT NULL,
  `shippingaddressline3` text NOT NULL,
  `shippingaddressregion` text NOT NULL,
  `shippingaddresscity` text NOT NULL,
  `shippingaddresspostcode` text NOT NULL,
  `shippingaddresscountry` text NOT NULL,
  `shippingaddresscountrycode` text NOT NULL,
  `booking` text NOT NULL,
  `csvdate` date NOT NULL,
  `unit` text NOT NULL,
  `addedby` text NOT NULL,
  `merge` text NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `zenstoresOrderTotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
