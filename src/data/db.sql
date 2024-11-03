-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1
-- Čas generovania: Sun 03.Nov 2024, 14:55
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
(10, 'Moderní trendy v softwarovém inženýrství', 'Vývoj softwaru, DevOps', 'Praktické workshopy a přednášky o moderních praktikách vývoje softwaru, včetně mikroservisních architektur, cloudových řešení a automatizace v DevOps.', '2024-11-12 10:00:00', '2024-11-12 17:00:00', 195, 5, 6);

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

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `presentations`
--

CREATE TABLE `presentations` (
  `id` int(11) NOT NULL,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` enum('approved','waiting','denied') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lecturer_id` int(11) NOT NULL,
  `lecture_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
(7, 'Janko', 'Hraško', 'janci555@gmail.com', '$2y$10$ALvATocAGVzN1K1hyKa67uylELgW2wZuKuNlvhsxI.7F6sjE/lLnS', 'user'),
(8, 'Sv.', 'Mikuláš', 'mikulas@gmail.com', '$2y$10$8vX.8XdxiwgbxYMB8AGbH./FygLCtU7GyAJbxyMztWa4dWSTv/e.S', 'user'),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `presentations`
--
ALTER TABLE `presentations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pre tabuľku `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

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
