-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2025 at 10:14 PM
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
(5, 5, 1);

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
(1, 3, '2025-11-08 19:33:50'),
(2, 4, '2025-11-09 19:33:50'),
(3, NULL, '2025-11-10 19:33:50'),
(4, NULL, '2025-11-12 19:53:09'),
(5, 12, '2025-11-12 20:24:13'),
(6, 12, '2025-11-12 20:24:37'),
(7, 12, '2025-11-12 21:05:10');

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
(1, 'vadybininkas1', 'vadyb@example.com', 'h', 'vadybininkas', 0.00),
(2, 'buhalteris1', 'buh@example.com', 'hash123', 'buhalteris', 0.00),
(3, 'jonas', 'jonas@example.com', 'hash123', 'vartotojas', 150.00),
(4, 'ieva', 'ieva@example.com', 'hash123', 'vartotojas', 80.00),
(5, 'petras', 'petras@example.com', 'hash123', 'vartotojas', 500.00),
(12, 'linas', 'linas.danusevicius@ktu.edu', '$2y$10$fV7rC6SOWv8ZjdzVRQJh9eNiWWEKnucIpGl9GiV8KxcWP4vS012/m', 'vartotojas', 10000.00);

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
(1, 'Ausinės HD560S', 'klausymui', 'ausinės', 'Sennheiser', 'HD560S', 199.00, 25),
(2, 'Ausinės ATH-M50x', 'klausymui', 'ausinės', 'Audio-Technica', 'ATH-M50x', 149.00, 40),
(3, 'Garso interfeisas Scarlett 2i2', 'įrašymui', 'garso interfeisas', 'Focusrite', 'Scarlett 2i2 (4th Gen)', 189.00, 15),
(4, 'Studijinis mikrofonas NT1', 'įrašymui', 'mikrofonas', 'RØDE', 'NT1 (2021)', 269.00, 10),
(5, 'Stiprintuvas A-9110', 'klausymui', 'stiprintuvas', 'Onkyo', 'A-9110', 299.00, 8),
(6, 'Skaitmeninis-analoginis keitiklis D50s', 'klausymui', 'DAC', 'Topping', 'D50s', 249.00, 12),
(7, 'Kolonėlės R1280T', 'klausymui', 'kolonėlės', 'Edifier', 'R1280T', 129.00, 30),
(8, 'Kabelis 3.5mm–RCA 1.5m', 'priedai', 'kabelis', 'Hama', '001', 9.99, 100);

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
(1, 3, 'priimtas', 138.99, '2025-11-09 19:32:35', NULL),
(2, 4, 'rezervuotos_prekės', 158.99, '2025-11-10 19:32:35', '2025-11-17 19:32:35'),
(3, 5, 'įvykdytas', 548.00, '2025-11-05 19:32:35', NULL),
(4, 12, 'pateiktas', 597.00, '2025-11-12 21:05:18', NULL);

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
(1, 7, 1, 129.00),
(1, 8, 1, 9.99),
(2, 2, 1, 149.00),
(2, 8, 1, 9.99),
(3, 5, 1, 299.00),
(3, 6, 1, 249.00),
(4, 1, 3, 199.00);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `krepšelis`
--
ALTER TABLE `krepšelis`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `naudotojas`
--
ALTER TABLE `naudotojas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `prekė`
--
ALTER TABLE `prekė`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `užsakymas`
--
ALTER TABLE `užsakymas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
