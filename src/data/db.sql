-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1
-- Čas generovania: Sun 17.Nov 2024, 13:33
-- Verzia serveru: 10.4.32-MariaDB
-- Verzia PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `iis`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `conferences`
--

CREATE TABLE `conferences` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_of_interest` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `capacity` int(11) NOT NULL,
  `organiser_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Sťahujem dáta pre tabuľku `conferences`
--

INSERT INTO `conferences` (`id`, `name`, `area_of_interest`, `description`, `start_time`, `end_time`, `price`, `capacity`, `organiser_id`) VALUES
(6, 'Kybernetická bezpečnost v digitálním věku', 'Kybernetická bezpečnost, ochrana dat', 'Konference zaměřená na aktuální výzvy v oblasti kybernetické bezpečnosti, ochrany soukromí a obrany proti kybernetickým útokům. Součástí programu budou případy z praxe, diskuse o nových bezpečnostních technologiích a doporučení pro firmy i jednotlivce.', '2024-11-12 10:00:00', '2024-11-12 17:00:00', 180, 18, 6),
(7, 'Konference o umělé inteligenci', 'Umělá inteligence, strojové učení', 'Na této konferenci budou odborníci z celého světa prezentovat nové metody a výzkumy v oblasti umělé inteligence, včetně praktických aplikací strojového učení a etických otázek.', '2024-11-09 09:00:00', '2024-11-09 17:00:00', 150, 12, 5),
(8, 'Technologie ve zdravotnictví', 'Digitální zdravotnictví, biotechnologie', 'Konference zaměřená na technologické inovace ve zdravotnictví, od umělé inteligence v diagnostice po nejnovější biotechnologické pokroky.', '2024-11-15 10:00:00', '2024-11-15 18:00:00', 195, 16, 5),
(9, 'Udržitelná energie a životní prostředí', 'Obnovitelné zdroje, klimatické změny', 'Diskuse a přednášky o udržitelných energetických řešeních a způsobech, jak bojovat proti klimatickým změnám, s důrazem na inovace a environmentální politiku.', '2024-11-05 08:30:00', '2024-11-09 16:30:00', 200, 10, 6),
(10, 'Moderní trendy v softwarovém inženýrství', 'Vývoj softwaru, DevOps', 'Praktické workshopy a přednášky o moderních praktikách vývoje softwaru, včetně mikroservisních architektur, cloudových řešení a automatizace v DevOps.', '2024-11-12 10:00:00', '2024-11-12 17:00:00', 195, 10, 6),
(11, 'Skuska rozvrhu', '', 'skuska', '2024-11-25 06:07:00', '2024-11-25 22:14:00', 100, 56, 6);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `conference_has_rooms`
--

