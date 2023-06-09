-- MySQL Script generated by MySQL Workbench

-- Tue Jun  6 13:32:09 2023

-- Model: New Model    Version: 1.0

-- MySQL Workbench Forward Engineering

SET @ OLD_UNIQUE_CHECKS = @ @ UNIQUE_CHECKS, UNIQUE_CHECKS = 0;

SET
    @ OLD_FOREIGN_KEY_CHECKS = @ @ FOREIGN_KEY_CHECKS,
    FOREIGN_KEY_CHECKS = 0;

SET
    @ OLD_SQL_MODE = @ @ SQL_MODE,
    SQL_MODE = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------

-- Schema mydb

-- -----------------------------------------------------

-- -----------------------------------------------------

-- Schema pwa_project

-- -----------------------------------------------------

CREATE SCHEMA IF NOT EXISTS `pwa_project` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `pwa_project`;

-- -----------------------------------------------------

-- Table `pwa_project`.`users`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `users` (
    `uuid` BINARY(16) NOT NULL,
    `username` VARCHAR(256) NOT NULL,
    `password` VARCHAR(256) NOT NULL,
    `level` INT(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`uuid`),
    UNIQUE INDEX `username` (`username` ASC)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- -----------------------------------------------------

-- Table `pwa_project`.`blog_posts`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `blog_posts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `type` enum('music', 'sport') COLLATE utf8mb4_unicode_ci NOT NULL,
    `image` blob NOT NULL,
    `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `text` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `date_added` date DEFAULT NULL,
    `date_modified` date DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `title_UNIQUE` (`title`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- -----------------------------------------------------

-- Placeholder table for view `pwa_project`.`select_user`

-- -----------------------------------------------------

CREATE TABLE IF NOT EXISTS `select_user` (
    `uuid` INT,
    `username` INT,
    `password` INT,
    `level` INT
);

-- -----------------------------------------------------

-- function BIN_TO_UUID

-- -----------------------------------------------------

DELIMITER $$

CREATE OR REPLACE DEFINER = `root` @ `localhost` FUNCTION `BIN_TO_UUID`(BIN BINARY(16))
RETURNS char(36) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci
BEGIN 
    DECLARE UUID CHARACTER(36);
    DECLARE hexVal CHARACTER(32);
    SET hexVal = HEX(bin);

    SET UUID = LOWER(
        CONCAT(
            LEFT(hexVal, 8),
            '-',
            MID(hexVal, 9, 4),
            '-',
            MID(hexVal, 13, 4),
            '-',
            MID(hexVal, 17, 4),
            '-',
            RIGHT(hexVal, 12)
        )
    );

    RETURN UUID;
END $$

DELIMITER ;

-- -----------------------------------------------------

-- function UUID_TO_BIN

-- -----------------------------------------------------

DELIMITER $$

CREATE OR REPLACE DEFINER = `root` @ `localhost` FUNCTION `UUID_TO_BIN`(UUID CHARACTER(36))
RETURNS binary(16) BEGIN 
    DECLARE bin BINARY(16);
    SET bin = UNHEX(REPLACE(UUID, '-', ''));
    RETURN BIN;
END $$

DELIMITER ;

-- -----------------------------------------------------

-- View `pwa_project`.`select_user`

-- -----------------------------------------------------

DROP TABLE IF EXISTS `select_user`;

CREATE OR REPLACE ALGORITHM = UNDEFINED DEFINER = `root` @ `localhost` SQL SECURITY DEFINER
VIEW `select_user` AS
SELECT
    `BIN_TO_UUID`(`users`.`uuid`) AS `uuid`,
    `users`.`username` AS `username`,
    `users`.`password` AS `password`,
    `users`.`level` AS `level`
FROM `users`;

-- -----------------------------------------------------

-- Trigger `pwa_project`.`users`.`INSERT_UUID`

-- -----------------------------------------------------

DELIMITER $$

CREATE DEFINER = `root` @ `localhost` TRIGGER IF NOT EXISTS `INSERT_UUID` 
BEFORE INSERT ON `users` 
FOR EACH ROW 
BEGIN
    SET NEW.uuid = UUID_TO_BIN(UUID());
END $$

DELIMITER ;

-- -----------------------------------------------------

-- Trigger `pwa_project`.`blog_posts`.`BLOG_POST_INSERT`

-- -----------------------------------------------------

DELIMITER $$

CREATE DEFINDER = `root` @ `localhost` TRIGGER IF NOT EXISTS `BLOG_POST_INSERT`
BEFORE INSERT ON `blog_posts`
BEGIN
    SET NEW.date_added = CURDATE();
EMD $$

DELIMITER ;

-- -----------------------------------------------------

-- Trigger `pwa_project`.`blog_posts`.`BLOG_POST_UPDATE`

-- -----------------------------------------------------

DELIMITER $$

CREATE DEFINER = `root` @ `localhost` TRIGGER IF NOT EXISTS `BLOG_POST_UPDATE`
BEFORE UPDATE ON `blog_posts`
FOR EACH ROW
BEGIN
    SET NEW.date_modified = CURDATE();
END $$

DELIMITER ;

SET SQL_MODE = @ OLD_SQL_MODE;

SET FOREIGN_KEY_CHECKS = @ OLD_FOREIGN_KEY_CHECKS;

SET UNIQUE_CHECKS = @ OLD_UNIQUE_CHECKS;