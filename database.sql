SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `zend`
--

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `building` varchar(50) NOT NULL,
  `attendant` varchar(50) NOT NULL,
  `posts` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `number` (`number`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `number`, `name`, `building`, `attendant`, `posts`) VALUES
(1, '100', 'Pracownia Automatyki', 'D', 'brak','15'),
(2, '101', 'Pracownia Elektroniki', 'D', 'Jan Kowalski', '15');

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE IF NOT EXISTS `equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `quantity` varchar(20) NOT NULL,
  `destiny` varchar(30) NOT NULL,
  `damaged` varchar(30) NOT NULL,
  `hire` varchar(30) NOT NULL,
  `adddate` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `cid`, `name`, `quantity`, `destiny`, `damaged`, `hire`, `adddate`) VALUES
(1, 1, 'Laptop', '15', 'dydaktyka', 'nie', 'nie', '2015-09-01');

--
-- Constraints for table `equipment`
--
ALTER TABLE `equipment`
  ADD CONSTRAINT `cid` FOREIGN KEY (`cid`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;