CREATE TABLE `conference_has_rooms` (
  `id` int(11) NOT NULL,
  `conference_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `booking_start` datetime NOT NULL,
  `booking_end` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Sťahujem dáta pre tabuľku `conference_has_rooms`
--

INSERT INTO `conference_has_rooms` (`id`, `conference_id`, `room_id`, `booking_start`, `booking_end`) VALUES
(10, 6, 15, '2024-11-12 10:00:00', '2024-11-12 17:00:00'),
(11, 6, 19, '2024-11-12 10:00:00', '2024-11-12 17:00:00'),
(12, 7, 20, '2024-11-09 09:00:00', '2024-11-09 17:00:00'),
(13, 9, 16, '2024-11-05 08:30:00', '2024-11-09 16:30:00'),
(14, 8, 16, '2024-11-15 10:00:00', '2024-11-15 18:00:00'),
(15, 8, 18, '2024-11-15 10:00:00', '2024-11-15 18:00:00'),
(16, 10, 14, '2024-11-12 10:00:00', '2024-11-12 17:00:00'),
(17, 10, 17, '2024-11-12 10:00:00', '2024-11-12 17:00:00'),
(18, 11, 14, '2024-11-25 06:07:00', '2024-11-25 22:14:00'),
(19, 11, 15, '2024-11-25 06:07:00', '2024-11-25 22:14:00'),
(20, 11, 16, '2024-11-25 06:07:00', '2024-11-25 22:14:00'),
(21, 11, 17, '2024-11-25 06:07:00', '2024-11-25 22:14:00'),
(22, 11, 18, '2024-11-25 06:07:00', '2024-11-25 22:14:00'),
(23, 11, 19, '2024-11-25 06:07:00', '2024-11-25 22:14:00'),
(24, 11, 20, '2024-11-25 06:07:00', '2024-11-25 22:14:00');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `lectures`
--

CREATE TABLE `lectures` (
  `id` int(11) NOT NULL,
  `id_conference_has_rooms` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `lectures`
--

INSERT INTO `lectures` (`id`, `id_conference_has_rooms`, `start_time`, `end_time`) VALUES
(22, 19, '2024-11-25 07:00:00', '2024-11-25 08:00:00'),
(23, 21, '2024-11-25 12:00:00', '2024-11-25 17:00:00'),
(24, 24, '2024-11-25 13:05:00', '2024-11-25 14:05:00'),
(25, 19, '2024-11-25 10:45:00', '2024-11-25 13:45:00'),
(26, 10, '2024-11-12 10:25:00', '2024-11-12 11:10:00'),
(27, 10, '2024-11-12 13:30:00', '2024-11-12 15:00:00'),
(28, 11, '2024-11-12 14:17:00', '2024-11-12 16:47:00'),
(29, 24, '2024-11-25 08:05:00', '2024-11-25 11:05:00');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `presentations`
--

CREATE TABLE `presentations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` enum('waiting','approved','denied') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lecturer_id` int(11) NOT NULL,
  `lecture_id` int(11) DEFAULT NULL,
  `conference_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Sťahujem dáta pre tabuľku `presentations`
--

INSERT INTO `presentations` (`id`, `name`, `description`, `state`, `attachment`, `lecturer_id`, `lecture_id`, `conference_id`) VALUES
(17, 'Skusobna prezentacia', 'Toto je skusobna prezentacia.', 'approved', 'Tu je osnova.', 5, 22, 11);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `created_date` date NOT NULL,
  `created_time` time NOT NULL,
  `price_to_pay` decimal(10,0) NOT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `customer_id` int(11) DEFAULT NULL,
  `conference_id` int(11) NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `num_reserved_tickets` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Sťahujem dáta pre tabuľku `reservations`
--

INSERT INTO `reservations` (`id`, `created_date`, `created_time`, `price_to_pay`, `is_paid`, `customer_id`, `conference_id`, `first_name`, `last_name`, `email`, `num_reserved_tickets`) VALUES
(27, '2024-11-03', '22:31:58', 390, 0, NULL, 10, 'Daniel', 'Lampa', 'lampa4@azet.cz', 2),
(28, '2024-11-03', '22:33:05', 975, 0, NULL, 8, 'Dana', 'Sviečková', 'dana@gmail.com', 5),
(29, '2024-11-03', '22:34:12', 180, 0, NULL, 6, 'Milan', 'Vačko', 'milo55@gmail.com', 1),
(30, '2024-11-03', '22:35:06', 975, 0, 9, 10, 'Martinko', 'Klingáčik', 'martin@azet.cz', 5),
(31, '2024-11-03', '22:35:19', 180, 0, 9, 6, 'Martinko', 'Klingáčik', 'martin@azet.cz', 1),
(32, '2024-11-03', '22:35:28', 450, 0, 9, 7, 'Martinko', 'Klingáčik', 'martin@azet.cz', 3),
(33, '2024-11-03', '22:37:17', 360, 0, 10, 6, 'Hana', 'Gregorová', 'hana1@gmail.com', 2),
(34, '2024-11-03', '22:37:23', 150, 0, 10, 7, 'Hana', 'Gregorová', 'hana1@gmail.com', 1),
(35, '2024-11-03', '22:37:31', 195, 0, 10, 8, 'Hana', 'Gregorová', 'hana1@gmail.com', 1),
(36, '2024-11-03', '22:38:33', 195, 0, 6, 8, 'Jana', 'Nováková', 'novakova@seznam.cz', 1),
(37, '2024-11-03', '22:38:41', 390, 0, 6, 8, 'Jana', 'Nováková', 'novakova@seznam.cz', 2),
(38, '2024-11-03', '22:38:49', 150, 0, 6, 7, 'Jana', 'Nováková', 'novakova@seznam.cz', 1);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `capacity` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Sťahujem dáta pre tabuľku `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `capacity`, `creator_id`) VALUES
(14, 'A11', 5, 4),
(15, 'A12', 10, 4),
(16, 'A28', 10, 4),
(17, 'B10', 5, 4),
(18, 'C01', 6, 4),
(19, 'C18', 8, 4),
(20, 'D105', 12, 4);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `selected_lectures`
--

