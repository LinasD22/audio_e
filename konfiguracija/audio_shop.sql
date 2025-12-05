-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2025 at 07:51 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `audio_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `krepšelio_prekė`
--

CREATE TABLE `krepšelio_prekė` (
  `krepšelio_id` int(10) UNSIGNED NOT NULL,
  `prekės_id` int(10) UNSIGNED NOT NULL,
  `kiekis` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;

--
-- Dumping data for table `krepšelio_prekė`
--

INSERT INTO `krepšelio_prekė` (`krepšelio_id`, `prekės_id`, `kiekis`) VALUES
(1, 7, 1),
(1, 8, 1),
(2, 2, 1),
(2, 8, 1),
(3, 1, 1),
(4, 1, 1),
(5, 2, 1),
(5, 5, 1),
(8, 1, 1),
(12, 1, 1),
(14, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `krepšelis`
--

CREATE TABLE `krepšelis` (
  `id` int(10) UNSIGNED NOT NULL,
  `naudotojo_id` int(10) UNSIGNED DEFAULT NULL,
  `sukūrimo_data` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;

--
-- Dumping data for table `krepšelis`
--

INSERT INTO `krepšelis` (`id`, `naudotojo_id`, `sukūrimo_data`) VALUES
(1, NULL, '2025-11-08 19:33:50'),
(2, NULL, '2025-11-09 19:33:50'),
(3, NULL, '2025-11-10 19:33:50'),
(4, NULL, '2025-11-12 19:53:09'),
(5, 12, '2025-11-12 20:24:13'),
(6, 12, '2025-11-12 20:24:37'),
(7, 12, '2025-11-12 21:05:10'),
(8, NULL, '2025-11-12 21:46:17'),
(9, NULL, '2025-11-12 22:15:53'),
(10, NULL, '2025-11-12 22:16:03'),
(11, 12, '2025-11-12 23:10:09'),
(12, 12, '2025-11-12 23:26:05'),
(13, NULL, '2025-11-12 23:29:55'),
(14, 12, '2025-11-12 23:41:05'),
(15, 16, '2025-11-13 21:53:28'),
(16, NULL, '2025-11-14 11:22:56'),
(17, NULL, '2025-11-14 11:58:04'),
(18, 16, '2025-11-14 12:05:18'),
(19, 16, '2025-11-14 12:10:27'),
(20, 14, '2025-11-14 12:10:54'),
(21, 16, '2025-11-14 12:18:26'),
(22, 16, '2025-11-30 16:51:55'),
(23, 16, '2025-11-30 17:09:08');

-- --------------------------------------------------------

--
-- Table structure for table `naudotojas`
--

CREATE TABLE `naudotojas` (
  `id` int(10) UNSIGNED NOT NULL,
  `prisijungimo_vardas` varchar(100) NOT NULL,
  `paštas` varchar(255) NOT NULL,
  `slaptažodis` varchar(255) NOT NULL,
  `rolė` enum('vadybininkas','buhalteris','vartotojas') NOT NULL,
  `pinigai` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;

--
-- Dumping data for table `naudotojas`
--

INSERT INTO `naudotojas` (`id`, `prisijungimo_vardas`, `paštas`, `slaptažodis`, `rolė`, `pinigai`) VALUES
(12, 'linas', 'linas.danusevicius@ktu.edu', '$2y$10$fV7rC6SOWv8ZjdzVRQJh9eNiWWEKnucIpGl9GiV8KxcWP4vS012/m', 'vartotojas', 1008806.00),
(14, 'v', 'v@ktu.edu', '$2y$10$jh5CDs8wNrIEwdfVw64RfeIZAO02ABLZRVGxftBtsIS/g//ooPfpu', 'vadybininkas', 0.00),
(15, 'b', 'b@ktu.edu', '$2y$10$6LNGcu85tpXEoG351M9nSusPK0ZraIA/riVRaCtDNJ4BvVNGcpDdK', 'buhalteris', 0.00),
(16, 'stud', 'stud@ktu.edu', '$2y$10$Hr5fwnCx36DJGSRmzVz03OFDe1nApryn1MWuNc3I2wysjZ7pees22', 'vartotojas', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `prekė`
--

CREATE TABLE `prekė` (
  `id` int(10) UNSIGNED NOT NULL,
  `pavadinimas` varchar(200) NOT NULL,
  `paskirtis` varchar(100) NOT NULL,
  `tipas` varchar(100) NOT NULL,
  `gamintojas` varchar(100) NOT NULL,
  `modelis` varchar(100) NOT NULL,
  `kaina` decimal(12,2) NOT NULL,
  `likutis` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;

--
-- Dumping data for table `prekė`
--

INSERT INTO `prekė` (`id`, `pavadinimas`, `paskirtis`, `tipas`, `gamintojas`, `modelis`, `kaina`, `likutis`) VALUES
(1, 'Ausinės HD560S', 'klausymui', 'ausinės', 'Sennheiser', 'HD560S', 199.00, 22),
(2, 'Ausinės ATH-M50x', 'klausymui', 'ausinės', 'Audio-Technica', 'ATH-M50x', 149.00, 38),
(3, 'Garso interfeisas Scarlett 2i2', 'įrašymui', 'garso interfeisas', 'Focusrite', 'Scarlett 2i2 (4th Gen)', 189.00, 15),
(4, 'Studijinis mikrofonas NT1', 'įrašymui', 'mikrofonas', 'RØDE', 'NT1 (2021)', 269.00, 10),
(5, 'Stiprintuvas A-9110', 'klausymui', 'stiprintuvas', 'Onkyo', 'A-9110', 299.00, 1),
(6, 'Skaitmeninis-analoginis keitiklis D50s', 'klausymui', 'DAC', 'Topping', 'D50s', 249.00, 12),
(7, 'Kolonėlės R1280T', 'klausymui', 'kolonėlės', 'Edifier', 'R1280T', 129.00, 29),
(8, 'Kabelis 3.5mm–RCA 1.5m', 'priedai', 'kabelis', 'Hama', '001', 9.99, 99);

-- --------------------------------------------------------

--
-- Table structure for table `užsakymas`
--

CREATE TABLE `užsakymas` (
  `id` int(10) UNSIGNED NOT NULL,
  `naudotojo_id` int(10) UNSIGNED NOT NULL,
  `būsena` enum('pateiktas','priimtas','rezervuotos_prekės','įvykdytas') NOT NULL,
  `suma` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sukūrimo_data` timestamp NOT NULL DEFAULT current_timestamp(),
  `rezervacijos_galiojimo_data` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;

