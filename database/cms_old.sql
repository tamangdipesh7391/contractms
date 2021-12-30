-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2021 at 07:25 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bill`
--

CREATE TABLE `tbl_bill` (
  `bid` int(11) NOT NULL,
  `bill_title` varchar(255) NOT NULL,
  `bill_no` bigint(20) NOT NULL,
  `bill_amount` bigint(20) NOT NULL,
  `con_id` int(11) NOT NULL,
  `bill_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contact_person`
--

CREATE TABLE `tbl_contact_person` (
  `cid` int(11) NOT NULL,
  `c_name` varchar(255) NOT NULL,
  `c_phone` bigint(20) NOT NULL,
  `c_email` varchar(120) NOT NULL,
  `org_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contract`
--

CREATE TABLE `tbl_contract` (
  `conid` int(11) NOT NULL,
  `con_title` varchar(255) NOT NULL,
  `org_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `file` varchar(255) NOT NULL,
  `con_amount` bigint(20) NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `visibility` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notes`
--

CREATE TABLE `tbl_notes` (
  `nid` int(11) NOT NULL,
  `note_title` varchar(255) NOT NULL,
  `note` text NOT NULL,
  `date` date NOT NULL,
  `pin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_organization`
--

CREATE TABLE `tbl_organization` (
  `oid` int(11) NOT NULL,
  `org_name` varchar(255) NOT NULL,
  `pan_no` bigint(20) NOT NULL,
  `vat_no` bigint(20) NOT NULL,
  `org_email` varchar(120) NOT NULL,
  `org_phone` bigint(20) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment`
--

CREATE TABLE `tbl_payment` (
  `pid` int(11) NOT NULL,
  `pay_amount` bigint(20) NOT NULL,
  `pay_date` date NOT NULL,
  `bill_id` int(11) NOT NULL,
  `receipt_no` bigint(20) NOT NULL,
  `file` varchar(255) NOT NULL,
  `check_no` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `uid` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(120) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`uid`, `username`, `password`, `user_type`, `status`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'adminstrator', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_bill`
--
ALTER TABLE `tbl_bill`
  ADD PRIMARY KEY (`bid`),
  ADD KEY `con_id` (`con_id`);

--
-- Indexes for table `tbl_contact_person`
--
ALTER TABLE `tbl_contact_person`
  ADD PRIMARY KEY (`cid`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `tbl_contract`
--
ALTER TABLE `tbl_contract`
  ADD PRIMARY KEY (`conid`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `tbl_notes`
--
ALTER TABLE `tbl_notes`
  ADD PRIMARY KEY (`nid`);

--
-- Indexes for table `tbl_organization`
--
ALTER TABLE `tbl_organization`
  ADD PRIMARY KEY (`oid`),
  ADD UNIQUE KEY `pan_no` (`pan_no`);

--
-- Indexes for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `bill_id` (`bill_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_bill`
--
ALTER TABLE `tbl_bill`
  MODIFY `bid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tbl_contact_person`
--
ALTER TABLE `tbl_contact_person`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_contract`
--
ALTER TABLE `tbl_contract`
  MODIFY `conid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `tbl_notes`
--
ALTER TABLE `tbl_notes`
  MODIFY `nid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_organization`
--
ALTER TABLE `tbl_organization`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_bill`
--
ALTER TABLE `tbl_bill`
  ADD CONSTRAINT `tbl_bill_ibfk_1` FOREIGN KEY (`con_id`) REFERENCES `tbl_contract` (`conid`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_contact_person`
--
ALTER TABLE `tbl_contact_person`
  ADD CONSTRAINT `tbl_contact_person_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `tbl_organization` (`oid`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_contract`
--
ALTER TABLE `tbl_contract`
  ADD CONSTRAINT `tbl_contract_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `tbl_organization` (`oid`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  ADD CONSTRAINT `tbl_payment_ibfk_1` FOREIGN KEY (`bill_id`) REFERENCES `tbl_bill` (`bid`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