CREATE TABLE `selected_lectures` (
  `id` int(11) NOT NULL,
  `id_lecture` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `is_selected` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `code` varchar(20) NOT NULL,
  `conference_id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Sťahujem dáta pre tabuľku `tickets`
--

INSERT INTO `tickets` (`id`, `price`, `code`, `conference_id`, `reservation_id`) VALUES
(72, 180, 'SFPMIHZDDI', 6, 29),
(73, 180, '13RLAO7098', 6, 31),
(74, 180, '2IEBH4PKAI', 6, 33),
(75, 180, 'BX50N11IIE', 6, 33),
(76, 180, 'DX6PYZ8428', 6, NULL),
(77, 180, 'JW9NDX3ZXQ', 6, NULL),
(78, 180, 'W501RA4NJ1', 6, NULL),
(79, 180, 'G32BH92M0M', 6, NULL),
(80, 180, 'ASKDULA5H4', 6, NULL),
(81, 180, 'AITH409T9L', 6, NULL),
(82, 180, 'T68O6JSDZX', 6, NULL),
(83, 180, 'O3Z58NK134', 6, NULL),
(84, 180, 'O28A9P5MBN', 6, NULL),
(85, 180, 'DZ8EBRDVGC', 6, NULL),
(86, 180, 'FLPHVIKJI7', 6, NULL),
(87, 180, 'LTZEA69KXA', 6, NULL),
(88, 180, '9SBVIB766C', 6, NULL),
(89, 180, '00WL3AB02G', 6, NULL),
(90, 150, 'BAWFYNTUKH', 7, 32),
(91, 150, 'H3UMQ357XD', 7, 32),
(92, 150, 'DXFIOHDICC', 7, 32),
(93, 150, 'F942MOJKPH', 7, 34),
(94, 150, 'PBJXRHDFM0', 7, 38),
(95, 150, 'OPZ25DAWW6', 7, NULL),
(96, 150, '8UJMXVFB8H', 7, NULL),
(97, 150, '609WURO7Y1', 7, NULL),
(98, 150, '79IYDBKDUI', 7, NULL),
(99, 150, 'T4RC8BK7M1', 7, NULL),
(100, 150, 'T3CRUL5WG3', 7, NULL),
(101, 150, '72CXAFGQI6', 7, NULL),
(102, 200, 'AG36LBBVQ0', 9, NULL),
(103, 200, 'WMT82XOBHM', 9, NULL),
(104, 200, 'BZPN98DQJS', 9, NULL),
(105, 200, '34NS1TLL5J', 9, NULL),
(106, 200, 'PXHVEAKPT0', 9, NULL),
(107, 200, '6TAL8IL4XA', 9, NULL),
(108, 200, 'WWD5N2UG5S', 9, NULL),
(109, 200, 'KIMHBDLONB', 9, NULL),
(110, 200, 'XMOM9JG1U6', 9, NULL),
(111, 200, 'JGM1SS4LHF', 9, NULL),
(112, 195, 'ZCU68DGHBM', 8, 28),
(113, 195, 'U3F5GUYGLU', 8, 28),
(114, 195, '3H48ZUZB7E', 8, 28),
(115, 195, '4QK89EDEOY', 8, 28),
(116, 195, 'LPSYTW7MS4', 8, 28),
(117, 195, '36L2Z3HY9V', 8, 35),
(118, 195, '0ZBDD8E6LB', 8, 36),
(119, 195, 'G6Q4KZ2AYS', 8, 37),
(120, 195, '0GOPI5HXSE', 8, 37),
(121, 195, '2ABZR4Q1LM', 8, NULL),
(122, 195, 'QVMSM5LW8P', 8, NULL),
(123, 195, 'ZK4T1PPVP5', 8, NULL),
(124, 195, 'EOHRW9WX56', 8, NULL),
(125, 195, 'P78VWH6LBD', 8, NULL),
(126, 195, '6IZVZXO8JH', 8, NULL),
(127, 195, 'UJ4PSJE6HR', 8, NULL),
(128, 195, 'MGV2FG0TUO', 10, 27),
(129, 195, '1W758NQHCL', 10, 27),
(130, 195, '9PSILDNXS0', 10, 30),
(131, 195, '8CW3UJRZD0', 10, 30),
(132, 195, '5GY5NFN3IL', 10, 30),
(133, 195, 'QFY60C7STB', 10, 30),
(134, 195, 'QTGS24Y1GS', 10, 30),
(135, 195, 'KD2U4NMJFO', 10, NULL),
(136, 195, 'ZJH06802BC', 10, NULL),
(137, 195, '8ZXHVPJ0SM', 10, NULL),
(138, 100, '2J58PCPMSQ', 11, NULL),
(139, 100, 'RLAZUD1ZHS', 11, NULL),
(140, 100, '1HJI8W4JB4', 11, NULL),
(141, 100, 'CN30OQCOK7', 11, NULL),
(142, 100, 'JXYXJ5U7ET', 11, NULL),
(143, 100, 'WCICL7HZM8', 11, NULL),
(144, 100, '9OK4L698OI', 11, NULL),
(145, 100, 'L9IE1H8NSL', 11, NULL),
(146, 100, '1HM5V8HU0X', 11, NULL),
(147, 100, 'LNG7JQVH0K', 11, NULL),
(148, 100, 'T7GG411SY3', 11, NULL),
(149, 100, 'NNRLBH9LPM', 11, NULL),
(150, 100, 'AMOK14WLMD', 11, NULL),
(151, 100, 'T05Y9PKTDH', 11, NULL),
(152, 100, 'E020972VSV', 11, NULL),
(153, 100, 'I42WM89SIL', 11, NULL),
(154, 100, '2WY1WMI7W2', 11, NULL),
(155, 100, 'RHKQAYE0RO', 11, NULL),
(156, 100, 'JZY72N9NV2', 11, NULL),
(157, 100, 'A6CM1HMJTO', 11, NULL),
(158, 100, 'J1KJ0HFNAY', 11, NULL),
(159, 100, 'JE873PAVL6', 11, NULL),
(160, 100, 'G6PKUVG38B', 11, NULL),
(161, 100, '5EMNDJF2VC', 11, NULL),
(162, 100, 'IA4AFK6DG5', 11, NULL),
(163, 100, 'L3RO369G55', 11, NULL),
(164, 100, 'TQJZGTE3IV', 11, NULL),
(165, 100, '17TY9AJKE9', 11, NULL),
(166, 100, 'DNY423RR2E', 11, NULL),
(167, 100, 'N5XZVEK5YB', 11, NULL),
(168, 100, 'C910B2KJHN', 11, NULL),
(169, 100, 'SUYZHRIUWC', 11, NULL),
(170, 100, 'LTCI5VX0CI', 11, NULL),
(171, 100, '9LR4GV0E9X', 11, NULL),
(172, 100, '6ZBUE0TDP8', 11, NULL),
(173, 100, '55ZL1O7J73', 11, NULL),
(174, 100, 'WET2O5961M', 11, NULL),
(175, 100, '68B87L0DSR', 11, NULL),
(176, 100, 'LLW7PH2R6A', 11, NULL),
(177, 100, 'P64TKIWOCL', 11, NULL),
(178, 100, 'FZQRUGJ9MS', 11, NULL),
(179, 100, 'Y9ZEYVHSAV', 11, NULL),
(180, 100, '0MDSUD66YY', 11, NULL),
(181, 100, 'TNC64O6RNF', 11, NULL),
(182, 100, '05CT79ZS5O', 11, NULL),
(183, 100, 'U3U7UJEDDO', 11, NULL),
(184, 100, 'L7GGFLKYHO', 11, NULL),
(185, 100, 'RGM73O1D8Z', 11, NULL),
(186, 100, 'N33PXNEBA9', 11, NULL),
(187, 100, '3AFD73PJM2', 11, NULL),
(188, 100, 'LURFXVG6J0', 11, NULL),
(189, 100, 'H2JGFK8UXB', 11, NULL),
(190, 100, 'ASCHKA6CS1', 11, NULL),
(191, 100, 'W9WGKF5JI2', 11, NULL),
(192, 100, 'G7E96HAUSA', 11, NULL),
(193, 100, 'WMUAKQ5M4H', 11, NULL);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_type` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `password`, `account_type`) VALUES
(4, 'admin', 'admin', 'admin@local.cz', '$2y$10$N1LXY8shBk2jwdlfox7fOeLGZg76vIhFyUhgE6owM3610UuQC6ovG', 'user'),
(5, 'Alojz', 'Žufánek', 'alojz11@seznam.cz', '$2y$10$6PlnXsZQM4yl84p228sMle86vGVVnfVVNEOA3v1iY2WVixX9RJ5.K', 'user'),
(6, 'Jana', 'Nováková', 'novakova@seznam.cz', '$2y$10$nsYn83KsMkTFROMBDSZaHOng2qNepAhGURjUCC7PqA8S4fVsLXdVO', 'user'),
(9, 'Martinko', 'Klingáčik', 'martin@azet.cz', '$2y$10$LuGf0dFSeosbd9ZpLYZqqO94x7mPA0YSR.lZg3MSBqbhcsa7BQdJK', 'user'),
(10, 'Hana', 'Gregorová', 'hana1@gmail.com', '$2y$10$DgWzNiAXUl/ULim7aUYi1Oi6xMsTkhSmEmjLXrwpoJDoswlMrcZZm', 'user');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `conferences`
--
ALTER TABLE `conferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conference_organiser` (`organiser_id`);

