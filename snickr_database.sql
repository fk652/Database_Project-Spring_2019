-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Aug 21, 2019 at 01:17 AM
-- Server version: 5.6.34
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `snickr_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `channels`
--

CREATE TABLE `channels` (
  `channel_name` varchar(255) NOT NULL,
  `workspace_id` int(11) NOT NULL,
  `channel_owner` varchar(255) NOT NULL,
  `channel_type` varchar(10) NOT NULL,
  `ch_created_timedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `channels`
--

INSERT INTO `channels` (`channel_name`, `workspace_id`, `channel_owner`, `channel_type`, `ch_created_timedate`) VALUES
('direct', 7, 'fk652@nyu.edu', 'direct', '2019-05-06 12:19:10'),
('earth1', 11, 'test@gmail.com', 'public', '2019-05-08 19:57:05'),
('general', 8, 'fk652@nyu.edu', 'public', '2019-05-05 14:18:53'),
('general world', 10, 'test@yahoo.com', 'public', '2019-05-09 03:23:16'),
('private', 9, 'fk652@nyu.edu', 'private', '2019-05-05 22:26:14'),
('privatedemo', 7, 'fk652@nyu.edu', 'private', '2019-05-10 19:31:31');

-- --------------------------------------------------------

--
-- Table structure for table `channel_invites`
--

