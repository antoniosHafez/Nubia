-- MySQL dump 10.13  Distrib 5.5.35, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: Telemedicine
-- ------------------------------------------------------
-- Server version	5.5.35-0ubuntu0.12.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Resources`
--
DROP DATABASE IF EXISTS `Telemedicine`;
CREATE DATABASE Telemedicine;
USE Telemedicine;

DROP TABLE IF EXISTS `resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `action` varchar(45) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `routeName` varchar(255) DEFAULT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `default` tinyint(1) DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `RoleResources`
--

DROP TABLE IF EXISTS `role_resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `resources_id` (`resource_id`),
  CONSTRAINT `role_resources_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `role_resources_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=414 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `street` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `postal` varchar(100) NOT NULL,
  PRIMARY KEY (`address_id`),
  KEY `id` (`id`),
  CONSTRAINT `address_ibfk_1` FOREIGN KEY (`id`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `disease`
--

DROP TABLE IF EXISTS `disease`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `disease` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `disease_history`
--

DROP TABLE IF EXISTS `disease_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `disease_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `disease_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `visit_request_id` int(11) DEFAULT NULL,
  `user_modified_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `disease_id` (`disease_id`),
  KEY `patient_id` (`patient_id`),
  KEY `visit_request_id` (`visit_request_id`),
  KEY `user_modified_id` (`user_modified_id`),
  CONSTRAINT `disease_history_ibfk_1` FOREIGN KEY (`disease_id`) REFERENCES `disease` (`id`),
  CONSTRAINT `disease_history_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `person` (`id`),
  CONSTRAINT `disease_history_ibfk_3` FOREIGN KEY (`visit_request_id`) REFERENCES `visit_request` (`id`),
  CONSTRAINT `disease_history_ibfk_5` FOREIGN KEY (`user_modified_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `group`
--

DROP TABLE IF EXISTS `group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `medication`
--

DROP TABLE IF EXISTS `medication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `medication_history`
--

DROP TABLE IF EXISTS `medication_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medication_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `medication_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `physician_id` int(11) DEFAULT NULL,
  `visit_request_id` int(11) DEFAULT NULL,
  `user_modified_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `medication_id` (`medication_id`),
  KEY `visit_request_id` (`visit_request_id`),
  KEY `physician_id` (`physician_id`),
  KEY `medication_id_2` (`medication_id`),
  KEY `user_modified_id` (`user_modified_id`),
  CONSTRAINT `medication_history_ibfk_1` FOREIGN KEY (`medication_id`) REFERENCES `medication` (`id`),
  CONSTRAINT `medication_history_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `person` (`id`),
  CONSTRAINT `medication_history_ibfk_3` FOREIGN KEY (`physician_id`) REFERENCES `physican` (`id`),
  CONSTRAINT `medication_history_ibfk_4` FOREIGN KEY (`visit_request_id`) REFERENCES `visit_request` (`id`),
  CONSTRAINT `medication_history_ibfk_5` FOREIGN KEY (`user_modified_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient` (
  `id` int(11) NOT NULL,
  `martial_status` varchar(50) NOT NULL,
  `DOB` varchar(50) NOT NULL,
  `IDNumber` int(15) NOT NULL,
  `job` varchar(50) NOT NULL,
  `ins_no` int(11) NOT NULL,
  `gp_id` int(11) NOT NULL,
  `patient_num` int(11) NOT NULL AUTO_INCREMENT,
  `user_modified_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`patient_num`),
  UNIQUE KEY `IDNumber` (`IDNumber`),
  KEY `id` (`id`),
  KEY `gp_id` (`gp_id`),
  KEY `user_modified_id` (`user_modified_id`),
  CONSTRAINT `patient_ibfk_1` FOREIGN KEY (`id`) REFERENCES `person` (`id`),
  CONSTRAINT `patient_ibfk_2` FOREIGN KEY (`gp_id`) REFERENCES `person` (`id`),
  CONSTRAINT `patient_ibfk_3` FOREIGN KEY (`user_modified_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `sex` enum('M','F') NOT NULL,
  `join_date` date NOT NULL,
  `status` enum('Active','Disabled') DEFAULT NULL,
  `type` enum('Admin','Patient','Clinician','Physician') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `physican`
--

DROP TABLE IF EXISTS `physican`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `physican` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `id` (`id`),
  CONSTRAINT `physican_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`),
  CONSTRAINT `physican_ibfk_2` FOREIGN KEY (`id`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `radiation`
--

DROP TABLE IF EXISTS `radiation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radiation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `radiation_result`
--

DROP TABLE IF EXISTS `radiation_result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `radiation_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `radiation_data` varchar(100) DEFAULT NULL,
  `radiation_id` int(11) NOT NULL,
  `visit_request_id` int(11) DEFAULT NULL,
  `type` enum('dep','pre') DEFAULT NULL,
  `user_modified_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `radiation_id` (`radiation_id`),
  KEY `visit_request_id` (`visit_request_id`),
  KEY `user_modified_id` (`user_modified_id`),
  CONSTRAINT `radiation_result_ibfk_1` FOREIGN KEY (`radiation_id`) REFERENCES `radiation` (`id`),
  CONSTRAINT `radiation_result_ibfk_2` FOREIGN KEY (`visit_request_id`) REFERENCES `visit_request` (`id`),
  CONSTRAINT `radiation_result_ibfk_3` FOREIGN KEY (`user_modified_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `surgery_history`
--

DROP TABLE IF EXISTS `surgery_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `surgery_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `surgery_id` int(11) NOT NULL,
  `visit_request_id` int(11) DEFAULT NULL,
  `user_modified_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `surgery_id` (`surgery_id`),
  KEY `visit_request_id` (`visit_request_id`),
  KEY `user_modified_id` (`user_modified_id`),
  CONSTRAINT `surgery_history_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `person` (`id`),
  CONSTRAINT `surgery_history_ibfk_3` FOREIGN KEY (`surgery_id`) REFERENCES `surgery` (`id`),
  CONSTRAINT `surgery_history_ibfk_4` FOREIGN KEY (`visit_request_id`) REFERENCES `visit_request` (`id`),
  CONSTRAINT `surgery_history_ibfk_5` FOREIGN KEY (`user_modified_id`) REFERENCES `user` (`id`)

) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `surgery`
--

