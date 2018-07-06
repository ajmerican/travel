-- MySQL dump 10.13  Distrib 5.7.22, for Linux (x86_64)
--
-- Host: localhost    Database: traveldb
-- ------------------------------------------------------
-- Server version	5.7.22-0ubuntu0.17.10.1

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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(10) DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `dashboard` varchar(100) DEFAULT NULL,
  `booking_details` varchar(100) DEFAULT NULL,
  `accounting` varchar(100) DEFAULT NULL,
  `agent_management` varchar(100) DEFAULT NULL,
  `systems_configuration` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agent_document`
--

DROP TABLE IF EXISTS `agent_document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agent_document` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(10) DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `attachment` varchar(100) DEFAULT NULL,
  `isverify` varchar(100) DEFAULT NULL,
  `verifydate` datetime DEFAULT NULL,
  `verifyby` varchar(100) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agent_document`
--

LOCK TABLES `agent_document` WRITE;
/*!40000 ALTER TABLE `agent_document` DISABLE KEYS */;
INSERT INTO `agent_document` VALUES (1,1,1,'2018-05-17 16:38:48',1,'2018-05-17 17:53:39','License','License expired in next month','sampletextfile_10kb_1526555328.txt','Yes','2018-05-17 00:00:00','1',1);
/*!40000 ALTER TABLE `agent_document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agent_master`
--

DROP TABLE IF EXISTS `agent_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agent_master` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(10) DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `agent_code` int(11) DEFAULT NULL,
  `agent_name` varchar(100) DEFAULT NULL,
  `agent_mobile` varchar(100) DEFAULT NULL,
  `agent_emailiD` varchar(100) DEFAULT NULL,
  `agent_language` varchar(100) DEFAULT NULL,
  `agent_address1` varchar(100) DEFAULT NULL,
  `agent_address2` varchar(100) DEFAULT NULL,
  `agent_city` varchar(100) DEFAULT NULL,
  `agent_country` varchar(100) DEFAULT NULL,
  `agent_PIN` varchar(100) DEFAULT NULL,
  `agent_aaddress1` varchar(100) DEFAULT NULL,
  `agent_baddress2` varchar(100) DEFAULT NULL,
  `agent_bcity` varchar(100) DEFAULT NULL,
  `agent_bCountry` varchar(100) DEFAULT NULL,
  `agent_accountType` varchar(100) DEFAULT NULL,
  `agent_logo` varchar(100) DEFAULT NULL,
  `agent_active` varchar(100) DEFAULT NULL,
  `agenttype_id` int(11) DEFAULT NULL,
  `agent_addressconnection` varchar(100) DEFAULT NULL,
  `agent_connectiontype` varchar(100) DEFAULT NULL,
  `agent_noofusers` varchar(100) DEFAULT NULL,
  `agent_isverify` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agent_master`
--

LOCK TABLES `agent_master` WRITE;
/*!40000 ALTER TABLE `agent_master` DISABLE KEYS */;
INSERT INTO `agent_master` VALUES (1,1,NULL,NULL,1,'2018-05-10 14:59:10',1256,'Alice','966332255','patel@alice.com','Hindi','asdf','asdf 2','amd','ind','368852',NULL,'adf','adsf 2','us 2','SB','3-2-640x815_1525943778.jpg',NULL,555,NULL,NULL,'25','Yes'),(2,1,1,'2018-05-10 20:25:45',NULL,NULL,1256,'Alice','966332255','patel@alice.com','Hindi','asdf','asdf 2','amd','ind','368852',NULL,'adf','adsf 2','us','SB',NULL,NULL,2,NULL,NULL,'25','Yes');
/*!40000 ALTER TABLE `agent_master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agent_plan`
--

DROP TABLE IF EXISTS `agent_plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agent_plan` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(10) DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `agent_ID` varchar(100) DEFAULT NULL,
  `category_id` varchar(100) DEFAULT NULL,
  `aplan_activatedate` datetime DEFAULT NULL,
  `aplan_expiredate` datetime DEFAULT NULL,
  `aplan_commission` decimal(8,2) DEFAULT NULL,
  `aplan_valuelimit` decimal(8,2) DEFAULT NULL,
  `aplan_balance` decimal(8,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agent_plan`
--

LOCK TABLES `agent_plan` WRITE;
/*!40000 ALTER TABLE `agent_plan` DISABLE KEYS */;
INSERT INTO `agent_plan` VALUES (1,1,1,'2018-05-11 15:12:50',NULL,NULL,'1','1','2018-03-05 00:00:00','2018-07-06 00:00:00',20.00,12500.00,NULL);
/*!40000 ALTER TABLE `agent_plan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agent_type`
--

DROP TABLE IF EXISTS `agent_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agent_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(10) DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `agenttype_name` varchar(100) DEFAULT NULL,
  `agenttype_deception` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agent_type`
--

LOCK TABLES `agent_type` WRITE;
/*!40000 ALTER TABLE `agent_type` DISABLE KEYS */;
INSERT INTO `agent_type` VALUES (1,1,1,'2018-05-10 20:13:41',1,'2018-05-10 20:17:04','Type 1','This is type 1 edited'),(2,1,1,'2018-05-10 20:17:24',NULL,NULL,'Type 2','asdf');
/*!40000 ALTER TABLE `agent_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime DEFAULT NULL,
  `u_id` int(11) DEFAULT NULL,
  `travel_agent_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `from_city` varchar(100) DEFAULT NULL,
  `to_city` varchar(100) DEFAULT NULL,
  `date` date NOT NULL,
  `time_from` timestamp NULL DEFAULT NULL,
  `time_to` timestamp NULL DEFAULT NULL,
  `flight_id` int(11) NOT NULL,
  `portal_pnr_no` int(11) DEFAULT NULL,
  `pnr_no` int(11) DEFAULT NULL,
  `contact_id` varchar(255) DEFAULT NULL,
  `promocode` varchar(10) DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `payment_status` varchar(10) DEFAULT '1',
  `hotel_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking`
--

LOCK TABLES `booking` WRITE;
/*!40000 ALTER TABLE `booking` DISABLE KEYS */;
INSERT INTO `booking` VALUES (1,1,0,NULL,'2018-04-19 11:48:02',NULL,NULL,NULL,NULL,'LAX','MSY','2018-04-25','2018-04-24 19:36:00','2018-04-25 01:21:00',504,NULL,NULL,NULL,NULL,NULL,'1',NULL),(2,1,0,NULL,'2018-04-19 03:18:23',NULL,NULL,NULL,NULL,'LAX','MSY','2018-04-25','2018-04-24 19:36:00','2018-04-25 01:21:00',504,NULL,NULL,NULL,NULL,NULL,'1',NULL),(3,1,0,NULL,'2018-04-19 03:24:39',NULL,NULL,NULL,NULL,'LAX','MSY','2018-04-25','2018-04-24 19:36:00','2018-04-25 01:21:00',504,NULL,NULL,NULL,NULL,NULL,'1',NULL),(4,1,0,NULL,'2018-04-19 03:24:57',NULL,NULL,NULL,NULL,'LAX','MSY','2018-04-25','2018-04-24 19:36:00','2018-04-25 01:21:00',504,NULL,NULL,NULL,NULL,NULL,'1',NULL),(5,1,0,NULL,'2018-04-19 03:25:14',NULL,NULL,NULL,NULL,'LAX','MSY','2018-04-25','2018-04-24 19:36:00','2018-04-25 01:21:00',504,NULL,NULL,NULL,NULL,NULL,'1',NULL),(6,1,0,NULL,'2018-04-19 03:25:31',NULL,NULL,NULL,NULL,'LAX','MSY','2018-04-25','2018-04-24 19:36:00','2018-04-25 01:21:00',504,NULL,NULL,NULL,NULL,NULL,'1',NULL),(7,1,0,NULL,'2018-04-20 12:23:51',NULL,NULL,NULL,NULL,'LAX','MSY','2018-04-25','2018-04-24 19:36:00','2018-04-25 01:21:00',504,NULL,NULL,NULL,NULL,NULL,'1',NULL),(8,1,0,NULL,'2018-04-20 12:26:11',NULL,NULL,NULL,NULL,'LAX','MSY','2018-04-25','2018-04-24 19:36:00','2018-04-25 01:21:00',504,NULL,NULL,NULL,NULL,NULL,'1',NULL),(9,1,0,NULL,'2018-04-20 12:26:26',NULL,NULL,NULL,NULL,'LAX','MSY','2018-04-25','2018-04-24 19:36:00','2018-04-25 01:21:00',504,NULL,NULL,NULL,NULL,NULL,'1',NULL),(10,1,0,NULL,'2018-04-20 12:27:15',NULL,NULL,NULL,NULL,'LAX','MSY','2018-04-25','2018-04-24 19:36:00','2018-04-25 01:21:00',504,NULL,NULL,NULL,NULL,NULL,'1',NULL),(11,1,0,NULL,'2018-04-20 12:29:10',NULL,NULL,NULL,NULL,'LAX','FLL','2018-04-25','2018-04-25 03:35:00','2018-04-25 23:32:00',310,NULL,NULL,NULL,NULL,NULL,'1',NULL),(12,1,0,NULL,'2018-04-20 12:31:01',NULL,NULL,NULL,NULL,'LAX','FLL','2018-04-25','2018-04-25 03:35:00','2018-04-25 23:32:00',310,NULL,NULL,NULL,NULL,NULL,'1',NULL),(13,1,0,NULL,'2018-04-20 12:31:48',NULL,NULL,NULL,NULL,'LAX','FLL','2018-04-25','2018-04-25 03:35:00','2018-04-25 23:32:00',310,NULL,NULL,NULL,NULL,NULL,'1',NULL),(14,1,0,NULL,'2018-04-20 12:32:17',NULL,NULL,NULL,NULL,'LAX','FLL','2018-04-25','2018-04-25 03:35:00','2018-04-25 23:32:00',310,NULL,NULL,NULL,NULL,NULL,'1',NULL),(15,1,2,NULL,'2018-04-20 12:39:28',NULL,2,NULL,NULL,'LAX','FLL','2018-04-25','2018-04-25 03:35:00','2018-04-25 23:32:00',310,NULL,NULL,NULL,NULL,NULL,'1',NULL),(16,1,2,NULL,'2018-04-20 01:00:38',NULL,2,NULL,NULL,'LAX','FLL','2018-04-25','2018-04-25 03:35:00','2018-04-25 23:32:00',310,78272,41005,'demo@test,com','',1,'Paid',NULL),(17,1,2,NULL,'2018-04-20 01:01:18',NULL,2,NULL,NULL,'LAX','FLL','2018-04-25','2018-04-25 03:35:00','2018-04-25 23:32:00',310,21575,82501,'demo@test,com','',1,'Paid',NULL),(18,1,2,NULL,'2018-04-20 01:01:48',NULL,2,NULL,NULL,'LAX','FLL','2018-04-25','2018-04-25 03:35:00','2018-04-25 23:32:00',310,81687,65428,'demo@test,com','',1,'Paid',NULL),(19,1,2,NULL,'2018-04-20 01:02:30',NULL,2,NULL,NULL,'LAX','FLL','2018-04-25','2018-04-25 03:35:00','2018-04-25 23:32:00',310,17987,11900,'demo@test,com','',1,'Paid',NULL),(20,1,2,NULL,'2018-04-20 01:06:22',NULL,2,NULL,NULL,'LAX','FLL','2018-04-25','2018-04-25 03:35:00','2018-04-25 23:32:00',310,45391,72412,'demo@test,com','',1,'Paid',NULL),(21,1,2,NULL,'2018-04-20 01:07:01',NULL,2,NULL,NULL,'LAX','FLL','2018-04-25','2018-04-25 03:35:00','2018-04-25 23:32:00',310,87765,55334,'demo@test,com','',1,'Paid',NULL),(22,1,2,NULL,'2018-04-28 12:48:41',NULL,2,NULL,NULL,NULL,NULL,'1970-01-01','1970-01-01 06:30:00','1970-01-01 06:30:00',31838,72777,42248,'demo@test,com','',1,'Paid',NULL),(23,1,2,NULL,'2018-04-28 12:52:24',NULL,2,NULL,NULL,NULL,NULL,'1970-01-01','1970-01-01 06:30:00','1970-01-01 06:30:00',31838,10604,96815,'demo@test,com','',1,'Paid',NULL),(24,1,2,NULL,'2018-04-28 12:53:00',NULL,2,NULL,NULL,NULL,NULL,'1970-01-01','1970-01-01 06:30:00','1970-01-01 06:30:00',31838,52726,34239,'demo@test,com','',1,'Paid',NULL),(25,1,2,NULL,'2018-04-28 12:53:03',NULL,2,NULL,NULL,NULL,NULL,'1970-01-01','1970-01-01 06:30:00','1970-01-01 06:30:00',31838,68902,52484,'demo@test,com','',1,'Paid',NULL),(26,1,2,NULL,'2018-04-28 12:53:26',NULL,2,NULL,NULL,NULL,NULL,'1970-01-01','1970-01-01 06:30:00','1970-01-01 06:30:00',31838,29394,63035,'demo@test,com','',1,'Paid',NULL),(27,1,2,NULL,'2018-04-28 12:55:45',NULL,2,NULL,NULL,NULL,NULL,'2018-05-02','2018-05-02 06:30:00','2018-05-05 06:30:00',31838,65974,74505,'demo@test,com','',1,'Paid',NULL),(28,1,2,NULL,'2018-04-28 12:56:19',NULL,2,NULL,NULL,NULL,NULL,'2018-05-02','2018-05-02 06:30:00','2018-05-05 06:30:00',31838,35956,33365,'demo@test,com','',1,'Paid',NULL),(29,1,2,NULL,'2018-04-28 12:56:51',NULL,2,NULL,NULL,NULL,NULL,'2018-05-02','2018-05-02 06:30:00','2018-05-05 06:30:00',31838,47693,82408,'demo@test,com','',1,'Paid',NULL),(30,1,2,NULL,'2018-04-28 01:00:25',NULL,2,NULL,NULL,NULL,NULL,'2018-05-02','2018-05-02 06:30:00','2018-05-05 06:30:00',31838,81999,14266,'demo@test,com','',1,'Paid','OMNI MANDALAY HOTEL'),(31,1,2,NULL,'2018-04-30 01:38:09',NULL,2,NULL,NULL,NULL,NULL,'2018-05-03','2018-05-03 06:30:00','2018-05-05 06:30:00',31838,73197,50630,'demo@test,com','',1,'Paid','OMNI MANDALAY HOTEL');
/*!40000 ALTER TABLE `booking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(10) DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `details` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,1,1,'2018-05-11 01:12:20',NULL,NULL,'Cat 1','Detail of cat one');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice`
--

DROP TABLE IF EXISTS `invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `invoice_name` varchar(100) NOT NULL,
  `invoice_date` datetime NOT NULL,
  `invoice_description` varchar(100) NOT NULL,
  `invoice_amount` int(20) NOT NULL,
  `u_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice`
--

LOCK TABLES `invoice` WRITE;
/*!40000 ALTER TABLE `invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK of table',
  `parent_id` int(11) DEFAULT '0',
  `name` varchar(80) NOT NULL,
  `alias` varchar(80) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 = Active, 0 = InActive',
  PRIMARY KEY (`permission_id`),
  KEY `idx_permissions_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,0,'User','user','2018-03-15 11:09:43',NULL,NULL,NULL,1),(2,1,'Add / Edit','edit','2018-03-15 11:09:43',NULL,NULL,NULL,1),(3,1,'Show','show','2018-03-15 11:09:43',NULL,NULL,NULL,1),(4,1,'Delete','delete','2018-03-15 11:09:43',NULL,NULL,NULL,1),(5,0,'Role','role','2018-03-15 11:09:43',NULL,NULL,NULL,1),(6,5,'Add / Edit','edit','2018-03-15 11:09:43',NULL,NULL,NULL,1),(7,5,'Show','show','2018-03-15 11:09:43',NULL,NULL,NULL,1),(8,5,'Delete','delete','2018-03-15 11:09:43',NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permissions` (
  `user_permission_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK of table',
  `role_id` int(11) DEFAULT NULL COMMENT 'FK of roles.id',
  `permission_id` int(11) DEFAULT NULL COMMENT 'FK of permissions.id',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_permission_id`),
  KEY `idx_role_permissions_role_id` (`role_id`),
  KEY `idx_role_permissions_permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK of table',
  `name` varchar(64) NOT NULL,
  `code` varchar(8) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '1 = Active, 0 = InActive',
  PRIMARY KEY (`role_id`),
  KEY `idx_roles_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Admin','level-99','2018-03-06 12:34:32',NULL,NULL,NULL,1),(2,'Agent','level-98','2018-03-06 12:37:06',NULL,NULL,NULL,1),(3,'Customer','level-1','2018-04-19 18:29:56',NULL,NULL,1,1);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `travel_agent`
--

DROP TABLE IF EXISTS `travel_agent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `travel_agent` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(10) DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `dashboard` varchar(100) DEFAULT NULL,
  `booking_details` varchar(100) DEFAULT NULL,
  `accounting` varchar(100) DEFAULT NULL,
  `commission` varchar(100) DEFAULT NULL,
  `payment` varchar(100) DEFAULT NULL,
  `document_management` varchar(100) DEFAULT NULL,
  `licence_details` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `travel_agent`
--

LOCK TABLES `travel_agent` WRITE;
/*!40000 ALTER TABLE `travel_agent` DISABLE KEYS */;
/*!40000 ALTER TABLE `travel_agent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `travel_portal`
--

DROP TABLE IF EXISTS `travel_portal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `travel_portal` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(10) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `flight` varchar(100) NOT NULL,
  `hotel` varchar(100) NOT NULL,
  `packges` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `travel_portal`
--

LOCK TABLES `travel_portal` WRITE;
/*!40000 ALTER TABLE `travel_portal` DISABLE KEYS */;
/*!40000 ALTER TABLE `travel_portal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `traveller`
--

DROP TABLE IF EXISTS `traveller`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `traveller` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  `traveller_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `traveller_first_name` varchar(20) NOT NULL,
  `traveller_last_name` varchar(20) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `mobile_no` int(11) NOT NULL,
  `email_id` int(100) NOT NULL,
  `travel_type` varchar(20) NOT NULL,
  `insurance` int(10) NOT NULL,
  `insurance_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `traveller`
--

LOCK TABLES `traveller` WRITE;
/*!40000 ALTER TABLE `traveller` DISABLE KEYS */;
/*!40000 ALTER TABLE `traveller` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_master`
--

DROP TABLE IF EXISTS `user_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_master` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(10) DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `user_firstname` varchar(100) DEFAULT NULL,
  `user_lastname` varchar(100) DEFAULT NULL,
  `user_contactno` varchar(100) DEFAULT NULL,
  `user_emailID` varchar(100) DEFAULT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `user_type` varchar(100) DEFAULT NULL,
  `user_active` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_master`
--

LOCK TABLES `user_master` WRITE;
/*!40000 ALTER TABLE `user_master` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `useragent_connection`
--

DROP TABLE IF EXISTS `useragent_connection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `useragent_connection` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(10) DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `ua_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `useragent_connection`
--

LOCK TABLES `useragent_connection` WRITE;
/*!40000 ALTER TABLE `useragent_connection` DISABLE KEYS */;
/*!40000 ALTER TABLE `useragent_connection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PK of table',
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `address` text,
  `mobile` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '1 = Active, 0 = InActive',
  PRIMARY KEY (`user_id`),
  KEY `idx_users_status` (`status`),
  KEY `idx_users_user_name` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin',NULL,'admin','ade0d35de822d513dcf6a8949104a7fd',NULL,NULL,'admin@test.com',1,'2018-03-06 12:30:20',NULL,'2018-03-09 02:25:30',1,1),(2,'Demo','','demo','d72e4344dd6d820b81c8f1204f479243','','','demo@test,com',2,'2018-04-19 18:28:42',NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-07-05 16:29:50
