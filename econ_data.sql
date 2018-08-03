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
(0, 7, 3650, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(9, 4, 3240, 57, 0, 55, 55, 6, 4, 0, 20, 55, 359, 0, 0, 0, 0, 0),
(36, 0, 4750, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(37, 0, 6000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

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

--
-- Dumping data for table `game1prodhistory`
--

INSERT INTO `game1prodhistory` (`item`, `timestamp`, `price`, `iterator`) VALUES
('shield', 1531450965, 1, 1),
('shield', 1531451035, 1, 2),
('phone', 1533061283, 1, 3),
('car', 1533262558, 1, 4);

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

INSERT INTO `game1sechistory` (`item`, `timestamp`, `price`, `iterator`) VALUES
('yui', 1533255829, 50, 1),
('hey', 1533256565, 1, 2),
('hey', 1533256565, 1, 3),
('hey', 1533262429, 2, 4),
('hey', 1533262429, 2, 5),
('hey', 1533262429, 2, 6),
('hey', 1533262429, 2, 7),
('hey', 1533262429, 2, 8),
('hey', 1533262448, 2, 9),
('hey', 1533262448, 2, 10),
('hey', 1533262448, 2, 11),
('hey', 1533262448, 2, 12),
('hey', 1533262481, 1, 13),
('hey', 1533262481, 1, 14),
('hey', 1533262481, 1, 15),
('hey', 1533262481, 1, 16),
('hey', 1533262481, 1, 17),
('hey', 1533262481, 1, 18),
('hey', 1533262519, 1, 19),
('hey', 1533262528, 1, 20),
('hey', 1533262683, 1, 21),
('hey', 1533262807, 1, 22),
('hey', 1533262862, 1, 23),
('hey', 1533262874, 1, 24),
('yui', 1533312422, 50, 25),
('yui', 1533312443, 50, 26);

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

INSERT INTO `game1secorders` (`item`, `type`, `price`, `amt`, `timestamp`, `id`, `iterator`) VALUES
('yui', 0, 50, 23, 1532916835, 0, 2),
('hey', 0, 50, 1, 1533319108, 9, 3);

-- --------------------------------------------------------

--
-- Table structure for table `game1shares`
--

DROP TABLE IF EXISTS `game1shares`;
CREATE TABLE IF NOT EXISTS `game1shares` (
  `id` int(11) NOT NULL,
  `hey` int(11) DEFAULT '0',
  `yui` int(11) DEFAULT '0',
  `me` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `game1shares`
--

INSERT INTO `game1shares` (`id`, `hey`, `yui`, `me`) VALUES
(0, 0, 25, 0),
(9, 99, 2, 0),
(36, 0, 75, 0),
(37, 0, 0, 100);

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
(0, '2000-01-01 00:00:00', '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00', 0),
(9, '2000-01-01 00:00:00', '2018-07-20 10:21:11', 0, '2018-07-20 10:21:11', 0),
(36, '2000-01-01 00:00:00', '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00', 0),
(37, '2000-01-01 00:00:00', '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `game1voting`
--

DROP TABLE IF EXISTS `game1voting`;
CREATE TABLE IF NOT EXISTS `game1voting` (
  `company` varchar(100) NOT NULL,
  `takeover` longtext,
  `votingends` int(11) NOT NULL,
  PRIMARY KEY (`company`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `game1voting`
--

INSERT INTO `game1voting` (`company`, `takeover`, `votingends`) VALUES
('hey', NULL, 1533404556),
('yui', NULL, 1533404556),
('me', NULL, 1533406386);

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
(0, 'admin', '7dd12f3a9afa0282a575b8ef99dea2a0c1becb51'),
(9, 'hey', 'aa0d576b8de5cef4668d69b6b9826ca6bd3f219c'),
(36, 'yui', 'e4278f8cf23079a0c1b90489c022050ebc2a63f7'),
(37, 'me', '48e6f9f95d1cc9e590d97378972fe1d03746a8f2');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
