-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 19, 2019 at 12:25 PM
-- Server version: 8.0.16
-- PHP Version: 7.2.19-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `adgg`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_audit_trail`
--

CREATE TABLE `auth_audit_trail` (
  `id` int(10) UNSIGNED NOT NULL,
  `action` tinyint(1) NOT NULL,
  `action_description` varchar(1000) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `ip_address` varchar(30) DEFAULT NULL,
  `user_agent` varchar(1000) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `details` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `auth_audit_trail`
--

INSERT INTO `auth_audit_trail` (`id`, `action`, `action_description`, `url`, `ip_address`, `user_agent`, `user_id`, `org_id`, `details`) VALUES
(1, 2, 'Created a resource. Table affected: email_template, Record modified:msmsmsmsm', 'http://localhost/medsource/conf/email/create', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:9:\"msmsmsmsm\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"The name\";}s:7:\"subject\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Moses\";}s:4:\"body\";a:2:{s:3:\"old\";N;s:3:\"new\";s:33:\"<p>The name I like is .....\r\n</p>\";}s:6:\"sender\";a:2:{s:3:\"old\";N;s:3:\"new\";s:15:\"gmail@gmail.com\";}s:8:\"comments\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(2, 4, 'Deleted a resource. Table affected: email_template, Record modified:msmsmsmsm', 'http://localhost/medsource/conf/email/delete?id=msmsmsmsm', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:9:\"msmsmsmsm\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"The name\";}s:7:\"subject\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Moses\";}s:4:\"body\";a:2:{s:3:\"old\";N;s:3:\"new\";s:33:\"<p>The name I like is .....\r\n</p>\";}s:6:\"sender\";a:2:{s:3:\"old\";N;s:3:\"new\";s:15:\"gmail@gmail.com\";}s:8:\"comments\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(3, 3, 'Updated a resource. Table affected: core_master_country, Record modified:1', 'http://localhost/medsource/core/country/update?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:8:\"currency\";a:2:{s:3:\"old\";s:3:\"EUR\";s:3:\"new\";s:3:\"USD\";}}'),
(4, 3, 'Updated a resource. Table affected: core_master_country, Record modified:1', 'http://localhost/medsource/core/country/update?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:8:\"currency\";a:2:{s:3:\"old\";s:3:\"USD\";s:3:\"new\";s:3:\"UGX\";}}'),
(5, 2, 'Created a resource. Table affected: core_master_currency, Record modified:6', 'http://localhost/medsource/core/currency/create', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"iso3\";a:2:{s:3:\"old\";N;s:3:\"new\";s:3:\"CAD\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:15:\"Canadian DOllar\";}s:6:\"symbol\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(6, 2, 'Created a resource. Table affected: core_master_currency_conversion, Record modified:9', 'http://localhost/medsource/core/currency-conversion/update', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:16:\"default_currency\";a:2:{s:3:\"old\";N;s:3:\"new\";s:3:\"KES\";}s:8:\"currency\";a:2:{s:3:\"old\";N;s:3:\"new\";s:3:\"CAD\";}s:15:\"conversion_rate\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"85\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(7, 3, 'Updated a resource. Table affected: core_master_currency_conversion, Record modified:9', 'http://localhost/medsource/core/currency-conversion/update?default_currency=KES', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:15:\"conversion_rate\";a:2:{s:3:\"old\";d:85;s:3:\"new\";s:2:\"87\";}}'),
(8, 3, 'Updated a resource. Table affected: core_master_currency_conversion, Record modified:9', 'http://localhost/medsource/core/currency-conversion/update?default_currency=KES', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:15:\"conversion_rate\";a:2:{s:3:\"old\";d:87;s:3:\"new\";s:3:\"100\";}}'),
(9, 2, 'Created a resource. Table affected: conf_numbering_format, Record modified:1', 'http://localhost/medsource/conf/number-format/create?NumberingFormat%5Bis_active%5D=', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:10:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"20100101\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2020202020\";}s:11:\"next_number\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"min_digits\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"is_private\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"prefix\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"2020/\";}s:6:\"suffix\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"/2822\";}s:7:\"preview\";a:2:{s:3:\"old\";N;s:3:\"new\";s:13:\"2020/001/2822\";}}'),
(10, 3, 'Updated a resource. Table affected: conf_numbering_format, Record modified:1', 'http://localhost/medsource/conf/number-format/update?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:2:{s:6:\"prefix\";a:2:{s:3:\"old\";s:5:\"2020/\";s:3:\"new\";s:4:\"202/\";}s:7:\"preview\";a:2:{s:3:\"old\";s:13:\"2020/001/2822\";s:3:\"new\";s:12:\"202/001/2822\";}}'),
(11, 2, 'Created a resource. Table affected: auth_password_reset_history, Record modified:1', 'http://localhost/medsource/auth/user/reset-password?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:7:{s:7:\"user_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:17:\"old_password_hash\";a:2:{s:3:\"old\";N;s:3:\"new\";s:60:\"$2y$13$kS4ta6GDhnOB3AqynYjn3u/yAEIB/giLk1ze3tzO4CsfqSF9HfVAa\";}s:17:\"new_password_hash\";a:2:{s:3:\"old\";N;s:3:\"new\";s:60:\"$2y$13$.FKU1MkHCDgctO4Bql43pec4nDStyUBPW0Yv4.gBfOSBPFj.8qjz2\";}s:10:\"created_by\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:10:\"created_at\";a:2:{s:3:\"old\";N;s:3:\"new\";s:19:\"2019-05-22 12:57:44\";}s:20:\"password_reset_token\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"ip_address\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(12, 2, 'Created a resource. Table affected: auth_roles, Record modified:4', 'http://localhost/medsource/auth/role/create', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:5:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"Pharmacy\";}s:8:\"readonly\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:8:\"level_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(13, 2, 'Created a resource. Table affected: auth_permission, Record modified:1', 'http://localhost/medsource/auth/role/view?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"ACCOUNTING\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(14, 2, 'Created a resource. Table affected: auth_permission, Record modified:2', 'http://localhost/medsource/auth/role/view?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"AUTH_AUDIT_TRAIL\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(15, 2, 'Created a resource. Table affected: auth_permission, Record modified:3', 'http://localhost/medsource/auth/role/view?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:9:\"AUTH_USER\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(16, 2, 'Created a resource. Table affected: auth_permission, Record modified:4', 'http://localhost/medsource/auth/role/view?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:13:\"CONF_SETTINGS\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(17, 2, 'Created a resource. Table affected: auth_permission, Record modified:5', 'http://localhost/medsource/auth/role/view?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:4:\"HELP\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(18, 2, 'Created a resource. Table affected: auth_permission, Record modified:6', 'http://localhost/medsource/auth/role/view?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"ORG_ORGANIZATION\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(19, 2, 'Created a resource. Table affected: auth_permission, Record modified:7', 'http://localhost/medsource/auth/role/view?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"PRODUCT\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(20, 2, 'Created a resource. Table affected: auth_permission, Record modified:8', 'http://localhost/medsource/auth/role/view?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"REPORTS\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(21, 2, 'Created a resource. Table affected: auth_permission, Record modified:9', 'http://localhost/medsource/auth/role/view?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"REPORTS_SETTINGS\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(22, 2, 'Created a resource. Table affected: auth_permission, Record modified:10', 'http://localhost/medsource/auth/role/view?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"RES_EXPORT\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(23, 2, 'Created a resource. Table affected: auth_permission, Record modified:11', 'http://localhost/medsource/auth/role/view?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"ACCOUNTING\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(24, 2, 'Created a resource. Table affected: auth_permission, Record modified:12', 'http://localhost/medsource/auth/role/view?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"AUTH_AUDIT_TRAIL\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(25, 2, 'Created a resource. Table affected: auth_permission, Record modified:13', 'http://localhost/medsource/auth/role/view?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:9:\"AUTH_USER\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(26, 2, 'Created a resource. Table affected: auth_permission, Record modified:14', 'http://localhost/medsource/auth/role/view?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:13:\"CONF_SETTINGS\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(27, 2, 'Created a resource. Table affected: auth_permission, Record modified:15', 'http://localhost/medsource/auth/role/view?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:4:\"HELP\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(28, 2, 'Created a resource. Table affected: auth_permission, Record modified:16', 'http://localhost/medsource/auth/role/view?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"ORG_ORGANIZATION\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(29, 2, 'Created a resource. Table affected: auth_permission, Record modified:17', 'http://localhost/medsource/auth/role/view?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"PRODUCT\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(30, 2, 'Created a resource. Table affected: auth_permission, Record modified:18', 'http://localhost/medsource/auth/role/view?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"REPORTS\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(31, 2, 'Created a resource. Table affected: auth_permission, Record modified:19', 'http://localhost/medsource/auth/role/view?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"REPORTS_SETTINGS\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(32, 2, 'Created a resource. Table affected: auth_permission, Record modified:20', 'http://localhost/medsource/auth/role/view?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"RES_EXPORT\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(33, 3, 'Updated a resource. Table affected: auth_resources, Record Id modified:ORG_ORGANIZATION', 'http://localhost/medsource/auth/resource/update?id=ORG_ORGANIZATION', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:4:\"name\";a:2:{s:3:\"old\";s:9:\"SACCO/MFI\";s:3:\"new\";s:31:\"HOSPITALS, CLINICS & PHARMACIES\";}}'),
(34, 3, 'Updated a resource. Table affected: auth_user_levels, Record Id modified:-1', 'http://localhost/medsource/auth/user-level/update?id=-1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:15:\"forbidden_items\";a:2:{s:3:\"old\";a:11:{i:0;s:10:\"ACCOUNTING\";i:1;s:16:\"AUTH_AUDIT_TRAIL\";i:2;s:10:\"RES_EXPORT\";i:3;s:4:\"HELP\";i:4;s:16:\"ORG_ORGANIZATION\";i:5;s:7:\"PRODUCT\";i:6;s:7:\"REPORTS\";i:7;s:16:\"REPORTS_SETTINGS\";i:8;s:9:\"AUTH_ROLE\";i:9;s:13:\"CONF_SETTINGS\";i:10;s:9:\"AUTH_USER\";}s:3:\"new\";N;}}'),
(35, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:1', 'http://localhost/medsource/core/county/create', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Mombasa\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(36, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:2', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"2\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Kwale\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(37, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:3', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:6:\"Kilifi\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(38, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:4', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"4\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"Tana River\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(39, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:5', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"5\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:4:\"Lamu\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(40, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:6', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"6\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:12:\"Taita-Taveta\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(41, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:7', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"7\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Garissa\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(42, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:8', 'http://localhost/medsource/core/county/create', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"8\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Wajir\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(43, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:9', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"9\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Mandera\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(44, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:10', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"10\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"Marsabit\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(45, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:11', 'http://localhost/medsource/core/county/create?sort=code', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"11\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:6:\"Isiolo\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(46, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:12', 'http://localhost/medsource/core/county/create?sort=code&_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"12\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:4:\"Meru\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(47, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:13', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"13\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:13:\"Tharaka-Nithi\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(48, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:14', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"14\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:4:\"Embu\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(49, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:15', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"15\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Kitui\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(50, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:16', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"16\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"Machakos\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(51, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:17', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"17\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Makueni\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(52, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:18', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"18\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:9:\"Nyandarua\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(53, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:19', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"19\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Nyeri\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(54, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:20', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"20\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:9:\"Kirinyaga\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(55, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:21', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"21\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"Murang\'a\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(56, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:22', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"22\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:6:\"Kiambu\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(57, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:23', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"23\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Turkana\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(58, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:24', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"24\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"West Pokot\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(59, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:25', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"25\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Samburu\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(60, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:26', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"26\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"Trans Nzoia\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(61, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:27', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"27\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"Uasin Gishu\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(62, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:28', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"28\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:15:\"Elgeyo-Marakwet\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(63, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:29', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"29\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Nandi\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(64, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:30', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"30\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Baringo\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(65, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:31', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"31\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"Laikipia\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(66, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:32', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"32\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:6:\"Nakuru\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(67, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:33', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"33\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Narok\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(68, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:34', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"34\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Kajiado\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(69, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:35', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"35\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Kericho\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(70, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:36', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"36\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Bomet\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(71, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:37', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"37\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"Kakamega\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(72, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:38', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"38\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:6:\"Vihiga\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(73, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:39', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"39\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Bungoma\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(74, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:40', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"40\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Busia\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(75, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:41', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"41\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Siaya\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(76, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:42', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"42\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:6:\"Kisumu\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(77, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:43', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"43\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"Homa Bay\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(78, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:44', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"44\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:6:\"Migori\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(79, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:45', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"45\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Kisii\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(80, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:46', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"46\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Nyamira\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(81, 2, 'Created a resource. Table affected: core_master_county, Record Id modified:47', 'http://localhost/medsource/core/county/create?_pjax=%23County-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:4:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"47\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Nairobi\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(82, 2, 'Created a resource. Table affected: member_registration_document_type, Record Id modified:1', 'http://localhost/medsource/core/registration-document-type/create', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"Licence One\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"has_renewal\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";s:18:\"Sample description\";}s:14:\"business_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:54:\"a:4:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";}\";}s:21:\"business_entity_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"N;\";}}'),
(83, 4, 'Deleted a resource. Table affected: member_registration_document_type, Record Id modified:1', 'http://localhost/medsource/core/registration-document-type/delete?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:14:\"business_types\";a:2:{s:3:\"old\";N;s:3:\"new\";a:4:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";}}s:21:\"business_entity_types\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"Licence One\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"has_renewal\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";s:18:\"Sample description\";}}'),
(84, 2, 'Created a resource. Table affected: member_registration_document_type, Record Id modified:2', 'http://localhost/medsource/core/registration-document-type/create?_pjax=%23RegistrationDocumentType-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"Premises License\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"has_renewal\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"business_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:30:\"a:2:{i:0;s:1:\"3\";i:1;s:1:\"4\";}\";}s:21:\"business_entity_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"N;\";}}'),
(85, 3, 'Updated a resource. Table affected: member_registration_document_type, Record Id modified:2', 'http://localhost/medsource/core/registration-document-type/update?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:21:\"business_entity_types\";a:2:{s:3:\"old\";s:2:\"N;\";s:3:\"new\";s:42:\"a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}\";}}'),
(86, 2, 'Created a resource. Table affected: member_registration_document_type, Record Id modified:3', 'http://localhost/medsource/core/registration-document-type/create?_pjax=%23RegistrationDocumentType-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:46:\"Kenya Medical Practitioners & Dentists Licence\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"has_renewal\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"business_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:30:\"a:2:{i:0;s:1:\"3\";i:1;s:1:\"4\";}\";}s:21:\"business_entity_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:42:\"a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}\";}}'),
(87, 2, 'Created a resource. Table affected: member_registration_document_type, Record Id modified:4', 'http://localhost/medsource/core/registration-document-type/create?_pjax=%23RegistrationDocumentType-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:34:\"Individual Annual Practice License\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"has_renewal\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"business_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:54:\"a:4:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";}\";}s:21:\"business_entity_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:42:\"a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}\";}}'),
(88, 4, 'Deleted a resource. Table affected: member_registration_document_type, Record Id modified:4', 'http://localhost/medsource/core/registration-document-type/delete?id=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:14:\"business_types\";a:2:{s:3:\"old\";N;s:3:\"new\";a:4:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";}}s:21:\"business_entity_types\";a:2:{s:3:\"old\";N;s:3:\"new\";a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:34:\"Individual Annual Practice License\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"has_renewal\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(89, 2, 'Created a resource. Table affected: member_registration_document_type, Record Id modified:5', 'http://localhost/medsource/core/registration-document-type/create?_pjax=%23RegistrationDocumentType-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:35:\"PPB (Pharmacist or Pharmacist tech)\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"has_renewal\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"business_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:18:\"a:1:{i:0;s:1:\"3\";}\";}s:21:\"business_entity_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:42:\"a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}\";}}'),
(90, 2, 'Created a resource. Table affected: member_registration_document_type, Record Id modified:6', 'http://localhost/medsource/core/registration-document-type/create?_pjax=%23RegistrationDocumentType-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:25:\"KBPPB (hospital Pharmacy)\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"has_renewal\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"business_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:30:\"a:2:{i:0;s:1:\"3\";i:1;s:1:\"4\";}\";}s:21:\"business_entity_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:42:\"a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}\";}}');
INSERT INTO `auth_audit_trail` (`id`, `action`, `action_description`, `url`, `ip_address`, `user_agent`, `user_id`, `org_id`, `details`) VALUES
(91, 2, 'Created a resource. Table affected: member_registration_document_type, Record Id modified:7', 'http://localhost/medsource/core/registration-document-type/create?_pjax=%23RegistrationDocumentType-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:31:\"COC (Clinical Officers Council)\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"has_renewal\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"business_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:18:\"a:1:{i:0;s:1:\"4\";}\";}s:21:\"business_entity_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:42:\"a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}\";}}'),
(92, 2, 'Created a resource. Table affected: member_registration_document_type, Record Id modified:8', 'http://localhost/medsource/core/registration-document-type/create?_pjax=%23RegistrationDocumentType-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:30:\"NCK (Nursing Council of Kenya)\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"has_renewal\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"business_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:18:\"a:1:{i:0;s:1:\"4\";}\";}s:21:\"business_entity_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:42:\"a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}\";}}'),
(93, 2, 'Created a resource. Table affected: member_registration_document_type, Record Id modified:9', 'http://localhost/medsource/core/registration-document-type/create', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:44:\"KMLTTB (Lab Technologist or Lab technicians)\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"has_renewal\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"business_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:30:\"a:2:{i:0;s:1:\"3\";i:1;s:1:\"4\";}\";}s:21:\"business_entity_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:42:\"a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}\";}}'),
(94, 2, 'Created a resource. Table affected: member_registration_document_type, Record Id modified:10', 'http://localhost/medsource/core/registration-document-type/create?_pjax=%23RegistrationDocumentType-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:30:\"Certification of Incorporation\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"has_renewal\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"0\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"business_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:54:\"a:4:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";}\";}s:21:\"business_entity_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:42:\"a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}\";}}'),
(95, 2, 'Created a resource. Table affected: auth_resources, Record Id modified:SUPPLIER', 'http://localhost/medsource/auth/resource/create', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:7:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"SUPPLIER\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:41:\"SUPPLERS (DISTRIBUTORS AND MANUFACTURERS)\";}s:8:\"viewable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"creatable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:8:\"editable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"deletable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"executable\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(96, 2, 'Created a resource. Table affected: auth_resources, Record Id modified:MEMBER', 'http://localhost/medsource/auth/resource/create?_pjax=%23Resources-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:7:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:6:\"MEMBER\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:43:\"MEMBERS (PHARMACIES, HOSPITALS AND CLINICS)\";}s:8:\"viewable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"creatable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:8:\"editable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"deletable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"executable\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(97, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:21', 'http://localhost/medsource/auth/role/view?id=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:4;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"ACCOUNTING\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(98, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:22', 'http://localhost/medsource/auth/role/view?id=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:4;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"AUTH_AUDIT_TRAIL\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(99, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:23', 'http://localhost/medsource/auth/role/view?id=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:4;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:9:\"AUTH_USER\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(100, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:24', 'http://localhost/medsource/auth/role/view?id=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:4;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:13:\"CONF_SETTINGS\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"0\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(101, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:25', 'http://localhost/medsource/auth/role/view?id=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:4;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:4:\"HELP\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(102, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:26', 'http://localhost/medsource/auth/role/view?id=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:4;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:6:\"MEMBER\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(103, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:27', 'http://localhost/medsource/auth/role/view?id=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:4;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"ORG_ORGANIZATION\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(104, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:28', 'http://localhost/medsource/auth/role/view?id=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:4;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"PRODUCT\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(105, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:29', 'http://localhost/medsource/auth/role/view?id=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:4;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"REPORTS\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(106, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:30', 'http://localhost/medsource/auth/role/view?id=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:4;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"REPORTS_SETTINGS\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(107, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:31', 'http://localhost/medsource/auth/role/view?id=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:4;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"RES_EXPORT\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(108, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:32', 'http://localhost/medsource/auth/role/view?id=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:4;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"SUPPLIER\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(109, 2, 'Created a resource. Table affected: auth_users, Record Id modified:3', 'http://localhost/medsource/auth/user/create', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:11:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"Eric Munene\";}s:8:\"username\";a:2:{s:3:\"old\";N;s:3:\"new\";s:4:\"eric\";}s:5:\"email\";a:2:{s:3:\"old\";N;s:3:\"new\";s:23:\"eric@medsourcegroup.com\";}s:8:\"level_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:22:\"auto_generate_password\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"0\";}s:9:\"branch_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"profile_image\";a:2:{s:3:\"old\";N;s:3:\"new\";s:40:\"0e5d9de4-0357-4256-810c-4cedaccbb74c.jpg\";}s:8:\"timezone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"America/New_York\";}s:5:\"phone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"0707839641\";}}'),
(110, 2, 'Created a resource. Table affected: org_registration_document_type, Record Id modified:11', 'http://localhost/medsource/core/registration-document-type/create', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:6:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:23:\"Contract With Medsource\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"has_renewal\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"business_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:30:\"a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}\";}s:21:\"business_entity_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:42:\"a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}\";}}'),
(111, 3, 'Updated a resource. Table affected: organization, Record Id modified:2', 'http://localhost/medsource/core/organization/approve?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:2:{s:6:\"status\";a:2:{s:3:\"old\";i:1;s:3:\"new\";i:2;}s:11:\"is_approved\";a:2:{s:3:\"old\";i:0;s:3:\"new\";i:1;}}'),
(112, 3, 'Updated a resource. Table affected: organization, Record Id modified:1', 'http://localhost/medsource/core/organization/approve?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:2:{s:6:\"status\";a:2:{s:3:\"old\";i:1;s:3:\"new\";i:2;}s:11:\"is_approved\";a:2:{s:3:\"old\";i:0;s:3:\"new\";i:1;}}'),
(113, 2, 'Created a resource. Table affected: conf_numbering_format, Record Id modified:2', 'http://localhost/medsource/conf/number-format/create', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:10:{s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:23:\"organization_account_no\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:27:\"Organization Account Number\";}s:11:\"next_number\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"10003\";}s:10:\"min_digits\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"is_private\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"prefix\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"suffix\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:7:\"preview\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"10003\";}}'),
(114, 2, 'Created a resource. Table affected: auth_users, Record Id modified:4', 'http://localhost/medsource/auth/user/create?level_id=3&org_id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:11:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:13:\"Dr Moses Aran\";}s:8:\"username\";a:2:{s:3:\"old\";N;s:3:\"new\";s:9:\"mosesaran\";}s:5:\"email\";a:2:{s:3:\"old\";N;s:3:\"new\";s:19:\"mosesaran@gmail.com\";}s:8:\"level_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:22:\"auto_generate_password\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"0\";}s:9:\"branch_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"profile_image\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:8:\"timezone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:14:\"Africa/Nairobi\";}s:5:\"phone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"0713042478\";}}'),
(115, 3, 'Updated a resource. Table affected: organization, Record Id modified:1', 'http://localhost/medsource/core/organization/approve?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:11:\"approved_at\";a:2:{s:3:\"old\";s:19:\"2019-05-23 23:31:05\";s:3:\"new\";s:19:\"2019-05-24 01:26:43\";}}'),
(116, 3, 'Updated a resource. Table affected: organization, Record Id modified:1', 'http://localhost/medsource/core/organization/approve?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:11:\"approved_at\";a:2:{s:3:\"old\";s:19:\"2019-05-24 01:26:43\";s:3:\"new\";s:19:\"2019-05-24 01:26:52\";}}'),
(117, 3, 'Updated a resource. Table affected: organization, Record Id modified:1', 'http://localhost/medsource/core/organization/change-status?id=1&status=3', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:6:\"status\";a:2:{s:3:\"old\";i:2;s:3:\"new\";s:1:\"3\";}}'),
(118, 3, 'Updated a resource. Table affected: organization, Record Id modified:1', 'http://localhost/medsource/core/organization/change-status?id=1&status=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:6:\"status\";a:2:{s:3:\"old\";i:3;s:3:\"new\";s:1:\"2\";}}'),
(119, 3, 'Updated a resource. Table affected: organization, Record Id modified:1', 'http://localhost/medsource/core/organization/change-status?id=1&status=3', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:6:\"status\";a:2:{s:3:\"old\";i:2;s:3:\"new\";s:1:\"3\";}}'),
(120, 3, 'Updated a resource. Table affected: organization, Record Id modified:1', 'http://localhost/medsource/core/organization/change-status?id=1&status=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:6:\"status\";a:2:{s:3:\"old\";i:3;s:3:\"new\";s:1:\"2\";}}'),
(121, 3, 'Updated a resource. Table affected: organization, Record Id modified:1', 'http://localhost/medsource/core/organization/change-status?id=1&status=3', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:6:\"status\";a:2:{s:3:\"old\";i:2;s:3:\"new\";s:1:\"3\";}}'),
(122, 3, 'Updated a resource. Table affected: organization, Record Id modified:1', 'http://localhost/medsource/core/organization/change-status?id=1&status=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:6:\"status\";a:2:{s:3:\"old\";i:3;s:3:\"new\";s:1:\"2\";}}'),
(123, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:2', 'http://localhost/medsource/core/registration-document-type/update?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:14:\"has_start_date\";a:2:{s:3:\"old\";i:0;s:3:\"new\";s:1:\"1\";}}'),
(124, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:3', 'http://localhost/medsource/core/registration-document-type/update?id=3', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:14:\"has_start_date\";a:2:{s:3:\"old\";i:0;s:3:\"new\";s:1:\"1\";}}'),
(125, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:5', 'http://localhost/medsource/core/registration-document-type/update?id=5', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:14:\"has_start_date\";a:2:{s:3:\"old\";i:0;s:3:\"new\";s:1:\"1\";}}'),
(126, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:6', 'http://localhost/medsource/core/registration-document-type/update?id=6', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:14:\"has_start_date\";a:2:{s:3:\"old\";i:0;s:3:\"new\";s:1:\"1\";}}'),
(127, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:7', 'http://localhost/medsource/core/registration-document-type/update?id=7', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:14:\"has_start_date\";a:2:{s:3:\"old\";i:0;s:3:\"new\";s:1:\"1\";}}'),
(128, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:8', 'http://localhost/medsource/core/registration-document-type/update?id=8', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:14:\"has_start_date\";a:2:{s:3:\"old\";i:0;s:3:\"new\";s:1:\"1\";}}'),
(129, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:9', 'http://localhost/medsource/core/registration-document-type/update?id=9', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:14:\"has_start_date\";a:2:{s:3:\"old\";i:0;s:3:\"new\";s:1:\"1\";}}'),
(130, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:10', 'http://localhost/medsource/core/registration-document-type/update?id=10', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:14:\"has_start_date\";a:2:{s:3:\"old\";i:0;s:3:\"new\";s:1:\"1\";}}'),
(131, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:11', 'http://localhost/medsource/core/registration-document-type/update?id=11', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:14:\"has_start_date\";a:2:{s:3:\"old\";i:0;s:3:\"new\";s:1:\"1\";}}'),
(132, 4, 'Deleted a resource. Table affected: auth_resources, Record Id modified:ORG_ORGANIZATION', 'http://localhost/medsource/auth/resource/delete?id=ORG_ORGANIZATION', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:7:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"ORG_ORGANIZATION\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:31:\"HOSPITALS, CLINICS & PHARMACIES\";}s:8:\"viewable\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:9:\"creatable\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:8:\"editable\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:9:\"deletable\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:10:\"executable\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}}'),
(133, 3, 'Updated a resource. Table affected: auth_resources, Record Id modified:ORG_MEMBER', 'http://localhost/medsource/auth/resource/update?id=MEMBER', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:2:\"id\";a:2:{s:3:\"old\";s:6:\"MEMBER\";s:3:\"new\";s:10:\"ORG_MEMBER\";}}'),
(134, 3, 'Updated a resource. Table affected: auth_resources, Record Id modified:ORG_SUPPLIER', 'http://localhost/medsource/auth/resource/update?id=SUPPLIER', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:2:\"id\";a:2:{s:3:\"old\";s:8:\"SUPPLIER\";s:3:\"new\";s:12:\"ORG_SUPPLIER\";}}'),
(135, 4, 'Deleted a resource. Table affected: auth_resources, Record Id modified:ACCOUNTING', 'http://localhost/medsource/auth/resource/delete?id=ACCOUNTING', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:7:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"ACCOUNTING\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"ACCOUNTING\";}s:8:\"viewable\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:9:\"creatable\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:8:\"editable\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:9:\"deletable\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:10:\"executable\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}}'),
(136, 2, 'Created a resource. Table affected: auth_resources, Record Id modified:ORG_REGISTRATION_DOCUMENT', 'http://localhost/medsource/auth/resource/create?_pjax=%23Resources-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:7:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:25:\"ORG_REGISTRATION_DOCUMENT\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:22:\"REGISTRATION DOCUMENTS\";}s:8:\"viewable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"creatable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:8:\"editable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"deletable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"executable\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(137, 2, 'Created a resource. Table affected: org_registration_document, Record Id modified:1', 'http://localhost/medsource/core/registration-document/create?org_id=fc55a5c2-a75a-42c6-a814-16c5797b5e8b', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:9:{s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"document_no\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"22020202020\";}s:11:\"doc_type_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"10\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"is_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}s:10:\"start_date\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-05-01\";}s:12:\"renewal_date\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-12-31\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";s:18:\"Sample Description\";}s:9:\"file_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:19:\"1558672278.6444.jpg\";}}'),
(138, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:2', 'http://localhost/medsource/core/registration-document-type/update?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:14:\"business_types\";a:2:{s:3:\"old\";s:30:\"a:2:{i:0;s:1:\"3\";i:1;s:1:\"4\";}\";s:3:\"new\";s:18:\"a:1:{i:0;s:1:\"3\";}\";}}'),
(139, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:6', 'http://localhost/medsource/core/registration-document-type/update?id=6', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:14:\"business_types\";a:2:{s:3:\"old\";s:30:\"a:2:{i:0;s:1:\"3\";i:1;s:1:\"4\";}\";s:3:\"new\";s:18:\"a:1:{i:0;s:1:\"3\";}\";}}'),
(140, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:7', 'http://localhost/medsource/core/registration-document-type/update?id=7', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:14:\"business_types\";a:2:{s:3:\"old\";s:18:\"a:1:{i:0;s:1:\"4\";}\";s:3:\"new\";s:18:\"a:1:{i:0;s:1:\"5\";}\";}}'),
(141, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:8', 'http://localhost/medsource/core/registration-document-type/update?id=8', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:14:\"business_types\";a:2:{s:3:\"old\";s:18:\"a:1:{i:0;s:1:\"4\";}\";s:3:\"new\";s:54:\"a:4:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"5\";}\";}}'),
(142, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:10', 'http://localhost/medsource/core/registration-document-type/update?id=10', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:14:\"business_types\";a:2:{s:3:\"old\";s:54:\"a:4:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";}\";s:3:\"new\";s:42:\"a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}\";}}'),
(143, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:9', 'http://localhost/medsource/core/registration-document-type/update?id=9', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:14:\"business_types\";a:2:{s:3:\"old\";s:30:\"a:2:{i:0;s:1:\"3\";i:1;s:1:\"4\";}\";s:3:\"new\";s:18:\"a:1:{i:0;s:1:\"3\";}\";}}'),
(144, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:3', 'http://localhost/medsource/core/registration-document-type/update?id=3', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:21:\"business_entity_types\";a:2:{s:3:\"old\";s:42:\"a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}\";s:3:\"new\";s:30:\"a:2:{i:0;s:1:\"2\";i:1;s:1:\"3\";}\";}}'),
(145, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:3', 'http://localhost/medsource/core/registration-document-type/update?id=3', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:21:\"business_entity_types\";a:2:{s:3:\"old\";s:30:\"a:2:{i:0;s:1:\"2\";i:1;s:1:\"3\";}\";s:3:\"new\";s:42:\"a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}\";}}'),
(146, 3, 'Updated a resource. Table affected: org_registration_document_type, Record Id modified:5', 'http://localhost/medsource/core/registration-document-type/update?id=5', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:14:\"business_types\";a:2:{s:3:\"old\";s:18:\"a:1:{i:0;s:1:\"3\";}\";s:3:\"new\";s:30:\"a:2:{i:0;s:1:\"3\";i:1;s:1:\"4\";}\";}}'),
(147, 2, 'Created a resource. Table affected: org_registration_document, Record Id modified:2', 'http://localhost/medsource/core/registration-document/create?org_id=fc55a5c2-a75a-42c6-a814-16c5797b5e8b', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:9:{s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"document_no\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"20202020202\";}s:11:\"doc_type_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"is_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}s:10:\"start_date\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-05-01\";}s:12:\"renewal_date\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-05-31\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"KMPDU\";}s:9:\"file_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:19:\"1558675580.2886.pdf\";}}'),
(148, 3, 'Updated a resource. Table affected: org_registration_document, Record Id modified:1', 'http://localhost/medsource/core/registration-document/update?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:11:\"doc_type_id\";a:2:{s:3:\"old\";i:10;s:3:\"new\";s:1:\"3\";}}'),
(149, 3, 'Updated a resource. Table affected: org_registration_document, Record Id modified:2', 'http://localhost/medsource/core/registration-document/approve?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:11:\"is_approved\";a:2:{s:3:\"old\";i:0;s:3:\"new\";i:1;}}'),
(150, 3, 'Updated a resource. Table affected: org_registration_document, Record Id modified:1', 'http://localhost/medsource/core/registration-document/approve?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:11:\"is_approved\";a:2:{s:3:\"old\";i:0;s:3:\"new\";i:1;}}'),
(151, 2, 'Created a resource. Table affected: org_registration_document, Record Id modified:3', 'http://localhost/medsource/core/registration-document/create?org_id=fc55a5c2-a75a-42c6-a814-16c5797b5e8b&_pjax=%23RegistrationDocument-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:9:{s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"document_no\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2020202020\";}s:11:\"doc_type_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"is_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}s:10:\"start_date\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-05-24\";}s:12:\"renewal_date\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-05-31\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";s:25:\"This is a sample document\";}s:9:\"file_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:20:\"1558686147.8187.docx\";}}'),
(152, 2, 'Created a resource. Table affected: organization, Record Id modified:3', 'http://localhost/medsource/core/organization/create?is_member=1&business_type=5', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:36:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:9:\"Four Ways\";}s:13:\"business_type\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"5\";}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"KE\";}s:18:\"contact_first_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Moses\";}s:17:\"contact_last_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Maina\";}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"07259393393\";}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";s:24:\"mosesmain@fourways.co.ke\";}s:6:\"status\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"is_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}s:11:\"approved_by\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:20:\"business_entity_type\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:33:\"applicant_business_ownership_type\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:19:\"is_credit_requested\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"is_supplier\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}s:9:\"is_member\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:18:\"account_manager_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:16:\"application_date\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-05-24\";}s:13:\"date_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:11:\"approved_at\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:19:\"membership_end_date\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"account_no\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"10003\";}s:14:\"applicant_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"Moses Maina\";}s:19:\"contact_middle_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:15:\"daily_customers\";a:2:{s:3:\"old\";N;s:3:\"new\";s:6:\"50-100\";}s:15:\"applicant_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"county\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Nairobi\";}s:10:\"sub_county\";a:2:{s:3:\"old\";N;s:3:\"new\";s:15:\"Dagoretti South\";}s:6:\"street\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"Naivasha Rd\";}s:14:\"postal_address\";a:2:{s:3:\"old\";N;s:3:\"new\";s:27:\"P.O. Box 20202-0001 NAIROBI\";}s:14:\"approval_notes\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:11:\"map_address\";a:2:{s:3:\"old\";N;s:3:\"new\";s:34:\"Haile Selassie Ave, Nairobi, Kenya\";}s:15:\"applicant_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"07259393393\";}s:17:\"contact_alt_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_title\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:12:\"map_latitude\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"-1.2920659\";}s:13:\"map_longitude\";a:2:{s:3:\"old\";N;s:3:\"new\";s:17:\"36.82194619999996\";}}'),
(153, 3, 'Updated a resource. Table affected: organization, Record Id modified:3', 'http://localhost/medsource/core/organization/update?id=747feb92-5363-4365-af53-148e0a5bd3d5', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:13:\"map_longitude\";a:2:{s:3:\"old\";s:11:\"36.82194620\";s:3:\"new\";s:17:\"36.82194619999996\";}}'),
(154, 2, 'Created a resource. Table affected: org_registration_document_type, Record Id modified:12', 'http://localhost/medsource/core/registration-document-type/create', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:7:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:20:\"Clinical Certificate\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:14:\"has_start_date\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"has_renewal\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"business_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:18:\"a:1:{i:0;s:1:\"5\";}\";}s:21:\"business_entity_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:18:\"a:1:{i:0;s:1:\"1\";}\";}}'),
(155, 2, 'Created a resource. Table affected: org_registration_document, Record Id modified:4', 'http://localhost/medsource/core/registration-document/create?org_id=747feb92-5363-4365-af53-148e0a5bd3d5', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:9:{s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"document_no\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"24555555\";}s:11:\"doc_type_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"12\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"is_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}s:10:\"start_date\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-01-01\";}s:12:\"renewal_date\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-12-31\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";s:9:\"2019 Cert\";}s:9:\"file_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:19:\"1558699498.6066.pdf\";}}'),
(156, 2, 'Created a resource. Table affected: org_registration_document, Record Id modified:5', 'http://localhost/medsource/core/registration-document/create?org_id=747feb92-5363-4365-af53-148e0a5bd3d5', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:9:{s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"document_no\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"22020202\";}s:11:\"doc_type_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"7\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"is_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}s:10:\"start_date\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-05-07\";}s:12:\"renewal_date\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-05-31\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:9:\"file_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:18:\"1558699526.474.png\";}}'),
(157, 3, 'Updated a resource. Table affected: org_registration_document, Record Id modified:5', 'http://localhost/medsource/core/registration-document/approve?id=5', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:11:\"is_approved\";a:2:{s:3:\"old\";i:0;s:3:\"new\";i:1;}}'),
(158, 3, 'Updated a resource. Table affected: org_registration_document, Record Id modified:4', 'http://localhost/medsource/core/registration-document/approve?id=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:11:\"is_approved\";a:2:{s:3:\"old\";i:0;s:3:\"new\";i:1;}}'),
(159, 3, 'Updated a resource. Table affected: organization, Record Id modified:3', 'http://localhost/medsource/core/organization/approve?id=3', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:2:{s:6:\"status\";a:2:{s:3:\"old\";i:1;s:3:\"new\";i:2;}s:11:\"is_approved\";a:2:{s:3:\"old\";i:0;s:3:\"new\";i:1;}}'),
(160, 3, 'Updated a resource. Table affected: organization, Record Id modified:3', 'http://localhost/medsource/core/organization/update?id=747feb92-5363-4365-af53-148e0a5bd3d5', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:3:{s:11:\"map_address\";a:2:{s:3:\"old\";s:34:\"Haile Selassie Ave, Nairobi, Kenya\";s:3:\"new\";s:37:\"Sheikh Abdullas F. Rd, Mombasa, Kenya\";}s:12:\"map_latitude\";a:2:{s:3:\"old\";s:11:\"-1.29206590\";s:3:\"new\";s:18:\"-4.043593351913261\";}s:13:\"map_longitude\";a:2:{s:3:\"old\";s:11:\"36.82194620\";s:3:\"new\";s:17:\"39.66831743714465\";}}'),
(161, 2, 'Created a resource. Table affected: auth_users, Record Id modified:5', 'http://localhost/medsource/auth/user/create?level_id=3&org_id=3', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:11:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"Beatrice Onyango\";}s:8:\"username\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"beatrice\";}s:5:\"email\";a:2:{s:3:\"old\";N;s:3:\"new\";s:26:\"beatrice.onyango@gmail.com\";}s:8:\"level_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"4\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:22:\"auto_generate_password\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"0\";}s:9:\"branch_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"profile_image\";a:2:{s:3:\"old\";N;s:3:\"new\";s:40:\"f37135ab-93f6-4062-980d-7d1474672c37.jpg\";}s:8:\"timezone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"America/New_York\";}s:5:\"phone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"0707292929\";}}'),
(162, 2, 'Created a resource. Table affected: org_registration_document_type, Record Id modified:13', 'http://localhost/medsource/core/registration-document-type/create', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:7:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:20:\"Sample Document Type\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:14:\"has_start_date\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"has_renewal\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"business_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:66:\"a:5:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";}\";}s:21:\"business_entity_types\";a:2:{s:3:\"old\";N;s:3:\"new\";s:42:\"a:3:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";}\";}}'),
(163, 3, 'Updated a resource. Table affected: org_registration_document, Record Id modified:17', 'http://localhost/medsource/core/registration-document/approve?id=17', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:11:\"is_approved\";a:2:{s:3:\"old\";i:0;s:3:\"new\";i:1;}}'),
(164, 3, 'Updated a resource. Table affected: org_registration_document, Record Id modified:16', 'http://localhost/medsource/core/registration-document/approve?id=16', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:11:\"is_approved\";a:2:{s:3:\"old\";i:0;s:3:\"new\";i:1;}}'),
(165, 3, 'Updated a resource. Table affected: org_registration_document, Record Id modified:15', 'http://localhost/medsource/core/registration-document/approve?id=15', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:11:\"is_approved\";a:2:{s:3:\"old\";i:0;s:3:\"new\";i:1;}}'),
(166, 3, 'Updated a resource. Table affected: org_registration_document, Record Id modified:14', 'http://localhost/medsource/core/registration-document/approve?id=14', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:11:\"is_approved\";a:2:{s:3:\"old\";i:0;s:3:\"new\";i:1;}}'),
(167, 3, 'Updated a resource. Table affected: organization, Record Id modified:6', 'http://localhost/medsource/core/organization/approve?id=6', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:2:{s:6:\"status\";a:2:{s:3:\"old\";i:1;s:3:\"new\";i:2;}s:11:\"is_approved\";a:2:{s:3:\"old\";i:0;s:3:\"new\";i:1;}}'),
(168, 2, 'Created a resource. Table affected: auth_users, Record Id modified:6', 'http://localhost/medsource/auth/user/create?level_id=3&org_id=6', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:11:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:13:\"Joseph Mukoko\";}s:8:\"username\";a:2:{s:3:\"old\";N;s:3:\"new\";s:6:\"mukoko\";}s:5:\"email\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"mukoko@gmail.com\";}s:8:\"level_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"6\";}s:22:\"auto_generate_password\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"0\";}s:9:\"branch_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"profile_image\";a:2:{s:3:\"old\";N;s:3:\"new\";s:40:\"1b9817de-5bd1-48b5-855c-a1db91b97ddc.jpg\";}s:8:\"timezone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:14:\"Africa/Nairobi\";}s:5:\"phone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"0724962381\";}}'),
(169, 3, 'Updated a resource. Table affected: auth_user_levels, Record Id modified:3', 'http://localhost/adgg/auth/user-level/update?id=3', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:4:\"name\";a:2:{s:3:\"old\";s:18:\"ORGANIZATION ADMIN\";s:3:\"new\";s:12:\"COUNTRY USER\";}}'),
(170, 2, 'Created a resource. Table affected: auth_user_levels, Record Id modified:4', 'http://localhost/adgg/auth/user-level/create?_pjax=%23UserLevels-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:5:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:4;}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"REGION USER\";}s:9:\"parent_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:15:\"forbidden_items\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(171, 2, 'Created a resource. Table affected: auth_user_levels, Record Id modified:5', 'http://localhost/adgg/auth/user-level/create?_pjax=%23UserLevels-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:5:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:5;}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:13:\"DISTRICT USER\";}s:9:\"parent_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"4\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:15:\"forbidden_items\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(172, 2, 'Created a resource. Table affected: auth_user_levels, Record Id modified:6', 'http://localhost/adgg/auth/user-level/create?_pjax=%23UserLevels-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:5:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:6;}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:9:\"WARD USER\";}s:9:\"parent_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"5\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:15:\"forbidden_items\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(173, 2, 'Created a resource. Table affected: auth_user_levels, Record Id modified:7', 'http://localhost/adgg/auth/user-level/create?_pjax=%23UserLevels-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:5:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:7;}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:12:\"VILLAGE USER\";}s:9:\"parent_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:15:\"forbidden_items\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(174, 3, 'Updated a resource. Table affected: auth_resources, Record Id modified:ORG_COUNTRY', 'http://localhost/adgg/auth/resource/update?id=ORG_MEMBER', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:2:{s:2:\"id\";a:2:{s:3:\"old\";s:10:\"ORG_MEMBER\";s:3:\"new\";s:11:\"ORG_COUNTRY\";}s:4:\"name\";a:2:{s:3:\"old\";s:43:\"MEMBERS (PHARMACIES, HOSPITALS AND CLINICS)\";s:3:\"new\";s:9:\"COUNTRIES\";}}'),
(175, 3, 'Updated a resource. Table affected: auth_resources, Record Id modified:ORG_REGION', 'http://localhost/adgg/auth/resource/update?id=ORG_REGISTRATION_DOCUMENT', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:2:\"id\";a:2:{s:3:\"old\";s:25:\"ORG_REGISTRATION_DOCUMENT\";s:3:\"new\";s:10:\"ORG_REGION\";}}'),
(176, 3, 'Updated a resource. Table affected: auth_resources, Record Id modified:ORG_DISTRICT', 'http://localhost/adgg/auth/resource/update?id=ORG_SUPPLIER', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:2:{s:2:\"id\";a:2:{s:3:\"old\";s:12:\"ORG_SUPPLIER\";s:3:\"new\";s:12:\"ORG_DISTRICT\";}s:4:\"name\";a:2:{s:3:\"old\";s:41:\"SUPPLERS (DISTRIBUTORS AND MANUFACTURERS)\";s:3:\"new\";s:9:\"DISTRICTS\";}}'),
(177, 3, 'Updated a resource. Table affected: auth_resources, Record Id modified:ORG_REGION', 'http://localhost/adgg/auth/resource/update?id=ORG_REGION', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:4:\"name\";a:2:{s:3:\"old\";s:22:\"REGISTRATION DOCUMENTS\";s:3:\"new\";s:7:\"REGIONS\";}}'),
(178, 4, 'Deleted a resource. Table affected: auth_resources, Record Id modified:PRODUCT', 'http://localhost/adgg/auth/resource/delete?id=PRODUCT', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:7:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"PRODUCT\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"PRODUCTS\";}s:8:\"viewable\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:9:\"creatable\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:8:\"editable\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:9:\"deletable\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:10:\"executable\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}}');
INSERT INTO `auth_audit_trail` (`id`, `action`, `action_description`, `url`, `ip_address`, `user_agent`, `user_id`, `org_id`, `details`) VALUES
(179, 2, 'Created a resource. Table affected: auth_resources, Record Id modified:ORG_WARD', 'http://localhost/adgg/auth/resource/create?_pjax=%23Resources-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:7:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"ORG_WARD\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"WARDS\";}s:8:\"viewable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"creatable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:8:\"editable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"deletable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"executable\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(180, 2, 'Created a resource. Table affected: auth_resources, Record Id modified:ORG_VILLAGE', 'http://localhost/adgg/auth/resource/create?_pjax=%23Resources-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:7:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"ORG_VILLAGE\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"VILLAGE\";}s:8:\"viewable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"creatable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"0\";}s:8:\"editable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"0\";}s:9:\"deletable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"0\";}s:10:\"executable\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(181, 3, 'Updated a resource. Table affected: auth_resources, Record Id modified:ORG_VILLAGE', 'http://localhost/adgg/auth/resource/update?id=ORG_VILLAGE', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:1:{s:4:\"name\";a:2:{s:3:\"old\";s:7:\"VILLAGE\";s:3:\"new\";s:8:\"VILLAGES\";}}'),
(182, 3, 'Updated a resource. Table affected: organization, Record Id modified:3', 'http://localhost/adgg/core/organization/update?id=3', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:2:{s:18:\"account_manager_id\";a:2:{s:3:\"old\";i:3;s:3:\"new\";N;}s:13:\"map_longitude\";a:2:{s:3:\"old\";s:11:\"39.66831744\";s:3:\"new\";s:17:\"39.66831744000001\";}}'),
(183, 4, 'Deleted a resource. Table affected: organization, Record Id modified:6', 'http://localhost/adgg/core/organization/delete?id=6', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:37:{s:13:\"business_type\";a:2:{s:3:\"old\";N;s:3:\"new\";i:4;}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"KE\";}s:18:\"contact_first_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"Fredrick\";}s:17:\"contact_last_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Onyango\";}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"0724962380\";}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";s:25:\"fred@competamillman.co.ke\";}s:6:\"status\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"is_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"approved_by\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:20:\"business_entity_type\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:33:\"applicant_business_ownership_type\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:19:\"is_credit_requested\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"is_supplier\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}s:9:\"is_member\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:18:\"account_manager_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:16:\"application_date\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"date_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-05-27\";}s:11:\"approved_at\";a:2:{s:3:\"old\";N;s:3:\"new\";s:19:\"2019-05-27 08:41:19\";}s:19:\"membership_end_date\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-05-27\";}s:10:\"account_no\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"10006\";}s:14:\"applicant_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"Fredrick\";}s:19:\"contact_middle_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:15:\"daily_customers\";a:2:{s:3:\"old\";N;s:3:\"new\";s:4:\"500+\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"Nairobi Hospital\";}s:15:\"applicant_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"county\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Nairobi\";}s:10:\"sub_county\";a:2:{s:3:\"old\";N;s:3:\"new\";s:15:\"Dagoretti South\";}s:6:\"street\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"Naivasha Rd\";}s:4:\"town\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"postal_address\";a:2:{s:3:\"old\";N;s:3:\"new\";s:27:\"P.O. Box 20202-0001 NAIROBI\";}s:14:\"approval_notes\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"Approved\";}s:11:\"map_address\";a:2:{s:3:\"old\";N;s:3:\"new\";s:50:\"P. O. Box 15000 Argwings Kodhek Rd, Nairobi, Kenya\";}s:15:\"applicant_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"0724962380\";}s:17:\"contact_alt_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_title\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:12:\"map_latitude\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"-1.29586300\";}s:13:\"map_longitude\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"36.80339950\";}}'),
(184, 4, 'Deleted a resource. Table affected: organization, Record Id modified:1', 'http://localhost/adgg/core/organization/delete?id=1', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:37:{s:13:\"business_type\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"KE\";}s:18:\"contact_first_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"FREDRICK\";}s:17:\"contact_last_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:6:\"OCHOLA\";}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"0724962380\";}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";s:25:\"fred@competamillman.co.ke\";}s:6:\"status\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"is_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"approved_by\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:20:\"business_entity_type\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:33:\"applicant_business_ownership_type\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:19:\"is_credit_requested\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}s:11:\"is_supplier\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}s:9:\"is_member\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:18:\"account_manager_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:16:\"application_date\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-05-08\";}s:13:\"date_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-05-23\";}s:11:\"approved_at\";a:2:{s:3:\"old\";N;s:3:\"new\";s:19:\"2019-05-24 01:26:52\";}s:19:\"membership_end_date\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"account_no\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"10001\";}s:14:\"applicant_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:19:\"contact_middle_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"ONYANGO\";}s:15:\"daily_customers\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"Melchizedek\";}s:15:\"applicant_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"county\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"sub_county\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"street\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"town\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"postal_address\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"approval_notes\";a:2:{s:3:\"old\";N;s:3:\"new\";s:25:\"This was approved by Fred\";}s:11:\"map_address\";a:2:{s:3:\"old\";N;s:3:\"new\";s:24:\"KU Plaza, Nairobi, Kenya\";}s:15:\"applicant_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:17:\"contact_alt_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_title\";a:2:{s:3:\"old\";N;s:3:\"new\";s:3:\"MR.\";}s:12:\"map_latitude\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"-1.29206590\";}s:13:\"map_longitude\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"36.82194620\";}}'),
(185, 4, 'Deleted a resource. Table affected: organization, Record Id modified:2', 'http://localhost/adgg/core/organization/delete?id=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:37:{s:13:\"business_type\";a:2:{s:3:\"old\";N;s:3:\"new\";i:4;}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"KE\";}s:18:\"contact_first_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"STEPHEN\";}s:17:\"contact_last_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"MAINA\";}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"0724962381\";}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";s:28:\"stephen@competamillman.co.ke\";}s:6:\"status\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"is_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"approved_by\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:20:\"business_entity_type\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:33:\"applicant_business_ownership_type\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:19:\"is_credit_requested\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}s:11:\"is_supplier\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}s:9:\"is_member\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:18:\"account_manager_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:16:\"application_date\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"date_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-05-24\";}s:11:\"approved_at\";a:2:{s:3:\"old\";N;s:3:\"new\";s:19:\"2019-05-23 23:29:04\";}s:19:\"membership_end_date\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"account_no\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"10002\";}s:14:\"applicant_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:19:\"contact_middle_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:15:\"daily_customers\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:17:\"Goodlife Pharmacy\";}s:15:\"applicant_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"county\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"sub_county\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"street\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"town\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"postal_address\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"approval_notes\";a:2:{s:3:\"old\";N;s:3:\"new\";s:25:\"This was approved by Fred\";}s:11:\"map_address\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:15:\"applicant_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:17:\"contact_alt_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_title\";a:2:{s:3:\"old\";N;s:3:\"new\";s:3:\"DR.\";}s:12:\"map_latitude\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"map_longitude\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(186, 4, 'Deleted a resource. Table affected: organization, Record Id modified:3', 'http://localhost/adgg/core/organization/delete?id=3', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:37:{s:13:\"business_type\";a:2:{s:3:\"old\";N;s:3:\"new\";i:5;}s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"KE\";}s:18:\"contact_first_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Moses\";}s:17:\"contact_last_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Maina\";}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"07259393393\";}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";s:24:\"mosesmain@fourways.co.ke\";}s:6:\"status\";a:2:{s:3:\"old\";N;s:3:\"new\";i:2;}s:11:\"is_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"approved_by\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:20:\"business_entity_type\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:33:\"applicant_business_ownership_type\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:19:\"is_credit_requested\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"is_supplier\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}s:9:\"is_member\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:18:\"account_manager_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:16:\"application_date\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-05-24\";}s:13:\"date_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-05-24\";}s:11:\"approved_at\";a:2:{s:3:\"old\";N;s:3:\"new\";s:19:\"2019-05-24 12:07:53\";}s:19:\"membership_end_date\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"2019-12-31\";}s:10:\"account_no\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"10003\";}s:14:\"applicant_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"Moses Maina\";}s:19:\"contact_middle_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:15:\"daily_customers\";a:2:{s:3:\"old\";N;s:3:\"new\";s:6:\"50-100\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:9:\"Four Ways\";}s:15:\"applicant_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"county\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Nairobi\";}s:10:\"sub_county\";a:2:{s:3:\"old\";N;s:3:\"new\";s:15:\"Dagoretti South\";}s:6:\"street\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"Naivasha Rd\";}s:4:\"town\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"postal_address\";a:2:{s:3:\"old\";N;s:3:\"new\";s:27:\"P.O. Box 20202-0001 NAIROBI\";}s:14:\"approval_notes\";a:2:{s:3:\"old\";N;s:3:\"new\";s:21:\"Registration approved\";}s:11:\"map_address\";a:2:{s:3:\"old\";N;s:3:\"new\";s:37:\"Sheikh Abdullas F. Rd, Mombasa, Kenya\";}s:15:\"applicant_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"07259393393\";}s:17:\"contact_alt_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_title\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:12:\"map_latitude\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"-4.04359335\";}s:13:\"map_longitude\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"39.66831744\";}}'),
(187, 2, 'Created a resource. Table affected: organization, Record Id modified:8', 'http://localhost/adgg/core/organization/create?_pjax=%23Organization-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0', 1, NULL, 'a:37:{s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"KE\";}s:18:\"contact_first_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"Fredrick\";}s:17:\"contact_last_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Onyango\";}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:13:\"+254728282828\";}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";s:17:\"info@smsmsm.co.ke\";}s:13:\"business_type\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"status\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"is_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}s:11:\"approved_by\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:20:\"business_entity_type\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:33:\"applicant_business_ownership_type\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:19:\"is_credit_requested\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:11:\"is_supplier\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:9:\"is_member\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:18:\"account_manager_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:16:\"application_date\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"date_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:11:\"approved_at\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:19:\"membership_end_date\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"account_no\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"10008\";}s:14:\"applicant_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:19:\"contact_middle_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:15:\"daily_customers\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Kenya\";}s:15:\"applicant_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"county\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"sub_county\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"street\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"town\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"postal_address\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"approval_notes\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:11:\"map_address\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:15:\"applicant_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:17:\"contact_alt_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:13:\"+020209282828\";}s:13:\"contact_title\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:12:\"map_latitude\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"map_longitude\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(188, 2, 'Created a resource. Table affected: organization, Record Id modified:9', 'http://localhost/adgg/core/organization/create', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:37:{s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"ET\";}s:18:\"contact_first_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"Fredrick\";}s:17:\"contact_last_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:6:\"Ochola\";}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"0723322322\";}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";s:14:\"info@cgiar.com\";}s:13:\"business_type\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"status\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"is_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";i:0;}s:11:\"approved_by\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:20:\"business_entity_type\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:33:\"applicant_business_ownership_type\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:19:\"is_credit_requested\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:11:\"is_supplier\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:9:\"is_member\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:18:\"account_manager_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:16:\"application_date\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"date_approved\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:11:\"approved_at\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:19:\"membership_end_date\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"account_no\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"10009\";}s:14:\"applicant_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:19:\"contact_middle_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:15:\"daily_customers\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"Ethiopia\";}s:15:\"applicant_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"county\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"sub_county\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:6:\"street\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"town\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"postal_address\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:14:\"approval_notes\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:11:\"map_address\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:15:\"applicant_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:17:\"contact_alt_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_title\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:12:\"map_latitude\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"map_longitude\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(189, 2, 'Created a resource. Table affected: auth_resources, Record Id modified:FARM', 'http://localhost/adgg/auth/resource/create', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:7:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:4:\"FARM\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"FARMS\";}s:8:\"viewable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"creatable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:8:\"editable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"deletable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"executable\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(190, 2, 'Created a resource. Table affected: auth_resources, Record Id modified:HERDS', 'http://localhost/adgg/auth/resource/create?_pjax=%23Resources-grid-pjax', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:7:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"HERDS\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"HERDS\";}s:8:\"viewable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"creatable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:8:\"editable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"deletable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"executable\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(191, 2, 'Created a resource. Table affected: auth_resources, Record Id modified:HERD_EVENT', 'http://localhost/adgg/auth/resource/create?_pjax=%23Resources-grid-pjax', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:7:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"HERD_EVENT\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"HERD EVENTS\";}s:8:\"viewable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"creatable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:8:\"editable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:9:\"deletable\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"executable\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(192, 2, 'Created a resource. Table affected: auth_roles, Record Id modified:1', 'http://localhost/adgg/auth/role/create', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:5:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:15:\"Project Manager\";}s:8:\"readonly\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:8:\"level_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"2\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(193, 2, 'Created a resource. Table affected: auth_roles, Record Id modified:2', 'http://localhost/adgg/auth/role/create', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:5:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:6:\"AITECH\";}s:8:\"readonly\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:8:\"level_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"5\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(194, 2, 'Created a resource. Table affected: auth_roles, Record Id modified:3', 'http://localhost/adgg/auth/role/create', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:5:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:15:\"Country Manager\";}s:8:\"readonly\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:8:\"level_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(195, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:33', 'http://localhost/adgg/auth/role/view?id=3', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"AUTH_AUDIT_TRAIL\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(196, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:34', 'http://localhost/adgg/auth/role/view?id=3', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:9:\"AUTH_USER\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(197, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:35', 'http://localhost/adgg/auth/role/view?id=3', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:13:\"CONF_SETTINGS\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(198, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:36', 'http://localhost/adgg/auth/role/view?id=3', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:4:\"FARM\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(199, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:37', 'http://localhost/adgg/auth/role/view?id=3', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:4:\"HELP\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(200, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:38', 'http://localhost/adgg/auth/role/view?id=3', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"HERD_EVENT\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(201, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:39', 'http://localhost/adgg/auth/role/view?id=3', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"HERDS\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(202, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:40', 'http://localhost/adgg/auth/role/view?id=3', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"ORG_COUNTRY\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(203, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:41', 'http://localhost/adgg/auth/role/view?id=3', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:12:\"ORG_DISTRICT\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(204, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:42', 'http://localhost/adgg/auth/role/view?id=3', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"ORG_REGION\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(205, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:43', 'http://localhost/adgg/auth/role/view?id=3', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"ORG_VILLAGE\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(206, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:44', 'http://localhost/adgg/auth/role/view?id=3', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"ORG_WARD\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(207, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:45', 'http://localhost/adgg/auth/role/view?id=3', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"REPORTS\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(208, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:46', 'http://localhost/adgg/auth/role/view?id=3', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:16:\"REPORTS_SETTINGS\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}}'),
(209, 2, 'Created a resource. Table affected: auth_permission, Record Id modified:47', 'http://localhost/adgg/auth/role/view?id=3', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:6:{s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:3;}s:11:\"resource_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"RES_EXPORT\";}s:8:\"can_view\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_create\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:10:\"can_update\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:10:\"can_delete\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(210, 2, 'Created a resource. Table affected: auth_users, Record Id modified:2', 'http://localhost/adgg/auth/user/create', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:11:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:12:\"David Mogaka\";}s:8:\"username\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"dmogaka\";}s:5:\"email\";a:2:{s:3:\"old\";N;s:3:\"new\";s:17:\"dmogaka@gmail.com\";}s:8:\"level_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"9\";}s:22:\"auto_generate_password\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"0\";}s:9:\"branch_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"profile_image\";a:2:{s:3:\"old\";N;s:3:\"new\";s:40:\"491d83e3-688e-4d07-a01c-664c9292d282.jpg\";}s:8:\"timezone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:14:\"Africa/Nairobi\";}s:5:\"phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(211, 2, 'Created a resource. Table affected: auth_users, Record Id modified:3', 'http://localhost/adgg/auth/user/create?level_id=3&org_id=9', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36', 1, NULL, 'a:11:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:15:\"Harrison Njamba\";}s:8:\"username\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"h.njamba\";}s:5:\"email\";a:2:{s:3:\"old\";N;s:3:\"new\";s:18:\"harrison@gmail.com\";}s:8:\"level_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:7:\"role_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"2\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"9\";}s:22:\"auto_generate_password\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"0\";}s:9:\"branch_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"profile_image\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:8:\"timezone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:14:\"Africa/Nairobi\";}s:5:\"phone\";a:2:{s:3:\"old\";N;s:3:\"new\";s:14:\"+2542929920202\";}}'),
(212, 3, 'Updated a resource. Table affected: auth_user_levels, Record Id modified:4', 'http://localhost/adgg/auth/user-level/update?id=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:1:{s:4:\"name\";a:2:{s:3:\"old\";s:11:\"REGION USER\";s:3:\"new\";s:19:\"COUNTRY UNIT 1 USER\";}}'),
(213, 3, 'Updated a resource. Table affected: auth_user_levels, Record Id modified:5', 'http://localhost/adgg/auth/user-level/update?id=5', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:1:{s:4:\"name\";a:2:{s:3:\"old\";s:13:\"DISTRICT USER\";s:3:\"new\";s:18:\"COUNTRY UNIT2 USER\";}}'),
(214, 3, 'Updated a resource. Table affected: auth_user_levels, Record Id modified:6', 'http://localhost/adgg/auth/user-level/update?id=6', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:1:{s:4:\"name\";a:2:{s:3:\"old\";s:9:\"WARD USER\";s:3:\"new\";s:18:\"COUNTRY UNIT3 USER\";}}'),
(215, 3, 'Updated a resource. Table affected: auth_user_levels, Record Id modified:7', 'http://localhost/adgg/auth/user-level/update?id=7', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:1:{s:4:\"name\";a:2:{s:3:\"old\";s:12:\"VILLAGE USER\";s:3:\"new\";s:18:\"COUNTRY UNIT4 USER\";}}'),
(216, 2, 'Created a resource. Table affected: organization, Record Id modified:10', 'http://localhost/adgg/core/organization/create', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:12:{s:7:\"country\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"KE\";}s:10:\"unit1_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:6:\"Region\";}s:10:\"unit2_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"District\";}s:10:\"unit3_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:4:\"Ward\";}s:10:\"unit4_name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:7:\"Village\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"10010\";}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:5:\"Kenya\";}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"uuid\";a:2:{s:3:\"old\";N;s:3:\"new\";s:36:\"59fe84b1-2636-4eb6-97cf-2d0546c718f8\";}s:14:\"contact_person\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(217, 2, 'Created a resource. Table affected: organization_units, Record Id modified:9', 'http://localhost/adgg/core/organization-units/create?level=1&org_id=59fe84b1-2636-4eb6-97cf-2d0546c718f8&tab=2', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:9:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"Region One\";}s:5:\"level\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:10;}s:9:\"parent_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:12:\"contact_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(218, 2, 'Created a resource. Table affected: organization_units, Record Id modified:10', 'http://localhost/adgg/core/organization-units/create?level=1&org_id=59fe84b1-2636-4eb6-97cf-2d0546c718f8&tab=2&_pjax=%23OrganizationUnits-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:9:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"Region Two\";}s:5:\"level\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:10;}s:9:\"parent_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:12:\"contact_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(219, 2, 'Created a resource. Table affected: organization_units, Record Id modified:11', 'http://localhost/adgg/core/organization-units/create?level=1&org_id=59fe84b1-2636-4eb6-97cf-2d0546c718f8&tab=2&_pjax=%23OrganizationUnits-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:9:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:12:\"Region Three\";}s:5:\"level\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:10;}s:9:\"parent_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:12:\"contact_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(220, 2, 'Created a resource. Table affected: organization_units, Record Id modified:12', 'http://localhost/adgg/core/organization-units/create?level=1&org_id=59fe84b1-2636-4eb6-97cf-2d0546c718f8&tab=2&_pjax=%23OrganizationUnits-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:9:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:11:\"Region Four\";}s:5:\"level\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"1\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:10;}s:9:\"parent_id\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:12:\"contact_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(221, 2, 'Created a resource. Table affected: organization_units, Record Id modified:13', 'http://localhost/adgg/core/organization-units/create?level=2&org_id=59fe84b1-2636-4eb6-97cf-2d0546c718f8&tab=3', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:9:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:12:\"District One\";}s:5:\"level\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"2\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:10;}s:9:\"parent_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"9\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:12:\"contact_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(222, 2, 'Created a resource. Table affected: organization_units, Record Id modified:14', 'http://localhost/adgg/core/organization-units/create?level=2&org_id=59fe84b1-2636-4eb6-97cf-2d0546c718f8&tab=3&_pjax=%23OrganizationUnits-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:9:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:12:\"District Two\";}s:5:\"level\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"2\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:10;}s:9:\"parent_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"9\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:12:\"contact_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(223, 2, 'Created a resource. Table affected: organization_units, Record Id modified:15', 'http://localhost/adgg/core/organization-units/create?level=2&org_id=59fe84b1-2636-4eb6-97cf-2d0546c718f8&tab=3&_pjax=%23OrganizationUnits-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:9:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:14:\"District Three\";}s:5:\"level\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"2\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:10;}s:9:\"parent_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"10\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:12:\"contact_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(224, 2, 'Created a resource. Table affected: organization_units, Record Id modified:16', 'http://localhost/adgg/core/organization-units/create?level=2&org_id=59fe84b1-2636-4eb6-97cf-2d0546c718f8&tab=3&_pjax=%23OrganizationUnits-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:9:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:13:\"District Four\";}s:5:\"level\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"2\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:10;}s:9:\"parent_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"10\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:12:\"contact_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(225, 2, 'Created a resource. Table affected: organization_units, Record Id modified:17', 'http://localhost/adgg/core/organization-units/create?level=3&org_id=59fe84b1-2636-4eb6-97cf-2d0546c718f8&tab=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:9:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"Ward One\";}s:5:\"level\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:10;}s:9:\"parent_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"13\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:12:\"contact_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";s:19:\"mconyango@gmail.com\";}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(226, 2, 'Created a resource. Table affected: organization_units, Record Id modified:18', 'http://localhost/adgg/core/organization-units/create?level=3&org_id=59fe84b1-2636-4eb6-97cf-2d0546c718f8&tab=4&_pjax=%23OrganizationUnits-grid-pjax', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:9:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:8:\"Ward Two\";}s:5:\"level\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:10;}s:9:\"parent_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"13\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:12:\"contact_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(227, 3, 'Updated a resource. Table affected: organization_units, Record Id modified:18', 'http://localhost/adgg/core/organization-units/update?id=18', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:1:{s:9:\"parent_id\";a:2:{s:3:\"old\";i:13;s:3:\"new\";s:2:\"16\";}}'),
(228, 2, 'Created a resource. Table affected: organization_units, Record Id modified:19', 'http://localhost/adgg/core/organization-units/create?level=3&org_id=59fe84b1-2636-4eb6-97cf-2d0546c718f8&tab=4', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:9:{s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:10:\"Ward Three\";}s:5:\"level\";a:2:{s:3:\"old\";N;s:3:\"new\";s:1:\"3\";}s:6:\"org_id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:10;}s:9:\"parent_id\";a:2:{s:3:\"old\";N;s:3:\"new\";s:2:\"15\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:4:\"code\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:12:\"contact_name\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_email\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}s:13:\"contact_phone\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}'),
(229, 2, 'Created a resource. Table affected: core_master_list_type, Record Id modified:1', 'http://localhost/adgg/core/list-type/create', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:67.0) Gecko/20100101 Firefox/67.0', 1, NULL, 'a:4:{s:2:\"id\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:4:\"name\";a:2:{s:3:\"old\";N;s:3:\"new\";s:19:\"House Hold (HH) Age\";}s:9:\"is_active\";a:2:{s:3:\"old\";N;s:3:\"new\";i:1;}s:11:\"description\";a:2:{s:3:\"old\";N;s:3:\"new\";N;}}');

-- --------------------------------------------------------

--
-- Table structure for table `auth_log`
--

CREATE TABLE `auth_log` (
  `id` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  `cookieBased` tinyint(1) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `error` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `host` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `userAgent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `auth_password_reset_history`
