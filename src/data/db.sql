-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1
-- Čas generovania: Sun 03.Nov 2024, 17:44
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
(10, 'Moderní trendy v softwarovém inženýrství', 'Vývoj softwaru, DevOps', 'Praktické workshopy a přednášky o moderních praktikách vývoje softwaru, včetně mikroservisních architektur, cloudových řešení a automatizace v DevOps.', '2024-11-12 10:00:00', '2024-11-12 17:00:00', 195, 10, 6);

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
(1, 6, 19, '2024-11-12 10:00:00', '2024-11-12 17:00:00'),
(2, 6, 15, '2024-11-12 10:00:00', '2024-11-12 17:00:00'),
(3, 7, 20, '2024-11-09 09:00:00', '2024-11-09 17:00:00'),
(4, 9, 16, '2024-11-05 08:30:00', '2024-11-05 16:30:00'),
(5, 8, 18, '2024-11-15 10:00:00', '2024-11-15 18:00:00'),
(6, 8, 16, '2024-11-15 10:00:00', '2024-11-15 18:00:00'),
(7, 10, 14, '2024-11-12 10:00:00', '2024-11-12 17:00:00'),
(8, 10, 17, '2024-11-12 10:00:00', '2024-11-12 17:00:00');

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
(1, 1, '2024-11-12 10:00:00', '2024-11-12 13:00:00'),
(2, 1, '2024-11-12 13:00:00', '2024-11-12 17:00:00'),
(3, 2, '2024-11-12 10:00:00', '2024-11-12 14:00:00'),
(4, 2, '2024-11-12 14:00:00', '2024-11-12 17:00:00'),
(5, 3, '2024-11-09 09:00:00', '2024-11-09 13:00:00'),
(6, 3, '2024-11-09 13:00:00', '2024-11-09 17:00:00'),
(7, 6, '2024-11-15 10:00:00', '2024-11-15 13:00:00'),
(8, 6, '2024-11-15 13:00:00', '2024-11-15 16:00:00'),
(9, 6, '2024-11-15 16:00:00', '2024-11-15 18:00:00'),
(10, 5, '2024-11-15 10:00:00', '2024-11-15 12:00:00'),
(11, 5, '2024-11-15 12:00:00', '2024-11-15 15:00:00'),
(12, 5, '2024-11-15 15:00:00', '2024-11-15 15:30:00'),
(13, 5, '2024-11-15 15:30:00', '2024-11-15 18:00:00'),
(14, 4, '2024-11-05 08:30:00', '2024-11-05 12:30:00'),
(15, 4, '2024-11-05 12:30:00', '2024-11-05 16:30:00'),
(16, 7, '2024-11-12 10:00:00', '2024-11-12 12:00:00'),
(17, 7, '2024-11-12 12:00:00', '2024-11-12 14:00:00'),
(18, 7, '2024-11-12 14:00:00', '2024-11-12 17:00:00'),
(19, 8, '2024-11-12 10:00:00', '2024-11-12 12:00:00'),
(20, 8, '2024-11-12 12:00:00', '2024-11-12 14:00:00'),
(21, 8, '2024-11-12 14:00:00', '2024-11-12 17:00:00');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `presentations`
--

CREATE TABLE `presentations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` enum('approved','waiting','denied') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lecturer_id` int(11) NOT NULL,
  `lecture_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Sťahujem dáta pre tabuľku `presentations`
--