--
-- Indexy pre tabuľku `conference_has_rooms`
--
ALTER TABLE `conference_has_rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conference_room` (`conference_id`),
  ADD KEY `room_conference` (`room_id`);

--
-- Indexy pre tabuľku `lectures`
--
ALTER TABLE `lectures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conference_and_room` (`id_conference_has_rooms`);

--
-- Indexy pre tabuľku `presentations`
--
ALTER TABLE `presentations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lecturer_submitted_presentation` (`lecturer_id`),
  ADD KEY `lecture_lesson` (`lecture_id`),
  ADD KEY `presentation_for_conference` (`conference_id`);

--
-- Indexy pre tabuľku `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_created_reservation` (`customer_id`),
  ADD KEY `reservation_for_conference` (`conference_id`);

--
-- Indexy pre tabuľku `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `room_created_by` (`creator_id`);

--
-- Indexy pre tabuľku `selected_lectures`
--
ALTER TABLE `selected_lectures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `which_lecture_is_selected` (`id_lecture`),
  ADD KEY `whose_schedule` (`id_user`);

--
-- Indexy pre tabuľku `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conference_ticket` (`conference_id`),
  ADD KEY `reservation_ticket` (`reservation_id`);

--
-- Indexy pre tabuľku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `conferences`
--
ALTER TABLE `conferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pre tabuľku `conference_has_rooms`
--
ALTER TABLE `conference_has_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pre tabuľku `lectures`
--
ALTER TABLE `lectures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pre tabuľku `presentations`
--
ALTER TABLE `presentations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pre tabuľku `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pre tabuľku `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pre tabuľku `selected_lectures`
--
ALTER TABLE `selected_lectures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT pre tabuľku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `conferences`
--
ALTER TABLE `conferences`
  ADD CONSTRAINT `conference_organiser` FOREIGN KEY (`organiser_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `conference_has_rooms`
--
ALTER TABLE `conference_has_rooms`
  ADD CONSTRAINT `conference_room` FOREIGN KEY (`conference_id`) REFERENCES `conferences` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room_conference` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `lectures`
--
ALTER TABLE `lectures`
  ADD CONSTRAINT `conference_and_room` FOREIGN KEY (`id_conference_has_rooms`) REFERENCES `conference_has_rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `presentations`
--
ALTER TABLE `presentations`
  ADD CONSTRAINT `lecturer_submitted_presentation` FOREIGN KEY (`lecturer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `presentation_for_conference` FOREIGN KEY (`conference_id`) REFERENCES `conferences` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `customer_created_reservation` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservation_for_conference` FOREIGN KEY (`conference_id`) REFERENCES `conferences` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `room_created_by` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);

--
-- Obmedzenie pre tabuľku `selected_lectures`
--
ALTER TABLE `selected_lectures`
  ADD CONSTRAINT `which_lecture_is_selected` FOREIGN KEY (`id_lecture`) REFERENCES `lectures` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `whose_schedule` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `conference_ticket` FOREIGN KEY (`conference_id`) REFERENCES `conferences` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservation_ticket` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