--

CREATE TABLE `auth_password_reset_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `old_password_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `new_password_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(128) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `password_reset_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_permission`
--

CREATE TABLE `auth_permission` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `resource_id` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT '1',
  `can_create` tinyint(1) NOT NULL DEFAULT '0',
  `can_update` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete` tinyint(1) NOT NULL DEFAULT '0',
  `can_execute` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_permission`
--

INSERT INTO `auth_permission` (`id`, `role_id`, `resource_id`, `can_view`, `can_create`, `can_update`, `can_delete`, `can_execute`) VALUES
(2, 1, 'AUTH_AUDIT_TRAIL', 1, 1, 1, 1, 1),
(3, 1, 'AUTH_USER', 1, 1, 1, 1, 1),
(4, 1, 'CONF_SETTINGS', 1, 1, 1, 1, 1),
(5, 1, 'HELP', 1, 1, 1, 1, 1),
(8, 1, 'REPORTS', 1, 1, 1, 1, 1),
(9, 1, 'REPORTS_SETTINGS', 1, 1, 1, 1, 1),
(10, 1, 'RES_EXPORT', 1, 1, 0, 0, 1),
(12, 2, 'AUTH_AUDIT_TRAIL', 1, 1, 1, 1, 1),
(13, 2, 'AUTH_USER', 1, 1, 1, 1, 1),
(14, 2, 'CONF_SETTINGS', 1, 1, 1, 1, 1),
(15, 2, 'HELP', 1, 1, 1, 1, 1),
(18, 2, 'REPORTS', 1, 1, 1, 1, 1),
(19, 2, 'REPORTS_SETTINGS', 1, 1, 1, 1, 1),
(20, 2, 'RES_EXPORT', 1, 1, 0, 0, 1),
(22, 4, 'AUTH_AUDIT_TRAIL', 1, 1, 1, 1, 1),
(23, 4, 'AUTH_USER', 1, 1, 1, 1, 1),
(24, 4, 'CONF_SETTINGS', 0, 1, 1, 1, 1),
(25, 4, 'HELP', 1, 1, 1, 1, 1),
(26, 4, 'ORG_COUNTRY', 1, 1, 1, 1, 1),
(29, 4, 'REPORTS', 1, 1, 1, 1, 1),
(30, 4, 'REPORTS_SETTINGS', 1, 1, 1, 1, 1),
(31, 4, 'RES_EXPORT', 1, 1, 0, 0, 1),
(32, 4, 'ORG_DISTRICT', 1, 1, 1, 1, 1),
(33, 3, 'AUTH_AUDIT_TRAIL', 1, 1, 1, 1, 1),
(34, 3, 'AUTH_USER', 1, 1, 1, 1, 1),
(35, 3, 'CONF_SETTINGS', 1, 1, 1, 1, 1),
(36, 3, 'FARM', 1, 1, 1, 1, 1),
(37, 3, 'HELP', 1, 1, 1, 1, 1),
(38, 3, 'HERD_EVENT', 1, 1, 1, 1, 1),
(39, 3, 'HERDS', 1, 1, 1, 1, 1),
(40, 3, 'ORG_COUNTRY', 1, 1, 1, 1, 1),
(41, 3, 'ORG_DISTRICT', 1, 1, 1, 1, 1),
(42, 3, 'ORG_REGION', 1, 1, 1, 1, 1),
(43, 3, 'ORG_VILLAGE', 1, 0, 0, 0, 1),
(44, 3, 'ORG_WARD', 1, 1, 1, 1, 1),
(45, 3, 'REPORTS', 1, 1, 1, 1, 1),
(46, 3, 'REPORTS_SETTINGS', 1, 1, 1, 1, 1),
(47, 3, 'RES_EXPORT', 1, 1, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_resources`
--