--
-- Dumping data for table `užsakymas`
--

INSERT INTO `užsakymas` (`id`, `naudotojo_id`, `būsena`, `suma`, `sukūrimo_data`, `rezervacijos_galiojimo_data`) VALUES
(8, 16, 'rezervuotos_prekės', 468.00, '2025-11-14 12:05:36', '2025-11-21 12:09:29'),
(9, 16, 'pateiktas', 39.96, '2025-11-14 12:06:03', NULL),
(10, 16, 'pateiktas', 598.00, '2025-11-14 12:07:47', NULL),
(11, 16, 'pateiktas', 9.99, '2025-11-14 12:10:29', NULL),
(12, 16, 'pateiktas', 378.00, '2025-11-14 12:19:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `užsakymo_prekė`
--

CREATE TABLE `užsakymo_prekė` (
  `užsakymo_id` int(10) UNSIGNED NOT NULL,
  `prekės_id` int(10) UNSIGNED NOT NULL,
  `kiekis` int(11) NOT NULL DEFAULT 1,
  `kaina` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;

--
-- Dumping data for table `užsakymo_prekė`
--

INSERT INTO `užsakymo_prekė` (`užsakymo_id`, `prekės_id`, `kiekis`, `kaina`) VALUES
(8, 1, 1, 199.00),
(8, 4, 1, 269.00),
(9, 8, 4, 9.99),
(10, 5, 2, 299.00),
(11, 8, 1, 9.99),
(12, 3, 2, 189.00);

-- --------------------------------------------------------

--
-- Table structure for table `žinutė`
--

CREATE TABLE `žinutė` (
  `id` int(10) UNSIGNED NOT NULL,
  `naudotojo_id` int(10) UNSIGNED NOT NULL,
  `turinys` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `krepšelio_prekė`
--
ALTER TABLE `krepšelio_prekė`
  ADD PRIMARY KEY (`krepšelio_id`,`prekės_id`),
  ADD KEY `idx_krepšelio_prekė_prekė` (`prekės_id`);

--
-- Indexes for table `krepšelis`
--
ALTER TABLE `krepšelis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_krepšelis_naudotojas` (`naudotojo_id`);

--
-- Indexes for table `naudotojas`
--
ALTER TABLE `naudotojas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `paštas` (`paštas`);

--
-- Indexes for table `prekė`
--
ALTER TABLE `prekė`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `užsakymas`
--
ALTER TABLE `užsakymas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_užsakymas_naudotojas` (`naudotojo_id`);

--
-- Indexes for table `užsakymo_prekė`
--
ALTER TABLE `užsakymo_prekė`
  ADD PRIMARY KEY (`užsakymo_id`,`prekės_id`),
  ADD KEY `idx_užsakymo_prekė_prekė` (`prekės_id`);

--
-- Indexes for table `žinutė`
--
ALTER TABLE `žinutė`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_žinutė_naudotojas` (`naudotojo_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `krepšelis`
--
ALTER TABLE `krepšelis`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `naudotojas`
--
ALTER TABLE `naudotojas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `prekė`
--
ALTER TABLE `prekė`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `užsakymas`
--
ALTER TABLE `užsakymas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `žinutė`
--
ALTER TABLE `žinutė`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `krepšelio_prekė`
--
ALTER TABLE `krepšelio_prekė`
  ADD CONSTRAINT `fk_kp_krepšelis` FOREIGN KEY (`krepšelio_id`) REFERENCES `krepšelis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_kp_prekė` FOREIGN KEY (`prekės_id`) REFERENCES `prekė` (`id`);

--
-- Constraints for table `krepšelis`
--
ALTER TABLE `krepšelis`
  ADD CONSTRAINT `fk_krepšelis_naudotojas` FOREIGN KEY (`naudotojo_id`) REFERENCES `naudotojas` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `užsakymas`
--
ALTER TABLE `užsakymas`
  ADD CONSTRAINT `fk_užsakymas_naudotojas` FOREIGN KEY (`naudotojo_id`) REFERENCES `naudotojas` (`id`);

--
-- Constraints for table `užsakymo_prekė`
--
ALTER TABLE `užsakymo_prekė`
  ADD CONSTRAINT `fk_up_prekė` FOREIGN KEY (`prekės_id`) REFERENCES `prekė` (`id`),
  ADD CONSTRAINT `fk_up_uzsakymas` FOREIGN KEY (`užsakymo_id`) REFERENCES `užsakymas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `žinutė`
--
ALTER TABLE `žinutė`
  ADD CONSTRAINT `fk_žinutė_naudotojas` FOREIGN KEY (`naudotojo_id`) REFERENCES `naudotojas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
