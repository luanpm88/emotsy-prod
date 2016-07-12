-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 12, 2016 at 02:32 AM
-- Server version: 5.7.12-0ubuntu1
-- PHP Version: 7.0.4-7ubuntu2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mailixa`
--

-- --------------------------------------------------------

--
-- Table structure for table `blacklists`
--

CREATE TABLE `mailixa_blacklists` (
  `id` int(10) UNSIGNED NOT NULL,
  `subscriber_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `blacklists`
--

INSERT INTO `mailixa_blacklists` (`id`, `subscriber_id`, `email`, `created_at`, `updated_at`) VALUES
(2, 2, 'luanpm88@gmail.com', '2016-07-20 07:00:00', '2016-07-21 07:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `bounce_handlers`
--

CREATE TABLE `mailixa_bounce_handlers` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `port` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `protocol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `custom_order` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bounce_logs`
--

CREATE TABLE `mailixa_bounce_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `message_id` int(11) NOT NULL,
  `bounce_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `raw` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `mailixa_campaigns` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `mail_list_id` int(10) UNSIGNED DEFAULT NULL,
  `segment_id` int(10) UNSIGNED DEFAULT NULL,
  `type` text COLLATE utf8_unicode_ci NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `subject` text COLLATE utf8_unicode_ci NOT NULL,
  `html` text COLLATE utf8_unicode_ci NOT NULL,
  `plain` text COLLATE utf8_unicode_ci NOT NULL,
  `from_email` text COLLATE utf8_unicode_ci NOT NULL,
  `from_name` text COLLATE utf8_unicode_ci NOT NULL,
  `reply_to` text COLLATE utf8_unicode_ci NOT NULL,
  `status` text COLLATE utf8_unicode_ci NOT NULL,
  `sign_dkim` tinyint(1) NOT NULL,
  `track_open` tinyint(1) NOT NULL,
  `track_click` tinyint(1) NOT NULL,
  `resend` int(11) NOT NULL,
  `custom_order` int(11) NOT NULL,
  `delivery_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `mailixa_campaigns` (`id`, `uid`, `user_id`, `mail_list_id`, `segment_id`, `type`, `name`, `subject`, `html`, `plain`, `from_email`, `from_name`, `reply_to`, `status`, `sign_dkim`, `track_open`, `track_click`, `resend`, `custom_order`, `delivery_at`, `created_at`, `updated_at`) VALUES
(1, '5781b681df05f', 1, 1, NULL, 'regular', 'My Campaign', 'Newsletter of June, 13', '<p><b>Those</b> were the <a href="http://google.com">days</a></p>\r\n<p>{UNSUBSCRIBE_URL} <a href="http://yahoo.com">sdfdsfs</a></p>', 'Those were the <i>days</i>', 'gaugau@from.com', 'Those were the days', 'gaugau@from.com', 'sending', 0, 0, 0, 0, 2, '2016-07-11 04:24:00', '2016-07-10 09:44:17', '2016-07-12 14:20:22'),
(2, '57834ecc67e25', 1, 1, NULL, 'regular', 'Untitled', '', '', '', '', '', '', 'new', 1, 1, 1, 0, 1, '0000-00-00 00:00:00', '2016-07-11 14:46:20', '2016-07-12 14:20:22'),
(3, '57849a362c237', 1, NULL, NULL, 'regular', 'Untitled', '', '', '', '', '', '', 'new', 1, 1, 1, 0, 0, '0000-00-00 00:00:00', '2016-07-12 14:20:22', '2016-07-12 14:20:22');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_links`
--

CREATE TABLE `mailixa_campaign_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `campaign_id` int(10) UNSIGNED NOT NULL,
  `link_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `campaign_links`
--

INSERT INTO `mailixa_campaign_links` (`id`, `campaign_id`, `link_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2016-07-11 11:24:37', '2016-07-11 11:24:37'),
(2, 1, 2, '2016-07-11 11:24:37', '2016-07-11 11:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `click_logs`
--

CREATE TABLE `mailixa_click_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `message_id` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `click_logs`
--

INSERT INTO `mailixa_click_logs` (`id`, `message_id`, `url`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, 'http://google.com', '111.111.00.11', '', '2016-07-10 09:44:17', '2016-07-10 09:44:17'),
(2, 1, 'http://google.com', '192.168.204.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36', '2016-07-10 10:56:29', '2016-07-10 10:56:29');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `mailixa_contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_id` int(10) UNSIGNED NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `mailixa_contacts` (`id`, `uid`, `first_name`, `last_name`, `company`, `address_1`, `address_2`, `country_id`, `state`, `zip`, `phone`, `url`, `created_at`, `updated_at`, `email`, `city`) VALUES
(1, '', 'Luan', 'Pham', 'Mailixa Co,. Ldt.', '1202 Nguyen Van Qua, Tan Thoi Hiep, Dist. 12', NULL, 1, 'Ho Chi Minh City', '70000', NULL, NULL, '2016-07-10 09:44:17', '2016-07-10 09:44:17', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `mailixa_countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `mailixa_countries` (`id`, `name`, `code`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Việt Nam', 'vn', '', '2016-07-10 09:44:17', '2016-07-10 09:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `feedback_logs`
--

CREATE TABLE `mailixa_feedback_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `message_id` int(11) NOT NULL,
  `feedback_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `raw_feedback_content` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `feedback_logs`
--

INSERT INTO `mailixa_feedback_logs` (`id`, `message_id`, `feedback_type`, `raw_feedback_content`, `created_at`, `updated_at`) VALUES
(1, 1, '0', '0', '2016-07-10 09:44:17', '2016-07-10 09:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `feedback_loop_handlers`
--

CREATE TABLE `mailixa_feedback_loop_handlers` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `port` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `protocol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `custom_order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

CREATE TABLE `mailixa_fields` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `mail_list_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `default_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visible` tinyint(1) NOT NULL,
  `required` tinyint(1) NOT NULL,
  `custom_order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fields`
--

INSERT INTO `mailixa_fields` (`id`, `uid`, `mail_list_id`, `label`, `type`, `tag`, `default_value`, `visible`, `required`, `custom_order`, `created_at`, `updated_at`) VALUES
(1, '5781b681e02af', 1, 'Email', 'text', 'EMAIL', NULL, 1, 1, 0, '2016-07-10 09:44:17', '2016-07-10 09:44:17'),
(2, '5781b681e0b09', 1, 'First name', 'text', 'FNAME', NULL, 1, 0, 0, '2016-07-10 09:44:17', '2016-07-10 09:44:17'),
(3, '5781b681e0f3d', 1, 'Last name', 'text', 'LNAME', NULL, 1, 0, 0, '2016-07-10 09:44:17', '2016-07-10 09:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `field_options`
--

CREATE TABLE `mailixa_field_options` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `field_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ip_locations`
--

CREATE TABLE `mailixa_ip_locations` (
  `id` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `region_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `region_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `longitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `metro_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `areacode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ip_locations`
--

INSERT INTO `mailixa_ip_locations` (`id`, `ip_address`, `country_code`, `country_name`, `region_code`, `region_name`, `city`, `zipcode`, `latitude`, `longitude`, `metro_code`, `areacode`, `created_at`, `updated_at`) VALUES
(1, '192.168.204.1', '', '', '', '', '', '', '0', '0', '0', '', '2016-07-10 10:24:04', '2016-07-10 10:24:38'),
(2, '113.172.50.164', 'VN', 'Vietnam', '', '', 'hcm', '', '16', '106', '0', '', '2016-07-10 11:38:37', '2016-07-10 11:38:37');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `mailixa_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `mailixa_languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `region_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `mailixa_languages` (`id`, `uid`, `name`, `code`, `region_code`, `status`, `created_at`, `updated_at`) VALUES
(1, '5781b681c1b34', 'English', 'en', 'us', '', '2016-07-10 09:44:17', '2016-07-10 09:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `layouts`
--

CREATE TABLE `mailixa_layouts` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `group_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `layouts`
--

INSERT INTO `mailixa_layouts` (`id`, `uid`, `alias`, `group_name`, `content`, `type`, `created_at`, `updated_at`) VALUES
(1, '57639eb9715cf', 'sign_up_form', 'Sign-up', '	<!-- Page container -->\r\n	<div class="page-container login-container" style="min-height:249px">\r\n\r\n		<!-- Page content -->\r\n		<div class="page-content">\r\n\r\n			<!-- Main content -->\r\n			<div class="content-wrapper">\r\n				<div class="row">\r\n					<div class="col-sm-2 col-md-3">\r\n						\r\n					</div>\r\n					<div class="col-sm-8 col-md-6">\r\n\r\n                    <!-- subscribe form -->\r\n				                  \r\n					<h2 class="text-semibold mt-40 text-white">{LIST_NAME}</h2>\r\n                        <div class="panel panel-body">						\r\n                                                \r\n                        <h4>Welcome to {CONTACT_NAME}</h4>\r\n                        <hr>\r\n                        {FIELDS}\r\n                        <br>\r\n                        {SUBSCRIBE_BUTTON}\r\n\r\n					</div>\r\n\r\n                    <!-- /subscribe form -->\r\n    \r\n						\r\n					</div>\r\n				</div>\r\n			</div>\r\n			<!-- /main content -->\r\n\r\n		</div>\r\n		<!-- /page content -->\r\n\r\n\r\n		<!-- Footer -->\r\n		<div class="footer text-white">\r\n			© 2016. <span class="text-white">Mailixa Email Marketing Application</span> by <a href="http://mailixa.net" class="text-white" target="_blank">Mailixa.net</a>			\r\n		</div>\r\n		<!-- /footer -->\r\n\r\n	</div>\r\n	<!-- /page container -->', 'page', '2016-06-19 00:54:49', '2016-06-19 00:54:49'),
(2, '5763c01613687', 'sign_up_thankyou_page', 'Sign-up', '<!-- Page container -->\r\n	<div class="page-container login-container" style="min-height:249px">\r\n\r\n		<!-- Page content -->\r\n		<div class="page-content">\r\n\r\n			<!-- Main content -->\r\n			<div class="content-wrapper">\r\n				<div class="row">\r\n					<div class="col-sm-2 col-md-3">\r\n						\r\n					</div>\r\n					<div class="col-sm-8 col-md-6">\r\n\r\n                    <!-- subscribe form -->\r\n				                  \r\n					<h2 class="text-semibold mt-40 text-white">{LIST_NAME}</h2>\r\n                        <div class="panel panel-body">						\r\n                                                \r\n                        <h4>Almost finished...</h4>\r\n                        <hr>\r\n						<p>We need to confirm your email address.</p>\r\n						<p>To complete the subscription process, please click the link in the email we just sent you.\r\n						</p>\r\n						--<br />\r\n						\r\n                        {CONTACT_NAME}\r\n\r\n					</div>\r\n\r\n                    <!-- /subscribe form -->\r\n    \r\n						\r\n					</div>\r\n				</div>\r\n			</div>\r\n			<!-- /main content -->\r\n\r\n		</div>\r\n		<!-- /page content -->\r\n\r\n\r\n		<!-- Footer -->\r\n		<div class="footer text-white">\r\n			© 2016. <span class="text-white">Mailixa Email Marketing Application</span> by <a href="http://mailixa.net" class="text-white" target="_blank">Mailixa.net</a>			\r\n		</div>\r\n		<!-- /footer -->\r\n\r\n	</div>\r\n	<!-- /page container -->', 'page', '2016-06-19 03:17:10', '2016-06-19 03:17:10'),
(3, '5763c3ef7bb8e', 'sign_up_confirmation_email', 'Sign-up', '<!-- Page container -->\r\n<div class="page-container login-container" style="min-height: 249px;"><!-- Page content -->\r\n<div class="page-content"><!-- Main content -->\r\n<div class="content-wrapper">\r\n<div class="row">\r\n<div class="col-sm-2 col-md-3"></div>\r\n<div class="col-sm-8 col-md-6"><!-- subscribe form -->\r\n<h2 class="text-semibold mt-40 text-white">{LIST_NAME}</h2>\r\n<div class="panel panel-body">\r\n<h4>Please Confirm Subscriptiona</h4>\r\n<hr />Click the link below to confirm your subscription:<br /> {SUBSCRIBE_CONFIRM_URL}<hr />\r\n<p>If you received this email by mistake, simply delete it. You won\'t be subscribed if you don\'t click the confirmation link above.</p>\r\n<p>For questions about this list, please contact: <br /> <a href="mailto:{CONTACT_EMAIL}">{CONTACT_EMAIL}</a></p>\r\n</div>\r\n<!-- /subscribe form --></div>\r\n</div>\r\n</div>\r\n<!-- /main content --></div>\r\n<!-- /page content --> <!-- Footer -->\r\n<div class="footer text-white">&copy; 2016. <span class="text-white">Mailixa Email Marketing Application</span> by <a href="http://mailixa.net" class="text-white" target="_blank">Mailixa.net</a></div>\r\n<!-- /footer --></div>\r\n<!-- /page container -->', 'email', '2016-06-19 03:33:35', '2016-06-30 03:55:55'),
(4, '5763cab0459c8', 'sign_up_confirmation_thankyou', 'Sign-up', '<!-- Page container -->\r\n	<div class="page-container login-container" style="min-height:249px">\r\n\r\n		<!-- Page content -->\r\n		<div class="page-content">\r\n\r\n			<!-- Main content -->\r\n			<div class="content-wrapper">\r\n				<div class="row">\r\n					<div class="col-sm-2 col-md-3">\r\n						\r\n					</div>\r\n					<div class="col-sm-8 col-md-6">\r\n\r\n                    <!-- subscribe form -->\r\n				                  \r\n					<h2 class="text-semibold mt-40 text-white">{LIST_NAME}</h2>\r\n                        <div class="panel panel-body">						\r\n                                                \r\n                        <h4>Subscription Confirmed</h4>\r\n                        <hr>\r\n						<p>Your subscription to our list has been confirmed.\r\n</p>\r\n<p>Thank you for subscribing!</p>\r\n\r\n						\r\n                        <a href="{CONTACT_URL}" class="btn btn-info bg-teal-800">Continue to our website</a> or <a href="{UPDATE_PROFILE_URL}" class="btn btn-info bg-teal-800">Manage your references</a>\r\n\r\n					</div>\r\n\r\n                    <!-- /subscribe form -->\r\n    \r\n						\r\n					</div>\r\n				</div>\r\n			</div>\r\n			<!-- /main content -->\r\n\r\n		</div>\r\n		<!-- /page content -->\r\n\r\n\r\n		<!-- Footer -->\r\n		<div class="footer text-white">\r\n			© 2016. <span class="text-white">Mailixa Email Marketing Application</span> by <a href="http://mailixa.net" class="text-white" target="_blank">Mailixa.net</a>			\r\n		</div>\r\n		<!-- /footer -->\r\n\r\n	</div>\r\n	<!-- /page container -->', 'page', '2016-06-19 04:02:24', '2016-06-19 04:02:24'),
(5, '5764b3c8bcf4a', 'sign_up_welcome_email', 'Sign-up', '<!-- Page container -->\r\n<div class="page-container login-container" style="min-height: 249px;"><!-- Page content -->\r\n<div class="page-content"><!-- Main content -->\r\n<div class="content-wrapper">\r\n<div class="row">\r\n<div class="col-sm-2 col-md-3"></div>\r\n<div class="col-sm-8 col-md-6"><!-- subscribe form -->\r\n<h2 class="text-semibold mt-40 text-white">{LIST_NAME}</h2>\r\n<div class="panel panel-body">\r\n<h4>Your subscription to our list has been confirmed.</h4>\r\n<hr />\r\n<p>For your records, here is a copy of the information you submitted to us...</p>\r\n{SUBSCRIBER_SUMMARY}<hr />\r\n<p>If at any time you wish to stop receiving our emails, you can: <br /> <a href="{UNSUBSCRIBE_URL}" class="btn btn-info bg-teal-800">Unsubscribe here</a></p>\r\n<p>You may also contact us at: <br /> <a href="mailto:{CONTACT_EMAIL}">{CONTACT_EMAIL}</a></p>\r\n</div>\r\n<!-- /subscribe form --></div>\r\n</div>\r\n</div>\r\n<!-- /main content --></div>\r\n<!-- /page content --> <!-- Footer -->\r\n<div class="footer text-white">&copy; 2016. <span class="text-white">Mailixa Email Marketing Application</span> by <a href="http://mailixa.net" class="text-white" target="_blank">Mailixa.net</a></div>\r\n<!-- /footer --></div>\r\n<!-- /page container -->', 'email', '2016-06-19 20:36:56', '2016-06-30 03:56:51'),
(6, '5764bac04f223', 'unsubscribe_form', 'Unsubscribe', '	<!-- Page container -->\r\n	<div class="page-container login-container" style="min-height:249px">\r\n\r\n		<!-- Page content -->\r\n		<div class="page-content">\r\n\r\n			<!-- Main content -->\r\n			<div class="content-wrapper">\r\n				<div class="row">\r\n					<div class="col-sm-2 col-md-3">\r\n						\r\n					</div>\r\n					<div class="col-sm-8 col-md-6">\r\n\r\n                    <!-- form -->\r\n				                  \r\n					<h2 class="text-semibold mt-40 text-white">{LIST_NAME}</h2>\r\n                        <div class="panel panel-body">						\r\n                                                \r\n                        <h4>Unsubscribe</h4>\r\n                        <hr>\r\n                        <p>Enter your email address to unsubscribe from {LIST_NAME}</p>\r\n                        \r\n                        \r\n\r\n{EMAIL_FIELD}\r\n\r\n<p>{UNSUBSCRIBE_BUTTON}</p>\r\n					</div>\r\n\r\n                    <!-- /form -->\r\n    \r\n						\r\n					</div>\r\n				</div>\r\n			</div>\r\n			<!-- /main content -->\r\n\r\n		</div>\r\n		<!-- /page content -->\r\n\r\n\r\n		<!-- Footer -->\r\n		<div class="footer text-white">\r\n			© 2016. <span class="text-white">Mailixa Email Marketing Application</span> by <a href="http://mailixa.net" class="text-white" target="_blank">Mailixa.net</a>			\r\n		</div>\r\n		<!-- /footer -->\r\n\r\n	</div>\r\n	<!-- /page container -->', 'page', '2016-06-19 21:06:40', '2016-06-19 21:06:40'),
(7, '5764c2d612aea', 'unsubscribe_success_page', 'Unsubscribe', '	<!-- Page container -->\n	<div class="page-container login-container" style="min-height:249px">\n\n		<!-- Page content -->\n		<div class="page-content">\n\n			<!-- Main content -->\n			<div class="content-wrapper">\n				<div class="row">\n					<div class="col-sm-2 col-md-3">\n						\n					</div>\n					<div class="col-sm-8 col-md-6">\n\n                    <!-- form -->\n				                  \n					<h2 class="text-semibold mt-40 text-white">{LIST_NAME}</h2>\n                        <div class="panel panel-body">						\n                                                \n                        <h4>Unsubscribe Successful</h4>\n                        <hr>\n                        <p>You have been removed from {LIST_NAME}.</p>\n                        \n                        <br />\n<a href="{CONTACT_URL}" class="btn btn-info bg-teal-800"><< return to our website</a>\n					</div>\n\n                    <!-- /form -->\n    \n						\n					</div>\n				</div>\n			</div>\n			<!-- /main content -->\n\n		</div>\n		<!-- /page content -->\n\n\n		<!-- Footer -->\n		<div class="footer text-white">\n			© 2016. <span class="text-white">Mailixa Email Marketing Application</span> by <a href="http://mailixa.net" class="text-white" target="_blank">Mailixa.net</a>			\n		</div>\n		<!-- /footer -->\n\n	</div>\n	<!-- /page container -->', 'page', '2016-06-19 21:41:10', '2016-06-19 21:41:10'),
(8, '5764c466641c7', 'unsubscribe_goodbye_email', 'Unsubscribe', '	<!-- Page container -->\n	<div class="page-container login-container" style="min-height:249px">\n\n		<!-- Page content -->\n		<div class="page-content">\n\n			<!-- Main content -->\n			<div class="content-wrapper">\n				<div class="row">\n					<div class="col-sm-2 col-md-3">\n						\n					</div>\n					<div class="col-sm-8 col-md-6">\n\n                    <!-- form -->\n				                  \n					<h2 class="text-semibold mt-40 text-white">{LIST_NAME}</h2>\n                        <div class="panel panel-body">						\n                                                \n                        <h4>We have removed your email address from our list.</h4>\n                        <hr>\n                        <p>We\'re sorry to see you go.</p>\n                        <p>Was this a mistake? Did you forward one of our emails to a friend, and they clicked the unsubscribe link not realizing they were in fact unsubscribing you from this list? If this was a mistake, you can re-subscribe at: <br />\n<a href="{SUBSCRIBE_URL}" class="btn btn-info bg-teal-800">Subscribe</a>\n</p>\n                        <br />\nFor questions or comments, please contact us at:<br />\n<a href="mailto:{CONTACT_EMAIL}">{CONTACT_EMAIL}</a>\n					</div>\n\n                    <!-- /form -->\n    \n						\n					</div>\n				</div>\n			</div>\n			<!-- /main content -->\n\n		</div>\n		<!-- /page content -->\n\n\n		<!-- Footer -->\n		<div class="footer text-white">\n			© 2016. <span class="text-white">Mailixa Email Marketing Application</span> by <a href="http://mailixa.net" class="text-white" target="_blank">Mailixa.net</a>			\n		</div>\n		<!-- /footer -->\n\n	</div>\n	<!-- /page container -->', 'email', '2016-06-19 21:47:50', '2016-06-19 21:47:50'),
(9, '5764c710527b8', 'profile_update_email', 'Update profile', '	<!-- Page container -->\r\n	<div class="page-container login-container" style="min-height:249px">\r\n\r\n		<!-- Page content -->\r\n		<div class="page-content">\r\n\r\n			<!-- Main content -->\r\n			<div class="content-wrapper">\r\n				<div class="row">\r\n					<div class="col-sm-2 col-md-3">\r\n						\r\n					</div>\r\n					<div class="col-sm-8 col-md-6">\r\n\r\n                    <!-- form -->\r\n				                  \r\n					<h2 class="text-semibold mt-40 text-white">{LIST_NAME}</h2>\r\n                        <div class="panel panel-body">						\r\n                                                \r\n                        <p>We received a request to change your subscription preferences for List 1.</p>\r\n\r\n<p>If you made this request, and would like to change your preferences, use the link below</p>\r\n\r\n<p><a href="{UPDATE_PROFILE_URL}">Update your preferences</a></p>\r\n\r\n<p>If you did not make this request, it was probably submitted by someone else by mistake. You can ignore this email, and no changes will be made to your subscription preferences.</p>\r\n\r\n<p>\r\nFor questions about this list, please contact: <br />\r\n<a href="{CONTACT_EMAIL}">{CONTACT_EMAIL}</a>\r\n</p>\r\n					</div>\r\n\r\n                    <!-- /form -->\r\n    \r\n						\r\n					</div>\r\n				</div>\r\n			</div>\r\n			<!-- /main content -->\r\n\r\n		</div>\r\n		<!-- /page content -->\r\n\r\n\r\n		<!-- Footer -->\r\n		<div class="footer text-white">\r\n			© 2016. <span class="text-white">Mailixa Email Marketing Application</span> by <a href="http://mailixa.net" class="text-white" target="_blank">Mailixa.net</a>			\r\n		</div>\r\n		<!-- /footer -->\r\n\r\n	</div>\r\n	<!-- /page container -->', 'email', '2016-06-19 21:59:12', '2016-06-19 21:59:12'),
(10, '5764c87e6bad1', 'profile_update_form', 'Update profile', '	<!-- Page container -->\r\n	<div class="page-container login-container" style="min-height:249px">\r\n\r\n		<!-- Page content -->\r\n		<div class="page-content">\r\n\r\n			<!-- Main content -->\r\n			<div class="content-wrapper">\r\n				<div class="row">\r\n					<div class="col-sm-2 col-md-3">\r\n						\r\n					</div>\r\n					<div class="col-sm-8 col-md-6">\r\n\r\n                    <!-- form -->\r\n				                  \r\n					<h2 class="text-semibold mt-40 text-white">{LIST_NAME}</h2>\r\n                        <div class="panel panel-body">						\r\n<h4>Update your preferences</h4>\r\n<hr />\r\n                        {FIELDS}<br />\r\n{UPDATE_PROFILE_BUTTON} or <a class="btn btn-info bg-grey" href="{UNSUBSCRIBE_URL}">Unsubscribe</a>\r\n\r\n\r\n					</div>\r\n\r\n                    <!-- /form -->\r\n    \r\n						\r\n					</div>\r\n				</div>\r\n			</div>\r\n			<!-- /main content -->\r\n\r\n		</div>\r\n		<!-- /page content -->\r\n\r\n\r\n		<!-- Footer -->\r\n		<div class="footer text-white">\r\n			© 2016. <span class="text-white">Mailixa Email Marketing Application</span> by <a href="http://mailixa.net" class="text-white" target="_blank">Mailixa.net</a>			\r\n		</div>\r\n		<!-- /footer -->\r\n\r\n	</div>\r\n	<!-- /page container -->', 'page', '2016-06-19 22:05:18', '2016-06-19 22:05:18'),
(11, '5764c90505f1c', 'profile_update_success_page', 'Update profile', '<!-- Page container -->\r\n	<div class="page-container login-container" style="min-height:249px">\r\n\r\n		<!-- Page content -->\r\n		<div class="page-content">\r\n\r\n			<!-- Main content -->\r\n			<div class="content-wrapper">\r\n				<div class="row">\r\n					<div class="col-sm-2 col-md-3">\r\n						\r\n					</div>\r\n					<div class="col-sm-8 col-md-6">\r\n\r\n                    <!-- subscribe form -->\r\n				                  \r\n					<h2 class="text-semibold mt-40 text-white">{LIST_NAME}</h2>\r\n                        <div class="panel panel-body">						\r\n                                                \r\n                        <h4>Profile Updated</h4>\r\n                        <hr>\r\n						<p>Your profile information has been updated. For your records, here is a copy of the information you submitted to us...</p>\r\n\r\n{SUBSCRIBER_SUMMARY}\r\n\r\n\r\n\r\n\r\n<hr />\r\n<a class="btn btn-info bg-teal-800" href="{CONTACT_URL}">Return to our website</a>\r\n\r\n					</div>\r\n\r\n                    <!-- /subscribe form -->\r\n    \r\n						\r\n					</div>\r\n				</div>\r\n			</div>\r\n			<!-- /main content -->\r\n\r\n		</div>\r\n		<!-- /page content -->\r\n\r\n\r\n		<!-- Footer -->\r\n		<div class="footer text-white">\r\n			© 2016. <span class="text-white">Mailixa Email Marketing Application</span> by <a href="http://mailixa.net" class="text-white" target="_blank">Mailixa.net</a>			\r\n		</div>\r\n		<!-- /footer -->\r\n\r\n	</div>\r\n	<!-- /page container -->', 'page', '2016-06-19 22:07:33', '2016-06-19 22:07:33'),
(12, '5764c9812b0bc', 'profile_update_email_sent', 'Update profile', '	<!-- Page container -->\r\n	<div class="page-container login-container" style="min-height:249px">\r\n\r\n		<!-- Page content -->\r\n		<div class="page-content">\r\n\r\n			<!-- Main content -->\r\n			<div class="content-wrapper">\r\n				<div class="row">\r\n					<div class="col-sm-2 col-md-3">\r\n						\r\n					</div>\r\n					<div class="col-sm-8 col-md-6">\r\n\r\n                    <!-- form -->\r\n				                  \r\n					<h2 class="text-semibold mt-40 text-white">{LIST_NAME}</h2>\r\n                        <div class="panel panel-body">						\r\n<h4>Email sent</h4>          \r\n<hr />\r\n                        <p>For security, we\'ve sent an email to your inbox that contains a link to update your preferences.</p>\r\n--<br />\r\n{CONTACT_NAME}\r\n					</div>\r\n\r\n                    <!-- /form -->\r\n    \r\n						\r\n					</div>\r\n				</div>\r\n			</div>\r\n			<!-- /main content -->\r\n\r\n		</div>\r\n		<!-- /page content -->\r\n\r\n\r\n		<!-- Footer -->\r\n		<div class="footer text-white">\r\n			© 2016. <span class="text-white">Mailixa Email Marketing Application</span> by <a href="http://mailixa.net" class="text-white" target="_blank">Mailixa.net</a>			\r\n		</div>\r\n		<!-- /footer -->\r\n\r\n	</div>\r\n	<!-- /page container -->', 'page', '2016-06-19 21:59:11', '2016-06-19 22:09:37');

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `mailixa_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `links`
--

INSERT INTO `mailixa_links` (`id`, `url`, `created_at`, `updated_at`) VALUES
(1, 'http://google.com', '2016-07-11 11:24:37', '2016-07-11 11:24:37'),
(2, 'http://yahoo.com', '2016-07-11 11:24:37', '2016-07-11 11:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `mailixa_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `mailixa_logs` (`id`, `user_id`, `type`, `name`, `data`, `created_at`, `updated_at`) VALUES
(1, 1, 'campaign', 'started', '{"id":1,"name":"My Campaign","list_id":1,"list_name":"My List"}', '2016-07-11 11:24:42', '2016-07-11 11:24:42'),
(2, 1, 'list', 'import_started', '{"id":1,"name":"My List"}', '2016-07-12 11:35:23', '2016-07-12 11:35:23'),
(3, 1, 'list', 'import_success', '{"id":1,"name":"My List","count":4,"error":0}', '2016-07-12 11:35:25', '2016-07-12 11:35:25');

-- --------------------------------------------------------

--
-- Table structure for table `mail_lists`
--

CREATE TABLE `mailixa_mail_lists` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `contact_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `default_subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `from_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remind_message` text COLLATE utf8_unicode_ci,
  `email_subscribe` text COLLATE utf8_unicode_ci,
  `email_unsubscribe` text COLLATE utf8_unicode_ci,
  `email_daily` text COLLATE utf8_unicode_ci,
  `send_welcome_email` tinyint(1) NOT NULL DEFAULT '0',
  `unsubscribe_notification` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `custom_order` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `mail_lists`
--

INSERT INTO `mailixa_mail_lists` (`id`, `uid`, `user_id`, `contact_id`, `name`, `default_subject`, `from_email`, `from_name`, `remind_message`, `email_subscribe`, `email_unsubscribe`, `email_daily`, `send_welcome_email`, `unsubscribe_notification`, `status`, `created_at`, `updated_at`, `custom_order`) VALUES
(1, '5781b681de53c', 1, 1, 'My List', '', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 'active', '2016-07-10 09:44:17', '2016-07-10 09:44:17', 0);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `mailixa_migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `mailixa_migrations` (`migration`, `batch`) VALUES
('2014_10_12_000000_create_users_table', 1),
('2014_10_12_100000_create_password_resets_table', 1),
('2016_06_09_172556_create_mail_lists_table', 1),
('2016_06_09_174024_create_contacts_table', 1),
('2016_06_09_174351_create_countries_table', 1),
('2016_06_10_024025_create_user_groups_table', 1),
('2016_06_10_041252_create_languages_table', 1),
('2016_06_10_170200_add_names_to_users_table', 1),
('2016_06_10_174521_create_campaigns_table', 1),
('2016_06_11_073311_add_foreign_keys', 1),
('2016_06_11_182326_add_email_to_contact', 1),
('2016_06_11_182432_add_city_to_contact', 1),
('2016_06_12_030828_rename_mail_list_description_to_remind_message', 1),
('2016_06_12_152501_add_custom_order_to_mail_lists', 1),
('2016_06_13_045445_create_tracking_logs_table', 1),
('2016_06_14_025716_create_fields_table', 1),
('2016_06_14_164304_create_field_options_table', 1),
('2016_06_15_025158_create_subscribers_table', 1),
('2016_06_15_064031_create_subscriber_fields_table', 1),
('2016_06_16_034306_create_segments_table', 1),
('2016_06_16_062814_create_segment_conditions_table', 1),
('2016_06_16_133626_create_jobs_table', 1),
('2016_06_17_041253_create_layouts_table', 1),
('2016_06_17_042331_create_pages_table', 1),
('2016_06_19_145423_create_open_logs_table', 1),
('2016_06_19_145434_create_click_logs_table', 1),
('2016_06_19_145452_create_unsubscribe_logs_table', 1),
('2016_06_19_145506_create_feedback_logs_table', 1),
('2016_06_21_074950_create_templates_table', 1),
('2016_06_21_145755_add_segment_foreign_key_to_campaigns_table', 1),
('2016_06_24_152627_create_user_group_settings_table', 1),
('2016_06_27_152255_create_user_group_sending_servers_table', 1),
('2016_06_28_081813_add_columns_to_user_groups_table', 1),
('2016_06_28_094014_create_links_table', 1),
('2016_06_29_024509_create_bounce_handlers_table', 1),
('2016_06_29_041133_create_feedback_loop_handlers_table', 1),
('2016_07_01_150630_create_sending_servers_table', 1),
('2016_07_04_092555_create_sending_domains_table', 1),
('2016_07_05_020001_create_settings_table', 1),
('2016_07_05_073002_create_campaign_links_table', 1),
('2016_07_06_034536_add_user_foreign_key_to_users', 1),
('2016_07_06_145609_create_logs_table', 1),
('2016_07_07_034047_add_tracking_logs_foreign_keys', 1),
('2016_07_07_080813_create_bounce_logs_table', 1),
('2016_07_10_030645_create_ip_locations_table', 2),
('2016_07_12_031139_create_blacklists_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `open_logs`
--

CREATE TABLE `mailixa_open_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `message_id` int(11) NOT NULL,
  `ip_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `open_logs`
--

INSERT INTO `mailixa_open_logs` (`id`, `message_id`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, '111.111.00.11', '', '2016-07-10 09:44:17', '2016-07-10 09:44:17'),
(2, 1, '192.168.204.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36', '2016-07-10 10:47:08', '2016-07-10 10:47:08'),
(3, 1, '113.172.50.164', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36', '2016-07-10 11:38:37', '2016-07-10 11:38:37'),
(4, 1, '113.172.50.164', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36', '2016-07-10 11:38:42', '2016-07-10 11:38:42');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `mailixa_pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `layout_id` int(10) UNSIGNED NOT NULL,
  `mail_list_id` int(10) UNSIGNED NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `mailixa_password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `segments`
--

CREATE TABLE `mailixa_segments` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `mail_list_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `matching` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `segment_conditions`
--

CREATE TABLE `mailixa_segment_conditions` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `segment_id` int(10) UNSIGNED NOT NULL,
  `field_id` int(10) UNSIGNED NOT NULL,
  `operator` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sending_domains`
--

CREATE TABLE `mailixa_sending_domains` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dkim_private` text COLLATE utf8_unicode_ci NOT NULL,
  `dkim_public` text COLLATE utf8_unicode_ci NOT NULL,
  `signing_enabled` tinyint(1) NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `custom_order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sending_servers`
--

CREATE TABLE `mailixa_sending_servers` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `port` int(11) NOT NULL,
  `sendmail_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `protocol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `custom_order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sending_servers`
--

INSERT INTO `mailixa_sending_servers` (`id`, `uid`, `user_id`, `name`, `type`, `host`, `username`, `password`, `port`, `sendmail_path`, `protocol`, `status`, `custom_order`, `created_at`, `updated_at`) VALUES
(1, '5781b681e3489', 1, 'Server 1', 'aws-ses-smtp', 'email-smtp.us-east-1.amazonaws.com', 'AKIAIWEPV5HTR2NOPTDQ', 'Al5DNO3GhaFQ3mxLT81FDqyGOKMwvHKtraisvh//EG3Z', 25, '', 'tls', '', 0, '2016-07-10 09:44:17', '2016-07-10 09:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `mailixa_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `mailixa_settings` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Mailixa', '2016-07-11 20:09:16', '2016-07-11 20:09:16'),
(2, 'site_keyword', 'Email Marketing, Campaigns, Lists', '2016-07-11 20:09:16', '2016-07-11 20:09:16'),
(3, 'site_online', 'false', '2016-07-11 20:09:16', '2016-07-11 22:01:03'),
(4, 'site_offline_message', 'Mailixa application currently offline. We will come back soon!', '2016-07-11 20:09:16', '2016-07-11 20:09:16'),
(5, 'site_description', 'Mailixa makes it easy for you to create, send, and optimize your email marketing campaigns.', '2016-07-11 20:09:16', '2016-07-11 20:09:16'),
(6, 'sending_campaigns_at_once', '10', '2016-07-11 20:09:16', '2016-07-11 20:09:16'),
(7, 'sending_change_server_time', '300', '2016-07-11 20:09:16', '2016-07-11 20:09:16'),
(8, 'sending_emails_per_minute', '150', '2016-07-11 20:09:16', '2016-07-11 20:09:16'),
(9, 'sending_pause', '10', '2016-07-11 20:09:16', '2016-07-11 20:09:16'),
(10, 'sending_at_once', '50', '2016-07-11 20:09:16', '2016-07-11 20:09:16'),
(11, 'sending_subscribers_at_once', '100', '2016-07-11 20:09:16', '2016-07-11 20:09:16');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `mailixa_subscribers` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `mail_list_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `from` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `mailixa_subscribers` (`id`, `uid`, `user_id`, `mail_list_id`, `email`, `status`, `from`, `ip`, `created_at`, `updated_at`) VALUES
(1, '5781b681e1e1a', 1, 1, 'luanpm88@gmail.com', 'unsubscribed', '', '', '2016-07-10 09:44:17', '2016-07-10 11:18:41'),
(2, '5781b681e2741', 1, 1, 'minhnghivn@yahoo.com', 'active', '', '', '2016-07-10 09:44:17', '2016-07-10 09:44:17'),
(3, '5784738ceba0d', 1, 1, 'marry@domain.com', 'subscribed', '', '', '2016-07-12 11:35:24', '2016-07-12 11:35:24'),
(4, '5784738d00992', 1, 1, 'jame@domain.com', 'subscribed', '', '', '2016-07-12 11:35:24', '2016-07-12 11:35:24'),
(5, '5784738d08acc', 1, 1, 'luan@domain.com', 'subscribed', '', '', '2016-07-12 11:35:25', '2016-07-12 11:35:25'),
(6, '5784738d0e5d9', 1, 1, 'nghi@domain.com', 'subscribed', '', '', '2016-07-12 11:35:25', '2016-07-12 11:35:25');

-- --------------------------------------------------------

--
-- Table structure for table `subscriber_fields`
--

CREATE TABLE `mailixa_subscriber_fields` (
  `id` int(10) UNSIGNED NOT NULL,
  `subscriber_id` int(10) UNSIGNED NOT NULL,
  `field_id` int(10) UNSIGNED NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `subscriber_fields`
--

INSERT INTO `mailixa_subscriber_fields` (`id`, `subscriber_id`, `field_id`, `value`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'marry@domain.com', '2016-07-12 11:35:24', '2016-07-12 11:35:24'),
(2, 3, 2, 'Marry', '2016-07-12 11:35:24', '2016-07-12 11:35:24'),
(3, 3, 3, 'Anna', '2016-07-12 11:35:24', '2016-07-12 11:35:24'),
(4, 4, 1, 'jame@domain.com', '2016-07-12 11:35:25', '2016-07-12 11:35:25'),
(5, 4, 2, 'Jame', '2016-07-12 11:35:25', '2016-07-12 11:35:25'),
(6, 4, 3, 'Bonce', '2016-07-12 11:35:25', '2016-07-12 11:35:25'),
(7, 5, 1, 'luan@domain.com', '2016-07-12 11:35:25', '2016-07-12 11:35:25'),
(8, 5, 2, 'Luan', '2016-07-12 11:35:25', '2016-07-12 11:35:25'),
(9, 5, 3, 'Pham', '2016-07-12 11:35:25', '2016-07-12 11:35:25'),
(10, 6, 1, 'nghi@domain.com', '2016-07-12 11:35:25', '2016-07-12 11:35:25'),
(11, 6, 2, 'Nghi', '2016-07-12 11:35:25', '2016-07-12 11:35:25'),
(12, 6, 3, 'Pham', '2016-07-12 11:35:25', '2016-07-12 11:35:25');

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `mailixa_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `custom_order` int(11) NOT NULL,
  `shared` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tracking_logs`
--

CREATE TABLE `mailixa_tracking_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `message_id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `sending_server_id` int(10) UNSIGNED NOT NULL,
  `campaign_id` int(10) UNSIGNED NOT NULL,
  `subscriber_id` int(10) UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `note` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tracking_logs`
--

INSERT INTO `mailixa_tracking_logs` (`id`, `message_id`, `user_id`, `sending_server_id`, `campaign_id`, `subscriber_id`, `status`, `note`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 'sent', '', '2016-07-10 09:44:17', '2016-07-10 09:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `unsubscribe_logs`
--

CREATE TABLE `mailixa_unsubscribe_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `message_id` int(11) NOT NULL,
  `ip_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `unsubscribe_logs`
--

INSERT INTO `mailixa_unsubscribe_logs` (`id`, `message_id`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, '111.111.00.11', '0', '2016-07-10 09:44:17', '2016-07-10 09:44:17'),
(2, 1, '192.168.204.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36', '2016-07-10 11:18:42', '2016-07-10 11:18:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `mailixa_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `contact_id` int(10) UNSIGNED DEFAULT NULL,
  `user_group_id` int(10) UNSIGNED NOT NULL,
  `language_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `mailixa_users` (`id`, `uid`, `user_id`, `contact_id`, `user_group_id`, `language_id`, `name`, `email`, `password`, `remember_token`, `image`, `timezone`, `status`, `created_at`, `updated_at`, `first_name`, `last_name`) VALUES
(1, '5781b681c2e1a', NULL, 1, 1, 1, '', 'luanpm88@gmail.com', '$2y$10$3blprN9DstyLNdsfXRgox.EVD35oajYl/B/CVzcVDZnAv.rzYwY02', 'yHP1hGRH2YlUq0ADAKguolUcxE545PANAh5giswK7x2yT5kErBF8Q1ktDH6p', NULL, '', 'active', '2016-07-10 09:44:17', '2016-07-11 10:47:17', 'Luan', 'Pham'),
(2, '5783526d4ccc7', 1, NULL, 1, 1, '', 'luanpm@Live.com', '$2y$10$sQ9oxr5LoV9mekIFZItY9e9.Xu4pg4JXLJuI1X2N/NjW.W.CDcmD.', 'kALbNCW7lgTJ5aEQoiSioYiMKOHdOQSm41QpLv9vfIyXAZi3xXnpiJgX1QRa', NULL, 'Atlantic/South_Georgia', 'active', '2016-07-11 15:01:49', '2016-07-11 15:44:22', 'Meo', 'Meo'),
(3, '57835402cff54', 2, NULL, 1, 1, '', 'minhnghivn@gmail.com', '$2y$10$8gecZfz6kyqsODVk396c9.2BrfW8ZxY1WiOSL/EQreb7x.rq38pw6', NULL, NULL, 'Atlantic/South_Georgia', 'active', '2016-07-11 15:08:34', '2016-07-11 15:08:34', 'Nghi', 'Pham');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `mailixa_user_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `frontend_access` tinyint(1) NOT NULL,
  `backend_access` tinyint(1) NOT NULL,
  `options` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `custom_order` int(11) NOT NULL DEFAULT '0',
  `user_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `mailixa_user_groups` (`id`, `name`, `frontend_access`, `backend_access`, `options`, `created_at`, `updated_at`, `custom_order`, `user_id`) VALUES
(1, 'Default', 1, 1, '{"frontend":{"list_max":"0","subscriber_max":"-1","subscriber_per_list_max":"-1","segment_per_list_max":"0","campaign_max":"-1","campaign_subscribers_at_once":"100","campaign_pause":"10","campaign_emails_per_minute":"100","campaign_change_server_time":"200","campaign_send_at_once":"50","sending_quota":"-1","sending_quota_time":"-1","sending_quota_time_unit":"month"},"backend":{"user_group_read":"all","user_group_create":"yes","user_group_update":"all","user_group_delete":"own","user_read":"own","user_create":"yes","user_update":"own","user_delete":"own","user_switch":"own","sending_server_read":"all","sending_server_create":"yes","sending_server_update":"own","sending_server_delete":"own","bounce_handler_read":"own","bounce_handler_create":"yes","bounce_handler_update":"own","bounce_handler_delete":"own","fbl_handler_read":"own","fbl_handler_create":"yes","fbl_handler_update":"own","fbl_handler_delete":"own","sending_domain_read":"own","sending_domain_create":"no","sending_domain_update":"own","sending_domain_delete":"own","template_read":"own","template_create":"yes","template_update":"own","template_delete":"own","layout_read":"yes","layout_update":"yes","setting_general":"yes","setting_sending":"yes","setting_system_urls":"yes","setting_access_when_offline":"yes","language_read":"yes","language_create":"yes","language_update":"yes","language_delete":"yes","report_blacklist":"no","report_tracking_log":"no","report_bounce_log":"no","report_feedback_log":"no","report_open_log":"no","report_click_log":"no","report_unsubscribe_log":"no"}}', '2016-07-10 09:44:17', '2016-07-12 11:13:34', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_group_sending_servers`
--

CREATE TABLE `mailixa_user_group_sending_servers` (
  `id` int(10) UNSIGNED NOT NULL,
  `sending_server_id` int(11) NOT NULL,
  `user_group_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_group_settings`
--

CREATE TABLE `mailixa_user_group_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_group_settings`
--

INSERT INTO `mailixa_user_group_settings` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'use_all_sending_servers', '1', '2016-07-10 09:44:17', '2016-07-10 09:44:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blacklists`
--
ALTER TABLE `mailixa_blacklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blacklists_subscriber_id_foreign` (`subscriber_id`);

--
-- Indexes for table `bounce_handlers`
--
ALTER TABLE `mailixa_bounce_handlers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bounce_handlers_user_id_foreign` (`user_id`);

--
-- Indexes for table `bounce_logs`
--
ALTER TABLE `mailixa_bounce_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bounce_logs_message_id_foreign` (`message_id`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `mailixa_campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaigns_user_id_foreign` (`user_id`),
  ADD KEY `campaigns_mail_list_id_foreign` (`mail_list_id`),
  ADD KEY `campaigns_segment_id_foreign` (`segment_id`);

--
-- Indexes for table `campaign_links`
--
ALTER TABLE `mailixa_campaign_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_links_campaign_id_foreign` (`campaign_id`),
  ADD KEY `campaign_links_link_id_foreign` (`link_id`);

--
-- Indexes for table `click_logs`
--
ALTER TABLE `mailixa_click_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `click_logs_message_id_foreign` (`message_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `mailixa_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_country_id_foreign` (`country_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `mailixa_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback_logs`
--
ALTER TABLE `mailixa_feedback_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_logs_message_id_foreign` (`message_id`);

--
-- Indexes for table `feedback_loop_handlers`
--
ALTER TABLE `mailixa_feedback_loop_handlers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_loop_handlers_user_id_foreign` (`user_id`);

--
-- Indexes for table `fields`
--
ALTER TABLE `mailixa_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fields_mail_list_id_foreign` (`mail_list_id`);

--
-- Indexes for table `field_options`
--
ALTER TABLE `mailixa_field_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `field_options_field_id_foreign` (`field_id`);

--
-- Indexes for table `ip_locations`
--
ALTER TABLE `mailixa_ip_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `mailixa_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_reserved_reserved_at_index` (`queue`,`reserved`,`reserved_at`);

--
-- Indexes for table `languages`
--
ALTER TABLE `mailixa_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `layouts`
--
ALTER TABLE `mailixa_layouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `links`
--
ALTER TABLE `mailixa_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `mailixa_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mail_lists`
--
ALTER TABLE `mailixa_mail_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mail_lists_contact_id_foreign` (`contact_id`),
  ADD KEY `mail_lists_user_id_foreign` (`user_id`);

--
-- Indexes for table `open_logs`
--
ALTER TABLE `mailixa_open_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `open_logs_message_id_foreign` (`message_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `mailixa_pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pages_mail_list_id_foreign` (`mail_list_id`),
  ADD KEY `pages_layout_id_foreign` (`layout_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `mailixa_password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `segments`
--
ALTER TABLE `mailixa_segments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `segments_mail_list_id_foreign` (`mail_list_id`);

--
-- Indexes for table `segment_conditions`
--
ALTER TABLE `mailixa_segment_conditions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `segment_conditions_segment_id_foreign` (`segment_id`),
  ADD KEY `segment_conditions_field_id_foreign` (`field_id`);

--
-- Indexes for table `sending_domains`
--
ALTER TABLE `mailixa_sending_domains`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sending_domains_user_id_foreign` (`user_id`);

--
-- Indexes for table `sending_servers`
--
ALTER TABLE `mailixa_sending_servers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sending_servers_user_id_foreign` (`user_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `mailixa_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `mailixa_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscribers_mail_list_id_foreign` (`mail_list_id`),
  ADD KEY `subscribers_user_id_foreign` (`user_id`);

--
-- Indexes for table `subscriber_fields`
--
ALTER TABLE `mailixa_subscriber_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriber_fields_subscriber_id_foreign` (`subscriber_id`),
  ADD KEY `subscriber_fields_field_id_foreign` (`field_id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `mailixa_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `templates_user_id_foreign` (`user_id`);

--
-- Indexes for table `tracking_logs`
--
ALTER TABLE `mailixa_tracking_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracking_logs_message_id_unique` (`message_id`),
  ADD KEY `tracking_logs_user_id_foreign` (`user_id`),
  ADD KEY `tracking_logs_sending_server_id_foreign` (`sending_server_id`),
  ADD KEY `tracking_logs_campaign_id_foreign` (`campaign_id`),
  ADD KEY `tracking_logs_subscriber_id_foreign` (`subscriber_id`);

--
-- Indexes for table `unsubscribe_logs`
--
ALTER TABLE `mailixa_unsubscribe_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unsubscribe_logs_message_id_foreign` (`message_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `mailixa_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_contact_id_foreign` (`contact_id`),
  ADD KEY `users_user_group_id_foreign` (`user_group_id`),
  ADD KEY `users_language_id_foreign` (`language_id`),
  ADD KEY `users_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `mailixa_user_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_groups_user_id_foreign` (`user_id`);

--
-- Indexes for table `user_group_sending_servers`
--
ALTER TABLE `mailixa_user_group_sending_servers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_group_settings`
--
ALTER TABLE `mailixa_user_group_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blacklists`
--
ALTER TABLE `mailixa_blacklists`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `bounce_handlers`
--
ALTER TABLE `mailixa_bounce_handlers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bounce_logs`
--
ALTER TABLE `mailixa_bounce_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `mailixa_campaigns`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `campaign_links`
--
ALTER TABLE `mailixa_campaign_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `click_logs`
--
ALTER TABLE `mailixa_click_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `mailixa_contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `mailixa_countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `feedback_logs`
--
ALTER TABLE `mailixa_feedback_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `feedback_loop_handlers`
--
ALTER TABLE `mailixa_feedback_loop_handlers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fields`
--
ALTER TABLE `mailixa_fields`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `field_options`
--
ALTER TABLE `mailixa_field_options`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ip_locations`
--
ALTER TABLE `mailixa_ip_locations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `mailixa_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `mailixa_languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `layouts`
--
ALTER TABLE `mailixa_layouts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `mailixa_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `mailixa_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `mail_lists`
--
ALTER TABLE `mailixa_mail_lists`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `open_logs`
--
ALTER TABLE `mailixa_open_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `mailixa_pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `segments`
--
ALTER TABLE `mailixa_segments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `segment_conditions`
--
ALTER TABLE `mailixa_segment_conditions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sending_domains`
--
ALTER TABLE `mailixa_sending_domains`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sending_servers`
--
ALTER TABLE `mailixa_sending_servers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `mailixa_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `mailixa_subscribers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `subscriber_fields`
--
ALTER TABLE `mailixa_subscriber_fields`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `mailixa_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tracking_logs`
--
ALTER TABLE `mailixa_tracking_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `unsubscribe_logs`
--
ALTER TABLE `mailixa_unsubscribe_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `mailixa_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `mailixa_user_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user_group_sending_servers`
--
ALTER TABLE `mailixa_user_group_sending_servers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_group_settings`
--
ALTER TABLE `mailixa_user_group_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `blacklists`
--
ALTER TABLE `mailixa_blacklists`
  ADD CONSTRAINT `blacklists_subscriber_id_foreign` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bounce_handlers`
--
ALTER TABLE `mailixa_bounce_handlers`
  ADD CONSTRAINT `bounce_handlers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bounce_logs`
--
ALTER TABLE `mailixa_bounce_logs`
  ADD CONSTRAINT `bounce_logs_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `tracking_logs` (`message_id`) ON DELETE CASCADE;

--
-- Constraints for table `campaigns`
--
ALTER TABLE `mailixa_campaigns`
  ADD CONSTRAINT `campaigns_mail_list_id_foreign` FOREIGN KEY (`mail_list_id`) REFERENCES `mail_lists` (`id`),
  ADD CONSTRAINT `campaigns_segment_id_foreign` FOREIGN KEY (`segment_id`) REFERENCES `segments` (`id`),
  ADD CONSTRAINT `campaigns_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `campaign_links`
--
ALTER TABLE `mailixa_campaign_links`
  ADD CONSTRAINT `campaign_links_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campaign_links_link_id_foreign` FOREIGN KEY (`link_id`) REFERENCES `links` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `click_logs`
--
ALTER TABLE `mailixa_click_logs`
  ADD CONSTRAINT `click_logs_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `tracking_logs` (`message_id`) ON DELETE CASCADE;

--
-- Constraints for table `contacts`
--
ALTER TABLE `mailixa_contacts`
  ADD CONSTRAINT `contacts_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback_logs`
--
ALTER TABLE `mailixa_feedback_logs`
  ADD CONSTRAINT `feedback_logs_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `tracking_logs` (`message_id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback_loop_handlers`
--
ALTER TABLE `mailixa_feedback_loop_handlers`
  ADD CONSTRAINT `feedback_loop_handlers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fields`
--
ALTER TABLE `mailixa_fields`
  ADD CONSTRAINT `fields_mail_list_id_foreign` FOREIGN KEY (`mail_list_id`) REFERENCES `mail_lists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `field_options`
--
ALTER TABLE `mailixa_field_options`
  ADD CONSTRAINT `field_options_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mail_lists`
--
ALTER TABLE `mailixa_mail_lists`
  ADD CONSTRAINT `mail_lists_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mail_lists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `open_logs`
--
ALTER TABLE `mailixa_open_logs`
  ADD CONSTRAINT `open_logs_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `tracking_logs` (`message_id`) ON DELETE CASCADE;

--
-- Constraints for table `pages`
--
ALTER TABLE `mailixa_pages`
  ADD CONSTRAINT `pages_layout_id_foreign` FOREIGN KEY (`layout_id`) REFERENCES `layouts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pages_mail_list_id_foreign` FOREIGN KEY (`mail_list_id`) REFERENCES `mail_lists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `segments`
--
ALTER TABLE `mailixa_segments`
  ADD CONSTRAINT `segments_mail_list_id_foreign` FOREIGN KEY (`mail_list_id`) REFERENCES `mail_lists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `segment_conditions`
--
ALTER TABLE `mailixa_segment_conditions`
  ADD CONSTRAINT `segment_conditions_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `segment_conditions_segment_id_foreign` FOREIGN KEY (`segment_id`) REFERENCES `segments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sending_domains`
--
ALTER TABLE `mailixa_sending_domains`
  ADD CONSTRAINT `sending_domains_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sending_servers`
--
ALTER TABLE `mailixa_sending_servers`
  ADD CONSTRAINT `sending_servers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscribers`
--
ALTER TABLE `mailixa_subscribers`
  ADD CONSTRAINT `subscribers_mail_list_id_foreign` FOREIGN KEY (`mail_list_id`) REFERENCES `mail_lists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscribers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriber_fields`
--
ALTER TABLE `mailixa_subscriber_fields`
  ADD CONSTRAINT `subscriber_fields_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriber_fields_subscriber_id_foreign` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `templates`
--
ALTER TABLE `mailixa_templates`
  ADD CONSTRAINT `templates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tracking_logs`
--
ALTER TABLE `mailixa_tracking_logs`
  ADD CONSTRAINT `tracking_logs_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tracking_logs_sending_server_id_foreign` FOREIGN KEY (`sending_server_id`) REFERENCES `sending_servers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tracking_logs_subscriber_id_foreign` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tracking_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `unsubscribe_logs`
--
ALTER TABLE `mailixa_unsubscribe_logs`
  ADD CONSTRAINT `unsubscribe_logs_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `tracking_logs` (`message_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `mailixa_users`
  ADD CONSTRAINT `users_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_user_group_id_foreign` FOREIGN KEY (`user_group_id`) REFERENCES `user_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_groups`
--
ALTER TABLE `mailixa_user_groups`
  ADD CONSTRAINT `user_groups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