CREATE TABLE `channel_invites` (
  `workspace_id` int(11) NOT NULL,
  `channel_name` varchar(255) NOT NULL,
  `chi_receiver` varchar(255) NOT NULL,
  `chi_invite_timedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `chi_sender` varchar(255) NOT NULL,
  `chi_status` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `channel_invites`
--

INSERT INTO `channel_invites` (`workspace_id`, `channel_name`, `chi_receiver`, `chi_invite_timedate`, `chi_sender`, `chi_status`) VALUES
(7, 'direct', 'test2@gmail.com', '2019-05-08 19:45:14', 'fk652@nyu.edu', 0),
(7, 'privatedemo', 'demo@gmail.com', '2019-05-10 19:32:57', 'fk652@nyu.edu', 0),
(8, 'general', 'test2@gmail.com', '2019-05-05 15:18:49', 'fk652@nyu.edu', 0),
(8, 'general', 'test3@gmail.com', '2019-05-05 22:25:29', 'fk652@nyu.edu', 3),
(8, 'general', 'test@aol.com', '2019-05-05 19:27:51', 'fk652@nyu.edu', 1),
(8, 'general', 'test@aol.com', '2019-05-06 00:41:02', 'fk652@nyu.edu', 1),
(8, 'general', 'test@gmail.com', '2019-05-05 22:25:42', 'fk652@nyu.edu', 1),
(8, 'general', 'test@yahoo.com', '2019-05-05 22:25:48', 'fk652@nyu.edu', 0),
(9, 'private', 'test2@gmail.com', '2019-05-05 22:26:29', 'fk652@nyu.edu', 0),
(9, 'private', 'test@aol.com', '2019-05-05 22:26:35', 'fk652@nyu.edu', 1),
(9, 'private', 'test@aol.com', '2019-05-06 00:36:35', 'fk652@nyu.edu', 1),
(9, 'private', 'test@yahoo.com', '2019-05-05 22:26:42', 'fk652@nyu.edu', 0);

-- --------------------------------------------------------

--
-- Table structure for table `channel_members`
--

CREATE TABLE `channel_members` (
  `email` varchar(255) NOT NULL,
  `channel_name` varchar(255) NOT NULL,
  `workspace_id` int(11) NOT NULL,
  `chm_added_timedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `channel_members`
--

INSERT INTO `channel_members` (`email`, `channel_name`, `workspace_id`, `chm_added_timedate`) VALUES
('fk652@nyu.edu', 'direct', 7, '2019-05-06 12:19:10'),
('fk652@nyu.edu', 'general', 8, '2019-05-05 14:18:53'),
('fk652@nyu.edu', 'private', 9, '2019-05-05 22:26:14'),
('fk652@nyu.edu', 'privatedemo', 7, '2019-05-10 19:31:31'),
('test3@gmail.com', 'general', 8, '2019-05-06 00:18:44'),
('test@aol.com', 'private', 9, '2019-05-06 00:38:52'),
('test@gmail.com', 'earth1', 11, '2019-05-08 19:57:05'),
('test@gmail.com', 'general', 8, '2019-05-10 02:16:47'),
('test@yahoo.com', 'general world', 10, '2019-05-09 03:23:16');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `posted_timedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `workspace_id` int(11) NOT NULL,
  `channel_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `message_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`posted_timedate`, `workspace_id`, `channel_name`, `email`, `message`, `message_id`) VALUES
('2019-05-06 15:51:53', 8, 'general', 'fk652@nyu.edu', 'hello', 2),
('2019-05-06 15:52:31', 8, 'general', 'fk652@nyu.edu', 'this is Fahim', 3),
('2019-05-06 15:52:41', 8, 'general', 'fk652@nyu.edu', 'I\'m testing this', 4),
('2019-05-06 15:53:18', 8, 'general', 'fk652@nyu.edu', 'random stuff', 5),
('2019-05-06 15:57:55', 8, 'general', 'fk652@nyu.edu', 'hi again', 6),
('2019-05-06 16:01:05', 8, 'general', 'test@gmail.com', 'hello this is jon', 7),
('2019-05-06 16:01:45', 8, 'general', 'test@gmail.com', 'nice to meet you', 8),
('2019-05-06 16:05:48', 8, 'general', 'test@gmail.com', 'spamming', 9),
('2019-05-06 16:05:50', 8, 'general', 'test@gmail.com', 'spamming', 10),
('2019-05-06 16:05:50', 8, 'general', 'test@gmail.com', 'spamming', 11),
('2019-05-06 16:05:50', 8, 'general', 'test@gmail.com', 'spamming', 12),
('2019-05-06 16:05:50', 8, 'general', 'test@gmail.com', 'spamming', 13),
('2019-05-06 16:05:50', 8, 'general', 'test@gmail.com', 'spamming', 14),
('2019-05-06 16:05:51', 8, 'general', 'test@gmail.com', 'spamming', 15),
('2019-05-06 16:05:51', 8, 'general', 'test@gmail.com', 'spamming', 16),
('2019-05-06 16:05:51', 8, 'general', 'test@gmail.com', 'spamming', 17),
('2019-05-06 16:05:51', 8, 'general', 'test@gmail.com', 'spamming', 18),
('2019-05-06 16:05:51', 8, 'general', 'test@gmail.com', 'spamming', 19),
('2019-05-06 16:05:51', 8, 'general', 'test@gmail.com', 'spamming', 20),
('2019-05-06 16:05:52', 8, 'general', 'test@gmail.com', 'spamming', 21),
('2019-05-06 16:05:56', 8, 'general', 'test@gmail.com', 'spamming', 22),
('2019-05-06 16:17:30', 8, 'general', 'test@gmail.com', 'he', 23),
('2019-05-06 16:23:33', 8, 'general', 'test@gmail.com', 'testing', 24),
('2019-05-06 16:25:42', 8, 'general', 'test@gmail.com', 'bye', 25),
('2019-05-06 19:28:12', 8, 'general', 'fk652@nyu.edu', 'testing new', 26),
('2019-05-06 19:28:25', 8, 'general', 'fk652@nyu.edu', 'testing new', 27),
('2019-05-06 19:59:26', 8, 'general', 'fk652@nyu.edu', 'is this working', 28),
('2019-05-06 20:42:34', 8, 'general', 'fk652@nyu.edu', 'asd', 29),
('2019-05-06 20:43:54', 8, 'general', 'fk652@nyu.edu', 'hi', 30),
('2019-05-06 20:44:04', 8, 'general', 'fk652@nyu.edu', 'hio', 31),
('2019-05-06 20:45:04', 8, 'general', 'fk652@nyu.edu', 'hi', 32),
('2019-05-06 20:48:05', 8, 'general', 'fk652@nyu.edu', 'test', 33),
('2019-05-06 20:51:42', 8, 'general', 'fk652@nyu.edu', 'test', 34),
('2019-05-06 20:52:28', 8, 'general', 'fk652@nyu.edu', 'test', 35),
('2019-05-06 20:53:33', 8, 'general', 'fk652@nyu.edu', 'test', 36),
('2019-05-06 20:58:09', 8, 'general', 'fk652@nyu.edu', 'f\na', 37),
('2019-05-06 20:59:45', 8, 'general', 'fk652@nyu.edu', 'd\na', 38),
('2019-05-06 21:03:06', 8, 'general', 'fk652@nyu.edu', 'f\nf\nf\nf\nf\nf\n', 39),
('2019-05-06 21:07:07', 8, 'general', 'fk652@nyu.edu', '	this is my paragraph\nhello', 40),
('2019-05-06 21:19:51', 8, 'general', 'fk652@nyu.edu', 'send', 41),
('2019-05-06 21:22:34', 8, 'general', 'fk652@nyu.edu', 'kk', 42),
('2019-05-07 00:07:32', 8, 'general', 'fk652@nyu.edu', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 44),
('2019-05-07 00:11:08', 8, 'general', 'fk652@nyu.edu', 'a\ndddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd\n	b', 45),
('2019-05-07 19:12:22', 8, 'general', 'fk652@nyu.edu', 'testing', 46),
('2019-05-07 21:13:41', 8, 'general', 'test@gmail.com', 'Hi', 47),
('2019-05-07 21:13:58', 8, 'general', 'fk652@nyu.edu', 'hello', 48),
('2019-05-09 04:28:59', 9, 'private', 'test@aol.com', 'first one here!\n', 49),
('2019-05-10 19:35:54', 8, 'general', 'fk652@nyu.edu', 'hi\n', 50),
('2019-08-10 04:40:38', 7, 'direct', 'fk652@nyu.edu', 'hello\n', 51);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `email` varchar(255) NOT NULL,
  `u_date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `u_first_name` varchar(255) NOT NULL,
  `u_last_name` varchar(255) NOT NULL,
  `u_nickname` varchar(255) NOT NULL,
  `u_password` varchar(255) NOT NULL,
  `active` bit(1) NOT NULL DEFAULT b'0',
  `last_active_timedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_description` text,
  `contact_info` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`email`, `u_date_created`, `u_first_name`, `u_last_name`, `u_nickname`, `u_password`, `active`, `last_active_timedate`, `profile_description`, `contact_info`) VALUES