CREATE TABLE `auth_resources` (
  `id` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `viewable` tinyint(1) NOT NULL DEFAULT '1',
  `creatable` tinyint(1) NOT NULL DEFAULT '1',
  `editable` tinyint(1) NOT NULL DEFAULT '1',
  `deletable` tinyint(1) NOT NULL DEFAULT '1',
  `executable` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_resources`
--

INSERT INTO `auth_resources` (`id`, `name`, `viewable`, `creatable`, `editable`, `deletable`, `executable`) VALUES
('AUTH_AUDIT_TRAIL', 'AUDIT TRAIL', 1, 1, 1, 1, 0),
('AUTH_ROLE', 'ROLES', 1, 1, 1, 1, 0),
('AUTH_USER', 'USER MANAGEMENT', 1, 1, 1, 1, 0),
('CONF_SETTINGS', 'SETTINGS', 1, 1, 1, 1, 0),
('FARM', 'FARMS', 1, 1, 1, 1, 0),
('HELP', 'HELP MODULE', 1, 1, 1, 1, 1),
('HERD_EVENT', 'HERD EVENTS', 1, 1, 1, 1, 0),
('HERDS', 'HERDS', 1, 1, 1, 1, 0),
('ORG_COUNTRY', 'COUNTRIES', 1, 1, 1, 1, 0),
('ORG_DISTRICT', 'DISTRICTS', 1, 1, 1, 1, 0),
('ORG_REGION', 'REGIONS', 1, 1, 1, 1, 0),
('ORG_VILLAGE', 'VILLAGES', 1, 0, 0, 0, 0),
('ORG_WARD', 'WARDS', 1, 1, 1, 1, 0),
('REPORTS', 'REPORTS', 1, 1, 1, 1, 1),
('REPORTS_SETTINGS', 'REPORTS SETTINGS', 1, 1, 1, 1, 0),
('RES_EXPORT', 'EXPORTING/PRINTING DATA', 1, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `auth_roles`
--

CREATE TABLE `auth_roles` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `readonly` tinyint(1) NOT NULL DEFAULT '0',
  `level_id` int(3) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_roles`
--

INSERT INTO `auth_roles` (`id`, `name`, `description`, `readonly`, `level_id`, `is_active`, `created_at`, `created_by`) VALUES
(1, 'Project Manager', NULL, 0, 2, 1, '2019-05-31 08:00:32', 1),
(2, 'AITECH', NULL, 0, 5, 1, '2019-05-31 08:03:21', 1),
(3, 'Country Manager', NULL, 0, 3, 1, '2019-05-31 08:12:02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_users`
--

CREATE TABLE `auth_users` (
  `id` int(11) NOT NULL,
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '1',
  `timezone` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `auth_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_activation_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `level_id` int(11) NOT NULL,
  `org_id` int(11) DEFAULT NULL,
  `is_main_account` tinyint(1) NOT NULL DEFAULT '1',
  `role_id` int(11) DEFAULT NULL,
  `profile_image` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `require_password_change` tinyint(1) NOT NULL DEFAULT '1',
  `auto_generate_password` tinyint(1) NOT NULL DEFAULT '0',
  `branch_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `uuid` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `auth_users`
--

INSERT INTO `auth_users` (`id`, `name`, `username`, `phone`, `email`, `status`, `timezone`, `password_hash`, `password_reset_token`, `auth_key`, `account_activation_token`, `level_id`, `org_id`, `is_main_account`, `role_id`, `profile_image`, `require_password_change`, `auto_generate_password`, `branch_id`, `created_by`, `updated_at`, `updated_by`, `is_deleted`, `deleted_at`, `deleted_by`, `last_login`, `uuid`) VALUES
(1, 'Fredrick Ochola', 'admin', '0724962380', 'mconyango@gmail.com', 1, 'Africa/Nairobi', '$2y$13$9Mdns3kGFeEDbtVnPJWR9.ZwqE7pXRnJvLmRec0Lu20EnqNU4in5i', 'Ba210zVHKfFawTmBLgAA7VEgEX84PH0i_1558947450', NULL, NULL, -1, NULL, 1, 1, '1700f92b-0a6f-4b78-a154-d625ae6a1b3f.jpg', 0, 0, NULL, NULL, '2019-05-27 08:57:30', 1, 0, NULL, NULL, '2019-06-19 09:05:16', 'b9200532-ff13-4c7e-8aac-c48c6f09064b');

-- --------------------------------------------------------

--
-- Table structure for table `auth_user_levels`
--

CREATE TABLE `auth_user_levels` (
  `id` int(11) NOT NULL,
  `name` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `forbidden_items` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_id` smallint(6) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `auth_user_levels`
--

INSERT INTO `auth_user_levels` (`id`, `name`, `forbidden_items`, `parent_id`, `is_active`) VALUES
(-1, 'DEVELOPER', NULL, NULL, 1),
(1, 'SUPER-ADMIN', 'a:2:{i:0;s:13:\"AUTH_RESOURCE\";i:1;s:15:\"AUTH_USER_LEVEL\";}', -1, 1),
(2, 'SYSTEM ADMIN', 'a:1:{i:0;s:9:\"AUTH_ROLE\";}', 1, 1),
(3, 'COUNTRY USER', NULL, 2, 1),
(4, 'COUNTRY UNIT 1 USER', NULL, 3, 1),
(5, 'COUNTRY UNIT2 USER', NULL, 4, 1),
(6, 'COUNTRY UNIT3 USER', NULL, 5, 1),
(7, 'COUNTRY UNIT4 USER', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `conf_jobs`
--

CREATE TABLE `conf_jobs` (
  `id` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_run` timestamp NULL DEFAULT NULL,
  `execution_type` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `threads` int(11) NOT NULL DEFAULT '0',
  `max_threads` int(11) NOT NULL DEFAULT '3',
  `sleep` int(11) NOT NULL DEFAULT '5',
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `conf_jobs`
--

INSERT INTO `conf_jobs` (`id`, `last_run`, `execution_type`, `is_active`, `threads`, `max_threads`, `sleep`, `start_time`, `end_time`) VALUES
('generalCron', NULL, 1, 0, 0, 1, 5, NULL, NULL),
('notificationCron', NULL, 1, 0, 0, 1, 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `conf_job_processes`
--

CREATE TABLE `conf_job_processes` (
  `id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `job_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_run_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conf_notif`
--

CREATE TABLE `conf_notif` (
  `id` int(11) UNSIGNED NOT NULL,
  `notif_type_id` varchar(60) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `item_id` int(11) UNSIGNED NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `is_seen` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `conf_notif_queue`
--

CREATE TABLE `conf_notif_queue` (
  `id` int(11) NOT NULL,
  `notif_type_id` varchar(128) NOT NULL,
  `item_id` varchar(128) NOT NULL,
  `max_notifications` int(11) NOT NULL DEFAULT '1',
  `notifications_count` int(11) NOT NULL DEFAULT '1',
  `notification_time` time DEFAULT '08:00:00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `conf_notif_types`
--

CREATE TABLE `conf_notif_types` (
  `id` varchar(60) NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `template` varchar(500) NOT NULL,
  `email_template_id` varchar(128) DEFAULT NULL,
  `sms_template_id` varchar(128) DEFAULT NULL,
  `enable_internal_notification` tinyint(1) NOT NULL DEFAULT '1',
  `enable_email_notification` tinyint(1) NOT NULL DEFAULT '1',
  `enable_sms_notification` tinyint(1) DEFAULT '0',
  `notify_all_users` tinyint(1) NOT NULL DEFAULT '0',
  `notify_days_before` int(11) UNSIGNED DEFAULT NULL,
  `model_class_name` varchar(60) NOT NULL,
  `fa_icon_class` varchar(30) NOT NULL DEFAULT 'fa-bell',
  `notification_trigger` tinyint(4) NOT NULL DEFAULT '1',
  `max_notifications` int(11) NOT NULL DEFAULT '1',
  `notification_time` time DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `users` varchar(1000) DEFAULT NULL,
  `roles` varchar(1000) DEFAULT NULL,
  `email` varchar(1000) DEFAULT NULL,
  `phone` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `conf_numbering_format`
--

CREATE TABLE `conf_numbering_format` (
  `id` int(11) NOT NULL,
  `code` varchar(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `name` varchar(255) NOT NULL,
  `next_number` int(11) NOT NULL DEFAULT '1',
  `min_digits` smallint(6) NOT NULL DEFAULT '3',
  `prefix` varchar(5) DEFAULT NULL,
  `suffix` varchar(5) DEFAULT NULL,
  `preview` varchar(128) DEFAULT NULL,
  `org_id` int(11) DEFAULT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `conf_numbering_format`
--

INSERT INTO `conf_numbering_format` (`id`, `code`, `name`, `next_number`, `min_digits`, `prefix`, `suffix`, `preview`, `org_id`, `is_private`, `is_active`, `created_by`) VALUES
(1, '20100101', '2020202020', 1, 3, '202/', '/2822', '202/001/2822', NULL, 1, 1, 1),
(2, 'organization_account_no', 'Organization Account Number', 10011, 3, NULL, NULL, '10003', NULL, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `conf_timezone_ref`
--

CREATE TABLE `conf_timezone_ref` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `conf_timezone_ref`
--

INSERT INTO `conf_timezone_ref` (`id`, `name`) VALUES
(2, 'Africa/Abidjan'),
(3, 'Africa/Accra'),
(4, 'Africa/Addis_Ababa'),
(5, 'Africa/Algiers'),
(6, 'Africa/Asmara'),
(7, 'Africa/Asmera'),
(8, 'Africa/Bamako'),
(9, 'Africa/Bangui'),
(10, 'Africa/Banjul'),
(11, 'Africa/Bissau'),
(12, 'Africa/Blantyre'),
(13, 'Africa/Brazzaville'),
(14, 'Africa/Bujumbura'),
(15, 'Africa/Cairo'),
(16, 'Africa/Casablanca'),
(17, 'Africa/Ceuta'),
(18, 'Africa/Conakry'),
(19, 'Africa/Dakar'),
(20, 'Africa/Dar_es_Salaam'),
(21, 'Africa/Djibouti'),
(22, 'Africa/Douala'),
(23, 'Africa/El_Aaiun'),
(24, 'Africa/Freetown'),
(25, 'Africa/Gaborone'),
(26, 'Africa/Harare'),
(27, 'Africa/Johannesburg'),
(28, 'Africa/Kampala'),
(29, 'Africa/Khartoum'),
(30, 'Africa/Kigali'),
(31, 'Africa/Kinshasa'),
(32, 'Africa/Lagos'),
(33, 'Africa/Libreville'),
(34, 'Africa/Lome'),
(35, 'Africa/Luanda'),
(36, 'Africa/Lubumbashi'),
(37, 'Africa/Lusaka'),
(38, 'Africa/Malabo'),
(39, 'Africa/Maputo'),
(40, 'Africa/Maseru'),
(41, 'Africa/Mbabane'),
(42, 'Africa/Mogadishu'),
(43, 'Africa/Monrovia'),
(44, 'Africa/Nairobi'),
(45, 'Africa/Ndjamena'),
(46, 'Africa/Niamey'),
(47, 'Africa/Nouakchott'),
(48, 'Africa/Ouagadougou'),
(49, 'Africa/Porto-Novo'),
(50, 'Africa/Sao_Tome'),
(51, 'Africa/Timbuktu'),
(52, 'Africa/Tripoli'),
(53, 'Africa/Tunis'),
(54, 'Africa/Windhoek'),
(55, 'America/Adak'),
(56, 'America/Anchorage'),
(57, 'America/Anguilla'),
(58, 'America/Antigua'),
(59, 'America/Araguaina'),
(60, 'America/Argentina/Buenos_Aires'),
(61, 'America/Argentina/Catamarca'),
(62, 'America/Argentina/ComodRivadavia'),
(63, 'America/Argentina/Cordoba'),
(64, 'America/Argentina/Jujuy'),
(65, 'America/Argentina/La_Rioja'),
(66, 'America/Argentina/Mendoza'),
(67, 'America/Argentina/Rio_Gallegos'),
(68, 'America/Argentina/Salta'),
(69, 'America/Argentina/San_Juan'),
(70, 'America/Argentina/San_Luis'),
(71, 'America/Argentina/Tucuman'),
(72, 'America/Argentina/Ushuaia'),
(73, 'America/Aruba'),
(74, 'America/Asuncion'),
(75, 'America/Atikokan'),
(76, 'America/Atka'),
(77, 'America/Bahia'),
(78, 'America/Bahia_Banderas'),
(79, 'America/Barbados'),
(80, 'America/Belem'),
(81, 'America/Belize'),
(82, 'America/Blanc-Sablon'),
(83, 'America/Boa_Vista'),
(84, 'America/Bogota'),
(85, 'America/Boise'),
(86, 'America/Buenos_Aires'),
(87, 'America/Cambridge_Bay'),
(88, 'America/Campo_Grande'),
(89, 'America/Cancun'),
(90, 'America/Caracas'),
(91, 'America/Catamarca'),
(92, 'America/Cayenne'),
(93, 'America/Cayman'),
(94, 'America/Chicago'),
(95, 'America/Chihuahua'),
(96, 'America/Coral_Harbour'),
(97, 'America/Cordoba'),
(98, 'America/Costa_Rica'),
(99, 'America/Cuiaba'),
(100, 'America/Curacao'),
(101, 'America/Danmarkshavn'),
(102, 'America/Dawson'),
(103, 'America/Dawson_Creek'),
(104, 'America/Denver'),
(105, 'America/Detroit'),
(106, 'America/Dominica'),
(107, 'America/Edmonton'),
(108, 'America/Eirunepe'),
(109, 'America/El_Salvador'),
(110, 'America/Ensenada'),
(111, 'America/Fortaleza'),
(112, 'America/Fort_Wayne'),
(113, 'America/Glace_Bay'),
(114, 'America/Godthab'),
(115, 'America/Goose_Bay'),
(116, 'America/Grand_Turk'),
(117, 'America/Grenada'),
(118, 'America/Guadeloupe'),
(119, 'America/Guatemala'),
(120, 'America/Guayaquil'),
(121, 'America/Guyana'),
(122, 'America/Halifax'),
(123, 'America/Havana'),
(124, 'America/Hermosillo'),
(125, 'America/Indiana/Indianapolis'),
(126, 'America/Indiana/Knox'),
(127, 'America/Indiana/Marengo'),
(128, 'America/Indiana/Petersburg'),
(129, 'America/Indianapolis'),
(130, 'America/Indiana/Tell_City'),
(131, 'America/Indiana/Vevay'),
(132, 'America/Indiana/Vincennes'),
(133, 'America/Indiana/Winamac'),
(134, 'America/Inuvik'),
(135, 'America/Iqaluit'),
(136, 'America/Jamaica'),
(137, 'America/Jujuy'),
(138, 'America/Juneau'),
(139, 'America/Kentucky/Louisville'),
(140, 'America/Kentucky/Monticello'),
(141, 'America/Knox_IN'),
(142, 'America/La_Paz'),
(143, 'America/Lima'),
(144, 'America/Los_Angeles'),
(145, 'America/Louisville'),
(146, 'America/Maceio'),
(147, 'America/Managua'),
(148, 'America/Manaus'),
(149, 'America/Marigot'),
(150, 'America/Martinique'),
(151, 'America/Matamoros'),
(152, 'America/Mazatlan'),
(153, 'America/Mendoza'),
(154, 'America/Menominee'),
(155, 'America/Merida'),
(156, 'America/Metlakatla'),
(157, 'America/Mexico_City'),
(158, 'America/Miquelon'),
(159, 'America/Moncton'),
(160, 'America/Monterrey'),
(161, 'America/Montevideo'),
(162, 'America/Montreal'),
(163, 'America/Montserrat'),
(164, 'America/Nassau'),
(165, 'America/New_York'),
(166, 'America/Nipigon'),
(167, 'America/Nome'),
(168, 'America/Noronha'),
(169, 'America/North_Dakota/Beulah'),
(170, 'America/North_Dakota/Center'),
(171, 'America/North_Dakota/New_Salem'),
(172, 'America/Ojinaga'),
(173, 'America/Panama'),
(174, 'America/Pangnirtung'),
(175, 'America/Paramaribo'),
(176, 'America/Phoenix'),
(177, 'America/Port-au-Prince'),
(178, 'America/Porto_Acre'),
(179, 'America/Port_of_Spain'),
(180, 'America/Porto_Velho'),
(181, 'America/Puerto_Rico'),
(182, 'America/Rainy_River'),
(183, 'America/Rankin_Inlet'),
(184, 'America/Recife'),
(185, 'America/Regina'),
(186, 'America/Resolute'),
(187, 'America/Rio_Branco'),
(188, 'America/Rosario'),
(189, 'America/Santa_Isabel'),
(190, 'America/Santarem'),
(191, 'America/Santiago'),
(192, 'America/Santo_Domingo'),
(193, 'America/Sao_Paulo'),
(194, 'America/Scoresbysund'),
(195, 'America/Shiprock'),
(196, 'America/Sitka'),
(197, 'America/St_Barthelemy'),
(198, 'America/St_Johns'),
(199, 'America/St_Kitts'),
(200, 'America/St_Lucia'),
(201, 'America/St_Thomas'),
(202, 'America/St_Vincent'),
(203, 'America/Swift_Current'),
(204, 'America/Tegucigalpa'),
(205, 'America/Thule'),
(206, 'America/Thunder_Bay'),
(207, 'America/Tijuana'),
(208, 'America/Toronto'),
(209, 'America/Tortola'),
(210, 'America/Vancouver'),
(211, 'America/Virgin'),
(212, 'America/Whitehorse'),
(213, 'America/Winnipeg'),
(214, 'America/Yakutat'),
(215, 'America/Yellowknife'),
(216, 'Antarctica/Casey'),
(217, 'Antarctica/Davis'),
(218, 'Antarctica/DumontDUrville'),
(219, 'Antarctica/Macquarie'),
(220, 'Antarctica/Mawson'),
(221, 'Antarctica/McMurdo'),
(222, 'Antarctica/Palmer'),
(223, 'Antarctica/Rothera'),
(224, 'Antarctica/South_Pole'),
(225, 'Antarctica/Syowa'),
(226, 'Antarctica/Vostok'),
(227, 'Arctic/Longyearbyen'),
(228, 'Asia/Aden'),
(229, 'Asia/Almaty'),
(230, 'Asia/Amman'),
(231, 'Asia/Anadyr'),
(232, 'Asia/Aqtau'),
(233, 'Asia/Aqtobe'),
(234, 'Asia/Ashgabat'),
(235, 'Asia/Ashkhabad'),
(236, 'Asia/Baghdad'),
(237, 'Asia/Bahrain'),
(238, 'Asia/Baku'),
(239, 'Asia/Bangkok'),
(240, 'Asia/Beirut'),
(241, 'Asia/Bishkek'),
(242, 'Asia/Brunei'),
(243, 'Asia/Calcutta'),
(244, 'Asia/Choibalsan'),
(245, 'Asia/Chongqing'),
(246, 'Asia/Chungking'),
(247, 'Asia/Colombo'),
(248, 'Asia/Dacca'),
(249, 'Asia/Damascus'),
(250, 'Asia/Dhaka'),
(251, 'Asia/Dili'),
(252, 'Asia/Dubai'),
(253, 'Asia/Dushanbe'),
(254, 'Asia/Gaza'),
(255, 'Asia/Harbin'),
(256, 'Asia/Ho_Chi_Minh'),
(257, 'Asia/Hong_Kong'),
(258, 'Asia/Hovd'),
(259, 'Asia/Irkutsk'),
(260, 'Asia/Istanbul'),
(261, 'Asia/Jakarta'),
(262, 'Asia/Jayapura'),
(263, 'Asia/Jerusalem'),
(264, 'Asia/Kabul'),
(265, 'Asia/Kamchatka'),
(266, 'Asia/Karachi'),
(267, 'Asia/Kashgar'),
(268, 'Asia/Kathmandu'),
(269, 'Asia/Katmandu'),
(270, 'Asia/Kolkata'),
(271, 'Asia/Krasnoyarsk'),
(272, 'Asia/Kuala_Lumpur'),
(273, 'Asia/Kuching'),
(274, 'Asia/Kuwait'),
(275, 'Asia/Macao'),
(276, 'Asia/Macau'),
(277, 'Asia/Magadan'),
(278, 'Asia/Makassar'),
(279, 'Asia/Manila'),
(280, 'Asia/Muscat'),
(281, 'Asia/Nicosia'),
(282, 'Asia/Novokuznetsk'),
(283, 'Asia/Novosibirsk'),
(284, 'Asia/Omsk'),
(285, 'Asia/Oral'),
(286, 'Asia/Phnom_Penh'),
(287, 'Asia/Pontianak'),
(288, 'Asia/Pyongyang'),
(289, 'Asia/Qatar'),
(290, 'Asia/Qyzylorda'),
(291, 'Asia/Rangoon'),
(292, 'Asia/Riyadh'),
(293, 'Asia/Saigon'),
(294, 'Asia/Sakhalin'),
(295, 'Asia/Samarkand'),
(296, 'Asia/Seoul'),
(297, 'Asia/Shanghai'),
(298, 'Asia/Singapore'),
(299, 'Asia/Taipei'),
(300, 'Asia/Tashkent'),
(301, 'Asia/Tbilisi'),
(302, 'Asia/Tehran'),
(303, 'Asia/Tel_Aviv'),
(304, 'Asia/Thimbu'),
(305, 'Asia/Thimphu'),
(306, 'Asia/Tokyo'),
(307, 'Asia/Ujung_Pandang'),
(308, 'Asia/Ulaanbaatar'),
(309, 'Asia/Ulan_Bator'),
(310, 'Asia/Urumqi'),
(311, 'Asia/Vientiane'),
(312, 'Asia/Vladivostok'),
(313, 'Asia/Yakutsk'),
(314, 'Asia/Yekaterinburg'),
(315, 'Asia/Yerevan'),
(316, 'Atlantic/Azores'),
(317, 'Atlantic/Bermuda'),
(318, 'Atlantic/Canary'),
(319, 'Atlantic/Cape_Verde'),
(320, 'Atlantic/Faeroe'),
(321, 'Atlantic/Faroe'),
(322, 'Atlantic/Jan_Mayen'),
(323, 'Atlantic/Madeira'),
(324, 'Atlantic/Reykjavik'),
(325, 'Atlantic/South_Georgia'),
(326, 'Atlantic/Stanley'),
(327, 'Atlantic/St_Helena'),
(328, 'Australia/ACT'),
(329, 'Australia/Adelaide'),
(330, 'Australia/Brisbane'),
(331, 'Australia/Broken_Hill'),
(332, 'Australia/Canberra'),
(333, 'Australia/Currie'),
(334, 'Australia/Darwin'),
(335, 'Australia/Eucla'),
(336, 'Australia/Hobart'),
(337, 'Australia/LHI'),
(338, 'Australia/Lindeman'),
(339, 'Australia/Lord_Howe'),
(340, 'Australia/Melbourne'),
(341, 'Australia/North'),
(342, 'Australia/NSW'),
(343, 'Australia/Perth'),
(344, 'Australia/Queensland'),
(345, 'Australia/South'),
(346, 'Australia/Sydney'),
(347, 'Australia/Tasmania'),
(348, 'Australia/Victoria'),
(349, 'Australia/West'),
(350, 'Australia/Yancowinna'),
(351, 'Europe/Amsterdam'),
(352, 'Europe/Andorra'),
(353, 'Europe/Athens'),
(354, 'Europe/Belfast'),
(355, 'Europe/Belgrade'),
(356, 'Europe/Berlin'),
(357, 'Europe/Bratislava'),
(358, 'Europe/Brussels'),
(359, 'Europe/Bucharest'),
(360, 'Europe/Budapest'),
(361, 'Europe/Chisinau'),
(362, 'Europe/Copenhagen'),
(363, 'Europe/Dublin'),
(364, 'Europe/Gibraltar'),
(365, 'Europe/Guernsey'),
(366, 'Europe/Helsinki'),
(367, 'Europe/Isle_of_Man'),
(368, 'Europe/Istanbul'),
(369, 'Europe/Jersey'),
(370, 'Europe/Kaliningrad'),
(371, 'Europe/Kiev'),
(372, 'Europe/Lisbon'),
(373, 'Europe/Ljubljana'),
(374, 'Europe/London'),
(375, 'Europe/Luxembourg'),
(376, 'Europe/Madrid'),
(377, 'Europe/Malta'),
(378, 'Europe/Mariehamn'),
(379, 'Europe/Minsk'),
(380, 'Europe/Monaco'),
(381, 'Europe/Moscow'),
(382, 'Europe/Nicosia'),
(383, 'Europe/Oslo'),
(384, 'Europe/Paris'),
(385, 'Europe/Podgorica'),
(386, 'Europe/Prague'),
(387, 'Europe/Riga'),
(388, 'Europe/Rome'),
(389, 'Europe/Samara'),
(390, 'Europe/San_Marino'),
(391, 'Europe/Sarajevo'),
(392, 'Europe/Simferopol'),
(393, 'Europe/Skopje'),
(394, 'Europe/Sofia'),
(395, 'Europe/Stockholm'),
(396, 'Europe/Tallinn'),
(397, 'Europe/Tirane'),
(398, 'Europe/Tiraspol'),
(399, 'Europe/Uzhgorod'),
(400, 'Europe/Vaduz'),
(401, 'Europe/Vatican'),
(402, 'Europe/Vienna'),
(403, 'Europe/Vilnius'),
(404, 'Europe/Volgograd'),
(405, 'Europe/Warsaw'),
(406, 'Europe/Zagreb'),
(407, 'Europe/Zaporozhye'),
(408, 'Europe/Zurich'),
(409, 'Indian/Antananarivo'),
(410, 'Indian/Chagos'),
(411, 'Indian/Christmas'),
(412, 'Indian/Cocos'),
(413, 'Indian/Comoro'),
(414, 'Indian/Kerguelen'),
(415, 'Indian/Mahe'),
(416, 'Indian/Maldives'),
(417, 'Indian/Mauritius'),
(418, 'Indian/Mayotte'),
(419, 'Indian/Reunion'),
(420, 'Pacific/Apia'),
(421, 'Pacific/Auckland'),
(422, 'Pacific/Chatham'),
(423, 'Pacific/Chuuk'),
(424, 'Pacific/Easter'),
(425, 'Pacific/Efate'),
(426, 'Pacific/Enderbury'),
(427, 'Pacific/Fakaofo'),
(428, 'Pacific/Fiji'),
(429, 'Pacific/Funafuti'),
(430, 'Pacific/Galapagos'),
(431, 'Pacific/Gambier'),
(432, 'Pacific/Guadalcanal'),
(433, 'Pacific/Guam'),
(434, 'Pacific/Honolulu'),
(435, 'Pacific/Johnston'),
(436, 'Pacific/Kiritimati'),
(437, 'Pacific/Kosrae'),
(438, 'Pacific/Kwajalein'),
(439, 'Pacific/Majuro'),
(440, 'Pacific/Marquesas'),
(441, 'Pacific/Midway'),
(442, 'Pacific/Nauru'),
(443, 'Pacific/Niue'),
(444, 'Pacific/Norfolk'),
(445, 'Pacific/Noumea'),
(446, 'Pacific/Pago_Pago'),
(447, 'Pacific/Palau'),
(448, 'Pacific/Pitcairn'),
(449, 'Pacific/Pohnpei'),
(450, 'Pacific/Ponape'),
(451, 'Pacific/Port_Moresby'),
(452, 'Pacific/Rarotonga'),
(453, 'Pacific/Saipan'),
(454, 'Pacific/Samoa'),
(455, 'Pacific/Tahiti'),
(456, 'Pacific/Tarawa'),
(457, 'Pacific/Tongatapu'),
(458, 'Pacific/Truk'),
(459, 'Pacific/Wake'),
(460, 'Pacific/Wallis'),
(461, 'Pacific/Yap');

-- --------------------------------------------------------

--
-- Table structure for table `core_extendable_table`
--

CREATE TABLE `core_extendable_table` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `label` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `uuid` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_farm`
--

CREATE TABLE `core_farm` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `lat` decimal(13,8) DEFAULT NULL,
  `lng` decimal(13,8) DEFAULT NULL,
  `latlng` point DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_master_country`
--

CREATE TABLE `core_master_country` (
  `id` int(11) NOT NULL,
  `iso2` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `call_code` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `core_master_country`
--

INSERT INTO `core_master_country` (`id`, `iso2`, `name`, `is_active`, `call_code`, `currency`) VALUES
(1, 'AD', 'Andorra', 1, '23838', 'UGX'),
(2, 'AE', 'United Arab Emirates', 1, NULL, 'EUR'),
(3, 'AF', 'Afghanistan', 1, '2322', 'EUR'),
(4, 'AG', 'Antigua and Barbuda', 1, NULL, NULL),
(5, 'AI', 'Anguilla', 1, NULL, NULL),
(6, 'AL', 'Albania', 1, NULL, NULL),
(7, 'AM', 'Armenia', 1, NULL, NULL),
(8, 'AN', 'Netherlands Antilles', 1, NULL, NULL),
(9, 'AO', 'Angola', 1, NULL, NULL),
(10, 'AQ', 'Antarctica', 1, NULL, NULL),
(11, 'AR', 'Argentina', 1, NULL, NULL),
(12, 'AS', 'American Samoa', 1, NULL, NULL),
(13, 'AT', 'Austria', 1, NULL, NULL),
(14, 'AU', 'Australia', 1, NULL, NULL),
(15, 'AW', 'Aruba', 1, NULL, NULL),
(16, 'AX', 'Aland Islands', 1, NULL, NULL),
(17, 'AZ', 'Azerbaijan', 1, NULL, NULL),
(18, 'BA', 'Bosnia and Herzegovina', 1, NULL, NULL),
(19, 'BB', 'Barbados', 1, NULL, NULL),
(20, 'BD', 'Bangladesh', 1, NULL, NULL),
(21, 'BE', 'Belgium', 1, NULL, NULL),
(22, 'BF', 'Burkina Faso', 1, NULL, NULL),
(23, 'BG', 'Bulgaria', 1, NULL, NULL),
(24, 'BH', 'Bahrain', 1, NULL, NULL),
(25, 'BI', 'Burundi', 1, NULL, NULL),
(26, 'BJ', 'Benin', 1, NULL, NULL),
(27, 'BL', 'Saint-BarthÃ©lemy', 1, NULL, NULL),
(28, 'BM', 'Bermuda', 1, NULL, NULL),
(29, 'BN', 'Brunei Darussalam', 1, NULL, NULL),
(30, 'BO', 'Bolivia', 1, NULL, NULL),
(31, 'BR', 'Brazil', 1, NULL, NULL),
(32, 'BS', 'Bahamas', 1, NULL, NULL),
(33, 'BT', 'Bhutan', 1, NULL, NULL),
(34, 'BV', 'Bouvet Island', 1, NULL, NULL),
(35, 'BW', 'Botswana', 1, NULL, NULL),
(36, 'BY', 'Belarus', 1, NULL, NULL),
(37, 'BZ', 'Belize', 1, NULL, NULL),
(38, 'CA', 'Canada', 1, NULL, NULL),
(39, 'CC', 'Cocos (Keeling) Islands', 1, NULL, NULL),
(40, 'CD', 'Congo, (Kinshasa)', 1, NULL, NULL),
(41, 'CF', 'Central African Republic', 1, NULL, NULL),
(42, 'CG', 'Congo (Brazzaville)', 1, NULL, NULL),
(43, 'CH', 'Switzerland', 1, NULL, NULL),
(44, 'CI', 'CÃ´te d\'Ivoire', 1, NULL, NULL),
(45, 'CK', 'Cook Islands', 1, NULL, NULL),
(46, 'CL', 'Chile', 1, NULL, NULL),
(47, 'CM', 'Cameroon', 1, NULL, NULL),
(48, 'CN', 'China', 1, NULL, NULL),
(49, 'CO', 'Colombia', 1, NULL, NULL),
(50, 'CR', 'Costa Rica', 1, NULL, NULL),
(51, 'CU', 'Cuba', 1, NULL, NULL),
(52, 'CV', 'Cape Verde', 1, NULL, NULL),
(53, 'CX', 'Christmas Island', 1, NULL, NULL),
(54, 'CY', 'Cyprus', 1, NULL, NULL),
(55, 'CZ', 'Czech Republic', 1, NULL, NULL),
(56, 'DE', 'Germany', 1, NULL, NULL),
(57, 'DJ', 'Djibouti', 1, NULL, NULL),
(58, 'DK', 'Denmark', 1, NULL, NULL),
(59, 'DM', 'Dominica', 1, NULL, NULL),
(60, 'DO', 'Dominican Republic', 1, NULL, NULL),
(61, 'DZ', 'Algeria', 1, NULL, NULL),
(62, 'EC', 'Ecuador', 1, NULL, NULL),
(63, 'EE', 'Estonia', 1, NULL, NULL),
(64, 'EG', 'Egypt', 1, NULL, NULL),
(65, 'EH', 'Western Sahara', 1, NULL, NULL),
(66, 'ER', 'Eritrea', 1, NULL, NULL),
(67, 'ES', 'Spain', 1, NULL, NULL),
(68, 'ET', 'Ethiopia', 1, NULL, NULL),
(69, 'FI', 'Finland', 1, NULL, NULL),
(70, 'FJ', 'Fiji', 1, NULL, NULL),
(71, 'FK', 'Falkland Islands (Malvinas)', 1, NULL, NULL),
(72, 'FM', 'Micronesia, Federated States of', 1, NULL, NULL),
(73, 'FO', 'Faroe Islands', 1, NULL, NULL),
(74, 'FR', 'France', 1, NULL, NULL),
(75, 'GA', 'Gabon', 1, NULL, NULL),
(76, 'GB', 'United Kingdom', 1, NULL, NULL),
(77, 'GD', 'Grenada', 1, NULL, NULL),
(78, 'GE', 'Georgia', 1, NULL, NULL),
(79, 'GF', 'French Guiana', 1, NULL, NULL),
(80, 'GG', 'Guernsey', 1, NULL, NULL),
(81, 'GH', 'Ghana', 1, NULL, NULL),
(82, 'GI', 'Gibraltar', 1, NULL, NULL),
(83, 'GL', 'Greenland', 1, NULL, NULL),
(84, 'GM', 'Gambia', 1, NULL, NULL),
(85, 'GN', 'Guinea', 1, NULL, NULL),
(86, 'GP', 'Guadeloupe', 1, NULL, NULL),
(87, 'GQ', 'Equatorial Guinea', 1, NULL, NULL),
(88, 'GR', 'Greece', 1, NULL, NULL),
(89, 'GS', 'South Georgia and the South Sandwich Islands', 1, NULL, NULL),
(90, 'GT', 'Guatemala', 1, NULL, NULL),
(91, 'GU', 'Guam', 1, NULL, NULL),
(92, 'GW', 'Guinea-Bissau', 1, NULL, NULL),
(93, 'GY', 'Guyana', 1, NULL, NULL),
(94, 'HK', 'Hong Kong, SAR China', 1, NULL, NULL),
(95, 'HM', 'Heard and Mcdonald Islands', 1, NULL, NULL),
(96, 'HN', 'Honduras', 1, NULL, NULL),
(97, 'HR', 'Croatia', 1, NULL, NULL),
(98, 'HT', 'Haiti', 1, NULL, NULL),
(99, 'HU', 'Hungary', 1, NULL, NULL),
(100, 'ID', 'Indonesia', 1, NULL, NULL),
(101, 'IE', 'Ireland', 1, NULL, NULL),
(102, 'IL', 'Israel', 1, NULL, NULL),
(103, 'IM', 'Isle of Man', 1, NULL, NULL),
(104, 'IN', 'India', 1, NULL, NULL),
(105, 'IO', 'British Indian Ocean Territory', 1, NULL, NULL),
(106, 'IQ', 'Iraq', 1, NULL, NULL),
(107, 'IR', 'Iran, Islamic Republic of', 1, NULL, NULL),
(108, 'IS', 'Iceland', 1, NULL, NULL),
(109, 'IT', 'Italy', 1, NULL, NULL),
(110, 'JE', 'Jersey', 1, NULL, NULL),
(111, 'JM', 'Jamaica', 1, NULL, NULL),
(112, 'JO', 'Jordan', 1, NULL, NULL),
(113, 'JP', 'Japan', 1, NULL, NULL),
(114, 'KE', 'Kenya', 1, NULL, NULL),
(115, 'KG', 'Kyrgyzstan', 1, NULL, NULL),
(116, 'KH', 'Cambodia', 1, NULL, NULL),
(117, 'KI', 'Kiribati', 1, NULL, NULL),
(118, 'KM', 'Comoros', 1, NULL, NULL),
(119, 'KN', 'Saint Kitts and Nevis', 1, NULL, NULL),
(120, 'KP', 'Korea (North)', 1, NULL, NULL),
(121, 'KR', 'Korea (South)', 1, NULL, NULL),
(122, 'KW', 'Kuwait', 1, NULL, NULL),
(123, 'KY', 'Cayman Islands', 1, NULL, NULL),
(124, 'KZ', 'Kazakhstan', 1, NULL, NULL),
(125, 'LA', 'Lao PDR', 1, NULL, NULL),
(126, 'LB', 'Lebanon', 1, NULL, NULL),
(127, 'LC', 'Saint Lucia', 1, NULL, NULL),
(128, 'LI', 'Liechtenstein', 1, NULL, NULL),
(129, 'LK', 'Sri Lanka', 1, NULL, NULL),
(130, 'LR', 'Liberia', 1, NULL, NULL),
(131, 'LS', 'Lesotho', 1, NULL, NULL),
(132, 'LT', 'Lithuania', 1, NULL, NULL),
(133, 'LU', 'Luxembourg', 1, NULL, NULL),
(134, 'LV', 'Latvia', 1, NULL, NULL),
(135, 'LY', 'Libya', 1, NULL, NULL),
(136, 'MA', 'Morocco', 1, NULL, NULL),
(137, 'MC', 'Monaco', 1, NULL, NULL),
(138, 'MD', 'Moldova', 1, NULL, NULL),
(139, 'ME', 'Montenegro', 1, NULL, NULL),
(140, 'MF', 'Saint-Martin (French part)', 1, NULL, NULL),
(141, 'MG', 'Madagascar', 1, NULL, NULL),
(142, 'MH', 'Marshall Islands', 1, NULL, NULL),
(143, 'MK', 'Macedonia, Republic of', 1, NULL, NULL),
(144, 'ML', 'Mali', 1, NULL, NULL),
(145, 'MM', 'Myanmar', 1, NULL, NULL),
(146, 'MN', 'Mongolia', 1, NULL, NULL),
(147, 'MO', 'Macao, SAR China', 1, NULL, NULL),
(148, 'MP', 'Northern Mariana Islands', 1, NULL, NULL),
(149, 'MQ', 'Martinique', 1, NULL, NULL),
(150, 'MR', 'Mauritania', 1, NULL, NULL),
(151, 'MS', 'Montserrat', 1, NULL, NULL),
(152, 'MT', 'Malta', 1, NULL, NULL),
(153, 'MU', 'Mauritius', 1, NULL, NULL),
(154, 'MV', 'Maldives', 1, NULL, NULL),
(155, 'MW', 'Malawi', 1, NULL, NULL),
(156, 'MX', 'Mexico', 1, NULL, NULL),
(157, 'MY', 'Malaysia', 1, NULL, NULL),
(158, 'MZ', 'Mozambique', 1, NULL, NULL),
(159, 'NA', 'Namibia', 1, NULL, NULL),
(160, 'NC', 'New Caledonia', 1, NULL, NULL),
(161, 'NE', 'Niger', 1, NULL, NULL),
(162, 'NF', 'Norfolk Island', 1, NULL, NULL),
(163, 'NG', 'Nigeria', 1, NULL, NULL),
(164, 'NI', 'Nicaragua', 1, NULL, NULL),
(165, 'NL', 'Netherlands', 1, NULL, NULL),
(166, 'NO', 'Norway', 1, NULL, NULL),
(167, 'NP', 'Nepal', 1, NULL, NULL),
(168, 'NR', 'Nauru', 1, NULL, NULL),
(169, 'NU', 'Niue', 1, NULL, NULL),
(170, 'NZ', 'New Zealand', 1, NULL, NULL),
(171, 'OM', 'Oman', 1, NULL, NULL),
(172, 'PA', 'Panama', 1, NULL, NULL),
(173, 'PE', 'Peru', 1, NULL, NULL),
(174, 'PF', 'French Polynesia', 1, NULL, NULL),
(175, 'PG', 'Papua New Guinea', 1, NULL, NULL),
(176, 'PH', 'Philippines', 1, NULL, NULL),
(177, 'PK', 'Pakistan', 1, NULL, NULL),
(178, 'PL', 'Poland', 1, NULL, NULL),
(179, 'PM', 'Saint Pierre and Miquelon', 1, NULL, NULL),
(180, 'PN', 'Pitcairn', 1, NULL, NULL),
(181, 'PR', 'Puerto Rico', 1, NULL, NULL),
(182, 'PS', 'Palestinian Territory', 1, NULL, NULL),
(183, 'PT', 'Portugal', 1, NULL, NULL),
(184, 'PW', 'Palau', 1, NULL, NULL),
(185, 'PY', 'Paraguay', 1, NULL, NULL),
(186, 'QA', 'Qatar', 1, NULL, NULL),
(187, 'RE', 'RÃ©union', 1, NULL, NULL),
(188, 'RO', 'Romania', 1, NULL, NULL),
(189, 'RS', 'Serbia', 1, NULL, NULL),
(190, 'RU', 'Russian Federation', 1, NULL, NULL),
(191, 'RW', 'Rwanda', 1, NULL, NULL),
(192, 'SA', 'Saudi Arabia', 1, NULL, NULL),
(193, 'SB', 'Solomon Islands', 1, NULL, NULL),
(194, 'SC', 'Seychelles', 1, NULL, NULL),
(195, 'SD', 'Sudan', 1, NULL, NULL),
(196, 'SE', 'Sweden', 1, NULL, NULL),
(197, 'SG', 'Singapore', 1, NULL, NULL),
(198, 'SH', 'Saint Helena', 1, NULL, NULL),
(199, 'SI', 'Slovenia', 1, NULL, NULL),
(200, 'SJ', 'Svalbard and Jan Mayen Islands', 1, NULL, NULL),
(201, 'SK', 'Slovakia', 1, NULL, NULL),
(202, 'SL', 'Sierra Leone', 1, NULL, NULL),
(203, 'SM', 'San Marino', 1, NULL, NULL),
(204, 'SN', 'Senegal', 1, NULL, NULL),
(205, 'SO', 'Somalia', 1, NULL, NULL),
(206, 'SR', 'Suriname', 1, NULL, NULL),
(207, 'SS', 'South Sudan', 1, NULL, NULL),
(208, 'ST', 'Sao Tome and Principe', 1, NULL, NULL),
(209, 'SV', 'El Salvador', 1, NULL, NULL),
(210, 'SY', 'Syrian Arab Republic (Syria)', 1, NULL, NULL),
(211, 'SZ', 'Swaziland', 1, NULL, NULL),
(212, 'TC', 'Turks and Caicos Islands', 1, NULL, NULL),
(213, 'TD', 'Chad', 1, NULL, NULL),
(214, 'TF', 'French Southern Territories', 1, NULL, NULL),
(215, 'TG', 'Togo', 1, NULL, NULL),
(216, 'TH', 'Thailand', 1, NULL, NULL),
(217, 'TJ', 'Tajikistan', 1, NULL, NULL),
(218, 'TK', 'Tokelau', 1, NULL, NULL),
(219, 'TL', 'Timor-Leste', 1, NULL, NULL),
(220, 'TM', 'Turkmenistan', 1, NULL, NULL),
(221, 'TN', 'Tunisia', 1, NULL, NULL),
(222, 'TO', 'Tonga', 1, NULL, NULL),
(223, 'TR', 'Turkey', 1, NULL, NULL),
(224, 'TT', 'Trinidad and Tobago', 1, NULL, NULL),
(225, 'TV', 'Tuvalu', 1, NULL, NULL),
(226, 'TW', 'Taiwan, Republic of China', 1, NULL, NULL),
(227, 'TZ', 'Tanzania, United Republic of', 1, NULL, NULL),
(228, 'UA', 'Ukraine', 1, NULL, NULL),
(229, 'UG', 'Uganda', 1, NULL, NULL),
(230, 'UM', 'US Minor Outlying Islands', 1, NULL, NULL),
(231, 'US', 'United States of America', 1, NULL, NULL),
(232, 'UY', 'Uruguay', 1, NULL, NULL),
(233, 'UZ', 'Uzbekistan', 1, NULL, NULL),
(234, 'VA', 'Holy See (Vatican City State)', 1, NULL, NULL),
(235, 'VC', 'Saint Vincent and Grenadines', 1, NULL, NULL),
(236, 'VE', 'Venezuela (Bolivarian Republic)', 1, NULL, NULL),
(237, 'VG', 'British Virgin Islands', 1, NULL, NULL),
(238, 'VI', 'Virgin Islands, US', 1, NULL, NULL),
(239, 'VN', 'Viet Nam', 1, NULL, NULL),
(240, 'VU', 'Vanuatu', 1, NULL, NULL),
(241, 'WF', 'Wallis and Futuna Islands', 1, NULL, NULL),
(242, 'WS', 'Samoa', 1, NULL, NULL),
(243, 'YE', 'Yemen', 1, NULL, NULL),
(244, 'YT', 'Mayotte', 1, NULL, NULL),
(245, 'ZA', 'South Africa', 1, NULL, NULL),
(246, 'ZM', 'Zambia', 1, NULL, NULL),
(247, 'ZW', 'Zimbabwe', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `core_master_county`
--

CREATE TABLE `core_master_county` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `country` varchar(3) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `core_master_county`
--

INSERT INTO `core_master_county` (`id`, `code`, `name`, `country`, `is_active`, `created_by`) VALUES
(1, '1', 'Mombasa', 'KE', 1, 1),
(2, '2', 'Kwale', 'KE', 1, 1),
(3, '3', 'Kilifi', 'KE', 1, 1),
(4, '4', 'Tana River', 'KE', 1, 1),
(5, '5', 'Lamu', 'KE', 1, 1),
(6, '6', 'Taita-Taveta', 'KE', 1, 1),
(7, '7', 'Garissa', 'KE', 1, 1),
(8, '8', 'Wajir', 'KE', 1, 1),
(9, '9', 'Mandera', 'KE', 1, 1),
(10, '10', 'Marsabit', 'KE', 1, 1),
(11, '11', 'Isiolo', 'KE', 1, 1),
(12, '12', 'Meru', 'KE', 1, 1),
(13, '13', 'Tharaka-Nithi', 'KE', 1, 1),
(14, '14', 'Embu', 'KE', 1, 1),
(15, '15', 'Kitui', 'KE', 1, 1),
(16, '16', 'Machakos', 'KE', 1, 1),
(17, '17', 'Makueni', 'KE', 1, 1),
(18, '18', 'Nyandarua', 'KE', 1, 1),
(19, '19', 'Nyeri', 'KE', 1, 1),
(20, '20', 'Kirinyaga', 'KE', 1, 1),
(21, '21', 'Murang\'a', 'KE', 1, 1),
(22, '22', 'Kiambu', 'KE', 1, 1),
(23, '23', 'Turkana', 'KE', 1, 1),
(24, '24', 'West Pokot', 'KE', 1, 1),
(25, '25', 'Samburu', 'KE', 1, 1),
(26, '26', 'Trans Nzoia', 'KE', 1, 1),
(27, '27', 'Uasin Gishu', 'KE', 1, 1),
(28, '28', 'Elgeyo-Marakwet', 'KE', 1, 1),
(29, '29', 'Nandi', 'KE', 1, 1),
(30, '30', 'Baringo', 'KE', 1, 1),
(31, '31', 'Laikipia', 'KE', 1, 1),
(32, '32', 'Nakuru', 'KE', 1, 1),
(33, '33', 'Narok', 'KE', 1, 1),
(34, '34', 'Kajiado', 'KE', 1, 1),
(35, '35', 'Kericho', 'KE', 1, 1),
(36, '36', 'Bomet', 'KE', 1, 1),
(37, '37', 'Kakamega', 'KE', 1, 1),
(38, '38', 'Vihiga', 'KE', 1, 1),
(39, '39', 'Bungoma', 'KE', 1, 1),
(40, '40', 'Busia', 'KE', 1, 1),
(41, '41', 'Siaya', 'KE', 1, 1),
(42, '42', 'Kisumu', 'KE', 1, 1),
(43, '43', 'Homa Bay', 'KE', 1, 1),
(44, '44', 'Migori', 'KE', 1, 1),
(45, '45', 'Kisii', 'KE', 1, 1),
(46, '46', 'Nyamira', 'KE', 1, 1),
(47, '47', 'Nairobi', 'KE', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `core_master_currency`
--

CREATE TABLE `core_master_currency` (
  `id` int(11) NOT NULL,
  `iso3` varchar(3) NOT NULL,
  `name` varchar(128) NOT NULL,
  `symbol` varchar(30) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `core_master_currency`
--

INSERT INTO `core_master_currency` (`id`, `iso3`, `name`, `symbol`, `is_active`, `created_by`) VALUES
(1, 'KES', 'Kenyan Shillings', NULL, 1, 1),
(2, 'UGX', 'Ugandan Shillings', NULL, 1, 1),
(3, 'TZS', 'Tanzanian Shillings', NULL, 1, 1),
(4, 'USD', 'United States Dollar', NULL, 1, 1),
(5, 'EUR', 'Euro', NULL, 1, 1),
(6, 'CAD', 'Canadian DOllar', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `core_master_currency_conversion`
--

CREATE TABLE `core_master_currency_conversion` (
  `id` int(11) NOT NULL,
  `default_currency` varchar(3) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `conversion_rate` decimal(13,4) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `core_master_currency_conversion`
--

INSERT INTO `core_master_currency_conversion` (`id`, `default_currency`, `currency`, `conversion_rate`, `is_active`, `created_by`, `updated_at`, `updated_by`) VALUES
(5, 'KES', 'EUR', '112.4400', 1, 1, '2019-05-24 11:54:31', 1),
(6, 'KES', 'TZS', '0.0440', 1, 1, '2019-05-24 11:54:31', 1),
(7, 'KES', 'UGX', '0.0270', 1, 1, '2019-05-24 11:54:31', 1),
(8, 'KES', 'USD', '101.3400', 1, 1, '2019-05-24 11:54:31', 1),
(9, 'KES', 'CAD', '100.0000', 1, 1, '2019-05-24 11:54:31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `core_master_list`
--

CREATE TABLE `core_master_list` (
  `id` int(11) NOT NULL,
  `value` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `list_type_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `core_master_list_type`
--

CREATE TABLE `core_master_list_type` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `core_master_list_type`
--

INSERT INTO `core_master_list_type` (`id`, `name`, `description`, `is_active`, `created_by`, `is_deleted`, `deleted_at`, `deleted_by`) VALUES
(1, 'House Hold (HH) Age', NULL, 1, 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `core_master_payment_mode`
--

CREATE TABLE `core_master_payment_mode` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `org_id` int(11) NOT NULL,
  `is_withdrawable` tinyint(1) NOT NULL DEFAULT '0',
  `link_to_cheque` tinyint(1) NOT NULL DEFAULT '0',
  `link_to_eft` tinyint(1) NOT NULL DEFAULT '0',
  `link_to_mobile_money` tinyint(1) NOT NULL DEFAULT '0',
  `link_to_abc` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `core_master_payment_mode`
--

INSERT INTO `core_master_payment_mode` (`id`, `name`, `org_id`, `is_withdrawable`, `link_to_cheque`, `link_to_eft`, `link_to_mobile_money`, `link_to_abc`, `is_active`, `created_by`, `updated_at`, `updated_by`, `is_deleted`, `deleted_at`, `deleted_by`) VALUES
(1, 'CASH', 12, 0, 0, 0, 0, 0, 1, 3, '2019-01-20 18:54:22', NULL, 0, NULL, NULL),
(2, 'CHEQUE', 12, 0, 1, 0, 0, 0, 1, 3, '2019-01-20 18:54:43', NULL, 0, NULL, NULL),
(3, 'BANK TRANFER', 12, 0, 0, 1, 0, 0, 1, 3, '2019-01-20 18:55:02', NULL, 0, NULL, NULL),
(4, 'M-PESA', 12, 0, 0, 0, 1, 0, 1, 3, '2019-01-20 18:56:30', NULL, 0, NULL, NULL),
(5, 'CASH', 11, 0, 0, 0, 0, 0, 1, 2, '2019-02-18 12:14:45', NULL, 0, NULL, NULL),
(6, 'CHEQUE', 11, 0, 1, 0, 0, 0, 1, 2, '2019-02-18 14:33:49', NULL, 0, NULL, NULL),
(7, 'BANK TRANSFER', 11, 0, 0, 1, 0, 0, 1, 2, '2019-02-18 14:35:12', 2, 0, NULL, NULL),
(8, 'M-PESA', 11, 0, 0, 0, 1, 0, 1, 2, '2019-02-18 14:34:29', NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `core_master_salutation`
--

CREATE TABLE `core_master_salutation` (
  `id` int(11) NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `core_master_salutation`
--

INSERT INTO `core_master_salutation` (`id`, `name`, `is_active`) VALUES
(1, 'Mr.', 1),
(2, 'Mrs.', 1),
(3, 'Miss.', 1),
(4, 'Prof.', 1),
(5, 'Hon.', 1),
(6, 'Dr.', 1),
(7, 'Eng.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `core_table_attributes`
--

CREATE TABLE `core_table_attributes` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `label` varchar(255) NOT NULL,
  `table_id` int(11) NOT NULL,
  `data_type` int(11) NOT NULL,
  `min_length` int(11) DEFAULT NULL,
  `max_length` int(11) DEFAULT NULL,
  `allow_null` tinyint(1) NOT NULL DEFAULT '1',
  `default_value` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_outbox`
--

CREATE TABLE `email_outbox` (
  `id` int(11) UNSIGNED NOT NULL,
  `message` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `sender_name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `sender_email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `recipient_email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `attachment` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `attachment_mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `cc` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `bcc` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `template_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `ref_id` int(11) UNSIGNED DEFAULT NULL,
  `date_queued` timestamp NULL DEFAULT NULL,
  `date_sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `attempts` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_template`
--

CREATE TABLE `email_template` (
  `id` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sender` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comments` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `email_template`
--

INSERT INTO `email_template` (`id`, `name`, `subject`, `body`, `sender`, `comments`, `created_at`, `created_by`) VALUES
('user_forgot_password', 'Email sent to a user who forgot his/her password to asssist in password recovery', 'Password Recovery', '<p><strong></strong><strong></strong><strong></strong>\r\n</p><p>\r\n	Hello {{name}},\r\n</p><p \"=\"\">\r\n	You told us you forgot your password. No need to Panic :)\r\n</p><p \"=\"\">Please follow this URL to reset your password:\r\n</p><p \"=\"\"><strong>{{url}}</strong>\r\n</p><p \"=\">This password reset link will expire in 1 hour.\r\n</p>\r\n<p \">If you didn\'t mean to initiate the password recovery process, don\'t worry! Your password is still safe and you can ignore this e-mail.</p><p \"=\">This password reset link will expire in 1 hour.\r\n</p>\r\n<p \">This link will expire after 3 hours.<br>\r\n</p>', 'noreply@btimillman.com', NULL, '2017-05-23 11:14:24', 1),
('user_login_details', 'User login details email', 'Login details', '<p \"=\"\">Hi {{name}},\r\n</p><p \"=\"\">\r\n	Your account in {{app_name}} is now ready. These are your login details:\r\n</p><ul><li>Username: <strong>{{username}}</strong><strong></strong></li><li>Password:<strong> {{password}}</strong></li><li><strong><strong></strong></strong>Login Link:<strong><strong> </strong>{{url}}</strong></li></ul><p \"=\"\">Please login and ensure you change your password. Do not share your password with anyone.</p>', 'noreply@btimillman.com', NULL, '2017-05-23 11:39:42', 1),
('user_new_login_details', 'Email sent when admin resets user password', 'New Login details', '<p>Hi {{name}}</p><p \"=\"\">Your login details in {{app_name}} have been reset by admin. Here are your new login details:</p><ul><li>Username: <strong>{{username}}</strong></li><li>Password: <strong>{{password}</strong></li><li><span class=\"redactor-invisible-space\">Login Link: <strong>{{url}}</strong></span></li></ul><p \"=\"\">Please login and choose a new password. Do not share your password with anyone.</p>', 'noreply@btimillman.com', NULL, '2017-05-23 11:45:05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization`
--

CREATE TABLE `organization` (
  `id` int(11) NOT NULL,
  `code` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(255) NOT NULL,
  `country` varchar(3) NOT NULL,
  `contact_person` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `contact_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `contact_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `uuid` varchar(255) NOT NULL,
  `unit1_name` varchar(30) NOT NULL,
  `unit2_name` varchar(30) NOT NULL,
  `unit3_name` varchar(30) NOT NULL,
  `unit4_name` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `organization`
--

INSERT INTO `organization` (`id`, `code`, `name`, `country`, `contact_person`, `contact_phone`, `contact_email`, `is_active`, `uuid`, `unit1_name`, `unit2_name`, `unit3_name`, `unit4_name`, `created_by`, `updated_at`, `updated_by`) VALUES
(10, '10010', 'Kenya', 'KE', NULL, NULL, NULL, 1, '59fe84b1-2636-4eb6-97cf-2d0546c718f8', 'Region', 'District', 'Ward', 'Village', 1, '2019-06-18 22:22:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `organization_units`
--

CREATE TABLE `organization_units` (
  `id` int(11) NOT NULL,
  `code` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `level` tinyint(1) NOT NULL,
  `org_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `contact_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `contact_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `contact_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `uuid` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `organization_units`
--

INSERT INTO `organization_units` (`id`, `code`, `name`, `level`, `org_id`, `parent_id`, `contact_name`, `contact_phone`, `contact_email`, `is_active`, `uuid`, `created_by`, `updated_at`, `updated_by`) VALUES
(9, NULL, 'Region One', 1, 10, NULL, NULL, NULL, 'mconyango@gmail.com', 1, '0c11250e-91c2-47e9-a62e-0c1d8b29a5f2', 1, '2019-06-18 23:59:50', 1),
(10, NULL, 'Region Two', 1, 10, NULL, NULL, NULL, 'mconyango@gmail.com', 1, '86b40fff-e456-4ba8-a759-9785ca64dd29', 1, '2019-06-19 00:00:05', 1),
(11, NULL, 'Region Three', 1, 10, NULL, NULL, NULL, 'mconyango@gmail.com', 1, 'a7aa26d4-37e6-4be4-8ec6-b2f509d69684', 1, '2019-06-19 00:00:11', 1),
(12, NULL, 'Region Four', 1, 10, NULL, NULL, NULL, 'mconyango@gmail.com', 1, 'b565a6bd-3104-4125-90e7-cb3c70284aa7', 1, '2019-06-19 00:00:17', 1),
(13, NULL, 'District One', 2, 10, 9, NULL, NULL, NULL, 1, 'd3dc515d-dff3-4bf0-9817-96ec53afa5ca', 1, '2019-06-18 23:49:27', NULL),
(14, NULL, 'District Two', 2, 10, 9, NULL, NULL, NULL, 1, '6fd50627-d636-4463-b93e-f05c9c149ddb', 1, '2019-06-18 23:49:50', NULL),
(15, NULL, 'District Three', 2, 10, 10, NULL, NULL, NULL, 1, 'ee7bbe5b-5e91-43b5-ac78-14f7e4d10242', 1, '2019-06-18 23:50:00', NULL),
(16, NULL, 'District Four', 2, 10, 10, NULL, NULL, NULL, 1, 'd37700bb-4122-4019-9ba6-7dcdbfaf988d', 1, '2019-06-18 23:50:22', NULL),
(17, NULL, 'Ward One', 3, 10, 13, NULL, NULL, 'mconyango@gmail.com', 1, '1e17d784-1d2e-40b8-9c9b-20a2f6a98169', 1, '2019-06-19 00:01:23', NULL),
(18, NULL, 'Ward Two', 3, 10, 16, NULL, NULL, 'mconyango@gmail.com', 1, '5752e500-fa47-415d-94f3-7923c303c81e', 1, '2019-06-19 00:02:03', 1),
(19, NULL, 'Ward Three', 3, 10, 15, NULL, NULL, 'mconyango@gmail.com', 1, 'dd4565ac-2122-4cfb-bca6-9530a8295da0', 1, '2019-06-19 00:05:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `type` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `section` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '1',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `type`, `section`, `key`, `value`, `status`, `description`, `created_at`, `updated_at`) VALUES
(1, 'string', 'system', 'companyName', 'African Daily Genetic Gains - ADGG', 1, NULL, 1558082145, 1559281626),
(2, 'string', 'system', 'appName', 'African Daily Genetic Gains - ADGG', 1, NULL, 1558082145, 1559281614),
(3, 'string', 'system', 'defaultTimezone', 'Africa/Nairobi', 1, NULL, 1558082145, 1558944935),
(4, 'string', 'system', 'defaultCountry', 'KE', 1, NULL, 1558082145, 1558694395),
(5, 'string', 'system', 'defaultCurrency', 'KES', 1, NULL, 1558082145, 1558082145),
(6, 'string', 'system', 'paginationSize', '50', 1, NULL, 1558082145, 1558255815),
(7, 'string', 'system', 'companyEmail', 'fred@competamillman.co.ke', 1, NULL, 1558190776, 1558190776),
(8, 'string', 'system', 'defaultTheme', 'default', 1, NULL, 1558216647, 1558945025),
(9, 'string', 'password', 'usePreset', '1', 1, NULL, 1558469901, 1558469901),
(10, 'string', 'password', 'preset', 'normal', 1, NULL, 1558469901, 1558529838),
(11, 'string', 'googleMap', 'apiKey', 'AIzaSyAwQJXjzQ82D6nQsjwHHYZ1T6tDlRJe220', 1, NULL, 1558655907, 1558655907),
(12, 'string', 'googleMap', 'defaultMapCenter', '-1.2920659, 36.82194619999996', 1, NULL, 1558655907, 1558655907),
(13, 'string', 'admin_units', 'countryUnit1', 'Region', 1, NULL, 1560891384, 1560891405),
(14, 'string', 'admin_units', 'countryUnit2', 'District', 1, NULL, 1560891385, 1560891410),
(15, 'string', 'admin_units', 'countryUnit3', 'Ward', 1, NULL, 1560891385, 1560891415),
(16, 'string', 'admin_units', 'countryUnit4', 'Village', 1, NULL, 1560891385, 1560891385);

-- --------------------------------------------------------

--
-- Table structure for table `sms_outbox`
--

CREATE TABLE `sms_outbox` (
  `id` int(11) NOT NULL,
  `msisdn` varchar(15) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `sender_id` varchar(20) NOT NULL,
  `send_status` tinyint(1) NOT NULL,
  `response_code` varchar(20) DEFAULT NULL,
  `response_remarks` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `attempts` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_template`
--

CREATE TABLE `sms_template` (
  `id` int(11) NOT NULL,
  `code` varchar(128) NOT NULL,
  `name` varchar(255) NOT NULL,
  `template` varchar(1000) NOT NULL,
  `available_placeholders` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sms_template`
--

INSERT INTO `sms_template` (`id`, `code`, `name`, `template`, `available_placeholders`, `created_by`) VALUES
(1, 'password_reset_code', 'Password Reset Code', 'Your Password reset code is : [code]', '[code]', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sys_app_session`
--

CREATE TABLE `sys_app_session` (
  `id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `expire` int(11) NOT NULL,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sys_app_session`
--

INSERT INTO `sys_app_session` (`id`, `expire`, `data`) VALUES
('0s6b3c53m9rqv7a09m87jfiu0c', 1560871392, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a363a222f616467672f223b),
('1heapjoarn4vbn4h6nf3ct2gq3', 1559260906, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a363a222f616467672f223b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b5f5f69647c693a313b5f5f6578706972657c693a313535393235303130363b),
('31tj44hknvpf7rd211qo66o6k4', 1560917322, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a39333a222f616467672f636f72652f6f7267616e697a6174696f6e2d756e6974732f696e6465783f6c6576656c3d31266f72675f69643d35396665383462312d323633362d346562362d393763662d326430353436633731386638267461623d32223b5f5f69647c693a313b5f5f6578706972657c693a313536303930363532323b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('3ctiq9rcvu55nr03kchj3e25o7', 1558311019, 0x5f5f666c6173687c613a303a7b7d5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b5f5f69647c693a313b5f5f6578706972657c693a313535383330303231333b),
('4lcm3lrjgph6sd8f8ebinnp9vs', 1558239805, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b5f5f69647c693a313b5f5f6578706972657c693a313535383232383939393b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('4nc0a6nhi36at0jqqjcav2l0q5', 1559306135, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a363a222f616467672f223b5f5f69647c693a313b5f5f6578706972657c693a313535393239353333353b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('4ok0tjcqlpgramrnfqlkjutr2f', 1558719686, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a36323a222f6d6564736f757263652f636f72652f6f7267616e697a6174696f6e2f696e6465783f69735f6d656d6265723d3126627573696e6573735f747970653d35223b5f5f69647c693a313b5f5f6578706972657c693a313535383730383838363b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('59l561abmmq8lcbs9a9732f79t', 1560914721, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a363a222f616467672f223b5f5f69647c693a313b5f5f6578706972657c693a313536303930333932313b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('6vq8ilolri1b4n5d397752ttk5', 1558697975, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b5f5f69647c693a313b5f5f6578706972657c693a313535383638373137353b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('7r9dao6mlup5dup6qmssvcaud5', 1558354703, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b),
('88kupchk2if71a7t8kg2s190f7', 1559061588, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b),
('8s2vffrh7il0h0icorospp9g3g', 1558475301, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a32383a222f6d6564736f757263652f636f6e662f6e6f7469662f637265617465223b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('8ubqsfio2ugp4cp767nb3vdpe1', 1558489759, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a36333a222f6d6564736f757263652f636f72652f63757272656e63792d636f6e76657273696f6e2f7570646174653f64656661756c745f63757272656e63793d434144223b),
('9ap0rm5oj4offt9uoc8e9e32gt', 1558531434, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b),
('a9gp1hlvs9v1vgf2u1332vn8pb', 1558218313, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a34353a222f6d6564736f757263652f636f6e662f656d61696c2f696e6465783f5f746f6765656434313931663d70616765223b),
('aate43e7tun0mopuehkgnralss', 1559163065, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('eb8v70m15luhkbodkb8qd0hpeo', 1558096546, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b5f5f69647c693a313b5f5f6578706972657c693a313535383038353734363b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('er847i82spnqmh8405k8fd64ig', 1558362835, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b5f5f69647c693a313b5f5f6578706972657c693a313535383335323032393b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('eujoli7g6hscjkg62qpvcvqhuf', 1558214006, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b5f5f69647c693a313b5f5f6578706972657c693a313535383230333230303b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('fonshj0i8kn130dp2fcp220bcf', 1558929403, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b5f5f636170746368612f617574682f617574682f636170746368617c733a343a22767a7561223b5f5f636170746368612f617574682f617574682f63617074636861636f756e747c693a313b),
('fpsj8f0g469kunskicj0da3o1u', 1558661297, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a34383a222f6d6564736f757263652f636f72652f726567697374726174696f6e2d646f63756d656e742d747970652f696e646578223b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b5f5f69647c693a313b5f5f6578706972657c693a313535383635303439373b),
('g9lpaovmcr5qn8h5k6ksarol2g', 1558090660, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b5f5f69647c693a313b5f5f6578706972657c693a313535383037393836303b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('ijbpfgfm1aq32sgdj8554mqhmo', 1558489591, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a32383a222f6d6564736f757263652f636f6e662f6e6f7469662f637265617465223b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b5f5f69647c693a313b5f5f6578706972657c693a313535383437383738353b),
('ilvl9eajqk54k1uad5j751bmnv', 1558805397, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a32393a222f6d6564736f757263652f636f72652f636f756e7472792f696e646578223b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b5f5f69647c693a313b5f5f6578706972657c693a313535383739343539373b),
('j9qie0p7i6ujjt8as1smruc9f3', 1560788832, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a363a222f616467672f223b5f5f69647c693a313b5f5f6578706972657c693a313536303737383033323b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('ki6hgqndvjfvauatr26p9f88bn', 1558436536, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b5f5f69647c693a313b5f5f6578706972657c693a313535383432353733303b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('l3r77h8vtkjppl3gv58dm7em9t', 1558231681, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a34353a222f6d6564736f757263652f636f6e662f656d61696c2f696e6465783f5f746f6765656434313931663d70616765223b5f5f69647c693a313b5f5f6578706972657c693a313535383232303837353b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('l4ugpc7t3n331h4osve02t0qjq', 1558898125, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b),
('mtcghmbaluoc1jh4fesjl98i1r', 1559298972, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a363a222f616467672f223b5f5f69647c693a313b5f5f6578706972657c693a313535393238383137323b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('nebqr3mq63oh2shdcps6r2pvlu', 1558239111, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b),
('nt77hk7l6hhr7pdrgvehi1psev', 1558502915, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a36333a222f6d6564736f757263652f636f72652f63757272656e63792d636f6e76657273696f6e2f7570646174653f64656661756c745f63757272656e63793d434144223b5f5f69647c693a313b5f5f6578706972657c693a313535383439323130393b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('o601dqrl16dtns53vbms00k4ml', 1558462976, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b),
('oo3u8fcfoue5du4eopch091ro7', 1558563655, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a32363a222f6d6564736f757263652f617574682f726f6c652f696e646578223b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b5f5f69647c693a313b5f5f6578706972657c693a313535383535323835353b),
('ousvafe7ou8cs46s5pk7jmbnre', 1558283338, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b5f5f69647c693a313b5f5f6578706972657c693a313535383237323533323b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('p8see7h48esq3g8mld614d9ree', 1558296955, 0x5f5f666c6173687c613a303a7b7d5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b5f5f72657475726e55726c7c733a33303a222f6d6564736f757263652f636f6e662f73657474696e67732f696e646578223b),
('pkbv4g2gdrp7e1d7ddk2ilc7t0', 1558572906, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b5f5f69647c693a313b5f5f6578706972657c693a313535383536323130353b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('qpl0ku7vihr6hrk42p553hljq2', 1558670116, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a36323a222f6d6564736f757263652f636f72652f6f7267616e697a6174696f6e2f696e6465783f69735f6d656d6265723d3126627573696e6573735f747970653d33223b5f5f69647c693a313b5f5f6578706972657c693a313535383635393331363b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('r1no4gplo449abemgr3qiqljfa', 1558961850, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b),
('rechilf5dhh8h2egvlocv89rvc', 1558239107, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a33303a222f6d6564736f757263652f636f6e662f73657474696e67732f696e646578223b5f5f69647c693a313b5f5f6578706972657c693a313535383232383330313b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b),
('t3nbic080oq7nq8lka3ulsnpac', 1558231991, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a33303a222f6d6564736f757263652f636f6e662f73657474696e67732f696e646578223b),
('uiguhnli168mcqf6r3ij7l6qc7', 1559867698, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a363a222f616467672f223b),
('umasfrlonsmtifj57assrv3neo', 1558941022, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a31313a222f6d6564736f757263652f223b),
('vk9rba9j3j4p15f9vvm7kemoc3', 1560954287, 0x5f5f666c6173687c613a303a7b7d5f5f72657475726e55726c7c733a363a222f616467672f223b5f5f69647c693a313b5f5f6578706972657c693a313536303934333438373b5f757365725f74696d655a6f6e657c733a31343a224166726963612f4e6169726f6269223b);

-- --------------------------------------------------------

--
-- Table structure for table `sys_cache_form_selection`
--

CREATE TABLE `sys_cache_form_selection` (
  `id` int(10) UNSIGNED NOT NULL,
  `route` varchar(255) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `form_class` varchar(255) NOT NULL,
  `value` text,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sys_form_draft`
--

CREATE TABLE `sys_form_draft` (
  `id` int(11) NOT NULL,
  `model_attributes` json NOT NULL,
  `model_class` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sys_queue`
--

CREATE TABLE `sys_queue` (
  `id` int(11) NOT NULL,
  `channel` varchar(255) NOT NULL,
  `job` blob NOT NULL,
  `pushed_at` int(11) NOT NULL,
  `ttr` int(11) NOT NULL,
  `delay` int(11) NOT NULL DEFAULT '0',
  `priority` int(11) UNSIGNED NOT NULL DEFAULT '1024',
  `reserved_at` int(11) DEFAULT NULL,
  `attempt` int(11) DEFAULT NULL,
  `done_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sys_queue`
--

INSERT INTO `sys_queue` (`id`, `channel`, `job`, `pushed_at`, `ttr`, `delay`, `priority`, `reserved_at`, `attempt`, `done_at`) VALUES
(31, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3637383a223c703e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e0d0a3c2f703e3c703e0d0a0948656c6c6f20467265647269636b204f63686f6c612c0d0a3c2f703e3c7020223d22223e0d0a09596f7520746f6c6420757320796f7520666f72676f7420796f75722070617373776f72642e204e6f206e65656420746f2050616e6963203a290d0a3c2f703e3c7020223d22223e506c6561736520666f6c6c6f7720746869732055524c20746f20726573657420796f75722070617373776f72643a0d0a3c2f703e3c7020223d22223e3c7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f72657365742d70617373776f72643f746f6b656e3d4b72797131715544565a312d38596f352d375676585a5a4b47413535324a69625f313535383034393434303c2f7374726f6e673e0d0a3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e496620796f75206469646e2774206d65616e20746f20696e697469617465207468652070617373776f7264207265636f766572792070726f636573732c20646f6e277420776f7272792120596f75722070617373776f7264206973207374696c6c207361666520616e6420796f752063616e2069676e6f7265207468697320652d6d61696c2e3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e54686973206c696e6b2077696c6c20657870697265206166746572203320686f7572732e3c62723e0d0a3c2f703e223b733a373a227375626a656374223b733a31373a2250617373776f7264205265636f76657279223b733a31313a2273656e6465725f6e616d65223b733a31333a224d4544534f5552434520455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a31393a226d636f6e79616e676f40676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a32303a22757365725f666f72676f745f70617373776f7264223b733a363a227265665f6964223b693a313b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d31362032333a33303a3430223b733a31303a22637265617465645f6279223b4e3b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558049440, 300, 0, 1024, NULL, NULL, NULL),
(32, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3637383a223c703e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e0d0a3c2f703e3c703e0d0a0948656c6c6f20467265647269636b204f63686f6c612c0d0a3c2f703e3c7020223d22223e0d0a09596f7520746f6c6420757320796f7520666f72676f7420796f75722070617373776f72642e204e6f206e65656420746f2050616e6963203a290d0a3c2f703e3c7020223d22223e506c6561736520666f6c6c6f7720746869732055524c20746f20726573657420796f75722070617373776f72643a0d0a3c2f703e3c7020223d22223e3c7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f72657365742d70617373776f72643f746f6b656e3d7a655f47457a74786533706177676d436d722d466942595a47487645704d6f465f313535383034393438323c2f7374726f6e673e0d0a3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e496620796f75206469646e2774206d65616e20746f20696e697469617465207468652070617373776f7264207265636f766572792070726f636573732c20646f6e277420776f7272792120596f75722070617373776f7264206973207374696c6c207361666520616e6420796f752063616e2069676e6f7265207468697320652d6d61696c2e3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e54686973206c696e6b2077696c6c20657870697265206166746572203320686f7572732e3c62723e0d0a3c2f703e223b733a373a227375626a656374223b733a31373a2250617373776f7264205265636f76657279223b733a31313a2273656e6465725f6e616d65223b733a31333a224d4544534f5552434520455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a31393a226d636f6e79616e676f40676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a32303a22757365725f666f72676f745f70617373776f7264223b733a363a227265665f6964223b693a313b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d31362032333a33313a3232223b733a31303a22637265617465645f6279223b4e3b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558049482, 300, 0, 1024, NULL, NULL, NULL),
(33, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3637383a223c703e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e0d0a3c2f703e3c703e0d0a0948656c6c6f20467265647269636b204f63686f6c612c0d0a3c2f703e3c7020223d22223e0d0a09596f7520746f6c6420757320796f7520666f72676f7420796f75722070617373776f72642e204e6f206e65656420746f2050616e6963203a290d0a3c2f703e3c7020223d22223e506c6561736520666f6c6c6f7720746869732055524c20746f20726573657420796f75722070617373776f72643a0d0a3c2f703e3c7020223d22223e3c7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f72657365742d70617373776f72643f746f6b656e3d586570496d5843465155415a305931546c303230565154354b614a5a444373435f313535383034393633383c2f7374726f6e673e0d0a3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e496620796f75206469646e2774206d65616e20746f20696e697469617465207468652070617373776f7264207265636f766572792070726f636573732c20646f6e277420776f7272792120596f75722070617373776f7264206973207374696c6c207361666520616e6420796f752063616e2069676e6f7265207468697320652d6d61696c2e3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e54686973206c696e6b2077696c6c20657870697265206166746572203320686f7572732e3c62723e0d0a3c2f703e223b733a373a227375626a656374223b733a31373a2250617373776f7264205265636f76657279223b733a31313a2273656e6465725f6e616d65223b733a31333a224d4544534f5552434520455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a31393a226d636f6e79616e676f40676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a32303a22757365725f666f72676f745f70617373776f7264223b733a363a227265665f6964223b693a313b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d31362032333a33333a3538223b733a31303a22637265617465645f6279223b4e3b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558049638, 300, 0, 1024, NULL, NULL, NULL),
(34, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3637383a223c703e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e0d0a3c2f703e3c703e0d0a0948656c6c6f20467265647269636b204f63686f6c612c0d0a3c2f703e3c7020223d22223e0d0a09596f7520746f6c6420757320796f7520666f72676f7420796f75722070617373776f72642e204e6f206e65656420746f2050616e6963203a290d0a3c2f703e3c7020223d22223e506c6561736520666f6c6c6f7720746869732055524c20746f20726573657420796f75722070617373776f72643a0d0a3c2f703e3c7020223d22223e3c7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f72657365742d70617373776f72643f746f6b656e3d4256734a37776a72535a6f707a777641455a746d6c41716a3534474134594c445f313535383034393639303c2f7374726f6e673e0d0a3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e496620796f75206469646e2774206d65616e20746f20696e697469617465207468652070617373776f7264207265636f766572792070726f636573732c20646f6e277420776f7272792120596f75722070617373776f7264206973207374696c6c207361666520616e6420796f752063616e2069676e6f7265207468697320652d6d61696c2e3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e54686973206c696e6b2077696c6c20657870697265206166746572203320686f7572732e3c62723e0d0a3c2f703e223b733a373a227375626a656374223b733a31373a2250617373776f7264205265636f76657279223b733a31313a2273656e6465725f6e616d65223b733a31333a224d4544534f5552434520455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a31393a226d636f6e79616e676f40676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a32303a22757365725f666f72676f745f70617373776f7264223b733a363a227265665f6964223b693a313b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d31362032333a33343a3530223b733a31303a22637265617465645f6279223b4e3b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558049690, 300, 0, 1024, NULL, NULL, NULL),
(35, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3637383a223c703e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e0d0a3c2f703e3c703e0d0a0948656c6c6f20467265647269636b204f63686f6c612c0d0a3c2f703e3c7020223d22223e0d0a09596f7520746f6c6420757320796f7520666f72676f7420796f75722070617373776f72642e204e6f206e65656420746f2050616e6963203a290d0a3c2f703e3c7020223d22223e506c6561736520666f6c6c6f7720746869732055524c20746f20726573657420796f75722070617373776f72643a0d0a3c2f703e3c7020223d22223e3c7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f72657365742d70617373776f72643f746f6b656e3d65494f69464c777345316d536b5569437a4a684b3762384939744a4d6652794f5f313535383038313034303c2f7374726f6e673e0d0a3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e496620796f75206469646e2774206d65616e20746f20696e697469617465207468652070617373776f7264207265636f766572792070726f636573732c20646f6e277420776f7272792120596f75722070617373776f7264206973207374696c6c207361666520616e6420796f752063616e2069676e6f7265207468697320652d6d61696c2e3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e54686973206c696e6b2077696c6c20657870697265206166746572203320686f7572732e3c62723e0d0a3c2f703e223b733a373a227375626a656374223b733a31373a2250617373776f7264205265636f76657279223b733a31313a2273656e6465725f6e616d65223b733a31333a224d4544534f5552434520455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a31393a226d636f6e79616e676f40676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a32303a22757365725f666f72676f745f70617373776f7264223b733a363a227265665f6964223b693a313b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d31372030383a31373a3230223b733a31303a22637265617465645f6279223b4e3b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558081040, 300, 0, 1024, NULL, NULL, NULL),
(36, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3435313a223c703e4869205374657068656e204d61696e613c2f703e3c7020223d22223e596f7572206c6f67696e2064657461696c7320696e204d4544534f555243452047524f5550204552502068617665206265656e2072657365742062792061646d696e2e20486572652061726520796f7572206e6577206c6f67696e2064657461696c733a3c2f703e3c756c3e3c6c693e557365726e616d653a203c7374726f6e673e7374657068656e3c2f7374726f6e673e3c2f6c693e3c6c693e50617373776f72643a203c7374726f6e673e7b7b70617373776f72647d3c2f7374726f6e673e3c2f6c693e3c6c693e3c7370616e20636c6173733d227265646163746f722d696e76697369626c652d7370616365223e4c6f67696e204c696e6b3a203c7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f6c6f67696e3c2f7374726f6e673e3c2f7370616e3e3c2f6c693e3c2f756c3e3c7020223d22223e506c65617365206c6f67696e20616e642063686f6f73652061206e65772070617373776f72642e20446f206e6f7420736861726520796f75722070617373776f7264207769746820616e796f6e652e3c2f703e223b733a373a227375626a656374223b733a31373a224e6577204c6f67696e2064657461696c73223b733a31313a2273656e6465725f6e616d65223b733a31393a224d4544534f555243452047524f555020455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a32383a227374657068656e40636f6d706574616d696c6c6d616e2e636f2e6b65223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a32323a22757365725f6e65775f6c6f67696e5f64657461696c73223b733a363a227265665f6964223b693a323b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d32322031323a35373a3434223b733a31303a22637265617465645f6279223b693a313b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558529864, 300, 0, 1024, NULL, NULL, NULL),
(37, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3437303a223c7020223d22223e48692045726963204d756e656e652c0d0a3c2f703e3c7020223d22223e0d0a09596f7572206163636f756e7420696e204d4544534f555243452047524f555020455250206973206e6f772072656164792e2054686573652061726520796f7572206c6f67696e2064657461696c733a0d0a3c2f703e3c756c3e3c6c693e557365726e616d653a203c7374726f6e673e657269633c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f6c693e3c6c693e50617373776f72643a3c7374726f6e673e2041646d696e3132333435363c2f7374726f6e673e3c2f6c693e3c6c693e3c7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f7374726f6e673e4c6f67696e204c696e6b3a3c7374726f6e673e3c7374726f6e673e203c2f7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f6c6f67696e3c2f7374726f6e673e3c2f6c693e3c2f756c3e3c7020223d22223e506c65617365206c6f67696e20616e6420656e7375726520796f75206368616e676520796f75722070617373776f72642e20446f206e6f7420736861726520796f75722070617373776f7264207769746820616e796f6e652e3c2f703e223b733a373a227375626a656374223b733a31333a224c6f67696e2064657461696c73223b733a31313a2273656e6465725f6e616d65223b733a31393a224d4544534f555243452047524f555020455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a32333a2265726963406d6564736f7572636567726f75702e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a31383a22757365725f6c6f67696e5f64657461696c73223b733a363a227265665f6964223b693a333b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d32332031353a32313a3139223b733a31303a22637265617465645f6279223b693a313b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558624879, 300, 0, 1024, NULL, NULL, NULL),
(38, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3437303a223c7020223d22223e48692045726963204d756e656e652c0d0a3c2f703e3c7020223d22223e0d0a09596f7572206163636f756e7420696e204d4544534f555243452047524f555020455250206973206e6f772072656164792e2054686573652061726520796f7572206c6f67696e2064657461696c733a0d0a3c2f703e3c756c3e3c6c693e557365726e616d653a203c7374726f6e673e657269633c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f6c693e3c6c693e50617373776f72643a3c7374726f6e673e2041646d696e3132333435363c2f7374726f6e673e3c2f6c693e3c6c693e3c7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f7374726f6e673e4c6f67696e204c696e6b3a3c7374726f6e673e3c7374726f6e673e203c2f7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f6c6f67696e3c2f7374726f6e673e3c2f6c693e3c2f756c3e3c7020223d22223e506c65617365206c6f67696e20616e6420656e7375726520796f75206368616e676520796f75722070617373776f72642e20446f206e6f7420736861726520796f75722070617373776f7264207769746820616e796f6e652e3c2f703e223b733a373a227375626a656374223b733a31333a224c6f67696e2064657461696c73223b733a31313a2273656e6465725f6e616d65223b733a31393a224d4544534f555243452047524f555020455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a32333a2265726963406d6564736f7572636567726f75702e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a31383a22757365725f6c6f67696e5f64657461696c73223b733a363a227265665f6964223b693a333b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d32332031353a32313a3139223b733a31303a22637265617465645f6279223b693a313b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558624879, 300, 0, 1024, NULL, NULL, NULL),
(39, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3437373a223c7020223d22223e4869204472204d6f736573204172616e2c0d0a3c2f703e3c7020223d22223e0d0a09596f7572206163636f756e7420696e204d4544534f555243452047524f555020455250206973206e6f772072656164792e2054686573652061726520796f7572206c6f67696e2064657461696c733a0d0a3c2f703e3c756c3e3c6c693e557365726e616d653a203c7374726f6e673e6d6f7365736172616e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f6c693e3c6c693e50617373776f72643a3c7374726f6e673e2041646d696e3132333435363c2f7374726f6e673e3c2f6c693e3c6c693e3c7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f7374726f6e673e4c6f67696e204c696e6b3a3c7374726f6e673e3c7374726f6e673e203c2f7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f6c6f67696e3c2f7374726f6e673e3c2f6c693e3c2f756c3e3c7020223d22223e506c65617365206c6f67696e20616e6420656e7375726520796f75206368616e676520796f75722070617373776f72642e20446f206e6f7420736861726520796f75722070617373776f7264207769746820616e796f6e652e3c2f703e223b733a373a227375626a656374223b733a31333a224c6f67696e2064657461696c73223b733a31313a2273656e6465725f6e616d65223b733a31393a224d4544534f555243452047524f555020455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a31393a226d6f7365736172616e40676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a31383a22757365725f6c6f67696e5f64657461696c73223b733a363a227265665f6964223b693a343b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d32342030313a31353a3234223b733a31303a22637265617465645f6279223b693a313b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558660524, 300, 0, 1024, NULL, NULL, NULL),
(40, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3437383a223c7020223d22223e4869204265617472696365204f6e79616e676f2c0d0a3c2f703e3c7020223d22223e0d0a09596f7572206163636f756e7420696e204d4544534f555243452047524f555020455250206973206e6f772072656164792e2054686573652061726520796f7572206c6f67696e2064657461696c733a0d0a3c2f703e3c756c3e3c6c693e557365726e616d653a203c7374726f6e673e62656174726963653c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f6c693e3c6c693e50617373776f72643a3c7374726f6e673e2041646d696e31323334353c2f7374726f6e673e3c2f6c693e3c6c693e3c7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f7374726f6e673e4c6f67696e204c696e6b3a3c7374726f6e673e3c7374726f6e673e203c2f7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f6c6f67696e3c2f7374726f6e673e3c2f6c693e3c2f756c3e3c7020223d22223e506c65617365206c6f67696e20616e6420656e7375726520796f75206368616e676520796f75722070617373776f72642e20446f206e6f7420736861726520796f75722070617373776f7264207769746820616e796f6e652e3c2f703e223b733a373a227375626a656374223b733a31333a224c6f67696e2064657461696c73223b733a31313a2273656e6465725f6e616d65223b733a31393a224d4544534f555243452047524f555020455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a32363a2262656174726963652e6f6e79616e676f40676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a31383a22757365725f6c6f67696e5f64657461696c73223b733a363a227265665f6964223b693a353b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d32342031323a31343a3134223b733a31303a22637265617465645f6279223b693a313b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558700054, 300, 0, 1024, NULL, NULL, NULL),
(41, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3437383a223c7020223d22223e4869204265617472696365204f6e79616e676f2c0d0a3c2f703e3c7020223d22223e0d0a09596f7572206163636f756e7420696e204d4544534f555243452047524f555020455250206973206e6f772072656164792e2054686573652061726520796f7572206c6f67696e2064657461696c733a0d0a3c2f703e3c756c3e3c6c693e557365726e616d653a203c7374726f6e673e62656174726963653c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f6c693e3c6c693e50617373776f72643a3c7374726f6e673e2041646d696e31323334353c2f7374726f6e673e3c2f6c693e3c6c693e3c7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f7374726f6e673e4c6f67696e204c696e6b3a3c7374726f6e673e3c7374726f6e673e203c2f7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f6c6f67696e3c2f7374726f6e673e3c2f6c693e3c2f756c3e3c7020223d22223e506c65617365206c6f67696e20616e6420656e7375726520796f75206368616e676520796f75722070617373776f72642e20446f206e6f7420736861726520796f75722070617373776f7264207769746820616e796f6e652e3c2f703e223b733a373a227375626a656374223b733a31333a224c6f67696e2064657461696c73223b733a31313a2273656e6465725f6e616d65223b733a31393a224d4544534f555243452047524f555020455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a32363a2262656174726963652e6f6e79616e676f40676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a31383a22757365725f6c6f67696e5f64657461696c73223b733a363a227265665f6964223b693a353b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d32342031323a31343a3134223b733a31303a22637265617465645f6279223b693a313b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558700054, 300, 0, 1024, NULL, NULL, NULL),
(42, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3637383a223c703e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e0d0a3c2f703e3c703e0d0a0948656c6c6f20467265647269636b204f63686f6c612c0d0a3c2f703e3c7020223d22223e0d0a09596f7520746f6c6420757320796f7520666f72676f7420796f75722070617373776f72642e204e6f206e65656420746f2050616e6963203a290d0a3c2f703e3c7020223d22223e506c6561736520666f6c6c6f7720746869732055524c20746f20726573657420796f75722070617373776f72643a0d0a3c2f703e3c7020223d22223e3c7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f72657365742d70617373776f72643f746f6b656e3d756a715953716f727766456c53564e7073467a4456745a705772634e754f6a335f313535383730323331343c2f7374726f6e673e0d0a3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e496620796f75206469646e2774206d65616e20746f20696e697469617465207468652070617373776f7264207265636f766572792070726f636573732c20646f6e277420776f7272792120596f75722070617373776f7264206973207374696c6c207361666520616e6420796f752063616e2069676e6f7265207468697320652d6d61696c2e3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e54686973206c696e6b2077696c6c20657870697265206166746572203320686f7572732e3c62723e0d0a3c2f703e223b733a373a227375626a656374223b733a31373a2250617373776f7264205265636f76657279223b733a31313a2273656e6465725f6e616d65223b733a31393a224d4544534f555243452047524f555020455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a31393a226d636f6e79616e676f40676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a32303a22757365725f666f72676f745f70617373776f7264223b733a363a227265665f6964223b693a313b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d32342031323a35313a3534223b733a31303a22637265617465645f6279223b4e3b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558702314, 300, 0, 1024, NULL, NULL, NULL),
(43, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3637383a223c703e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e0d0a3c2f703e3c703e0d0a0948656c6c6f20467265647269636b204f63686f6c612c0d0a3c2f703e3c7020223d22223e0d0a09596f7520746f6c6420757320796f7520666f72676f7420796f75722070617373776f72642e204e6f206e65656420746f2050616e6963203a290d0a3c2f703e3c7020223d22223e506c6561736520666f6c6c6f7720746869732055524c20746f20726573657420796f75722070617373776f72643a0d0a3c2f703e3c7020223d22223e3c7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f72657365742d70617373776f72643f746f6b656e3d6f3779486f3235354c4f554267345641433738437750532d6b4a5175592d5f415f313535383839373935313c2f7374726f6e673e0d0a3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e496620796f75206469646e2774206d65616e20746f20696e697469617465207468652070617373776f7264207265636f766572792070726f636573732c20646f6e277420776f7272792120596f75722070617373776f7264206973207374696c6c207361666520616e6420796f752063616e2069676e6f7265207468697320652d6d61696c2e3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e54686973206c696e6b2077696c6c20657870697265206166746572203320686f7572732e3c62723e0d0a3c2f703e223b733a373a227375626a656374223b733a31373a2250617373776f7264205265636f76657279223b733a31313a2273656e6465725f6e616d65223b733a31393a224d4544534f555243452047524f555020455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a31393a226d636f6e79616e676f40676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a32303a22757365725f666f72676f745f70617373776f7264223b733a363a227265665f6964223b693a313b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d32362031393a31323a3331223b733a31303a22637265617465645f6279223b4e3b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558897951, 300, 0, 1024, NULL, NULL, NULL),
(44, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3637383a223c703e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e0d0a3c2f703e3c703e0d0a0948656c6c6f20467265647269636b204f63686f6c612c0d0a3c2f703e3c7020223d22223e0d0a09596f7520746f6c6420757320796f7520666f72676f7420796f75722070617373776f72642e204e6f206e65656420746f2050616e6963203a290d0a3c2f703e3c7020223d22223e506c6561736520666f6c6c6f7720746869732055524c20746f20726573657420796f75722070617373776f72643a0d0a3c2f703e3c7020223d22223e3c7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f72657365742d70617373776f72643f746f6b656e3d766634333973364e705257317970396a5a6b5a2d497453337545554a5366316a5f313535383839373939383c2f7374726f6e673e0d0a3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e496620796f75206469646e2774206d65616e20746f20696e697469617465207468652070617373776f7264207265636f766572792070726f636573732c20646f6e277420776f7272792120596f75722070617373776f7264206973207374696c6c207361666520616e6420796f752063616e2069676e6f7265207468697320652d6d61696c2e3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e54686973206c696e6b2077696c6c20657870697265206166746572203320686f7572732e3c62723e0d0a3c2f703e223b733a373a227375626a656374223b733a31373a2250617373776f7264205265636f76657279223b733a31313a2273656e6465725f6e616d65223b733a31393a224d4544534f555243452047524f555020455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a31393a226d636f6e79616e676f40676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a32303a22757365725f666f72676f745f70617373776f7264223b733a363a227265665f6964223b693a313b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d32362031393a31333a3138223b733a31303a22637265617465645f6279223b4e3b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558897998, 300, 0, 1024, NULL, NULL, NULL),
(45, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3637383a223c703e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e0d0a3c2f703e3c703e0d0a0948656c6c6f20467265647269636b204f63686f6c612c0d0a3c2f703e3c7020223d22223e0d0a09596f7520746f6c6420757320796f7520666f72676f7420796f75722070617373776f72642e204e6f206e65656420746f2050616e6963203a290d0a3c2f703e3c7020223d22223e506c6561736520666f6c6c6f7720746869732055524c20746f20726573657420796f75722070617373776f72643a0d0a3c2f703e3c7020223d22223e3c7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f72657365742d70617373776f72643f746f6b656e3d4b6a6869627856334e594f5a76337a7a77555134696c4c6b752d6639667a77535f313535383839393734313c2f7374726f6e673e0d0a3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e496620796f75206469646e2774206d65616e20746f20696e697469617465207468652070617373776f7264207265636f766572792070726f636573732c20646f6e277420776f7272792120596f75722070617373776f7264206973207374696c6c207361666520616e6420796f752063616e2069676e6f7265207468697320652d6d61696c2e3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e54686973206c696e6b2077696c6c20657870697265206166746572203320686f7572732e3c62723e0d0a3c2f703e223b733a373a227375626a656374223b733a31373a2250617373776f7264205265636f76657279223b733a31313a2273656e6465725f6e616d65223b733a31393a224d4544534f555243452047524f555020455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a31393a226d636f6e79616e676f40676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a32303a22757365725f666f72676f745f70617373776f7264223b733a363a227265665f6964223b693a313b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d32362031393a34323a3231223b733a31303a22637265617465645f6279223b4e3b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558899741, 300, 0, 1024, NULL, NULL, NULL),
(46, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3437333a223c7020223d22223e4869204a6f73657068204d756b6f6b6f2c0d0a3c2f703e3c7020223d22223e0d0a09596f7572206163636f756e7420696e204d4544534f555243452047524f555020455250206973206e6f772072656164792e2054686573652061726520796f7572206c6f67696e2064657461696c733a0d0a3c2f703e3c756c3e3c6c693e557365726e616d653a203c7374726f6e673e6d756b6f6b6f3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f6c693e3c6c693e50617373776f72643a3c7374726f6e673e2041646d696e31323334353c2f7374726f6e673e3c2f6c693e3c6c693e3c7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f7374726f6e673e4c6f67696e204c696e6b3a3c7374726f6e673e3c7374726f6e673e203c2f7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f6c6f67696e3c2f7374726f6e673e3c2f6c693e3c2f756c3e3c7020223d22223e506c65617365206c6f67696e20616e6420656e7375726520796f75206368616e676520796f75722070617373776f72642e20446f206e6f7420736861726520796f75722070617373776f7264207769746820616e796f6e652e3c2f703e223b733a373a227375626a656374223b733a31333a224c6f67696e2064657461696c73223b733a31313a2273656e6465725f6e616d65223b733a31393a224d4544534f555243452047524f555020455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a31363a226d756b6f6b6f40676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a31383a22757365725f6c6f67696e5f64657461696c73223b733a363a227265665f6964223b693a363b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d32372030383a34373a3437223b733a31303a22637265617465645f6279223b693a313b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558946867, 300, 0, 1024, NULL, NULL, NULL),
(47, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3437333a223c7020223d22223e4869204a6f73657068204d756b6f6b6f2c0d0a3c2f703e3c7020223d22223e0d0a09596f7572206163636f756e7420696e204d4544534f555243452047524f555020455250206973206e6f772072656164792e2054686573652061726520796f7572206c6f67696e2064657461696c733a0d0a3c2f703e3c756c3e3c6c693e557365726e616d653a203c7374726f6e673e6d756b6f6b6f3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f6c693e3c6c693e50617373776f72643a3c7374726f6e673e2041646d696e31323334353c2f7374726f6e673e3c2f6c693e3c6c693e3c7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f7374726f6e673e4c6f67696e204c696e6b3a3c7374726f6e673e3c7374726f6e673e203c2f7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f6c6f67696e3c2f7374726f6e673e3c2f6c693e3c2f756c3e3c7020223d22223e506c65617365206c6f67696e20616e6420656e7375726520796f75206368616e676520796f75722070617373776f72642e20446f206e6f7420736861726520796f75722070617373776f7264207769746820616e796f6e652e3c2f703e223b733a373a227375626a656374223b733a31333a224c6f67696e2064657461696c73223b733a31313a2273656e6465725f6e616d65223b733a31393a224d4544534f555243452047524f555020455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a31363a226d756b6f6b6f40676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a31383a22757365725f6c6f67696e5f64657461696c73223b733a363a227265665f6964223b693a363b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d32372030383a34373a3437223b733a31303a22637265617465645f6279223b693a313b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558946867, 300, 0, 1024, NULL, NULL, NULL),
(48, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3637383a223c703e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e0d0a3c2f703e3c703e0d0a0948656c6c6f20467265647269636b204f63686f6c612c0d0a3c2f703e3c7020223d22223e0d0a09596f7520746f6c6420757320796f7520666f72676f7420796f75722070617373776f72642e204e6f206e65656420746f2050616e6963203a290d0a3c2f703e3c7020223d22223e506c6561736520666f6c6c6f7720746869732055524c20746f20726573657420796f75722070617373776f72643a0d0a3c2f703e3c7020223d22223e3c7374726f6e673e687474703a2f2f6c6f63616c686f73742f6d6564736f757263652f617574682f617574682f72657365742d70617373776f72643f746f6b656e3d42613231307a56484b66466177546d424c6741413756456745583834504830695f313535383934373435303c2f7374726f6e673e0d0a3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e496620796f75206469646e2774206d65616e20746f20696e697469617465207468652070617373776f7264207265636f766572792070726f636573732c20646f6e277420776f7272792120596f75722070617373776f7264206973207374696c6c207361666520616e6420796f752063616e2069676e6f7265207468697320652d6d61696c2e3c2f703e3c7020223d223e546869732070617373776f7264207265736574206c696e6b2077696c6c2065787069726520696e203120686f75722e0d0a3c2f703e0d0a3c7020223e54686973206c696e6b2077696c6c20657870697265206166746572203320686f7572732e3c62723e0d0a3c2f703e223b733a373a227375626a656374223b733a31373a2250617373776f7264205265636f76657279223b733a31313a2273656e6465725f6e616d65223b733a31393a224d4544534f555243452047524f555020455250223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a31393a226d636f6e79616e676f40676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a32303a22757365725f666f72676f745f70617373776f7264223b733a363a227265665f6964223b693a313b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d32372030383a35373a3330223b733a31303a22637265617465645f6279223b4e3b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1558947450, 300, 0, 1024, NULL, NULL, NULL),
(49, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3438343a223c7020223d22223e4869204461766964204d6f67616b612c0d0a3c2f703e3c7020223d22223e0d0a09596f7572206163636f756e7420696e204166726963616e204461696c792047656e65746963204761696e73202d2041444747206973206e6f772072656164792e2054686573652061726520796f7572206c6f67696e2064657461696c733a0d0a3c2f703e3c756c3e3c6c693e557365726e616d653a203c7374726f6e673e646d6f67616b613c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f6c693e3c6c693e50617373776f72643a3c7374726f6e673e2041646d696e3132333435363c2f7374726f6e673e3c2f6c693e3c6c693e3c7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f7374726f6e673e4c6f67696e204c696e6b3a3c7374726f6e673e3c7374726f6e673e203c2f7374726f6e673e687474703a2f2f6c6f63616c686f73742f616467672f617574682f617574682f6c6f67696e3c2f7374726f6e673e3c2f6c693e3c2f756c3e3c7020223d22223e506c65617365206c6f67696e20616e6420656e7375726520796f75206368616e676520796f75722070617373776f72642e20446f206e6f7420736861726520796f75722070617373776f7264207769746820616e796f6e652e3c2f703e223b733a373a227375626a656374223b733a31333a224c6f67696e2064657461696c73223b733a31313a2273656e6465725f6e616d65223b733a33343a224166726963616e204461696c792047656e65746963204761696e73202d2041444747223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a31373a22646d6f67616b6140676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a31383a22757365725f6c6f67696e5f64657461696c73223b733a363a227265665f6964223b693a323b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d33312030383a31393a3235223b733a31303a22637265617465645f6279223b693a313b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1559290765, 300, 0, 1024, NULL, NULL, NULL),
(50, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3438343a223c7020223d22223e4869204461766964204d6f67616b612c0d0a3c2f703e3c7020223d22223e0d0a09596f7572206163636f756e7420696e204166726963616e204461696c792047656e65746963204761696e73202d2041444747206973206e6f772072656164792e2054686573652061726520796f7572206c6f67696e2064657461696c733a0d0a3c2f703e3c756c3e3c6c693e557365726e616d653a203c7374726f6e673e646d6f67616b613c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f6c693e3c6c693e50617373776f72643a3c7374726f6e673e2041646d696e3132333435363c2f7374726f6e673e3c2f6c693e3c6c693e3c7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f7374726f6e673e4c6f67696e204c696e6b3a3c7374726f6e673e3c7374726f6e673e203c2f7374726f6e673e687474703a2f2f6c6f63616c686f73742f616467672f617574682f617574682f6c6f67696e3c2f7374726f6e673e3c2f6c693e3c2f756c3e3c7020223d22223e506c65617365206c6f67696e20616e6420656e7375726520796f75206368616e676520796f75722070617373776f72642e20446f206e6f7420736861726520796f75722070617373776f7264207769746820616e796f6e652e3c2f703e223b733a373a227375626a656374223b733a31333a224c6f67696e2064657461696c73223b733a31313a2273656e6465725f6e616d65223b733a33343a224166726963616e204461696c792047656e65746963204761696e73202d2041444747223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a31373a22646d6f67616b6140676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a31383a22757365725f6c6f67696e5f64657461696c73223b733a363a227265665f6964223b693a323b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d33312030383a31393a3235223b733a31303a22637265617465645f6279223b693a313b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1559290765, 300, 0, 1024, NULL, NULL, NULL),
(51, 'default', 0x4f3a32353a22636f6e736f6c655c6a6f62735c53656e64456d61696c4a6f62223a31343a7b733a373a226d657373616765223b733a3438383a223c7020223d22223e4869204861727269736f6e204e6a616d62612c0d0a3c2f703e3c7020223d22223e0d0a09596f7572206163636f756e7420696e204166726963616e204461696c792047656e65746963204761696e73202d2041444747206973206e6f772072656164792e2054686573652061726520796f7572206c6f67696e2064657461696c733a0d0a3c2f703e3c756c3e3c6c693e557365726e616d653a203c7374726f6e673e682e6e6a616d62613c2f7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f6c693e3c6c693e50617373776f72643a3c7374726f6e673e2041646d696e3132333435363c2f7374726f6e673e3c2f6c693e3c6c693e3c7374726f6e673e3c7374726f6e673e3c2f7374726f6e673e3c2f7374726f6e673e4c6f67696e204c696e6b3a3c7374726f6e673e3c7374726f6e673e203c2f7374726f6e673e687474703a2f2f6c6f63616c686f73742f616467672f617574682f617574682f6c6f67696e3c2f7374726f6e673e3c2f6c693e3c2f756c3e3c7020223d22223e506c65617365206c6f67696e20616e6420656e7375726520796f75206368616e676520796f75722070617373776f72642e20446f206e6f7420736861726520796f75722070617373776f7264207769746820616e796f6e652e3c2f703e223b733a373a227375626a656374223b733a31333a224c6f67696e2064657461696c73223b733a31313a2273656e6465725f6e616d65223b733a33343a224166726963616e204461696c792047656e65746963204761696e73202d2041444747223b733a31323a2273656e6465725f656d61696c223b733a32323a226e6f7265706c79406274696d696c6c6d616e2e636f6d223b733a31353a22726563697069656e745f656d61696c223b733a31383a226861727269736f6e40676d61696c2e636f6d223b733a31303a226174746163686d656e74223b4e3b733a323a226363223b4e3b733a333a22626363223b4e3b733a31313a2274656d706c6174655f6964223b733a31383a22757365725f6c6f67696e5f64657461696c73223b733a363a227265665f6964223b693a333b733a31303a22637265617465645f6174223b733a31393a22323031392d30352d33312030383a32323a3035223b733a31303a22637265617465645f6279223b693a313b733a383a22617474656d707473223b693a313b733a323a226964223b4e3b7d, 1559290925, 300, 0, 1024, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `latlng` point NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`id`, `latlng`) VALUES
(1, '\0\0\0\0\0\0\0š™™™™YL@ìQ¸…«J@');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_audit_trail`
--
ALTER TABLE `auth_audit_trail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sacco_id` (`org_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_log`
--
ALTER TABLE `auth_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `auth_password_reset_history`
--
ALTER TABLE `auth_password_reset_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_permission`
--
ALTER TABLE `auth_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `resource_id` (`resource_id`);

--
-- Indexes for table `auth_resources`
--
ALTER TABLE `auth_resources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_roles`
--
ALTER TABLE `auth_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `level_id` (`level_id`);

--
-- Indexes for table `auth_users`
--
ALTER TABLE `auth_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `user_level` (`level_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `auth_user_levels`
--
ALTER TABLE `auth_user_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conf_jobs`
--
ALTER TABLE `conf_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conf_job_processes`
--
ALTER TABLE `conf_job_processes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `conf_notif`
--
ALTER TABLE `conf_notif`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notif_type_id` (`notif_type_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `is_read` (`is_read`);

--
-- Indexes for table `conf_notif_queue`
--
ALTER TABLE `conf_notif_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conf_notif_types`
--
ALTER TABLE `conf_notif_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conf_numbering_format`
--
ALTER TABLE `conf_numbering_format`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `conf_timezone_ref`
--
ALTER TABLE `conf_timezone_ref`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_extendable_table`
--
ALTER TABLE `core_extendable_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_farm`
--
ALTER TABLE `core_farm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_id` (`country_id`);

--
-- Indexes for table `core_master_country`
--
ALTER TABLE `core_master_country`
  ADD PRIMARY KEY (`id`),
  ADD KEY `currency_id` (`currency`);

--
-- Indexes for table `core_master_county`
--
ALTER TABLE `core_master_county`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_master_currency`
--
ALTER TABLE `core_master_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_master_currency_conversion`
--
ALTER TABLE `core_master_currency_conversion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_master_list`
--
ALTER TABLE `core_master_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `list_type_id` (`list_type_id`);

--
-- Indexes for table `core_master_list_type`
--
ALTER TABLE `core_master_list_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_master_payment_mode`
--
ALTER TABLE `core_master_payment_mode`
  ADD PRIMARY KEY (`id`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `core_master_salutation`
--
ALTER TABLE `core_master_salutation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `core_table_attributes`
--
ALTER TABLE `core_table_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `table_id` (`table_id`);

--
-- Indexes for table `email_outbox`
--
ALTER TABLE `email_outbox`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_template`
--
ALTER TABLE `email_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `organization`
--
ALTER TABLE `organization`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_units`
--
ALTER TABLE `organization_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `org_id` (`org_id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_outbox`
--
ALTER TABLE `sms_outbox`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_template`
--
ALTER TABLE `sms_template`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `sys_app_session`
--
ALTER TABLE `sys_app_session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expire` (`expire`);

--
-- Indexes for table `sys_cache_form_selection`
--
ALTER TABLE `sys_cache_form_selection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys_form_draft`
--
ALTER TABLE `sys_form_draft`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys_queue`
--
ALTER TABLE `sys_queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel` (`channel`),
  ADD KEY `reserved_at` (`reserved_at`),
  ADD KEY `priority` (`priority`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_audit_trail`
--
ALTER TABLE `auth_audit_trail`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;
--
-- AUTO_INCREMENT for table `auth_log`
--
ALTER TABLE `auth_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `auth_password_reset_history`
--
ALTER TABLE `auth_password_reset_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `auth_permission`
--
ALTER TABLE `auth_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `auth_roles`
--
ALTER TABLE `auth_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `auth_users`
--
ALTER TABLE `auth_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `conf_notif`
--
ALTER TABLE `conf_notif`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `conf_notif_queue`
--
ALTER TABLE `conf_notif_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `conf_numbering_format`
--
ALTER TABLE `conf_numbering_format`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `conf_timezone_ref`
--
ALTER TABLE `conf_timezone_ref`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=462;
--
-- AUTO_INCREMENT for table `core_extendable_table`
--
ALTER TABLE `core_extendable_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `core_farm`
--
ALTER TABLE `core_farm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `core_master_country`
--
ALTER TABLE `core_master_country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=248;
--
-- AUTO_INCREMENT for table `core_master_county`
--
ALTER TABLE `core_master_county`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `core_master_currency`
--
ALTER TABLE `core_master_currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `core_master_currency_conversion`
--
ALTER TABLE `core_master_currency_conversion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `core_master_list`
--
ALTER TABLE `core_master_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `core_master_payment_mode`
--
ALTER TABLE `core_master_payment_mode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `core_master_salutation`
--
ALTER TABLE `core_master_salutation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `core_table_attributes`
--
ALTER TABLE `core_table_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `email_outbox`
--
ALTER TABLE `email_outbox`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `organization`
--
ALTER TABLE `organization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `organization_units`
--
ALTER TABLE `organization_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `sms_outbox`
--
ALTER TABLE `sms_outbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sms_template`
--
ALTER TABLE `sms_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sys_cache_form_selection`
--
ALTER TABLE `sys_cache_form_selection`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sys_form_draft`
--
ALTER TABLE `sys_form_draft`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `sys_queue`
--
ALTER TABLE `sys_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_audit_trail`
--
ALTER TABLE `auth_audit_trail`
  ADD CONSTRAINT `auth_audit_trail_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_log`
--
ALTER TABLE `auth_log`
  ADD CONSTRAINT `auth_log_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `auth_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_password_reset_history`
--
ALTER TABLE `auth_password_reset_history`
  ADD CONSTRAINT `auth_password_reset_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_permission`
--
ALTER TABLE `auth_permission`
  ADD CONSTRAINT `auth_permission_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `auth_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_permission_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `auth_resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_roles`
--
ALTER TABLE `auth_roles`
  ADD CONSTRAINT `auth_roles_ibfk_2` FOREIGN KEY (`level_id`) REFERENCES `auth_user_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_users`
--
ALTER TABLE `auth_users`
  ADD CONSTRAINT `auth_users_ibfk_1` FOREIGN KEY (`level_id`) REFERENCES `auth_user_levels` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `auth_users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `auth_roles` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  ADD CONSTRAINT `auth_users_ibfk_3` FOREIGN KEY (`org_id`) REFERENCES `organization` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `conf_notif`
--
ALTER TABLE `conf_notif`
  ADD CONSTRAINT `conf_notif_ibfk_1` FOREIGN KEY (`notif_type_id`) REFERENCES `conf_notif_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `core_master_list`
--
ALTER TABLE `core_master_list`
  ADD CONSTRAINT `core_master_list_ibfk_1` FOREIGN KEY (`list_type_id`) REFERENCES `core_master_list_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `core_table_attributes`
--
ALTER TABLE `core_table_attributes`
  ADD CONSTRAINT `core_table_attributes_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `core_extendable_table` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `organization_units`
--
ALTER TABLE `organization_units`
  ADD CONSTRAINT `organization_units_ibfk_1` FOREIGN KEY (`org_id`) REFERENCES `organization` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
