-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2024 at 01:48 AM
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
-- Database: `filmfuse_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `aid` int(10) UNSIGNED NOT NULL,
  `addressLine1` varchar(100) DEFAULT NULL,
  `addressLine2` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `postCode` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `uid` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartID` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_product`
--

CREATE TABLE `cart_product` (
  `cartID` int(10) UNSIGNED NOT NULL,
  `pid` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `CategoryID` int(10) UNSIGNED NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Picture` varchar(255) DEFAULT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `uid` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`uid`, `first_name`, `last_name`, `username`, `email`, `password`) VALUES
(4, 'Al-Ameen', 'Baruwa', 'amz123', 'ameenbaruwa@gmail.com', '$2y$10$lEm/59yX02h2V/exm32Ate3ClgPnrk1VHtQk/zWy5uof3UHrtQY4q');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `orderID` int(10) UNSIGNED NOT NULL,
  `pid` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` int(10) UNSIGNED NOT NULL,
  `orderAmount` decimal(10,2) DEFAULT NULL,
  `orderDate` date DEFAULT NULL,
  `uid` int(10) UNSIGNED DEFAULT NULL,
  `paymentID` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentID` int(10) UNSIGNED NOT NULL,
  `paymentType` varchar(50) DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `pid` int(10) UNSIGNED NOT NULL,
  `p_Name` varchar(255) DEFAULT NULL,
  `p_Price` decimal(10,2) DEFAULT NULL,
  `p_RentPrice` decimal(10,2) DEFAULT NULL,
  `p_Description` text DEFAULT NULL,
  `p_ReleaseDate` date DEFAULT NULL,
  `categoryID` int(10) UNSIGNED DEFAULT NULL,
  `p_Stock` int(11) DEFAULT 100,
  `p_ageRating` varchar(100) DEFAULT NULL,
  `p_Duration` varchar(100) DEFAULT NULL,
  `p_Starring` varchar(200) DEFAULT NULL,
  `P_Director` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`aid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartID`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `cart_product`
--
ALTER TABLE `cart_product`
  ADD PRIMARY KEY (`cartID`,`pid`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`orderID`,`pid`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `uid` (`uid`),
  ADD KEY `paymentID` (`paymentID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `categoryID` (`categoryID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `aid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `CategoryID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `pid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `customer` (`uid`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `customer` (`uid`);

--
-- Constraints for table `cart_product`
--
ALTER TABLE `cart_product`
  ADD CONSTRAINT `cart_product_ibfk_1` FOREIGN KEY (`cartID`) REFERENCES `cart` (`cartID`),
  ADD CONSTRAINT `cart_product_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `product` (`pid`);

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`),
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `product` (`pid`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `customer` (`uid`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`paymentID`) REFERENCES `payment` (`paymentID`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`categoryID`) REFERENCES `category` (`CategoryID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
