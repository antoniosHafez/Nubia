-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 22, 2014 at 08:55 PM
-- Server version: 5.5.37
-- PHP Version: 5.3.10-1ubuntu3.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
CREATE DATABASE Telemedicine;
USE Telemedicine;
--
-- Database: `Telemedicine`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE IF NOT EXISTS `address` (
  `id` int(11) NOT NULL,
  `street` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `posta` varchar(100) NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `disease`
--

CREATE TABLE IF NOT EXISTS `disease` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `disease_history`
--

CREATE TABLE IF NOT EXISTS `disease_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `disease_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `disease_id` (`disease_id`),
  KEY `patient_id` (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `medication`
--

CREATE TABLE IF NOT EXISTS `medication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `medication_history`
--

CREATE TABLE IF NOT EXISTS `medication_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `medication_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `physician_id` int(11) NOT NULL,
  `visit_request_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `medication_id` (`medication_id`),
  KEY `visit_request_id` (`visit_request_id`),
  KEY `physician_id` (`physician_id`),
  KEY `medication_id_2` (`medication_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE IF NOT EXISTS `patient` (
  `id` int(11) NOT NULL,
  `martial status` varchar(50) NOT NULL,
  `DOB` varchar(50) NOT NULL,
  `IDNumber` int(15) NOT NULL,
  `job` varchar(50) NOT NULL,
  `ins_no` int(11) NOT NULL,
  `gp_id` int(11) NOT NULL,
  KEY `id` (`id`),
  KEY `gp_id` (`gp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE IF NOT EXISTS `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `sex` enum('M','F') NOT NULL,
  `join_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `physican`
--

CREATE TABLE IF NOT EXISTS `physican` (
  `id` int(11) NOT NULL,
  `password` char(32) NOT NULL,
  `email` varchar(100) NOT NULL,
  `title` varchar(50) NOT NULL,
  `privilage` varchar(300) NOT NULL,
  `group_id` int(11) NOT NULL,
  `foundation` varchar(50) NOT NULL,
  KEY `group_id` (`group_id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `radiation`
--

CREATE TABLE IF NOT EXISTS `radiation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `radiation_result`
--

CREATE TABLE IF NOT EXISTS `radiation_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `radiation_data` varchar(100) NOT NULL,
  `radiation_id` int(11) NOT NULL,
  `visit_request_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `radiation_id` (`radiation_id`),
  KEY `visit_request_id` (`visit_request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sugery_history`
--

CREATE TABLE IF NOT EXISTS `sugery_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `physician_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `surgery_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `physician_id` (`physician_id`),
  KEY `surgery_id` (`surgery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `surgery`
--

CREATE TABLE IF NOT EXISTS `surgery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operation` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `test_result`
--

CREATE TABLE IF NOT EXISTS `test_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_data` varchar(200) NOT NULL,
  `test_id` int(11) NOT NULL,
  `visit_request_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `test_id` (`test_id`),
  KEY `visit_request_id` (`visit_request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `privilage` varchar(300) NOT NULL,
  `password` char(32) NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visit_request`
--

CREATE TABLE IF NOT EXISTS `visit_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `group_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `physican_id` int(11) DEFAULT NULL,
  `patient_id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `notes` text NOT NULL,
  `gp_id` int(11) NOT NULL,
  `depandency` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `physican_id` (`physican_id`),
  KEY `gp_id` (`gp_id`),
  KEY `patient_id` (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vital`
--

CREATE TABLE IF NOT EXISTS `vital` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vital_result`
--

CREATE TABLE IF NOT EXISTS `vital_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vital_data` varchar(200) NOT NULL,
  `vital_id` int(11) NOT NULL,
  `visit_request_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vital_id` (`vital_id`),
  KEY `visit_request_id` (`visit_request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`id`) REFERENCES `person` (`id`);

--
-- Constraints for table `disease_history`
--
ALTER TABLE `disease_history`
  ADD CONSTRAINT `disease_history_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `disease_history_ibfk_1` FOREIGN KEY (`disease_id`) REFERENCES `disease` (`id`);

--
-- Constraints for table `medication_history`
--
ALTER TABLE `medication_history`
  ADD CONSTRAINT `medication_history_ibfk_4` FOREIGN KEY (`visit_request_id`) REFERENCES `visit_request` (`id`),
  ADD CONSTRAINT `medication_history_ibfk_1` FOREIGN KEY (`medication_id`) REFERENCES `medication` (`id`),
  ADD CONSTRAINT `medication_history_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `medication_history_ibfk_3` FOREIGN KEY (`physician_id`) REFERENCES `physican` (`id`);

--
-- Constraints for table `patient`
--
ALTER TABLE `patient`
  ADD CONSTRAINT `patient_ibfk_2` FOREIGN KEY (`gp_id`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`id`) REFERENCES `person` (`id`);

--
-- Constraints for table `physican`
--
ALTER TABLE `physican`
  ADD CONSTRAINT `physican_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`),
  ADD CONSTRAINT `physican_ibfk_2` FOREIGN KEY (`id`) REFERENCES `person` (`id`);

--
-- Constraints for table `radiation_result`
--
ALTER TABLE `radiation_result`
  ADD CONSTRAINT `radiation_result_ibfk_2` FOREIGN KEY (`visit_request_id`) REFERENCES `visit_request` (`id`),
  ADD CONSTRAINT `radiation_result_ibfk_1` FOREIGN KEY (`radiation_id`) REFERENCES `radiation` (`id`);

--
-- Constraints for table `sugery_history`
--
ALTER TABLE `sugery_history`
  ADD CONSTRAINT `sugery_history_ibfk_3` FOREIGN KEY (`surgery_id`) REFERENCES `surgery` (`id`),
  ADD CONSTRAINT `sugery_history_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `sugery_history_ibfk_2` FOREIGN KEY (`physician_id`) REFERENCES `physican` (`id`);

--
-- Constraints for table `test_result`
--
ALTER TABLE `test_result`
  ADD CONSTRAINT `test_result_ibfk_2` FOREIGN KEY (`visit_request_id`) REFERENCES `visit_request` (`id`),
  ADD CONSTRAINT `test_result_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id`) REFERENCES `person` (`id`);

--
-- Constraints for table `visit_request`
--
ALTER TABLE `visit_request`
  ADD CONSTRAINT `visit_request_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`),
  ADD CONSTRAINT `visit_request_ibfk_3` FOREIGN KEY (`physican_id`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `visit_request_ibfk_4` FOREIGN KEY (`gp_id`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `visit_request_ibfk_5` FOREIGN KEY (`patient_id`) REFERENCES `person` (`id`);

--
-- Constraints for table `vital_result`
--
ALTER TABLE `vital_result`
  ADD CONSTRAINT `vital_result_ibfk_2` FOREIGN KEY (`visit_request_id`) REFERENCES `visit_request` (`id`),
  ADD CONSTRAINT `vital_result_ibfk_1` FOREIGN KEY (`vital_id`) REFERENCES `vital` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
