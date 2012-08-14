/*
SQLyog Community Edition- MySQL GUI v8.14 
MySQL - 5.1.33-community : Database - zend_prj
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `zf_groupmodules` */

DROP TABLE IF EXISTS `zf_groupmodules`;

CREATE TABLE `zf_groupmodules` (
  `groupmodule_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `groupmodule_name` varchar(255) DEFAULT NULL,
  `is_locked` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`groupmodule_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

/*Data for the table `zf_groupmodules` */

insert  into `zf_groupmodules`(`groupmodule_id`,`groupmodule_name`,`is_locked`) values (1,'Administrator',1),(2,'NOTHAVEGROUP',1),(3,'Sample',0),(13,'sample2',0);

/*Table structure for table `zf_modules` */

DROP TABLE IF EXISTS `zf_modules`;

CREATE TABLE `zf_modules` (
  `module_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(255) DEFAULT NULL,
  `module_title` varchar(255) DEFAULT NULL,
  `sorted_number` int(11) DEFAULT '1',
  `groupmodule_id` bigint(20) DEFAULT '0',
  `is_active` smallint(6) DEFAULT '0',
  `parent_module` bigint(20) DEFAULT '0',
  `is_core` smallint(6) DEFAULT '0',
  `developer` varchar(255) DEFAULT '-',
  PRIMARY KEY (`module_id`)
) ENGINE=MyISAM AUTO_INCREMENT=136 DEFAULT CHARSET=latin1;

/*Data for the table `zf_modules` */

insert  into `zf_modules`(`module_id`,`module_name`,`module_title`,`sorted_number`,`groupmodule_id`,`is_active`,`parent_module`,`is_core`,`developer`) values (1,'zfmodwizard','Module Wizard',3,1,1,0,1,'Abdul Malik Ikhsan'),(2,'zfmodules','Modules',1,1,1,0,1,'Abdul Malik Ikhsan'),(3,'default','Default Module',1,2,1,0,1,'Abdul Malik Ikhsan'),(123,'zfusers','User Management',2,1,1,0,1,'Abdul Malik Ikhsan'),(5,'zfgroupmodules','Group Module',1,1,1,0,1,'Abdul Malik Ikhsan'),(6,'zfpriv','Privilege Control',3,1,1,0,1,'Abdul Malik Ikhsan'),(135,'test','test module',1,3,1,0,0,'Abdul Malik Ikhsan');

/*Table structure for table `zf_moduleshow` */

DROP TABLE IF EXISTS `zf_moduleshow`;

CREATE TABLE `zf_moduleshow` (
  `moduleshow_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) DEFAULT NULL,
  `access_type` varchar(20) DEFAULT NULL,
  `module_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`moduleshow_id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

/*Data for the table `zf_moduleshow` */

insert  into `zf_moduleshow`(`moduleshow_id`,`role_id`,`access_type`,`module_id`) values (52,1,'admin',123),(3,2,'view',3),(47,1,'admin',1),(49,1,'admin',6),(48,1,'admin',5),(54,2,'deny',135),(51,1,'admin',2);

/*Table structure for table `zf_moduleshow_override` */

DROP TABLE IF EXISTS `zf_moduleshow_override`;

CREATE TABLE `zf_moduleshow_override` (
  `moduleshow_override_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `moduleshow_id` bigint(20) DEFAULT NULL,
  `role_id` bigint(20) DEFAULT NULL,
  `access_type` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`moduleshow_override_id`)
) ENGINE=MyISAM AUTO_INCREMENT=132 DEFAULT CHARSET=latin1;

/*Data for the table `zf_moduleshow_override` */

/*Table structure for table `zf_roles` */

DROP TABLE IF EXISTS `zf_roles`;

CREATE TABLE `zf_roles` (
  `role_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(70) DEFAULT NULL,
  `role_inherit` bigint(20) DEFAULT '0',
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `zf_roles` */

insert  into `zf_roles`(`role_id`,`role_name`,`role_inherit`) values (2,'Everyone',0),(1,'admin',2),(3,'user',2),(5,'Developer',2),(6,'dev01',0);

/*Table structure for table `zf_users` */

DROP TABLE IF EXISTS `zf_users`;

CREATE TABLE `zf_users` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) DEFAULT NULL,
  `passwd` varchar(255) DEFAULT NULL,
  `information` text,
  `is_active` smallint(6) DEFAULT '0',
  `role_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Data for the table `zf_users` */

insert  into `zf_users`(`user_id`,`user_name`,`passwd`,`information`,`is_active`,`role_id`) values (1,'admin','21232f297a57a5a743894a0e4a801fc3','-',1,1),(2,'user','ee11cbb19052e40b07aac0ca060c23ee',NULL,1,3),(4,'admin1','21232f297a57a5a743894a0e4a801fc3',NULL,1,1),(5,'admin2','21232f297a57a5a743894a0e4a801fc3',NULL,1,1),(6,'admin3','21232f297a57a5a743894a0e4a801fc3',NULL,1,1),(7,'admin4','21232f297a57a5a743894a0e4a801fc3',NULL,1,1),(8,'admin5','21232f297a57a5a743894a0e4a801fc3',NULL,1,1),(9,'admin6','21232f297a57a5a743894a0e4a801fc3',NULL,1,1),(10,'admin7','21232f297a57a5a743894a0e4a801fc3',NULL,1,1),(11,'admin8','21232f297a57a5a743894a0e4a801fc3','',1,1),(12,'admin9','21232f297a57a5a743894a0e4a801fc3',NULL,1,1),(13,'admin10','21232f297a57a5a743894a0e4a801fc3',NULL,1,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
