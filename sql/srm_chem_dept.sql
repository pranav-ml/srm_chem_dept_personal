-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 13, 2021 at 10:32 AM
-- Server version: 5.7.11
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `srm_chem_dept`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `announce` varchar(255) NOT NULL,
  `autosorbiqann` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`announce`, `autosorbiqann`) VALUES
('.........CHEM DEPT IS NOT FULLY FUNCTIONAL DUE TO COVID-19 OUTBREAK.........', 'announcemnent 1');

-- --------------------------------------------------------

--
-- Table structure for table `autosorbiq_order_details`
--

CREATE TABLE `autosorbiq_order_details` (
  `order_id` int(11) NOT NULL,
  `nature_of_sample` varchar(255) NOT NULL,
  `porous_nature` varchar(255) NOT NULL,
  `analysis_type` text NOT NULL,
  `degassing_temp` text NOT NULL,
  `degassing_condition` text NOT NULL,
  `additional_details` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='The preferences and details of Autosorb IQ orders. ';

-- --------------------------------------------------------

--
-- Table structure for table `link`
--

CREATE TABLE `link` (
  `pid` int(11) NOT NULL,
  `table_name` varchar(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='This table stores the  link between product id and the product';

--
-- Dumping data for table `link`
--

INSERT INTO `link` (`pid`, `table_name`) VALUES
(1, 'slot_autosorbiq');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `user_id` int(11) NOT NULL,
  `date_of_order` date NOT NULL,
  `slot_time` text NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `slot_no` varchar(10) NOT NULL,
  `cancel_reason` text,
  `timestamp` datetime DEFAULT NULL,
  `ucomment` text,
  `payinfo` text,
  `tbank` varchar(100) DEFAULT NULL,
  `tid` varchar(100) DEFAULT NULL,
  `tdate` varchar(100) DEFAULT NULL,
  `tamm` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `order_under_process`
--

CREATE TABLE `order_under_process` (
  `inst_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_code` varchar(60) NOT NULL,
  `product_name` varchar(60) NOT NULL,
  `product_desc` text NOT NULL,
  `product_img_name` varchar(60) NOT NULL,
  `price` text NOT NULL,
  `pi` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_code`, `product_name`, `product_desc`, `product_img_name`, `price`, `pi`) VALUES
(1, 'CHEM1', 'Autosorb iQ-C-AG/MP/XR', 'Autosorb iQ- Chemisorption (&Physisorption) Gas sorption Analyzer', 'autosorb-iq.png', '1500.00', 'renjiths@srmist.edu.in');

-- --------------------------------------------------------

--
-- Table structure for table `slot_autosorbiq`
--

CREATE TABLE `slot_autosorbiq` (
  `slot_id` int(10) NOT NULL,
  `slot_date` date NOT NULL,
  `slot_9` varchar(255) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='slot table for scale.';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `lname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `institute` varchar(255) CHARACTER SET utf8 NOT NULL,
  `iid` varchar(100) CHARACTER SET utf8 NOT NULL,
  `address` text CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `pwd` varchar(255) CHARACTER SET utf8 NOT NULL,
  `phno` varchar(15) NOT NULL,
  `reg_date` text CHARACTER SET utf8,
  `last_active` text CHARACTER SET utf8,
  `last_ip` text CHARACTER SET utf8,
  `last_ip_proxy` text CHARACTER SET utf8,
  `type` varchar(20) NOT NULL DEFAULT 'user',
  `hash` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `active` int(2) DEFAULT '0',
  `ustatus` int(10) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `link`
--
ALTER TABLE `link`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `slot_autosorbiq`
--
ALTER TABLE `slot_autosorbiq`
  ADD PRIMARY KEY (`slot_id`);

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
-- AUTO_INCREMENT for table `link`
--
ALTER TABLE `link`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `slot_autosorbiq`
--
ALTER TABLE `slot_autosorbiq`
  MODIFY `slot_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=562;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
