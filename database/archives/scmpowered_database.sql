-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 07, 2023 at 01:14 PM
-- Server version: 8.0.34
-- PHP Version: 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scmpowered_scm_comp`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `user_id`, `message`, `created_at`) VALUES
(30, 2, 'What is Brandon\'s hourly rate?', '2023-04-13 19:28:21'),
(31, 1, 'Lemme look', '2023-04-13 19:28:45');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `database_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `company_name`, `logo`, `database_name`, `password`, `username`, `created_at`) VALUES
(1, 'Amazon', '/company_logo/c1.jpeg', 'scmpowered_demo', 'Salvage_1101', 'scmpowered_demo', '2023-07-06 07:17:38'),
(2, 'flipkart', '/company_logo/c2.png', 'scmpowered_demo2', 'L@42Hj$MC~bM', 'scmpowered_demo2', '2023-07-06 07:17:47'),
(3, 'Salvage', '/company_logo/scm.png', 'scmpowered_salvage', 'Salvage_1101', 'scmpowered_salvage', '2023-07-22 16:27:40');

-- --------------------------------------------------------

--
-- Table structure for table `construction_expenses`
--

CREATE TABLE `construction_expenses` (
  `id` int NOT NULL,
  `transaction_type` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `business` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` float NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `project_id` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `construction_expenses`
--

INSERT INTO `construction_expenses` (`id`, `transaction_type`, `date`, `business`, `description`, `amount`, `employee_id`, `project_id`) VALUES
(11, 'Fuel', '2023-02-27', 'Kwik Trip', 'Def Fuel', 18.98, 'Jacob', 2),
(10, 'Jobsite Expense', '2023-03-13', 'Menards', 'Job Supplies', 42.41, 'Josh', 1),
(9, 'Fuel', '2023-03-16', 'Kwik Trip', 'fuel card', 69.5, 'Josh', 1),
(13, 'Disposal', '2023-03-08', 'GFL - Zion Dump', 'Debris Disposal', 140.59, 'Jacob', 3),
(14, 'Disposal', '2023-03-08', 'GFL - Zion Dump', 'Debris Disposal', 90.52, 'Jacob', 3),
(15, 'Disposal', '2023-02-27', 'GFL - Zion Dump', 'Debris Disposal', 216.93, 'Jacob', 3),
(16, 'Disposal', '2023-03-06', 'GFL - Zion Dump', 'Debris Disposal', 215.13, 'Jacob', 3),
(17, 'Disposal', '2023-03-07', 'GFL - Zion Dump', 'Debris Disposal', 175.72, 'Jacob', 3),
(18, 'Disposal', '2023-02-28', 'GFL - Zion Dump', 'Debris Disposal', 290.73, 'Jacob', 3),
(19, 'Disposal', '2023-02-28', 'GFL - Zion Dump', 'Debris Disposal', 203.41, 'Jacob', 3),
(20, 'Disposal', '2023-03-01', 'GFL - Zion Dump', 'Debris Disposal', 321.85, 'Jacob', 3),
(21, 'Disposal', '2023-03-02', 'GFL - Zion Dump', 'Debris Disposal', 282.21, 'Jacob', 3),
(22, 'Fuel', '2023-03-08', 'Kwik Trip', 'Dodge/ Fuel Card', 58.27, 'Jacob', 3),
(23, 'Fuel', '2023-03-07', 'Kwik Trip', 'Dodge/ Fuel Card', 73, 'Jacob', 3),
(24, 'Fuel', '2023-03-06', 'Kwik Trip', 'Dodge/ Fuel Card', 74.84, 'Jacob', 3),
(25, 'Fuel', '2023-03-02', 'Kwik Trip', 'Dodge/ Fuel Card', 70.4, 'Jacob', 3),
(26, 'Fuel', '2023-03-01', 'Kwik Trip', 'Dodge/ Fuel Card', 68.31, 'Jacob', 3),
(27, 'Fuel', '2023-02-27', 'Kwik Trip', 'Dodge/ Fuel Card', 58.6, 'Jacob', 3),
(30, 'Fuel', '2023-03-20', 'Elgin Shell', 'Dodge/ Fuel Card', 81.77, 'Josh', 1),
(31, 'General Tools', '2023-03-06', 'Menards', 'Supplies, i.e. gloves ,elect. Tape ,gloves, razor blades, totes', 38.59, 'Josh', 1),
(33, 'Fuel', '2023-03-22', 'Kiki trip', 'Red truck', 35, 'Jacob', 1),
(52, 'Fuel', '2023-03-21', 'Kwik Trip', 'Fuel/ Red Truck', 70.37, 'DJ', 1),
(53, 'Fuel', '2023-03-15', 'Kwik Trip', 'Dodge/ Fuel Card', 72.51, 'Josh', 1),
(54, 'Fuel', '2023-03-16', 'Kwik Trip', 'Dodge/ Def Fluid', 18.98, 'Josh', 1),
(55, 'Fuel', '2023-03-15', 'Kwik Trip', 'Dodge/ Antifreeze', 14.76, 'Jacob', 1),
(56, 'Fuel', '2023-03-16', 'Kwik Trip', 'Dodge/ Fuel Card', 69.5, 'Jacob', 1),
(57, 'Disposal', '2023-02-21', 'Janesville Sanitary Landfill', 'Debris Disposal', 64.27, 'Shane', 2),
(62, 'Supplies', '2023-03-08', 'Uhaul', 'Propane Refill', 30.16, 'Jacob', 2);

-- --------------------------------------------------------

--
-- Table structure for table `contractors`
--

CREATE TABLE `contractors` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(25) NOT NULL,
  `state` varchar(2) NOT NULL,
  `zip` int NOT NULL,
  `phone` varchar(11) NOT NULL,
  `logo` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contractors`
--

INSERT INTO `contractors` (`id`, `name`, `address`, `city`, `state`, `zip`, `phone`, `logo`) VALUES
(1, 'NCI Roberts', '701 E. Washington Ave. #103', 'Madison', 'WI', 53703, '6082570500', 'nci-roberts-logo.png'),
(2, 'Gilbank', '301 Scot Drive', 'Clinton', 'WI', 53525, '6086762261', 'gilbank.png'),
(3, 'Midwest Construction Partners', '1300 Woodfield Rd Suite 150', 'Schaumburg', 'IL', 60173, '8472397515', 'mcp-logo.png'),
(4, 'Innovative Construction Solutions', 'N19W24101 Riverwood Dr Ste 100', 'Waukesha', 'WI', 53188, '2627901911', 'ics-logo.jpg'),
(5, 'Jackovic Construction', '300 Mt. Lebanon Boulevard Suite 211-A', 'Pittsburgh', 'PA', 15234, '4126680186', 'jackovic-logo.png'),
(6, 'Pinnacle Construction', 'S74 W16853 Janesville Rd', 'Muskego', 'WI', 53150, '4143693514', 'pinnacle-logo.jpg'),
(7, 'Sjostrom Construction', '129 Harrison Avenue', 'Rockford', 'IL', 61104, '8152260330', 'sjostrom-logo.png'),
(8, 'DeJager Construction Inc', '75  60th St SW, MI  49548', 'Wyoming', 'MI', 49548, '6165300060', 'De-Jager-Logo.png'),
(9, '1848 Construction', '3302 Latham Dr', 'Madison', 'WI', 53713, '6088331848', '1848-logo.png'),
(10, 'CCI', '3800 Milwaukee Rd Suite 200', 'Beloit', 'WI', 53511, '6083622912', 'cci-logo.png'),
(11, 'Horizon', '9999 E. Exploration Court', 'Sturtevant', 'WI', 53177, '2626386000', 'horizon-logo.png'),
(12, 'BCM', '1603 Orrington Avenue Suite 450', 'Evanston', 'IL', 60201, '2249275056', 'bcm-logo.png'),
(13, 'JD Commercial Builders', 'PO Box 6479', 'Bend', 'OR', 97708, '9492918246', 'jd-builders-logo.png'),
(14, 'WDS Construction', '100 Tower Drive', 'Beaver Dam', 'WI', 53916, '9203561255', 'wds-logo.png');

-- --------------------------------------------------------

--
-- Table structure for table `daily_logs`
--

CREATE TABLE `daily_logs` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `user_id` int NOT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'employee' COMMENT ' admin , employee',
  `content` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `timestampss` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `daily_logs`
--

INSERT INTO `daily_logs` (`id`, `project_id`, `user_id`, `user_type`, `content`, `date`, `timestampss`) VALUES
(36, 1, 1, 'employee', 'Log 2/2 - Tile is over halfway chipped, dropped ceiling grid   in teller drive thru, working on drywall yet', '2023-08-07 13:08:09', '1686572257'),
(33, 1, 1, 'employee', 'Started demo and dismantling office furniture and walls', '2023-08-07 13:08:13', '1686572257'),
(35, 1, 1, 'employee', 'Log 1/2 - Finished 2nd floor glass removal, removed grid and tiles from 4 offices. Finished moving bank furniture and cubicals to basement. Started demo on walls just before lunch.', '2023-08-07 13:08:17', '1686572257');

-- --------------------------------------------------------

--
-- Table structure for table `device_detail`
--

CREATE TABLE `device_detail` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL,
  `device_type` varchar(150) NOT NULL,
  `device_token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `dob` varchar(20) NOT NULL,
  `address` varchar(50) NOT NULL,
  `latitude` double NOT NULL DEFAULT '0',
  `longitude` double NOT NULL DEFAULT '0',
  `city` varchar(20) NOT NULL,
  `state` varchar(2) NOT NULL,
  `zip` int NOT NULL,
  `phone` varchar(11) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(100) NOT NULL,
  `hire_date` varchar(20) NOT NULL,
  `role` int NOT NULL,
  `department` int NOT NULL,
  `profile` varchar(50) NOT NULL,
  `status` int NOT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `first_name`, `last_name`, `dob`, `address`, `latitude`, `longitude`, `city`, `state`, `zip`, `phone`, `email`, `password`, `hire_date`, `role`, `department`, `profile`, `status`, `token`) VALUES
(1, 'David', 'Wood', '1986-12-29', '1015 N Willard Ave', 0, 0, 'Janesville', 'WI', 53548, '5759420611', 'david@salvagecontractors.net', '5f4dcc3b5aa765d61d8327deb882cf99', '2018-11-01', 1, 1, '1679929609.jpeg', 1, '81a9dbc61cc8fbdad8404d524925bc41'),
(4, 'Elise', 'Maynard', '2003-02-23', '703 first center ave', 0, 0, 'Brodhead', 'WI', 53520, '6082909838', 'elise@salvagecontractors.net', '1234567', '2023-01-03', 6, 1, '1679932262.jpeg', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `number` int NOT NULL,
  `amount` int NOT NULL,
  `payment` int NOT NULL,
  `date` varchar(15) NOT NULL,
  `project_id` int NOT NULL,
  `contractor` int NOT NULL,
  `file` varchar(25) NOT NULL,
  `status` int NOT NULL,
  `division` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `number`, `amount`, `payment`, `date`, `project_id`, `contractor`, `file`, `status`, `division`) VALUES
(3, 1123, 9464, 0, '2023-03-24', 1, 1, 'Invoice 1123.pdf', 1, 1),
(4, 1128, 9300, 0, '2023-04-27', 1, 1, 'invoice 1128.pdf', 1, 1),
(12, 1136, 10360, 0, '2023-06-21', 1, 0, 'Invoice 1136.pdf', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int NOT NULL,
  `user` int NOT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user`, `action`, `timestamp`) VALUES
