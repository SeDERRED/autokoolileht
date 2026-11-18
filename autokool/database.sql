-- database.sql
-- Andmebaasi struktuur ja algandmed

SET NAMES 'utf8mb4';
SET CHARACTER SET utf8mb4;

CREATE DATABASE IF NOT EXISTS `autokool` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `autokool`;

-- Kasutajate tabel (administraatorid)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(20) DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Kursuste tabel
CREATE TABLE IF NOT EXISTS `courses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `description` TEXT NOT NULL,
    `duration` VARCHAR(100) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `image` VARCHAR(255) DEFAULT 'course-default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Koolituse avalduste tabel
CREATE TABLE IF NOT EXISTS `applications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `course_id` INT,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `status` VARCHAR(50) DEFAULT 'new',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Administraatori konto: kasutaja 'admin', parool 'admin123'
INSERT INTO `users` (`username`, `password`, `role`) 
VALUES ('admin', '$2y$10$wT3yD4rYdZ7fL5o188qXl.yKsm23Xb9jPskrS6a9mO15F2z2N0Lmy', 'admin')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- Tavakasutaja konto: kasutaja 'user', parool 'user123'
INSERT INTO `users` (`username`, `password`, `role`) 
VALUES ('user', '$2y$10$wT3yD4rYdZ7fL5o188qXl.yKsm23Xb9jPskrS6a9mO15F2z2N0Lmy', 'user')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- Algkursused
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `courses`;
INSERT INTO `courses` (`title`, `category`, `description`, `duration`, `price`) VALUES
('B-kategooria (Sõiduauto)', 'B', 'Täielik teooria- ja sõiduõppekursus manuaal- või automaatkäigukastiga autol. Ettevalmistus riiklikeks eksamiteks.', '6-8 nädalat', 650.00),
('A-kategooria (Mootorrattad)', 'A', 'Kõikide mootorrataste juhtimisõigus. Praktiline sõiduõpe ja ettevalmistus Transpordiameti eksamiteks.', '4-5 nädalat', 450.00),
('C-kategooria (Veoautod)', 'C', 'Veoautojuhtide kutseõpe sõidukitele registrimassiga üle 3500 kg.', '8-10 nädalat', 850.00),
('Lõppastmekoolitus (Libedasõit)', 'B-talv', 'Spetsiaalne koolitus ohutuks ja säästlikuks sõiduks rasketes teeoludes ning libedarajal.', '1 päev', 120.00);
SET FOREIGN_KEY_CHECKS = 1;
