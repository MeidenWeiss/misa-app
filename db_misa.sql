-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2021 at 06:52 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_misa`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `admin_id` int(3) NOT NULL,
  `admin_fullname` varchar(30) NOT NULL,
  `admin_email` varchar(20) NOT NULL,
  `admin_password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `admin_fullname`, `admin_email`, `admin_password`) VALUES
(901, 'Mrs. Snow', 'snow@example.com', '4297f44b13955235245b2497399d7a93'),
(902, 'Fr. Felix Pasquin', 'felix@mail.com', '827ccb0eea8a706c4c34a16891f84e7b');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_appointments`
--

CREATE TABLE `tbl_appointments` (
  `appt_id` int(3) UNSIGNED NOT NULL,
  `appt_type` varchar(50) NOT NULL,
  `appt_priest` varchar(50) NOT NULL,
  `appt_client` varchar(50) NOT NULL,
  `contact_no` varchar(11) NOT NULL,
  `client_email` varchar(50) NOT NULL,
  `startDate` date NOT NULL,
  `startTime` time NOT NULL,
  `endDate` date NOT NULL,
  `endTime` time NOT NULL,
  `appt_note` varchar(1000) NOT NULL,
  `appt_status` varchar(30) NOT NULL,
  `pay_status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_appointments`
--

INSERT INTO `tbl_appointments` (`appt_id`, `appt_type`, `appt_priest`, `appt_client`, `contact_no`, `client_email`, `startDate`, `startTime`, `endDate`, `endTime`, `appt_note`, `appt_status`, `pay_status`) VALUES
(201, 'Baptism', 'Fr. Felix P. Pasquin', 'De la Cruz', '09876543210', 'cruz@mail.com', '2021-12-02', '10:30:00', '2021-12-02', '11:00:00', 'Scheduled Baptism talks with Mr. De La Cruz', 'RECURRING', 'NOT PAID'),
(217, 'Confession', 'Fr. Felix P. Pasquin', 'Sir Carlito ', '09812345678', 'carlito@mail.com', '2021-12-02', '13:30:00', '2021-12-02', '14:30:00', '', 'RECURRING', 'NOT PAID'),
(218, 'Renewal of Vows', 'Fr. Lucas', 'Mr. Tyler', '09876543212', 'tyler@mail.com', '2021-12-03', '09:30:00', '2021-12-03', '10:30:00', '', 'CANCELED', 'NOT PAID');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_log`
--

