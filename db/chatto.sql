-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 13, 2021 at 01:38 AM
-- Server version: 5.7.24
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chatto`
--

DELIMITER $$
--
-- Functions
--
DROP FUNCTION IF EXISTS `getUsername`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `getUsername` (`userId` INTEGER) RETURNS VARCHAR(200) CHARSET utf8 RETURN (SELECT username FROM registered_users WHERE id = userId)$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `fk_userFrom` int(11) NOT NULL,
  `fk_userTo` int(11) NOT NULL,
  `msgbody` varchar(1000) NOT NULL,
  `register_date5` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `fk_userFrom`, `fk_userTo`, `msgbody`, `register_date5`) VALUES
(1, 1, 3, 'OlÃ¡', '2021-01-11 22:40:51'),
(2, 3, 1, 'Oi, tudo bem?', '2021-01-11 22:40:51'),
(3, 1, 3, 'Tudo, obrigado rs', '2021-01-11 22:40:51'),
(4, 3, 1, 'Q bom rs', '2021-01-11 23:07:26'),
(5, 1, 3, 'O que faz de bom?', '2021-01-12 22:32:50'),
(6, 3, 1, 'Nada rs', '2021-01-12 22:32:50');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `id2` int(11) NOT NULL,
  `ad_id` varchar(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `register_date2` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id2`, `ad_id`, `created_by`, `title`, `register_date2`) VALUES
(4, '7845632', 1, 'Além do horizonte, existe um lugar...', '2020-07-05 10:51:26'),
(6, '4390875', 1, 'Devia ter arriscado menos...', '2020-07-05 12:25:17'),
(8, '6762345', 1, 'Coração, não faz assim...', '2020-07-05 13:08:58'),
(9, '1209843', 3, 'Linda, só você me fascina...', '2020-07-09 20:21:45');

-- --------------------------------------------------------

--
-- Table structure for table `registered_users`
--

DROP TABLE IF EXISTS `registered_users`;
CREATE TABLE `registered_users` (
  `id` int(11) NOT NULL,
  `credential2` int(11) NOT NULL DEFAULT '0',
  `username` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `fullname` varchar(200) NOT NULL DEFAULT '',
  `password2` varchar(100) NOT NULL,
  `reset_password_code` varchar(100) NOT NULL DEFAULT '',
  `activationcode` varchar(100) NOT NULL,
  `activated` int(11) NOT NULL DEFAULT '0',
  `canceled` int(11) NOT NULL DEFAULT '0',
  `deactivate` int(11) NOT NULL DEFAULT '0',
  `register_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userip` varchar(200) NOT NULL,
  `hostname` varchar(200) NOT NULL,
  `category` varchar(200) NOT NULL,
  `last_activity_update` datetime DEFAULT CURRENT_TIMESTAMP,
  `st_online` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `registered_users`
--

INSERT INTO `registered_users` (`id`, `credential2`, `username`, `email`, `fullname`, `password2`, `reset_password_code`, `activationcode`, `activated`, `canceled`, `deactivate`, `register_date`, `userip`, `hostname`, `category`, `last_activity_update`, `st_online`) VALUES
(1, 2, 'roguitar', 'rogeriobsoares5@gmail.com', '', '123', '', '83152f45', 0, 0, 0, '2020-06-24 10:37:48', '::1', 'Rogerio-PC', 'puts', '2021-01-12 22:32:50', 1),
(2, 0, 'joaozito', 'joaozinho@gmail.com', '', '123', '', '123', 0, 0, 0, '2020-06-24 00:00:00', '4532', '4532-sky', 'lan', NULL, 0),
(3, 0, 'joilson', 'joilson@gmail.com', 'Joilson Carvalho', '123', '', '', 1, 0, 0, '2020-07-28 17:01:36', '', '', '', '2021-01-12 22:32:50', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_messages`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_messages`;
CREATE TABLE `vw_messages` (
`id` int(11)
,`fk_userFrom` int(11)
,`userFrom` varchar(200)
,`fk_userTo` int(11)
,`userTo` varchar(200)
,`msgbody` varchar(1000)
,`register_date5` datetime
);

-- --------------------------------------------------------

--
-- Structure for view `vw_messages`
--
DROP TABLE IF EXISTS `vw_messages`;

DROP VIEW IF EXISTS `vw_messages`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_messages`  AS  select `chat_messages`.`id` AS `id`,`chat_messages`.`fk_userFrom` AS `fk_userFrom`,`getUsername`(`chat_messages`.`fk_userFrom`) AS `userFrom`,`chat_messages`.`fk_userTo` AS `fk_userTo`,`getUsername`(`chat_messages`.`fk_userTo`) AS `userTo`,`chat_messages`.`msgbody` AS `msgbody`,`chat_messages`.`register_date5` AS `register_date5` from `chat_messages` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_userFrom` (`fk_userFrom`),
  ADD KEY `fk_userTo` (`fk_userTo`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id2`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `registered_users`
--
ALTER TABLE `registered_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id2` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `registered_users`
--
ALTER TABLE `registered_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`fk_userFrom`) REFERENCES `registered_users` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`fk_userTo`) REFERENCES `registered_users` (`id`);

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `registered_users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