('demo@gmail.com', '2019-05-10 19:24:37', 'John', 'Doe', 'jd123', '$2y$10$JCjpKth3J24Aqma02KfAieVXMLzQL5XuGD/VMfzI8JL28ToIqKJIe', b'0', '2019-05-10 19:24:37', NULL, NULL),
('fk652@nyu.edu', '2019-05-04 17:40:08', 'Fahim', 'Khan', 'fk652', '$2y$10$Xv5J36ZhSSMzkb2il2ECjeM.jstM7cAoLt3jvh4gih.eZE7E6lAw.', b'0', '2019-05-04 17:40:08', 'This is my profile description', '123-456-7890'),
('random@gmail.com', '2019-05-10 19:05:49', 'John', 'Steel', 'JS123', '$2y$10$4oMWOuHoVyryXKMS1K0pQOXi5NDIz1.yFiGpdjWRHyOX5F3ogqc8.', b'0', '2019-05-10 19:05:49', NULL, NULL),
('test2@gmail.com', '2019-05-04 19:46:38', 'Jane', 'Doe', 'test3', '$2y$10$UOIGB3J.VnqGDA1FDGFPculAnh4QTCww96Pk7DwKbnOz4/KxU/lPe', b'0', '2019-05-04 19:46:38', NULL, NULL),
('test3@gmail.com', '2019-05-04 19:47:48', 'John', 'Does', 'test5', '$2y$10$Pr7XIyrlfjMknIdOY3hvpeUg1tp9ArmHtzAuCYV9V9elSLiGTi2ZW', b'0', '2019-05-04 19:47:48', NULL, NULL),
('test@aol.com', '2019-05-04 19:45:01', 'Jane', 'Doe', 'test3', '$2y$10$qHEsRxaBIur111nM7GlrNekFseOkteRF3P3cl3OJ4z/HOGl8I51Xu', b'0', '2019-05-04 19:45:01', NULL, NULL),
('test@gmail.com', '2019-05-04 19:42:34', 'john', 'doe', 'test1', '$2y$10$8PLiFYI68og1OjHtDiGmku6Af5yLF4Mlzv3KDMxcgU4tCEWC9bjHO', b'0', '2019-05-04 19:42:34', NULL, NULL),
('test@yahoo.com', '2019-05-04 19:44:11', 'Jane', 'Doe', 'test2', '$2y$10$KqoOVxTLG2HvqqoQRpEih.V.Mxc3gGqiUGYDMvQkLKER4b2QuuWxi', b'0', '2019-05-04 19:44:11', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workspace`
--

CREATE TABLE `workspace` (
  `workspace_id` int(11) NOT NULL,
  `workspace_name` varchar(255) NOT NULL,
  `workspace_owner` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `ws_created_timedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `workspace`
--

INSERT INTO `workspace` (`workspace_id`, `workspace_name`, `workspace_owner`, `description`, `ws_created_timedate`) VALUES
(7, 'Facebook', 'fk652@nyu.edu', 'test 1', '2019-05-05 00:07:57'),
(8, 'MySpace', 'fk652@nyu.edu', 'test2', '2019-05-05 00:08:08'),
(9, 'Twitter', 'fk652@nyu.edu', 'test 3', '2019-05-05 00:08:31'),
(10, 'Otherspace', 'test@yahoo.com', 'space from another world', '2019-05-08 19:06:29'),
(11, 'earth', 'test@gmail.com', 'the original workspace', '2019-05-08 19:56:48'),
(12, 'demo', 'fk652@nyu.edu', 'for the demo', '2019-05-10 19:26:30');

-- --------------------------------------------------------

--
-- Table structure for table `workspace_admins`
--

CREATE TABLE `workspace_admins` (
  `email` varchar(255) NOT NULL,
  `workspace_id` int(11) NOT NULL,
  `wsa_added_timedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `workspace_admins`
--

INSERT INTO `workspace_admins` (`email`, `workspace_id`, `wsa_added_timedate`) VALUES
('fk652@nyu.edu', 7, '2019-05-05 00:07:57'),
('fk652@nyu.edu', 8, '2019-05-05 00:08:08'),
('fk652@nyu.edu', 9, '2019-05-05 00:08:31'),
('fk652@nyu.edu', 12, '2019-05-10 19:26:30'),
('test@aol.com', 8, '2019-05-09 04:13:04'),
('test@gmail.com', 7, '2019-05-09 03:28:14'),
('test@gmail.com', 11, '2019-05-08 19:56:48'),
('test@yahoo.com', 7, '2019-05-10 19:29:43'),
('test@yahoo.com', 10, '2019-05-08 19:06:29');

-- --------------------------------------------------------

--
-- Table structure for table `workspace_invites`
--

CREATE TABLE `workspace_invites` (
  `workspace_id` int(11) NOT NULL,
  `wsi_receiver` varchar(255) NOT NULL,
  `wsi_invite_timedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `wsi_sender` varchar(255) NOT NULL,
  `wsi_status` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `workspace_invites`
--

INSERT INTO `workspace_invites` (`workspace_id`, `wsi_receiver`, `wsi_invite_timedate`, `wsi_sender`, `wsi_status`) VALUES
(7, 'test2@gmail.com', '2019-05-05 01:38:33', 'fk652@nyu.edu', 1),
(7, 'test3@gmail.com', '2019-05-05 22:23:00', 'fk652@nyu.edu', 1),
(7, 'test@aol.com', '2019-05-05 01:41:32', 'fk652@nyu.edu', 2),
(7, 'test@aol.com', '2019-05-10 19:27:59', 'fk652@nyu.edu', 0),
(7, 'test@gmail.com', '2019-05-05 22:23:13', 'fk652@nyu.edu', 1),
(7, 'test@yahoo.com', '2019-05-05 22:23:20', 'fk652@nyu.edu', 1),
(8, 'test2@gmail.com', '2019-05-05 01:09:28', 'fk652@nyu.edu', 2),
(8, 'test3@gmail.com', '2019-05-05 01:41:52', 'fk652@nyu.edu', 1),
(8, 'test@aol.com', '2019-05-05 01:42:00', 'fk652@nyu.edu', 1),
(8, 'test@aol.com', '2019-05-06 00:41:18', 'fk652@nyu.edu', 3),
(8, 'test@gmail.com', '2019-05-05 01:42:07', 'fk652@nyu.edu', 3),
(8, 'test@gmail.com', '2019-05-10 02:16:09', 'fk652@nyu.edu', 1),
(8, 'test@yahoo.com', '2019-05-05 01:42:15', 'fk652@nyu.edu', 1),
(9, 'test2@gmail.com', '2019-05-05 22:23:59', 'fk652@nyu.edu', 1),
(9, 'test3@gmail.com', '2019-05-05 01:41:46', 'fk652@nyu.edu', 2),
(9, 'test@aol.com', '2019-05-05 22:24:12', 'fk652@nyu.edu', 3),
(9, 'test@gmail.com', '2019-05-05 22:24:19', 'fk652@nyu.edu', 1),
(9, 'test@yahoo.com', '2019-05-05 01:41:40', 'fk652@nyu.edu', 0);

-- --------------------------------------------------------

--
-- Table structure for table `workspace_members`
--

CREATE TABLE `workspace_members` (
  `workspace_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `wsm_added_timedate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `workspace_members`
--

INSERT INTO `workspace_members` (`workspace_id`, `email`, `wsm_added_timedate`) VALUES
(7, 'fk652@nyu.edu', '2019-05-05 00:07:57'),
(7, 'test3@gmail.com', '2019-05-05 23:44:03'),
(7, 'test@gmail.com', '2019-05-06 00:55:57'),
(7, 'test@yahoo.com', '2019-05-10 19:28:42'),
(8, 'fk652@nyu.edu', '2019-05-05 00:08:08'),
(8, 'test3@gmail.com', '2019-05-05 23:44:59'),
(8, 'test@aol.com', '2019-05-06 00:42:50'),
(8, 'test@gmail.com', '2019-05-10 02:16:31'),
(8, 'test@yahoo.com', '2019-05-08 19:05:46'),
(9, 'fk652@nyu.edu', '2019-05-05 00:08:31'),
(9, 'test@aol.com', '2019-05-06 00:38:52'),
(9, 'test@gmail.com', '2019-05-06 00:56:04'),
(10, 'test@yahoo.com', '2019-05-08 19:06:29'),
(11, 'test@gmail.com', '2019-05-08 19:56:48'),
(12, 'fk652@nyu.edu', '2019-05-10 19:26:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `channels`
--
ALTER TABLE `channels`
  ADD PRIMARY KEY (`channel_name`,`workspace_id`),
  ADD KEY `channel_owner` (`channel_owner`),
  ADD KEY `channel_workspace` (`workspace_id`);

--
-- Indexes for table `channel_invites`
--
ALTER TABLE `channel_invites`
  ADD PRIMARY KEY (`workspace_id`,`channel_name`,`chi_receiver`,`chi_invite_timedate`),
  ADD KEY `chi_receiver` (`chi_receiver`),
  ADD KEY `chi_sender` (`chi_sender`),
  ADD KEY `chi_channel` (`channel_name`,`workspace_id`);

--
-- Indexes for table `channel_members`
--
ALTER TABLE `channel_members`
  ADD PRIMARY KEY (`email`,`channel_name`,`workspace_id`),
  ADD KEY `chm_workspace` (`workspace_id`),
  ADD KEY `chm_name` (`channel_name`,`workspace_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `message_workspace` (`workspace_id`),
  ADD KEY `message_owner` (`email`),
  ADD KEY `message_channel` (`channel_name`,`workspace_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `workspace`
--
ALTER TABLE `workspace`
  ADD PRIMARY KEY (`workspace_id`),
  ADD KEY `workspace_owner` (`workspace_owner`);

--
-- Indexes for table `workspace_admins`
--
ALTER TABLE `workspace_admins`
  ADD PRIMARY KEY (`email`,`workspace_id`),
  ADD KEY `wsa_workspace` (`workspace_id`);

--
-- Indexes for table `workspace_invites`
--
ALTER TABLE `workspace_invites`
  ADD PRIMARY KEY (`workspace_id`,`wsi_receiver`,`wsi_invite_timedate`),
  ADD KEY `wsi_sender` (`wsi_sender`),
  ADD KEY `wsi_receiver` (`wsi_receiver`);

--
-- Indexes for table `workspace_members`
--
ALTER TABLE `workspace_members`
  ADD PRIMARY KEY (`workspace_id`,`email`),
  ADD KEY `wsm_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `workspace`
--
ALTER TABLE `workspace`
  MODIFY `workspace_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `channels`
--
ALTER TABLE `channels`
  ADD CONSTRAINT `channel_owner` FOREIGN KEY (`channel_owner`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `channel_workspace` FOREIGN KEY (`workspace_id`) REFERENCES `workspace` (`workspace_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `channel_invites`
--
ALTER TABLE `channel_invites`
  ADD CONSTRAINT `chi_channel` FOREIGN KEY (`channel_name`,`workspace_id`) REFERENCES `channels` (`channel_name`, `workspace_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chi_receiver` FOREIGN KEY (`chi_receiver`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chi_sender` FOREIGN KEY (`chi_sender`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `channel_members`
--
ALTER TABLE `channel_members`
  ADD CONSTRAINT `chm_email` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chm_name` FOREIGN KEY (`channel_name`,`workspace_id`) REFERENCES `channels` (`channel_name`, `workspace_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `message_channel` FOREIGN KEY (`channel_name`,`workspace_id`) REFERENCES `channels` (`channel_name`, `workspace_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `message_owner` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `workspace`
--
ALTER TABLE `workspace`
  ADD CONSTRAINT `workspace_owner` FOREIGN KEY (`workspace_owner`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `workspace_admins`
--
ALTER TABLE `workspace_admins`
  ADD CONSTRAINT `wsa_email` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wsa_workspace` FOREIGN KEY (`workspace_id`) REFERENCES `workspace` (`workspace_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `workspace_invites`
--
ALTER TABLE `workspace_invites`
  ADD CONSTRAINT `wsi_receiver` FOREIGN KEY (`wsi_receiver`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wsi_sender` FOREIGN KEY (`wsi_sender`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wsi_workspace` FOREIGN KEY (`workspace_id`) REFERENCES `workspace` (`workspace_id`);

--
-- Constraints for table `workspace_members`
--
ALTER TABLE `workspace_members`
  ADD CONSTRAINT `wsm_email` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `wsm_workspace` FOREIGN KEY (`workspace_id`) REFERENCES `workspace` (`workspace_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
