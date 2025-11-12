CREATE TABLE `naudotojas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `prisijungimo_vardas` VARCHAR(100) NOT NULL,
  `paštas` VARCHAR(255) NOT NULL UNIQUE,
  `slaptažodis` VARCHAR(255) NOT NULL,
  `rolė` ENUM('vadybininkas','buhalteris','vartotojas') NOT NULL,
  `pinigai` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;


CREATE TABLE `prekė` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pavadinimas` VARCHAR(200) NOT NULL,
  `paskirtis`   VARCHAR(100) NOT NULL,
  `tipas`       VARCHAR(100) NOT NULL,
  `gamintojas`  VARCHAR(100) NOT NULL,
  `modelis`     VARCHAR(100) NOT NULL,
  `kaina`       DECIMAL(12,2) NOT NULL,
  `likutis`     INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;


-- Vienas aktyvus krepšelis naudotojui – taisyklė programos lygyje.
CREATE TABLE `krepšelis` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `naudotojo_id` INT UNSIGNED NULL,
  `sukūrimo_data` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_krepšelis_naudotojas` (`naudotojo_id`),
  CONSTRAINT `fk_krepšelis_naudotojas`
    FOREIGN KEY (`naudotojo_id`) REFERENCES `naudotojas`(`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;


-- Krepšelio prekės
CREATE TABLE `krepšelio_prekė` (
  `krepšelio_id` INT UNSIGNED NOT NULL,
  `prekės_id`    INT UNSIGNED NOT NULL,
  -- `kiekis`     INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`krepšelio_id`,`prekės_id`),
  KEY `idx_krepšelio_prekė_prekė` (`prekės_id`),
  CONSTRAINT `fk_kp_krepšelis`
    FOREIGN KEY (`krepšelio_id`) REFERENCES `krepšelis`(`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_kp_prekė`
    FOREIGN KEY (`prekės_id`) REFERENCES `prekė`(`id`)
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;


-- Užsakymai
CREATE TABLE `užsakymas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `naudotojo_id` INT UNSIGNED NOT NULL,
  `būsena` ENUM('pateiktas','priimtas','rezervuotos_prekės','įvykdytas') NOT NULL,
  `suma` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `sukūrimo_data` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rezervacijos_galiojimo_data` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `idx_užsakymas_naudotojas` (`naudotojo_id`),
  CONSTRAINT `fk_užsakymas_naudotojas`
    FOREIGN KEY (`naudotojo_id`) REFERENCES `naudotojas`(`id`)
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;


-- Užsakymo prekės
CREATE TABLE `užsakymo_prekė` (
  `užsakymo_id` INT UNSIGNED NOT NULL,
  `prekės_id`   INT UNSIGNED NOT NULL,
  `kaina`       DECIMAL(12,2) NOT NULL,  -- kaina užsakymo metu (snapshot)
  PRIMARY KEY (`užsakymo_id`,`prekės_id`),
  KEY `idx_užsakymo_prekė_prekė` (`prekės_id`),
  CONSTRAINT `fk_up_uzsakymas`
    FOREIGN KEY (`užsakymo_id`) REFERENCES `užsakymas`(`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_up_prekė`
    FOREIGN KEY (`prekės_id`) REFERENCES `prekė`(`id`)
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;
