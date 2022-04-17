-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 15 avr. 2022 à 19:32
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `rando_addicts`
--

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

DROP TABLE IF EXISTS `compte`;
CREATE TABLE IF NOT EXISTS `compte` (
  `Id_Compte` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(99) DEFAULT NULL,
  `Prenom` varchar(99) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Numero` varchar(10) DEFAULT NULL,
  `Email` varchar(99) DEFAULT NULL,
  `Mot_de_passe` varchar(99) DEFAULT NULL,
  `Date_creation` date,
  `Date_derniere_connexion` date,
  PRIMARY KEY (`Id_Compte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `compte_role`
--

DROP TABLE IF EXISTS `compte_role`;
CREATE TABLE IF NOT EXISTS `compte_role` (
  `Id_Compte` int(11) NOT NULL,
  `Id_Role` decimal(10,0) NOT NULL,
  PRIMARY KEY (`Id_Compte`,`Id_Role`),
  KEY `Id_Role` (`Id_Role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `equipement`
--

DROP TABLE IF EXISTS `equipement`;
CREATE TABLE IF NOT EXISTS `equipement` (
  `Id_Equipement` decimal(10,0) NOT NULL,
  `Nom` varchar(99) NOT NULL,
  `Description` varchar(254) DEFAULT NULL,
  `Poids_moyen` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`Id_Equipement`),
  UNIQUE KEY `Nom` (`Nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `excursion`
--

DROP TABLE IF EXISTS `excursion`;
CREATE TABLE IF NOT EXISTS `excursion` (
  `Id_Excursion` decimal(10,0) NOT NULL,
  `Nom` varchar(99) NOT NULL,
  `Description` varchar(254) NOT NULL,
  `Tarif` decimal(10,0) NOT NULL,
  `Capacite` decimal(10,0) NOT NULL,
  `Difficulte` decimal(10,0) NOT NULL,
  `Terrain` enum('foret','marais','montagne','urbain','mer','desert') NOT NULL,
  `Lieu_Debut` decimal(10,0) NOT NULL,
  `Lieu_Fin` decimal(10,0) NOT NULL,
  PRIMARY KEY (`Id_Excursion`),
  KEY `Lieu_Debut` (`Lieu_Debut`),
  KEY `Lieu_Fin` (`Lieu_Fin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `excursion_equipement`
--

DROP TABLE IF EXISTS `excursion_equipement`;
CREATE TABLE IF NOT EXISTS `excursion_equipement` (
  `Id_Excursion` decimal(10,0) NOT NULL,
  `Id_Equipement` decimal(10,0) NOT NULL,
  PRIMARY KEY (`Id_Excursion`,`Id_Equipement`),
  KEY `Id_Equipement` (`Id_Equipement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `lieu`
--

DROP TABLE IF EXISTS `lieu`;
CREATE TABLE IF NOT EXISTS `lieu` (
  `Id_Lieu` decimal(10,0) NOT NULL,
  `Ville` varchar(99) NOT NULL,
  `CP` decimal(10,0) NOT NULL,
  `Region` varchar(99) NOT NULL,
  `Latitude` decimal(10,0) DEFAULT NULL,
  `Longitude` decimal(10,0) DEFAULT NULL,
  `Altitude` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`Id_Lieu`),
  UNIQUE KEY `Ville` (`Ville`),
  UNIQUE KEY `CP` (`CP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `programme`
--

DROP TABLE IF EXISTS `programme`;
CREATE TABLE IF NOT EXISTS `programme` (
  `Id_Programme` int(11) NOT NULL AUTO_INCREMENT,
  `Description` varchar(254) NOT NULL,
  `Date_Debut` date NOT NULL,
  `Date_Fin` date NOT NULL,
  `Id_Excursion` decimal(10,0) NOT NULL,
  `Id_Guide` int(11) NOT NULL,
  PRIMARY KEY (`Id_Programme`),
  KEY `Id_Excursion` (`Id_Excursion`),
  KEY `Id_Guide` (`Id_Guide`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `Id_Compte` int(11) NOT NULL,
  `Id_Programme` int(11) NOT NULL,
  PRIMARY KEY (`Id_Compte`,`Id_Programme`),
  KEY `Id_Programme` (`Id_Programme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `Id_Role` decimal(10,0) NOT NULL,
  `Nom` varchar(99) NOT NULL,
  `Description` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Id_Role`),
  UNIQUE KEY `Nom` (`Nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `compte_role`
--
ALTER TABLE `compte_role`
  ADD CONSTRAINT `compte_role_ibfk_1` FOREIGN KEY (`Id_Compte`) REFERENCES `compte` (`Id_Compte`) ON UPDATE CASCADE,
  ADD CONSTRAINT `compte_role_ibfk_2` FOREIGN KEY (`Id_Role`) REFERENCES `role` (`Id_Role`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `excursion`
--
ALTER TABLE `excursion`
  ADD CONSTRAINT `excursion_ibfk_1` FOREIGN KEY (`Lieu_Debut`) REFERENCES `lieu` (`Id_Lieu`) ON UPDATE CASCADE,
  ADD CONSTRAINT `excursion_ibfk_2` FOREIGN KEY (`Lieu_Fin`) REFERENCES `lieu` (`Id_Lieu`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `excursion_equipement`
--
ALTER TABLE `excursion_equipement`
  ADD CONSTRAINT `excursion_equipement_ibfk_1` FOREIGN KEY (`Id_Excursion`) REFERENCES `excursion` (`Id_Excursion`) ON UPDATE CASCADE,
  ADD CONSTRAINT `excursion_equipement_ibfk_2` FOREIGN KEY (`Id_Equipement`) REFERENCES `equipement` (`Id_Equipement`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `programme`
--
ALTER TABLE `programme`
  ADD CONSTRAINT `programme_ibfk_1` FOREIGN KEY (`Id_Excursion`) REFERENCES `excursion` (`Id_Excursion`),
  ADD CONSTRAINT `programme_ibfk_2` FOREIGN KEY (`Id_Guide`) REFERENCES `compte` (`Id_Compte`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`Id_Compte`) REFERENCES `compte` (`Id_Compte`) ON UPDATE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`Id_Programme`) REFERENCES `programme` (`Id_Programme`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
