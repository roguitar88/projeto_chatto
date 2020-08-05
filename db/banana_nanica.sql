-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 04-Ago-2020 às 20:31
-- Versão do servidor: 5.7.24
-- versão do PHP: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `banana_nanica`
--

DELIMITER $$
--
-- Funções
--
CREATE DEFINER=`root`@`localhost` FUNCTION `getUsername` (`userId` INTEGER) RETURNS VARCHAR(200) CHARSET utf8 RETURN (SELECT username FROM registered_users WHERE id = userId)$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `fk_userFrom` int(11) NOT NULL,
  `fk_userTo` int(11) NOT NULL,
  `msgbody` varchar(1000) NOT NULL,
  `register_date5` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `classified_ads`
--

CREATE TABLE `classified_ads` (
  `id2` int(11) NOT NULL,
  `ad_id` varchar(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description2` text NOT NULL,
  `category` varchar(200) NOT NULL,
  `brand` varchar(200) NOT NULL DEFAULT '',
  `condition2` varchar(100) NOT NULL,
  `checkvalue` int(11) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL,
  `discount` varchar(11) NOT NULL,
  `register_date2` datetime NOT NULL,
  `location2` varchar(200) NOT NULL,
  `awaiting_approval` tinyint(1) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `unactive` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `classified_ads`
--

INSERT INTO `classified_ads` (`id2`, `ad_id`, `created_by`, `title`, `description2`, `category`, `brand`, `condition2`, `checkvalue`, `price`, `discount`, `register_date2`, `location2`, `awaiting_approval`, `published`, `unactive`) VALUES
(4, '7845632', 1, 'Criado-mudo', 'Criado-mudo Avelar', 'eletrodomesticos', 'Enamel', 'usado', 1, 15000, '0', '2020-07-05 10:51:26', '74355516', 1, 0, 0),
(6, '4390875', 1, 'Estante mor', 'Estante mor', 'estofados', 'Dracula', 'usado', 0, 14900, '0', '2020-07-05 12:25:17', '74355516', 1, 0, 0),
(8, '6762345', 1, 'Armario Enamel', 'Armario Enamel', 'estofados', 'Dracula', 'usado', 0, 14356, '0', '2020-07-05 13:08:58', '74355516', 1, 0, 0),
(9, '1209843', 3, 'Jogo de sofás', 'Jogo de sofás', 'estofados', 'Enamel', 'novo', 0, 79800, '0', '2020-07-09 20:21:45', '74355516', 1, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `registered_users`
--

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
  `category` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `registered_users`
--

INSERT INTO `registered_users` (`id`, `credential2`, `username`, `email`, `fullname`, `password2`, `reset_password_code`, `activationcode`, `activated`, `canceled`, `deactivate`, `register_date`, `userip`, `hostname`, `category`) VALUES
(1, 2, 'roguitar', 'rogeriobsoares5@gmail.com', '', '123', '', '83152f45', 0, 0, 0, '2020-06-24 10:37:48', '::1', 'Rogerio-PC', 'puts'),
(2, 0, 'joaozito', 'joaozinho@gmail.com', '', '123', '', '123', 0, 0, 0, '2020-06-24 00:00:00', '4532', '4532-sky', 'lan'),
(3, 0, 'joilson', 'joilson@gmail.com', 'Joilson Carvalho', '123', '', '', 1, 0, 0, '2020-07-28 17:01:36', '', '', '');

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `vw_messages`
-- (Veja abaixo para a view atual)
--
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
-- Estrutura para vista `vw_messages`
--
DROP TABLE IF EXISTS `vw_messages`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_messages`  AS  select `chat_messages`.`id` AS `id`,`chat_messages`.`fk_userFrom` AS `fk_userFrom`,`getUsername`(`chat_messages`.`fk_userFrom`) AS `userFrom`,`chat_messages`.`fk_userTo` AS `fk_userTo`,`getUsername`(`chat_messages`.`fk_userTo`) AS `userTo`,`chat_messages`.`msgbody` AS `msgbody`,`chat_messages`.`register_date5` AS `register_date5` from `chat_messages` ;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_userFrom` (`fk_userFrom`),
  ADD KEY `fk_userTo` (`fk_userTo`);

--
-- Índices para tabela `classified_ads`
--
ALTER TABLE `classified_ads`
  ADD PRIMARY KEY (`id2`),
  ADD KEY `created_by` (`created_by`);

--
-- Índices para tabela `registered_users`
--
ALTER TABLE `registered_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT de tabela `classified_ads`
--
ALTER TABLE `classified_ads`
  MODIFY `id2` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `registered_users`
--
ALTER TABLE `registered_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`fk_userFrom`) REFERENCES `registered_users` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`fk_userTo`) REFERENCES `registered_users` (`id`);

--
-- Limitadores para a tabela `classified_ads`
--
ALTER TABLE `classified_ads`
  ADD CONSTRAINT `classified_ads_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `registered_users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
