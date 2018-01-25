-- MySQL Script generated by MySQL Workbench
-- Thu Jan 25 11:55:21 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema diplomkatalog
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `diplomkatalog` ;

-- -----------------------------------------------------
-- Schema diplomkatalog
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `diplomkatalog` DEFAULT CHARACTER SET utf8 ;
USE `diplomkatalog` ;

-- -----------------------------------------------------
-- Table `diplomkatalog`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `diplomkatalog`.`users` ;

CREATE TABLE IF NOT EXISTS `diplomkatalog`.`users` (
  `email` VARCHAR(100) NOT NULL,
  `passhash` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`email`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `diplomkatalog`.`themas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `diplomkatalog`.`themas` ;

CREATE TABLE IF NOT EXISTS `diplomkatalog`.`themas` (
  `idthema` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `leiter` VARCHAR(100) NOT NULL,
  `betreuer` VARCHAR(100) NOT NULL,
  `textFile` VARCHAR(256) NULL,
  `imageFile` VARCHAR(256) NULL,
  PRIMARY KEY (`idthema`),
  INDEX `fk_themas_users_idx` (`leiter` ASC),
  INDEX `fk_themas_users1_idx` (`betreuer` ASC),
  CONSTRAINT `fk_themas_users`
    FOREIGN KEY (`leiter`)
    REFERENCES `diplomkatalog`.`users` (`email`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_themas_users1`
    FOREIGN KEY (`betreuer`)
    REFERENCES `diplomkatalog`.`users` (`email`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
