-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2021 at 07:37 AM
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
  `bill_amount` varchar(200) NOT NULL,
  `con_id` int(11) NOT NULL,
  `bill_date` date NOT NULL,
  `taxable_amount` varchar(255) NOT NULL,
  `vat_amount` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_bill`
--

INSERT INTO `tbl_bill` (`bid`, `bill_title`, `bill_no`, `bill_amount`, `con_id`, `bill_date`, `taxable_amount`, `vat_amount`) VALUES
(1, 'new paid bill', 1, '8000', 74, '2021-10-02', '7900', '1027');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commission`
--

CREATE TABLE `tbl_commission` (
  `com_id` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `conid` int(11) NOT NULL,
  `com_amount` varchar(120) NOT NULL,
  `pay_id` int(11) NOT NULL,
  `com_received_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_commission`
--

INSERT INTO `tbl_commission` (`com_id`, `bid`, `conid`, `com_amount`, `pay_id`, `com_received_date`) VALUES
(1, 1, 74, '9600.12', 1, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commission_checkout`
--

CREATE TABLE `tbl_commission_checkout` (
  `check_id` int(11) NOT NULL,
  `checkout_amount` varchar(120) NOT NULL,
  `com_id` int(11) NOT NULL,
  `checkout_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commission_details`
--

CREATE TABLE `tbl_commission_details` (
  `comd_id` int(11) NOT NULL,
  `com_id` int(11) NOT NULL,
  `received_amount` varchar(120) NOT NULL,
  `date` date NOT NULL
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
  `con_amount` varchar(20) NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `visibility` tinyint(1) NOT NULL,
  `commission_amt` varchar(122) NOT NULL,
  `contractor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_contract`
--

INSERT INTO `tbl_contract` (`conid`, `con_title`, `org_id`, `start_date`, `end_date`, `file`, `con_amount`, `payment_mode`, `status`, `visibility`, `commission_amt`, `contractor`) VALUES
(74, 'Website Contract', 74, '2021-09-23', '2021-11-17', '', '10000', 'monthly', 1, 0, '1200', 0);

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

--
-- Dumping data for table `tbl_notes`
--

INSERT INTO `tbl_notes` (`nid`, `note_title`, `note`, `date`, `pin`) VALUES
(5, 'cms problems', 'ajax ma add garna milena and add payment ma ajax ma js chalena', '2021-09-22', 0);

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
  `org_phone` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_organization`
--

INSERT INTO `tbl_organization` (`oid`, `org_name`, `pan_no`, `vat_no`, `org_email`, `org_phone`, `status`) VALUES
(74, 'Org Name', 11121, 1234543, 'tamangdipesh7391@gmail.com', '9818807391', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment`
--

CREATE TABLE `tbl_payment` (
  `pid` int(11) NOT NULL,
  `pay_amount` varchar(200) NOT NULL,
  `pay_date` date NOT NULL,
  `bill_id` int(11) NOT NULL,
  `receipt_no` bigint(20) NOT NULL,
  `file` varchar(255) NOT NULL,
  `check_no` bigint(20) NOT NULL,
  `with_tds` varchar(122) NOT NULL,
  `vat_amount` varchar(200) NOT NULL,
  `tds_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_payment`
--

INSERT INTO `tbl_payment` (`pid`, `pay_amount`, `pay_date`, `bill_id`, `receipt_no`, `file`, `check_no`, `with_tds`, `vat_amount`, `tds_status`) VALUES
(11, '5000', '2021-10-03', 1, 1, '', 0, '4925', '1027', 1);

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
  ADD PRIMARY KEY (`bid`);

--
-- Indexes for table `tbl_commission`
--
ALTER TABLE `tbl_commission`
  ADD PRIMARY KEY (`com_id`);

--
-- Indexes for table `tbl_commission_checkout`
--
ALTER TABLE `tbl_commission_checkout`
  ADD PRIMARY KEY (`check_id`);

--
-- Indexes for table `tbl_commission_details`
--
ALTER TABLE `tbl_commission_details`
  ADD PRIMARY KEY (`comd_id`);

--
-- Indexes for table `tbl_contact_person`
--
ALTER TABLE `tbl_contact_person`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `tbl_contract`
--
ALTER TABLE `tbl_contract`
  ADD PRIMARY KEY (`conid`);

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
  ADD PRIMARY KEY (`pid`);

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
  MODIFY `bid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_commission`
--
ALTER TABLE `tbl_commission`
  MODIFY `com_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_commission_checkout`
--
ALTER TABLE `tbl_commission_checkout`
  MODIFY `check_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_commission_details`
--
ALTER TABLE `tbl_commission_details`
  MODIFY `comd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_contact_person`
--
ALTER TABLE `tbl_contact_person`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_contract`
--
ALTER TABLE `tbl_contract`
  MODIFY `conid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `tbl_notes`
--
ALTER TABLE `tbl_notes`
  MODIFY `nid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_organization`
--
ALTER TABLE `tbl_organization`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
