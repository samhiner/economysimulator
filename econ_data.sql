-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 03, 2018 at 06:16 PM
-- Server version: 5.7.21
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `econ_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `game1players`
--

DROP TABLE IF EXISTS `game1players`;
CREATE TABLE IF NOT EXISTS `game1players` (
  `id` int(11) NOT NULL,
  `class` int(11) NOT NULL,
  `balance` int(11) NOT NULL,
  `glass` int(11) NOT NULL DEFAULT '0',
  `plastic` int(11) NOT NULL DEFAULT '0',
  `alum` int(11) NOT NULL DEFAULT '0',
  `sili` int(11) NOT NULL DEFAULT '0',
  `steel` int(11) NOT NULL DEFAULT '0',
  `bike` int(11) NOT NULL DEFAULT '0',
  `tv` int(11) NOT NULL DEFAULT '0',
  `shield` int(11) NOT NULL DEFAULT '0',
  `phone` int(11) NOT NULL DEFAULT '0',
  `car` int(11) NOT NULL DEFAULT '0',
  `laptop` int(11) NOT NULL DEFAULT '0',
  `smarttv` int(11) NOT NULL DEFAULT '0',
  `dogtags` int(11) NOT NULL DEFAULT '0',
  `shaver` int(11) NOT NULL DEFAULT '0',
  `blender` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `game1players`
--

INSERT INTO `game1players` (`id`, `class`, `balance`, `glass`, `plastic`, `alum`, `sili`, `steel`, `bike`, `tv`, `shield`, `phone`, `car`, `laptop`, `smarttv`, `dogtags`, `shaver`, `blender`) VALUES
(0, 7, 10000000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `game1prodhistory`
--

DROP TABLE IF EXISTS `game1prodhistory`;
CREATE TABLE IF NOT EXISTS `game1prodhistory` (
  `item` varchar(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `iterator` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`iterator`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `game1prodorders`
--

DROP TABLE IF EXISTS `game1prodorders`;
CREATE TABLE IF NOT EXISTS `game1prodorders` (
  `item` varchar(11) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT ' 1 = bid,  0 = ask',
  `price` int(11) NOT NULL,
  `amt` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `iterator` int(11) NOT NULL AUTO_INCREMENT COMMENT 'does nothing',
  PRIMARY KEY (`iterator`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `game1sechistory`
--

DROP TABLE IF EXISTS `game1sechistory`;
CREATE TABLE IF NOT EXISTS `game1sechistory` (
  `item` varchar(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `iterator` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`iterator`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `game1sechistory`
--

-- --------------------------------------------------------

--
-- Table structure for table `game1secorders`
--

DROP TABLE IF EXISTS `game1secorders`;
CREATE TABLE IF NOT EXISTS `game1secorders` (
  `item` varchar(11) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT ' 1 = bid,  0 = ask',
  `price` int(11) NOT NULL,
  `amt` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `iterator` int(11) NOT NULL AUTO_INCREMENT COMMENT 'does nothing',
  PRIMARY KEY (`iterator`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `game1secorders`
--

-- --------------------------------------------------------

--
-- Table structure for table `game1shares`
--

DROP TABLE IF EXISTS `game1shares`;
CREATE TABLE IF NOT EXISTS `game1shares` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `game1shares`
--

INSERT INTO `game1shares` (`id`) VALUES
(0);

-- --------------------------------------------------------

--
-- Table structure for table `game1time`
--

DROP TABLE IF EXISTS `game1time`;
CREATE TABLE IF NOT EXISTS `game1time` (
  `id` int(11) NOT NULL,
  `makeDate` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT 'set as 2000 when not used',
  `decayDate1` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT 'set as 2000 before first use',
  `haveSupply1` tinyint(11) NOT NULL DEFAULT '0',
  `decayDate2` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT 'set as 2000 before first use',
  `haveSupply2` tinyint(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `game1time`
--

INSERT INTO `game1time` (`id`, `makeDate`, `decayDate1`, `haveSupply1`, `decayDate2`, `haveSupply2`) VALUES
(0, '2000-01-01 00:00:00', '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00', 0);

-- --------------------------------------------------------

--
-- Here lies `game1voting`.
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(0, 'admin', 'c55629309132f201513e5ed77737849d1f9b5d92');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
