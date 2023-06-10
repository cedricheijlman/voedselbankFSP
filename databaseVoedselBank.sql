-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema voedselBank
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema voedselBank
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `voedselBank` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema voedselbank
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema voedselbank
-- -----------------------------------------------------
USE `voedselBank` ;

-- -----------------------------------------------------
-- Table `voedselBank`.`klant`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselBank`.`klant` (
  `id_klant` INT NOT NULL AUTO_INCREMENT,
  `naam` VARCHAR(45) NULL,
  `tussenvoegsel` VARCHAR(9) NULL,
  `achternaam` VARCHAR(45) NULL,
  `postcode` VARCHAR(6) NULL,
  `huisnummer` VARCHAR(9) NULL,
  `plaats` VARCHAR(99) NULL,
  `telefoon` VARCHAR(10) NULL,
  `email` VARCHAR(45) NULL,
  `volwassenen` INT NULL,
  `kinderen` INT NULL,
  `baby's` INT NULL,
  PRIMARY KEY (`id_klant`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `voedselBank`.`voedselpakket`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselBank`.`voedselpakket` (
  `pakket_nr` INT NOT NULL AUTO_INCREMENT,
  `klant_id` INT NOT NULL,
  `samenstelling` DATE NULL,
  `uitgifte` DATE NULL,
  PRIMARY KEY (`pakket_nr`),
  INDEX `fk_voedselpakket_klant_idx` (`klant_id` ASC) VISIBLE,
  CONSTRAINT `fk_voedselpakket_klant`
    FOREIGN KEY (`klant_id`)
    REFERENCES `voedselBank`.`klant` (`id_klant`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `voedselBank`.`categorie`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselBank`.`categorie` (
  `id_categorie` INT NOT NULL,
  `categorie` VARCHAR(99) NULL,
  PRIMARY KEY (`id_categorie`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `voedselBank`.`product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselBank`.`product` (
  `streepjescode` INT NOT NULL,
  `categorie_id` INT NOT NULL,
  `naam` VARCHAR(99) NULL,
  PRIMARY KEY (`streepjescode`),
  INDEX `fk_product_categorie1_idx` (`categorie_id` ASC) VISIBLE,
  CONSTRAINT `fk_product_categorie1`
    FOREIGN KEY (`categorie_id`)
    REFERENCES `voedselBank`.`categorie` (`id_categorie`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `voedselBank`.`productenlijst`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselBank`.`productenlijst` (
  `pakket_nr` INT NOT NULL,
  `streepjescode` INT NOT NULL,
  `aantal` INT NULL,
  PRIMARY KEY (`pakket_nr`, `streepjescode`),
  INDEX `fk_voedselpakket_has_product_product1_idx` (`streepjescode` ASC) VISIBLE,
  INDEX `fk_voedselpakket_has_product_voedselpakket1_idx` (`pakket_nr` ASC) VISIBLE,
  CONSTRAINT `fk_voedselpakket_has_product_voedselpakket1`
    FOREIGN KEY (`pakket_nr`)
    REFERENCES `voedselBank`.`voedselpakket` (`pakket_nr`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_voedselpakket_has_product_product1`
    FOREIGN KEY (`streepjescode`)
    REFERENCES `voedselBank`.`product` (`streepjescode`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `voedselBank`.`leverancier`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselBank`.`leverancier` (
  `id_leverancier` INT NOT NULL AUTO_INCREMENT,
  `bedrijfsnaam` VARCHAR(45) NULL,
  `postcode` VARCHAR(6) NULL,
  `huisnummer` VARCHAR(6) NULL,
  `plaats` VARCHAR(99) NULL,
  `email` VARCHAR(99) NULL,
  `telefoon` VARCHAR(10) NULL,
  PRIMARY KEY (`id_leverancier`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `voedselBank`.`eerst_volgende_levering`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselBank`.`eerst_volgende_levering` (
  `id_levering` INT NOT NULL AUTO_INCREMENT,
  `id_leverancier` INT NOT NULL,
  `datum` DATE NULL,
  `tijd` TIME NULL,
  PRIMARY KEY (`id_levering`),
  INDEX `fk_levering_leverancier1_idx` (`id_leverancier` ASC) VISIBLE,
  CONSTRAINT `fk_levering_leverancier1`
    FOREIGN KEY (`id_leverancier`)
    REFERENCES `voedselBank`.`leverancier` (`id_leverancier`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `voedselBank`.`levering_has_product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselBank`.`levering_has_product` (
  `id_levering` INT NOT NULL,
  `streepjescode` INT NOT NULL,
  `aantal` INT NULL,
  PRIMARY KEY (`id_levering`, `streepjescode`),
  INDEX `fk_levering_has_product_product1_idx` (`streepjescode` ASC) VISIBLE,
  INDEX `fk_levering_has_product_levering1_idx` (`id_levering` ASC) VISIBLE,
  CONSTRAINT `fk_levering_has_product_levering1`
    FOREIGN KEY (`id_levering`)
    REFERENCES `voedselBank`.`eerst_volgende_levering` (`id_levering`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_levering_has_product_product1`
    FOREIGN KEY (`streepjescode`)
    REFERENCES `voedselBank`.`product` (`streepjescode`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `voedselBank`.`soortgebruiker`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselBank`.`soortgebruiker` (
  `id_soortgebruiker` INT NOT NULL,
  `soortgebruiker` VARCHAR(99) NULL,
  PRIMARY KEY (`id_soortgebruiker`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `voedselBank`.`gebruiker`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselBank`.`gebruiker` (
  `id_gebruiker` INT NOT NULL,
  `id_soortgebruiker` INT NULL,
  `gebruikersnaam` VARCHAR(99) NULL,
  `naam` VARCHAR(99) NULL,
  `tussenvoegsel` VARCHAR(10) NULL,
  `achternaam` VARCHAR(99) NULL,
  `email` VARCHAR(99) NULL,
  `telefoon` VARCHAR(10) NULL,
  `wachtwoord` VARCHAR(999) NULL,
  PRIMARY KEY (`id_gebruiker`),
  INDEX `fk_gebruiker_soortgebruiker1_idx` (`id_soortgebruiker` ASC) VISIBLE,
  CONSTRAINT `fk_gebruiker_soortgebruiker1`
    FOREIGN KEY (`id_soortgebruiker`)
    REFERENCES `voedselBank`.`soortgebruiker` (`id_soortgebruiker`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `voedselBank`.`wens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselBank`.`wens` (
  `id_wens` INT NOT NULL AUTO_INCREMENT,
  `wens` VARCHAR(999) NULL,
  PRIMARY KEY (`id_wens`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `voedselBank`.`klant_has_wens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselBank`.`klant_has_wens` (
  `id_klant` INT NOT NULL,
  `id_wens` INT NOT NULL,
  PRIMARY KEY (`id_klant`, `id_wens`),
  INDEX `fk_klant_has_wens_wens1_idx` (`id_wens` ASC) VISIBLE,
  INDEX `fk_klant_has_wens_klant1_idx` (`id_klant` ASC) VISIBLE,
  CONSTRAINT `fk_klant_has_wens_klant1`
    FOREIGN KEY (`id_klant`)
    REFERENCES `voedselBank`.`klant` (`id_klant`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_klant_has_wens_wens1`
    FOREIGN KEY (`id_wens`)
    REFERENCES `voedselBank`.`wens` (`id_wens`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `voedselbank` ;

-- -----------------------------------------------------
-- Table `voedselbank`.`categorie`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselbank`.`categorie` (
  `id_categorie` INT NOT NULL,
  `categorie` VARCHAR(99) NULL DEFAULT NULL,
  PRIMARY KEY (`id_categorie`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `voedselbank`.`leverancier`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselbank`.`leverancier` (
  `id_leverancier` INT NOT NULL AUTO_INCREMENT,
  `bedrijfsnaam` VARCHAR(45) NULL DEFAULT NULL,
  `postcode` VARCHAR(6) NULL DEFAULT NULL,
  `huisnummer` VARCHAR(6) NULL DEFAULT NULL,
  `plaats` VARCHAR(99) NULL DEFAULT NULL,
  `email` VARCHAR(99) NULL DEFAULT NULL,
  `telefoon` VARCHAR(10) NULL DEFAULT NULL,
  PRIMARY KEY (`id_leverancier`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `voedselbank`.`eerst_volgende_levering`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselbank`.`eerst_volgende_levering` (
  `id_levering` INT NOT NULL AUTO_INCREMENT,
  `id_leverancier` INT NOT NULL,
  `datum` DATE NULL DEFAULT NULL,
  `tijd` TIME NULL DEFAULT NULL,
  PRIMARY KEY (`id_levering`),
  INDEX `fk_levering_leverancier1_idx` (`id_leverancier` ASC) VISIBLE,
  CONSTRAINT `fk_levering_leverancier1`
    FOREIGN KEY (`id_leverancier`)
    REFERENCES `voedselbank`.`leverancier` (`id_leverancier`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `voedselbank`.`soortgebruiker`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselbank`.`soortgebruiker` (
  `id_soortgebruiker` INT NOT NULL,
  `soortgebruiker` VARCHAR(99) NULL DEFAULT NULL,
  PRIMARY KEY (`id_soortgebruiker`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `voedselbank`.`gebruiker`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselbank`.`gebruiker` (
  `id_gebruiker` INT NOT NULL AUTO_INCREMENT,
  `id_soortgebruiker` INT NULL DEFAULT NULL,
  `naam` VARCHAR(99) NULL DEFAULT NULL,
  `tussenvoegsel` VARCHAR(10) NULL DEFAULT NULL,
  `achternaam` VARCHAR(99) NULL DEFAULT NULL,
  `email` VARCHAR(99) NULL DEFAULT NULL,
  `telefoon` VARCHAR(10) NULL DEFAULT NULL,
  `wachtwoord` VARCHAR(999) NULL DEFAULT NULL,
  `gebruikersnaam` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id_gebruiker`),
  INDEX `fk_gebruiker_soortgebruiker1_idx` (`id_soortgebruiker` ASC) VISIBLE,
  CONSTRAINT `fk_gebruiker_soortgebruiker1`
    FOREIGN KEY (`id_soortgebruiker`)
    REFERENCES `voedselbank`.`soortgebruiker` (`id_soortgebruiker`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `voedselbank`.`klant`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselbank`.`klant` (
  `id_klant` INT NOT NULL AUTO_INCREMENT,
  `naam` VARCHAR(45) NULL DEFAULT NULL,
  `tussenvoegsel` VARCHAR(9) NULL DEFAULT NULL,
  `achternaam` VARCHAR(45) NULL DEFAULT NULL,
  `postcode` VARCHAR(6) NULL DEFAULT NULL,
  `huisnummer` VARCHAR(9) NULL DEFAULT NULL,
  `plaats` VARCHAR(99) NULL DEFAULT NULL,
  `telefoon` VARCHAR(10) NULL DEFAULT NULL,
  `email` VARCHAR(45) NULL DEFAULT NULL,
  `volwassenen` INT NULL DEFAULT NULL,
  `kinderen` INT NULL DEFAULT NULL,
  `baby's` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id_klant`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `voedselbank`.`wens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselbank`.`wens` (
  `id_wens` INT NOT NULL,
  `wens` VARCHAR(999) NULL DEFAULT NULL,
  PRIMARY KEY (`id_wens`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `voedselbank`.`klant_has_wens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselbank`.`klant_has_wens` (
  `id_klant` INT NOT NULL,
  `id_wens` INT NOT NULL,
  PRIMARY KEY (`id_klant`, `id_wens`),
  INDEX `fk_klant_has_wens_wens1_idx` (`id_wens` ASC) VISIBLE,
  INDEX `fk_klant_has_wens_klant1_idx` (`id_klant` ASC) VISIBLE,
  CONSTRAINT `fk_klant_has_wens_klant1`
    FOREIGN KEY (`id_klant`)
    REFERENCES `voedselbank`.`klant` (`id_klant`),
  CONSTRAINT `fk_klant_has_wens_wens1`
    FOREIGN KEY (`id_wens`)
    REFERENCES `voedselbank`.`wens` (`id_wens`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `voedselbank`.`product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselbank`.`product` (
  `streepjescode` INT NOT NULL,
  `categorie_id` INT NOT NULL,
  `naam` VARCHAR(99) NULL DEFAULT NULL,
  PRIMARY KEY (`streepjescode`),
  INDEX `fk_product_categorie1_idx` (`categorie_id` ASC) VISIBLE,
  CONSTRAINT `fk_product_categorie1`
    FOREIGN KEY (`categorie_id`)
    REFERENCES `voedselbank`.`categorie` (`id_categorie`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;

ALTER TABLE gebruiker MODIFY id_soortgebruiker INT NULL;

-- -----------------------------------------------------
-- Table `voedselbank`.`levering_has_product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselbank`.`levering_has_product` (
  `id_levering` INT NOT NULL,
  `streepjescode` INT NOT NULL,
  `aantal` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id_levering`, `streepjescode`),
  INDEX `fk_levering_has_product_product1_idx` (`streepjescode` ASC) VISIBLE,
  INDEX `fk_levering_has_product_levering1_idx` (`id_levering` ASC) VISIBLE,
  CONSTRAINT `fk_levering_has_product_levering1`
    FOREIGN KEY (`id_levering`)
    REFERENCES `voedselbank`.`eerst_volgende_levering` (`id_levering`),
  CONSTRAINT `fk_levering_has_product_product1`
    FOREIGN KEY (`streepjescode`)
    REFERENCES `voedselbank`.`product` (`streepjescode`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `voedselbank`.`voedselpakket`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselbank`.`voedselpakket` (
  `pakket_nr` INT NOT NULL AUTO_INCREMENT,
  `klant_id` INT NOT NULL,
  `samenstelling` DATE NULL DEFAULT NULL,
  `uitgifte` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`pakket_nr`),
  INDEX `fk_voedselpakket_klant_idx` (`klant_id` ASC) VISIBLE,
  CONSTRAINT `fk_voedselpakket_klant`
    FOREIGN KEY (`klant_id`)
    REFERENCES `voedselbank`.`klant` (`id_klant`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `voedselbank`.`productenlijst`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `voedselbank`.`productenlijst` (
  `pakket_nr` INT NOT NULL,
  `streepjescode` INT NOT NULL,
  `aantal` INT NULL DEFAULT NULL,
  PRIMARY KEY (`pakket_nr`, `streepjescode`),
  INDEX `fk_voedselpakket_has_product_product1_idx` (`streepjescode` ASC) VISIBLE,
  INDEX `fk_voedselpakket_has_product_voedselpakket1_idx` (`pakket_nr` ASC) VISIBLE,
  CONSTRAINT `fk_voedselpakket_has_product_product1`
    FOREIGN KEY (`streepjescode`)
    REFERENCES `voedselbank`.`product` (`streepjescode`),
  CONSTRAINT `fk_voedselpakket_has_product_voedselpakket1`
    FOREIGN KEY (`pakket_nr`)
    REFERENCES `voedselbank`.`voedselpakket` (`pakket_nr`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `voedselBank`.`categorie`
-- -----------------------------------------------------
START TRANSACTION;
USE `voedselBank`;
INSERT INTO `voedselBank`.`categorie` (`id_categorie`, `categorie`) VALUES (1, 'Aardappelen, groente, fruit');
INSERT INTO `voedselBank`.`categorie` (`id_categorie`, `categorie`) VALUES (2, 'Kaas, vleeswaren');
INSERT INTO `voedselBank`.`categorie` (`id_categorie`, `categorie`) VALUES (3, 'Zuivel, plantaardig en eieren');
INSERT INTO `voedselBank`.`categorie` (`id_categorie`, `categorie`) VALUES (4, 'Bakkerij en banket');
INSERT INTO `voedselBank`.`categorie` (`id_categorie`, `categorie`) VALUES (5, 'Frisdrank, sappen, koffie en thee');
INSERT INTO `voedselBank`.`categorie` (`id_categorie`, `categorie`) VALUES (6, 'Pasta, rijst en wereldkeuken');
INSERT INTO `voedselBank`.`categorie` (`id_categorie`, `categorie`) VALUES (7, 'Soepen, sauzen, kruiden en olie');
INSERT INTO `voedselBank`.`categorie` (`id_categorie`, `categorie`) VALUES (8, 'Snoep, koek, chips en chocolade');
INSERT INTO `voedselBank`.`categorie` (`id_categorie`, `categorie`) VALUES (9, 'Baby, verzorging en hygiëne');

COMMIT;


-- -----------------------------------------------------
-- Data for table `voedselBank`.`soortgebruiker`
-- -----------------------------------------------------
START TRANSACTION;
USE `voedselBank`;
INSERT INTO `voedselBank`.`soortgebruiker` (`id_soortgebruiker`, `soortgebruiker`) VALUES (1, 'Directie');
INSERT INTO `voedselBank`.`soortgebruiker` (`id_soortgebruiker`, `soortgebruiker`) VALUES (2, 'Magazijnmedewerker');
INSERT INTO `voedselBank`.`soortgebruiker` (`id_soortgebruiker`, `soortgebruiker`) VALUES (3, 'Vrijwilliger');
INSERT INTO `voedselBank`.`soortgebruiker` (`id_soortgebruiker`, `soortgebruiker`) VALUES (4, 'Klant');

COMMIT;


-- -----------------------------------------------------
-- Data for table `voedselBank`.`wens`
-- -----------------------------------------------------
START TRANSACTION;
USE `voedselBank`;
INSERT INTO `voedselBank`.`wens` (`id_wens`, `wens`) VALUES (1, 'Geen varkensvlees');
INSERT INTO `voedselBank`.`wens` (`id_wens`, `wens`) VALUES (2, 'Veganistisch');
INSERT INTO `voedselBank`.`wens` (`id_wens`, `wens`) VALUES (3, 'Vegetarisch');

COMMIT;