DROP TABLE IF EXISTS `surgery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `surgery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operation` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `test_result`
--

DROP TABLE IF EXISTS `test_result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_data` varchar(200) DEFAULT NULL,
  `test_id` int(11) NOT NULL,
  `visit_request_id` int(11) DEFAULT NULL,
  `type` enum('dep','pre') DEFAULT NULL,
  `user_modified_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `test_id` (`test_id`),
  KEY `visit_request_id` (`visit_request_id`),
  KEY `user_modified_id` (`user_modified_id`),
  CONSTRAINT `test_result_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`),
  CONSTRAINT `test_result_ibfk_2` FOREIGN KEY (`visit_request_id`) REFERENCES `visit_request` (`id`),
  CONSTRAINT `test_result_ibfk_3` FOREIGN KEY (`user_modified_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `password` char(32) NOT NULL,
  `email` varchar(200) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '1',
  UNIQUE KEY `email` (`email`),
  KEY `id` (`id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id`) REFERENCES `person` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `visit_request`
--

DROP TABLE IF EXISTS `visit_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visit_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `group_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `physican_id` int(11) DEFAULT NULL,
  `patient_id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `notes` text NOT NULL,
  `gp_id` int(11) NOT NULL,
  `depandency` text NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `user_modified_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `physican_id` (`physican_id`),
  KEY `gp_id` (`gp_id`),
  KEY `patient_id` (`patient_id`),
  KEY `user_modified_id` (`user_modified_id`),
  CONSTRAINT `visit_request_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`),
  CONSTRAINT `visit_request_ibfk_3` FOREIGN KEY (`physican_id`) REFERENCES `person` (`id`),
  CONSTRAINT `visit_request_ibfk_4` FOREIGN KEY (`gp_id`) REFERENCES `person` (`id`),
  CONSTRAINT `visit_request_ibfk_5` FOREIGN KEY (`patient_id`) REFERENCES `person` (`id`),
  CONSTRAINT `visit_request_ibfk_6` FOREIGN KEY (`user_modified_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vital`
--

DROP TABLE IF EXISTS `vital`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vital` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vital_result`
--

DROP TABLE IF EXISTS `vital_result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vital_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vital_data` varchar(200) DEFAULT NULL,
  `vital_id` int(11) NOT NULL,
  `visit_request_id` int(11) DEFAULT NULL,
  `type` enum('dep','pre') DEFAULT NULL,
  `user_modified_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vital_id` (`vital_id`),
  KEY `visit_request_id` (`visit_request_id`),
  KEY `user_modified_id` (`user_modified_id`),
  CONSTRAINT `vital_result_ibfk_1` FOREIGN KEY (`vital_id`) REFERENCES `vital` (`id`),
  CONSTRAINT `vital_result_ibfk_2` FOREIGN KEY (`visit_request_id`) REFERENCES `visit_request` (`id`),
  CONSTRAINT `vital_result_ibfk_3` FOREIGN KEY (`user_modified_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


CREATE TABLE IF NOT EXISTS `radiation_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(400) DEFAULT NULL,
  `radiation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `radiation_id` (`radiation_id`),
  CONSTRAINT `radiation_images_ibfk_1` FOREIGN KEY (`radiation_id`) REFERENCES `radiation_result` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `test_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(400) DEFAULT NULL,
  `test_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `test_id` (`test_id`),
  CONSTRAINT `test_images_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `test_result` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `notification_admin`
--

CREATE TABLE IF NOT EXISTS `notification_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_created_id` int(11) DEFAULT NULL,
  `table_name` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `record_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_created_id` (`user_created_id`),
  CONSTRAINT `notification_admin_ibfk_1` FOREIGN KEY (`user_created_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;
--
-- Table structure for table `notification_clinician`
--

DROP TABLE IF EXISTS `notification_clinician`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification_clinician` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(20) NOT NULL,
  `physician_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `notification_physician`
--

DROP TABLE IF EXISTS `notification_physician`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification_physician` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(20) NOT NULL,
  `visit_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Triggers
--

DELIMITER $$
CREATE TRIGGER after_medication_history_update AFTER UPDATE ON
medication_history
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",NEW.user_modified_id,"medication_history","update",NEW.id,NOW());
END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_medication_history_insert AFTER INSERT ON
medication_history
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",NEW.user_modified_id,"medication_history","insert",NEW.id,NOW());
END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_medication_history_delete AFTER DELETE ON
medication_history
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",OLD.user_modified_id,"medication_history","delete",OLD.id,NOW());
END $$ 
DELIMITER ;


DELIMITER $$
CREATE TRIGGER after_disease_history_update AFTER UPDATE ON
disease_history
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",NEW.user_modified_id,"disease_history","update",NEW.id,NOW());
END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_disease_history_insert AFTER INSERT ON
disease_history
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",NEW.user_modified_id,"disease_history","insert",NEW.id,NOW());
END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_disease_history_delete AFTER DELETE ON
disease_history
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",OLD.user_modified_id,"disease_history","delete",OLD.id,NOW());
END $$ 
DELIMITER ;


DELIMITER $$
CREATE TRIGGER after_surgery_history_update AFTER UPDATE ON
surgery_history
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",NEW.user_modified_id,"surgery_history","update",NEW.id,NOW());
END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_surgery_history_insert AFTER INSERT ON
surgery_history
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",NEW.user_modified_id,"surgery_history","insert",NEW.id,NOW());
END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_surgery_history_delete AFTER DELETE ON
surgery_history
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",OLD.user_modified_id,"surgery_history","delete",OLD.id,NOW());
END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_vital_result_update AFTER UPDATE ON
vital_result
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",NEW.user_modified_id,"vital_result","update",NEW.id,NOW());
END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_vital_result_insert AFTER INSERT ON
vital_result
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",NEW.user_modified_id,"vital_result","insert",NEW.id,NOW());
END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_vital_result_delete AFTER DELETE ON
vital_result
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",OLD.user_modified_id,"vital_result","delete",OLD.id,NOW());
END $$ 
DELIMITER ;


DELIMITER $$
CREATE TRIGGER after_radiation_result_update AFTER UPDATE ON
radiation_result
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",NEW.user_modified_id,"radiation_result","update",NEW.id,NOW());
END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_radiation_result_insert AFTER INSERT ON
radiation_result
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",NEW.user_modified_id,"radiation_result","insert",NEW.id,NOW());
END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_radiation_result_delete AFTER DELETE ON
radiation_result
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",OLD.user_modified_id,"radiation_result","delete",OLD.id,NOW());
END $$ 
DELIMITER ;


DELIMITER $$
CREATE TRIGGER after_test_result_update AFTER UPDATE ON
test_result
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",NEW.user_modified_id,"test_result","update",NEW.id,NOW());
END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_test_result_insert AFTER INSERT ON
test_result
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",NEW.user_modified_id,"test_result","insert",NEW.id,NOW());
END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_test_result_delete AFTER DELETE ON
test_result
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",OLD.user_modified_id,"test_result","delete",OLD.id,NOW());
END $$ 
DELIMITER ;


DELIMITER $$
CREATE TRIGGER after_patient_update AFTER UPDATE ON
patient
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",NEW.user_modified_id,"patient","update",NEW.id,NOW());
END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_patient_insert AFTER INSERT ON
patient
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",NEW.user_modified_id,"patient","insert",NEW.id,NOW());
END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_patient_delete AFTER DELETE ON
patient
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",OLD.user_modified_id,"patient","delete",OLD.id,NOW());
END $$ 
DELIMITER ;


DELIMITER $$
CREATE TRIGGER after_visit_request_update AFTER UPDATE ON
visit_request
FOR EACH ROW BEGIN

INSERT INTO notification_admin
values("",NEW.user_modified_id,"visit_request","update",NEW.id,NOW());

INSERT INTO notification_physician
values("","updated",NEW.id,NEW.group_id);

IF (NEW.physican_id IS NOT NULL AND NEW.date IS NOT NULL) THEN
	INSERT INTO notification_clinician
	values("","accepted",NEW.physican_id,NEW.date);
END IF;

END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_visit_request_insert AFTER INSERT ON
visit_request
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",NEW.user_modified_id,"visit_request","insert",NEW.id,NOW());

INSERT INTO notification_physician
values("","new",NEW.id,NEW.group_id);
END $$ 
DELIMITER ;

DELIMITER $$
CREATE TRIGGER after_visit_request_delete AFTER DELETE ON
visit_request
FOR EACH ROW BEGIN
INSERT INTO notification_admin
values("",OLD.user_modified_id,"visit_request","delete",OLD.id,NOW());

INSERT INTO notification_physician
values("","cancelled",OLD.id,OLD.group_id);
END $$ 
DELIMITER ;


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-06-09 13:02:35