(1, 1, 'added transaction for project NCI Office', '0000-00-00 00:00:00'),
(3, 1, 'edited transaction 29 for project NCI Office', '2023-03-20 15:24:18'),
(9, 4, 'added transaction for project NCI Office', '2023-03-20 19:01:33'),
(10, 2, 'added transaction for project NCI Office', '2023-03-20 21:21:44'),
(11, 2, 'added transaction for project NCI Office', '2023-03-23 16:35:40'),
(12, 2, 'added transaction for project NCI Office', '2023-03-23 16:36:24'),
(13, 4, 'added transaction for project NCI Office', '2023-03-23 16:42:27'),
(14, 4, 'added transaction for project NCI Office', '2023-03-23 16:44:01'),
(15, 4, 'added transaction for project NCI Office', '2023-03-23 16:44:56'),
(16, 4, 'added transaction for project NCI Office', '2023-03-23 16:45:40'),
(17, 4, 'added transaction for project NCI Office', '2023-03-23 16:46:29'),
(18, 4, 'added transaction for project NCI Office', '2023-03-23 16:47:06');

-- --------------------------------------------------------

--
-- Table structure for table `log_photos`
--

CREATE TABLE `log_photos` (
  `id` int NOT NULL,
  `daily_log_id` int NOT NULL,
  `file_name` longtext NOT NULL,
  `thumbnail_name` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `employee_id` varchar(255) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` longtext NOT NULL,
  `timestamp` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `employee_id`, `title`, `description`, `timestamp`, `created_at`, `updated_at`) VALUES
(15, '27', 'Reminder', 'Remember to upload your daily log today!', '1689976046', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, '27', 'New Project Added!', 'You have been added to Fall River Foundry', '1690892376', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, '27', 'Reminder!', 'Don\'t forget todays daily log!', '1691105668', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `address` varchar(250) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(2) NOT NULL,
  `zip` varchar(5) NOT NULL,
  `latitude` double NOT NULL DEFAULT '0',
  `longitude` double NOT NULL DEFAULT '0',
  `contractor` varchar(200) NOT NULL,
  `budget` varchar(50) NOT NULL,
  `start_date` varchar(50) NOT NULL,
  `end_date` varchar(50) NOT NULL,
  `super_name` varchar(20) NOT NULL,
  `super_phone` varchar(15) NOT NULL,
  `pm_name` varchar(20) NOT NULL,
  `pm_phone` varchar(15) NOT NULL,
  `pm` int NOT NULL,
  `foreman` varchar(50) NOT NULL,
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `address`, `city`, `state`, `zip`, `latitude`, `longitude`, `contractor`, `budget`, `start_date`, `end_date`, `super_name`, `super_phone`, `pm_name`, `pm_phone`, `pm`, `foreman`, `status`) VALUES
(1, 'NCI Office', '455 S Jct Rd', 'Madison', 'WI', '53719', 43.02756, -89.50153, '1', '10000', '2023-03-27', '2023-05-05', '', '0', 'Scott Johnson', '6084401883', 14, '27', 2),
(2, 'TKO', '2205 Point Blvd #100-170', 'Elgin', 'IL', '60123', 43.02756, -89.50153, '3', '37400', '2023-01-16', '2023-02-03', 'Matthew Knapik', '2147483647', 'Zach Morris', '2147483647', 14, '13', 2),
(3, 'Five Below - Pleasant Prairie', '11211 120th Ave', 'Pleasant Prairie', 'WI', '52158', 0, 0, '5', '20000', '2023-02-27', '2023-03-08', 'Jonathan Jester', '2147483647', 'Debra Geyer', '2147483647', 14, '13', 2);

-- --------------------------------------------------------

--
-- Table structure for table `project_assign`
--

CREATE TABLE `project_assign` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `project_assign`
--

INSERT INTO `project_assign` (`id`, `project_id`, `employee_id`, `status`, `created_at`) VALUES
(15, 3, 13, 2, '2023-06-08 06:03:19'),
(14, 2, 13, 2, '2023-06-08 06:02:39'),
(13, 1, 1, 1, '2023-07-19 23:16:11');

-- --------------------------------------------------------

--
-- Table structure for table `todo`
--

CREATE TABLE `todo` (
  `id` int NOT NULL,
  `item` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `todo`
--

INSERT INTO `todo` (`id`, `item`, `user_id`, `date`) VALUES
(26, 'interview @ 9am  junios norfleet', 1, '2023-08-07 13:13:55'),
(25, 'interview @ 4pm  daniel arneson', 1, '2023-08-07 13:14:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` char(250) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `profile` varchar(100) NOT NULL,
  `title` varchar(20) NOT NULL,
  `permissions` int NOT NULL,
  `api_key` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `fname`, `lname`, `email`, `profile`, `title`, `permissions`, `api_key`) VALUES
(1, 'David', '$2y$10$0j48z2kVERSOUQj953XKee0iXhq4FT4ZHjJW0zBRk8WkOENmd1D2K', 'David', 'Wood', 'david@salvagecontractors.net', '1679929609.jpeg', 'President', 1, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `construction_expenses`
--
ALTER TABLE `construction_expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contractors`
--
ALTER TABLE `contractors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daily_logs`
--
ALTER TABLE `daily_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_detail`
--
ALTER TABLE `device_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_photos`
--
ALTER TABLE `log_photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_assign`
--
ALTER TABLE `project_assign`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `todo`
--
ALTER TABLE `todo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `construction_expenses`
--
ALTER TABLE `construction_expenses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=380;

--
-- AUTO_INCREMENT for table `contractors`
--
ALTER TABLE `contractors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `daily_logs`
--
ALTER TABLE `daily_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=425;

--
-- AUTO_INCREMENT for table `device_detail`
--
ALTER TABLE `device_detail`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=646;

--
-- AUTO_INCREMENT for table `log_photos`
--
ALTER TABLE `log_photos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=391;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `project_assign`
--
ALTER TABLE `project_assign`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `todo`
--
ALTER TABLE `todo`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
