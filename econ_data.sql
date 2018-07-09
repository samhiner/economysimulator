-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 09, 2018 at 03:22 AM
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
(9, 4, 700, 59, 0, 53, 77, 3, 2, 0, 0, 56, 358, 0, 0, 0, 0, 2),
(21, 2, 2000, 0, 0, 0, 0, 0, 0, 0, 2, 0, 0, 0, 0, 0, 0, 0),
(28, 6, 3000, 0, 0, 0, 0, 0, 118, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(29, 9, 2000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1348);

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `game1prodorders`
--

INSERT INTO `game1prodorders` (`item`, `type`, `price`, `amt`, `timestamp`, `id`, `iterator`) VALUES
('bike', 0, 500, 3, 1528766351, 28, 1),
('bike', 0, 600, 17, 1528766364, 28, 2),
('bike', 0, 700, 26, 1528766372, 28, 3),
('bike', 1, 499, 2, 1528766456, 9, 4),
('bike', 1, 400, 1, 1528766591, 9, 5);

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
(9, '2000-01-01 00:00:00', '2018-06-25 04:10:08', 0, '2018-07-02 15:04:39', 1),
(21, '2000-01-01 00:00:00', '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00', 0),
(28, '2000-01-01 00:00:00', '2000-01-01 00:00:00', 0, '2000-01-01 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(8, 'uu', '0708f09be4759012a5eff2b91916e0a548bacc1b', 'jj'),
(9, 'hey', 'aa0d576b8de5cef4668d69b6b9826ca6bd3f219c', 'hey'),
(14, 'yu', 'yu', 'yu'),
(16, 'ji', 'ji', 'ji'),
(17, '789', '789', '789'),
(18, 'uioo', 'uiouio', 'uio'),
(19, 'uiuoiui', 'uiouio', 'uiouoi'),
(20, 'hu', 'hu', 'hu'),
(21, 'ui', 'ui', 'ui'),
(22, 'jlk', 'jk', 'jlk'),
(23, 'jk', 'jk', 'jk'),
(24, 'io', 'io', 'io'),
(25, 'gh', 'gh', 'gh'),
(26, 'ty', 'ty', 'ty'),
(27, 'hj', 'hj', 'hj'),
(28, 'yui', 'yui', 'yiu'),
(29, 'kl', 'dd95c390bec1c2a71907bd8fcc61abe355a69332', 'kl');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
