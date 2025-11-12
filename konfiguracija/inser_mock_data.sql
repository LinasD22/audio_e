SET NAMES utf8mb4;
SET collation_connection = 'utf8mb4_lithuanian_ci';

-- ========== Naudotojai ==========
INSERT INTO `naudotojas`
(`id`,`prisijungimo_vardas`,`paštas`,`slaptažodis`,`rolė`,`pinigai`)
VALUES
(1,'vadybininkas1','vadyb@example.com','hash123','vadybininkas',0.00),
(2,'buhalteris1','buh@example.com','hash123','buhalteris',0.00),
(3,'jonas','jonas@example.com','hash123','vartotojas',150.00),
(4,'ieva','ieva@example.com','hash123','vartotojas',80.00),
(5,'petras','petras@example.com','hash123','vartotojas',500.00);

-- ========== Prekės ==========
INSERT INTO `naudotojas`
(`prisijungimo_vardas`,`paštas`,`slaptažodis`,`rolė`,`pinigai`)
VALUES
('vadybininkas1','vadyb@example.com','hash123','vadybininkas',0.00),
('buhalteris1','buh@example.com','hash123','buhalteris',0.00),
('jonas','jonas@example.com','hash123','vartotojas',150.00),
('ieva','ieva@example.com','hash123','vartotojas',80.00),
('petras','petras@example.com','hash123','vartotojas',500.00);

-- ========== Krepšelis ==========
INSERT INTO `krepšelis`
(`id`,`naudotojo_id`,`sukūrimo_data`)
VALUES
(1,3, NOW() - INTERVAL 2 DAY),   -- Jonas
(2,4, NOW() - INTERVAL 1 DAY),   -- Ieva
(3,NULL, NOW());                 -- svečio krepšelis

-- ========== Krepšelio prekės ==========
INSERT INTO `krepšelio_prekė`
(`krepšelio_id`,`prekės_id`)
VALUES
-- Jonas
(1,7),  -- Edifier R1280T
(1,8),  -- Kabelis
-- Ieva
(2,2),  -- ATH-M50x
(2,8),  -- Kabelis
-- Svečias
(3,1);  -- HD560S

-- ========== Užsakymai ==========
INSERT INTO `užsakymas`
(`naudotojo_id`,`būsena`,`suma`,`sukūrimo_data`,`rezervacijos_galiojimo_data`)
VALUES
-- Jonas: užsakymas patvirtintas
(3,'priimtas', 129.00 + 9.99, NOW() - INTERVAL 1 DAY, NULL),
-- Ieva: rezervo būsena savaitei
(4,'rezervuotos_prekės', 149.00 + 9.99, NOW(), NOW() + INTERVAL 7 DAY),
-- Petras: užsakymas įvykdytas
(5,'įvykdytas', 299.00 + 249.00, NOW() - INTERVAL 5 DAY, NULL);

-- ========== Užsakymo prekės ==========
INSERT INTO `užsakymo_prekė`
(`užsakymo_id`,`prekės_id`,`kaina`)
VALUES
-- Užsakymas #1 (Jonas): kolonėlės + kabelis
(1,7,129.00),
(1,8,9.99),

-- Užsakymas #2 (Ieva): ausinės + kabelis (rezervuota)
(2,2,149.00),
(2,8,9.99),

-- Užsakymas #3 (Petras): stiprintuvas + DAC (įvykdyta)
(3,5,299.00),
(3,6,249.00);
