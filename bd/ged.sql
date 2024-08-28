-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 28 août 2024 à 14:19
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12
SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
  time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;

/*!40101 SET NAMES utf8mb4 */
;

--
-- Base de données : `ged`
--
-- --------------------------------------------------------
--
-- Structure de la table `audit_logs`
--
CREATE TABLE `audit_logs` (
  `ID_AUDIT_LOGS` int(11) NOT NULL,
  `ID_UTILISATEUR` int(11) NOT NULL,
  `ACTION` varchar(200) DEFAULT NULL,
  `TIMESTAMP` datetime DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------
--
-- Structure de la table `categories`
--
CREATE TABLE `categories` (
  `ID_CATEGORIES` int(11) NOT NULL,
  `NOM` varchar(100) DEFAULT NULL,
  `DESCRIPTION_C` varchar(200) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------
--
-- Structure de la table `contenir`
--
CREATE TABLE `contenir` (
  `ID_DOCUMENT` int(11) NOT NULL,
  `ID_CATEGORIES` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------
--
-- Structure de la table `document`
--
CREATE TABLE `document` (
  `ID_DOCUMENT` int(11) NOT NULL,
  `ID_UTILISATEUR` int(11) NOT NULL,
  `TITRE` varchar(200) DEFAULT NULL,
  `DESCRIPTION` varchar(200) DEFAULT NULL,
  `FILE_PATH` varchar(200) DEFAULT NULL,
  `CREATED_AT` datetime DEFAULT NULL,
  `UPDATED_AT` datetime DEFAULT NULL,
  `VERSION` varchar(50) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------
--
-- Structure de la table `document_version`
--
CREATE TABLE `document_version` (
  `ID_DOCUMENT_VERSION` int(11) NOT NULL,
  `ID_DOCUMENT` int(11) NOT NULL,
  `VERSION_NUMBER` varchar(50) DEFAULT NULL,
  `FILE_PATH_D` varchar(200) DEFAULT NULL,
  `CREATED_AT_D` datetime DEFAULT NULL,
  `UPDATED_BY` int(11) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------
--
-- Structure de la table `permissions`
--
CREATE TABLE `permissions` (
  `ID_PERMISSIONS` int(11) NOT NULL,
  `CAN_VIEW` varchar(5) DEFAULT NULL,
  `CAN_EDIT` varchar(5) DEFAULT NULL,
  `CAN_DELETE` varchar(5) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------
--
-- Structure de la table `user_permission`
--
CREATE TABLE `user_permission` (
  `ID_UTILISATEUR` int(11) NOT NULL,
  `ID_DOCUMENT` int(11) NOT NULL,
  `ID_PERMISSIONS` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------
--
-- Structure de la table `utilisateur`
--
CREATE TABLE `utilisateur` (
  `ID_UTILISATEUR` int(11) NOT NULL,
  `MOT_DE_PASSE` varchar(200) DEFAULT NULL,
  `EMAIL` varchar(200) DEFAULT NULL,
  `ROLE` varchar(50) DEFAULT NULL,
  `NOM_UTIL` varchar(100) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--
INSERT INTO
  `utilisateur` (
    `ID_UTILISATEUR`,
    `MOT_DE_PASSE`,
    `EMAIL`,
    `ROLE`,
    `NOM_UTIL`
  )
VALUES
  (
    6,
    '$2y$10$tHCukm3o5cDtbLuDps8.XOKRMjM7beMYwAynCewORKiNzEbUR6w4i',
    'MELATAGUIAGUIRLANDE@GMAIL.COM',
    'admin',
    'GUIRLANDE'
  ),
  (
    8,
    '$2y$10$xNU0ptqPMwvmUJyb6Umil.COIYjjap3VyAOhpQKomQOsAxrQ2ReW6',
    'ngouenimirande@gmail.com',
    'gest',
    'mirande '
  ),
  (
    9,
    '$2y$10$YZwDZ6LVnLA.Yb6bfODWS.wK42frcOP0cOB0LpLET0An59kTaELLO',
    'julioj29@gmail.com',
    'user',
    'JULIO'
  ),
  (
    10,
    '$2y$10$1i5nzY9x0NyRtfaL7feEjeJr7nP4/arGXfargAWceVMlhe.4P1G22',
    'fozetj29@gmail.com',
    'admin',
    'junior'
  ),
  (
    11,
    '$2y$10$DPzalKplBq.DXsb161J.wOftCmioTSsdz3PDDI0aB3oqepIsSCR7i',
    'delmas@gamil.com',
    'user',
    'ines'
  );

--
-- Index pour les tables déchargées
--
--
-- Index pour la table `audit_logs`
--
ALTER TABLE
  `audit_logs`
ADD
  PRIMARY KEY (`ID_AUDIT_LOGS`),
ADD
  KEY `FK_GERE` (`ID_UTILISATEUR`);

--
-- Index pour la table `categories`
--
ALTER TABLE
  `categories`
ADD
  PRIMARY KEY (`ID_CATEGORIES`);

--
-- Index pour la table `contenir`
--
ALTER TABLE
  `contenir`
ADD
  PRIMARY KEY (`ID_DOCUMENT`, `ID_CATEGORIES`),
ADD
  KEY `FK_CONTENIR` (`ID_CATEGORIES`);

--
-- Index pour la table `document`
--
ALTER TABLE
  `document`
ADD
  PRIMARY KEY (`ID_DOCUMENT`),
ADD
  KEY `FK_APPARTIENT` (`ID_UTILISATEUR`);

--
-- Index pour la table `document_version`
--
ALTER TABLE
  `document_version`
ADD
  PRIMARY KEY (`ID_DOCUMENT_VERSION`),
ADD
  KEY `UPDATED_BY` (`UPDATED_BY`),
ADD
  KEY `FK_AVOIR` (`ID_DOCUMENT`);

--
-- Index pour la table `permissions`
--
ALTER TABLE
  `permissions`
ADD
  PRIMARY KEY (`ID_PERMISSIONS`);

--
-- Index pour la table `user_permission`
--
ALTER TABLE
  `user_permission`
ADD
  PRIMARY KEY (`ID_UTILISATEUR`, `ID_DOCUMENT`, `ID_PERMISSIONS`),
ADD
  KEY `FK_USER_PERMISSION` (`ID_PERMISSIONS`),
ADD
  KEY `FK_USER_PERMISSION_DOCUMENT` (`ID_DOCUMENT`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE
  `utilisateur`
ADD
  PRIMARY KEY (`ID_UTILISATEUR`);

--
-- AUTO_INCREMENT pour les tables déchargées
--
--
-- AUTO_INCREMENT pour la table `audit_logs`
--
ALTER TABLE
  `audit_logs`
MODIFY
  `ID_AUDIT_LOGS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE
  `categories`
MODIFY
  `ID_CATEGORIES` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `document`
--
ALTER TABLE
  `document`
MODIFY
  `ID_DOCUMENT` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `document_version`
--
ALTER TABLE
  `document_version`
MODIFY
  `ID_DOCUMENT_VERSION` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE
  `permissions`
MODIFY
  `ID_PERMISSIONS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE
  `utilisateur`
MODIFY
  `ID_UTILISATEUR` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 12;

--
-- Contraintes pour les tables déchargées
--
--
-- Contraintes pour la table `audit_logs`
--
ALTER TABLE
  `audit_logs`
ADD
  CONSTRAINT `FK_GERE` FOREIGN KEY (`ID_UTILISATEUR`) REFERENCES `utilisateur` (`ID_UTILISATEUR`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `contenir`
--
ALTER TABLE
  `contenir`
ADD
  CONSTRAINT `FK_CONTENIR` FOREIGN KEY (`ID_CATEGORIES`) REFERENCES `categories` (`ID_CATEGORIES`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD
  CONSTRAINT `FK_CONTENIR2` FOREIGN KEY (`ID_DOCUMENT`) REFERENCES `document` (`ID_DOCUMENT`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `document`
--
ALTER TABLE
  `document`
ADD
  CONSTRAINT `FK_APPARTIENT` FOREIGN KEY (`ID_UTILISATEUR`) REFERENCES `utilisateur` (`ID_UTILISATEUR`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `document_version`
--
ALTER TABLE
  `document_version`
ADD
  CONSTRAINT `FK_AVOIR` FOREIGN KEY (`ID_DOCUMENT`) REFERENCES `document` (`ID_DOCUMENT`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD
  CONSTRAINT `document_version_ibfk_1` FOREIGN KEY (`UPDATED_BY`) REFERENCES `utilisateur` (`ID_UTILISATEUR`) ON DELETE
SET
  NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_permission`
--
ALTER TABLE
  `user_permission`
ADD
  CONSTRAINT `FK_USER_PERMISSION` FOREIGN KEY (`ID_PERMISSIONS`) REFERENCES `permissions` (`ID_PERMISSIONS`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD
  CONSTRAINT `FK_USER_PERMISSION2` FOREIGN KEY (`ID_UTILISATEUR`) REFERENCES `utilisateur` (`ID_UTILISATEUR`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD
  CONSTRAINT `FK_USER_PERMISSION_DOCUMENT` FOREIGN KEY (`ID_DOCUMENT`) REFERENCES `document` (`ID_DOCUMENT`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;