CREATE TABLE `tbl_log` (
  `log_id` int(4) UNSIGNED NOT NULL,
  `log_type` varchar(50) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `log_date` datetime NOT NULL,
  `log_desc` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_log`
--

INSERT INTO `tbl_log` (`log_id`, `log_type`, `admin_name`, `log_date`, `log_desc`) VALUES
(1001, 'POSTS', 'Ms. Denver', '2021-10-30 09:00:00', 'Post have been created.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_posts`
--

CREATE TABLE `tbl_posts` (
  `post_id` int(3) UNSIGNED NOT NULL,
  `post_title` varchar(50) NOT NULL,
  `post_desc` varchar(1000) NOT NULL,
  `datetime_posted` datetime NOT NULL,
  `postedBy` varchar(50) NOT NULL,
  `post_type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_posts`
--

INSERT INTO `tbl_posts` (`post_id`, `post_title`, `post_desc`, `datetime_posted`, `postedBy`, `post_type`) VALUES
(301, 'No Afternoon Friday Mass', 'This December 3, Friday masses will only be held in the morning.', '2021-09-29 17:43:16', 'Fr. Gregory', 'Announcements'),
(302, 'Daily Prayer', '\"Be STILL and know that I Am GOD\"...\r\n(Psalm 46:10)\r\n\r\nOur serene moments with GOD...\r\n\r\nTen Ways to a DIVINE LIFE:\r\nListen without interrupting.\r\nSpeak without accusing.\r\nGive without sparing.\r\nPray without ceasing.\r\nAnswer without arguing.\r\nShare without pretending.\r\nEnjoy without complaint.\r\nTrust without wavering.\r\nForgive without punishing.\r\nPromise without forgetting.\r\nHappy Sunday!', '2021-09-29 17:45:36', 'Fr. Felix P. Pasquin', 'Prayer'),
(303, 'Church Capacity', 'Our Lady of Candles Parish will only allow a maximum of 50 attendees every official scheduled mass to comply with health and safety protocols.', '2021-10-04 12:29:23', 'Ms. Flores', 'Announcements'),
(304, 'Updated Office Hours', 'The new office hours for this month of October:\r\n\r\n(Open for inquires) 10:00 AM - 4:30 PM Everyday\r\n\r\nNote: The office may be open but is closed for inquiries during and after the 6:30 PM evening masses.\r\n', '2021-10-04 12:39:27', 'Ms. Denver', 'Announcements'),
(305, 'Reminder for our fellow parishioners', 'Remember to practice physical distancing within church premises.\r\nAlways wear your face mask before, during and after mass hours.', '2021-10-04 13:36:30', 'Ms. Flores', 'Announcements');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_requests`
--

CREATE TABLE `tbl_requests` (
  `req_id` int(3) UNSIGNED NOT NULL,
  `req_type` varchar(30) NOT NULL,
  `req_priest` varchar(50) NOT NULL,
  `req_client` varchar(50) NOT NULL,
  `contact_no` varchar(11) NOT NULL,
  `client_email` varchar(50) NOT NULL,
  `startDate` date NOT NULL,
  `startTime` time NOT NULL,
  `endDate` date NOT NULL,
  `endTime` time NOT NULL,
  `req_note` varchar(1000) NOT NULL,
  `req_status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_requests`
--

INSERT INTO `tbl_requests` (`req_id`, `req_type`, `req_priest`, `req_client`, `contact_no`, `client_email`, `startDate`, `startTime`, `endDate`, `endTime`, `req_note`, `req_status`) VALUES
(401, 'Scheduled Mass', 'Fr. Felix P. Pasquin', 'Sanny Rey Jover', '09876543210', 'rey@mail.com', '2021-12-02', '11:00:00', '2021-12-02', '12:30:00', 'Birthday mass for Rey.', 'PENDING'),
(413, 'Funerals', 'Fr. Gregory', 'John Xina', '12345678901', 'xina@mail.com', '2021-12-03', '16:30:00', '2021-12-03', '17:30:00', 'Mr. Xina would like to talk about the funeral schedule.', 'PENDING'),
(414, 'Confession', 'Fr. Felix P. Pasquin', 'Ms. Macbeth', '09812376541', 'beth@mail.com', '2021-12-02', '13:30:00', '2021-12-02', '14:30:00', '', 'PENDING'),
(415, 'Baptism', 'Fr. Felix P. Pasquin', 'De la Cruz', '09876543210', 'cruz@mail.com', '2021-12-02', '10:30:00', '2021-12-02', '11:00:00', 'Scheduled Baptism talks with Mr. De La Cruz', 'APPROVED'),
(416, 'Confession', 'Sir Carlito', 'Fr. Felix P. Pasquin', '09812345678', 'carlito@mail.com', '2021-12-02', '13:30:00', '2021-12-02', '14:30:00', '', 'APPROVED'),
(417, 'Renewal of Vows', 'Fr. Felix P. Pasquin', 'Jamal', '09786534210', 'jamal@mail.com', '2021-12-01', '15:00:00', '2021-12-01', '16:30:00', '', 'CANCELED');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_schedules`
--

CREATE TABLE `tbl_schedules` (
  `sched_id` int(3) UNSIGNED NOT NULL,
  `sched_title` varchar(50) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `category` varchar(30) NOT NULL,
  `sched_priest` varchar(50) NOT NULL,
  `client_name` varchar(50) NOT NULL,
  `contact_no` varchar(11) NOT NULL,
  `client_email` varchar(50) NOT NULL,
  `startDate` date NOT NULL,
  `startTime` time NOT NULL,
  `endDate` date NOT NULL,
  `endTime` time NOT NULL,
  `sched_note` varchar(1000) NOT NULL,
  `sched_status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_schedules`
--

INSERT INTO `tbl_schedules` (`sched_id`, `sched_title`, `event_type`, `category`, `sched_priest`, `client_name`, `contact_no`, `client_email`, `startDate`, `startTime`, `endDate`, `endTime`, `sched_note`, `sched_status`) VALUES
(701, 'Scheduled Private Mass', 'Scheduled Mass', 'Private', 'Fr. Felix P. Pasquin', 'Mr. Aguire', '09876543210', 'carl@mail.com', '2021-11-10', '09:00:00', '2021-11-10', '11:00:00', '', 'DONE'),
(702, 'Public Confession Session', 'Confession', 'Public', 'Fr. Felix P. Pasquin', 'Ms. Denver', '12345678901', 'user2@mail.com', '2021-12-02', '15:30:00', '2021-12-02', '17:30:00', 'Please wear a facemask when entering the church premises.\r\n\r\nObserve proper physical distancing.', 'SCHEDULED'),
(703, 'Wedding of Ms. Flores', 'Weddings', 'Private', 'Fr. Felix P. Pasquin', 'Ms. Flores', '09194886719', 'flores@mail.com', '2021-12-03', '16:30:00', '2021-12-03', '17:30:00', 'Invited guests entry only.', 'SCHEDULED');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tbl_appointments`
--
ALTER TABLE `tbl_appointments`
  ADD PRIMARY KEY (`appt_id`);

--
-- Indexes for table `tbl_log`
--
ALTER TABLE `tbl_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `tbl_posts`
--
ALTER TABLE `tbl_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `tbl_requests`
--
ALTER TABLE `tbl_requests`
  ADD PRIMARY KEY (`req_id`);

--
-- Indexes for table `tbl_schedules`
--
ALTER TABLE `tbl_schedules`
  ADD PRIMARY KEY (`sched_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=903;

--
-- AUTO_INCREMENT for table `tbl_appointments`
--
ALTER TABLE `tbl_appointments`
  MODIFY `appt_id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT for table `tbl_log`
--
ALTER TABLE `tbl_log`
  MODIFY `log_id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1002;

--
-- AUTO_INCREMENT for table `tbl_posts`
--
ALTER TABLE `tbl_posts`
  MODIFY `post_id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=306;

--
-- AUTO_INCREMENT for table `tbl_requests`
--
ALTER TABLE `tbl_requests`
  MODIFY `req_id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=418;

--
-- AUTO_INCREMENT for table `tbl_schedules`
--
ALTER TABLE `tbl_schedules`
  MODIFY `sched_id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=704;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