INSERT INTO `presentations` (`id`, `name`, `description`, `state`, `attachment`, `lecturer_id`, `lecture_id`) VALUES
(8, 'Kybernetická bezpečnost pro firmy', 'Přednáška se zaměří na nejnovější bezpečnostní strategie a technologie pro firmy, včetně ochrany proti kybernetickým útokům a zabezpečení soukromých dat.', 'waiting', 'OSNOVA\r\n\r\n1. Úvod do kybernetické bezpečnosti:\r\n- Význam a aktuální výzvy v oblasti ochrany dat\r\n- Vývoj hrozeb v digitálním světě\r\n\r\n2. Hlavní bezpečnostní hrozby:\r\n- Kybernetické útoky a jejich dopad na firmy a jednotlivce\r\n- Phishing, malware, ransomware, a další hrozby\r\n\r\n3. Ochrana soukromí a dat:\r\n- Právní předpisy a regulace (např. GDPR)\r\n- Nejlepší praktiky pro zabezpečení citlivých informací\r\n\r\n4. Moderní technologie pro zabezpečení:\r\n- Umělá inteligence a strojové učení v boji proti kybernetickým hrozbám\r\n- Role blockchainu a šifrování\r\n\r\n5. Praktické příklady a případové studie:\r\n- Úspěšné implementace bezpečnostních opatření\r\n- Analýza reálných útoků a poučení z nich\r\n\r\n6. Diskuse o budoucnosti kybernetické bezpečnosti:\r\n- Očekávaný vývoj technologií a nových hrozeb\r\n- Spolupráce mezi firmami a vládami pro lepší ochranu', 5, 2),
(10, 'Inovace v oblasti udržitelné energie', 'Přednáška se zaměří na moderní technologie a inovace v oblasti udržitelné energetiky, které pomáhají snižovat emise a bojovat proti klimatickým změnám. Představíme úspěšné projekty a budoucí směry vývoje v environmentální politice.', 'waiting', 'https://www.energiepriklad.cz/prednaska-materialy', 5, 14),
(14, 'Mikroslužby a jejich implementace v praxi', 'Tato přednáška se bude zabývat návrhem a implementací mikroslužební architektury. Ukážeme osvědčené postupy, výhody oproti monolitickým aplikacím a jak využít cloudová řešení pro škálování a flexibilitu softwarových projektů.', 'waiting', 'https://www.mikrosluzbypriklad.cz/prednaska', 10, 21),
(15, 'Automatizace v DevOps: Nejlepší postupy', 'Přednáška se zaměří na moderní přístupy k automatizaci v DevOps, včetně integrace mikroslužeb, optimalizace nasazování softwaru a využití cloudových řešení. Budou představeny příklady z praxe a tipy na efektivní řízení DevOps procesů.', 'waiting', 'https://www.devopspraktiky.cz/materialy', 10, 18);

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
(14, '2024-11-03', '17:27:47', 390, 0, NULL, 10, 'Daniel', 'Lampa', 'lampa4@azet.cz', 2),
(15, '2024-11-03', '17:28:49', 975, 0, NULL, 8, 'Dana', 'Sviečková', 'dana@gmail.com', 5),
(16, '2024-11-03', '17:29:23', 180, 0, NULL, 6, 'Milan', 'Vačko', 'milo55@gmail.com', 1),
(19, '2024-11-03', '17:34:19', 975, 0, 9, 10, 'Martinko', 'Klingáčik', 'martin@azet.cz', 5),
(20, '2024-11-03', '17:34:34', 450, 0, 9, 7, 'Martinko', 'Klingáčik', 'martin@azet.cz', 3),
(21, '2024-11-03', '17:35:06', 180, 0, 9, 6, 'Martinko', 'Klingáčik', 'martin@azet.cz', 1),
(22, '2024-11-03', '17:35:52', 360, 0, 10, 6, 'Hana', 'Gregorová', 'hana1@gmail.com', 2),
(23, '2024-11-03', '17:36:00', 150, 0, 10, 7, 'Hana', 'Gregorová', 'hana1@gmail.com', 1),
(24, '2024-11-03', '17:36:07', 195, 0, 10, 8, 'Hana', 'Gregorová', 'hana1@gmail.com', 1),
(25, '2024-11-03', '17:42:25', 150, 0, 6, 7, 'Jana', 'Nováková', 'novakova@seznam.cz', 1),
(26, '2024-11-03', '17:42:35', 390, 0, 6, 8, 'Jana', 'Nováková', 'novakova@seznam.cz', 2);

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
  `conference_id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Sťahujem dáta pre tabuľku `tickets`
--

INSERT INTO `tickets` (`id`, `price`, `conference_id`, `reservation_id`) VALUES
(1, 180, 6, 16),
(2, 180, 6, 21),
(3, 180, 6, 22),
(4, 180, 6, 22),
(5, 180, 6, NULL),
(6, 180, 6, NULL),
(7, 180, 6, NULL),
(8, 180, 6, NULL),
(9, 180, 6, NULL),
(10, 180, 6, NULL),
(11, 180, 6, NULL),
(12, 180, 6, NULL),
(13, 180, 6, NULL),
(14, 180, 6, NULL),
(15, 180, 6, NULL),
(16, 180, 6, NULL),
(17, 180, 6, NULL),
(18, 180, 6, NULL),
(19, 150, 7, 20),
(20, 150, 7, 20),
(21, 150, 7, 20),
(22, 150, 7, 23),
(23, 150, 7, 25),
(24, 150, 7, NULL),
(25, 150, 7, NULL),
(26, 150, 7, NULL),
(27, 150, 7, NULL),
(28, 150, 7, NULL),
(29, 150, 7, NULL),
(30, 150, 7, NULL),
(31, 195, 8, 15),
(32, 195, 8, 15),
(33, 195, 8, 15),
(34, 195, 8, 15),
(35, 195, 8, 15),
(36, 195, 8, 24),
(37, 195, 8, 26),
(38, 195, 8, 26),
(39, 195, 8, NULL),
(40, 195, 8, NULL),
(41, 195, 8, NULL),
(42, 195, 8, NULL),
(43, 195, 8, NULL),
(44, 195, 8, NULL),
(45, 195, 8, NULL),
(46, 195, 8, NULL),
(47, 200, 9, NULL),
(48, 200, 9, NULL),
(49, 200, 9, NULL),
(50, 200, 9, NULL),
(51, 200, 9, NULL),
(52, 200, 9, NULL),
(53, 200, 9, NULL),
(54, 200, 9, NULL),
(55, 200, 9, NULL),
(56, 200, 9, NULL),
(57, 195, 10, 14),
(58, 195, 10, 14),
(59, 195, 10, 19),
(60, 195, 10, 19),
(61, 195, 10, 19),
(62, 195, 10, 19),
(63, 195, 10, 19),
(64, 195, 10, NULL),
(65, 195, 10, NULL),
(66, 195, 10, NULL);

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
  ADD KEY `lecture_lesson` (`lecture_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pre tabuľku `conference_has_rooms`
--
ALTER TABLE `conference_has_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pre tabuľku `lectures`
--
ALTER TABLE `lectures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pre tabuľku `presentations`
--
ALTER TABLE `presentations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pre tabuľku `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

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
  ADD CONSTRAINT `lecture_lesson` FOREIGN KEY (`lecture_id`) REFERENCES `lectures` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lecturer_submitted_presentation` FOREIGN KEY (`lecturer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
