-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 25 mars 2022 à 17:53
-- Version du serveur :  5.7.31
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet_bts`
--

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `proc_maj_abo`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_maj_abo` (IN `id_user` INT)  NO SQL
BEGIN
    SET @report_curante_date = (SELECT CURRENT_DATE);
    SET @report_v_date_fin = (SELECT `date_fin` FROM abonnement WHERE abonnement.num_user = id_user);
    IF(@report_v_date_fin < @report_curante_date) THEN
        UPDATE `abonnement` SET `abo_statut` = 'false' WHERE `abonnement`.`num_user` = id_user;
    ELSEIF(@report_v_date_fin > @report_curante_date) THEN
    	UPDATE `abonnement` SET `abo_statut` = 'true' WHERE `abonnement`.`num_user` = id_user;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `abonnement`
--

DROP TABLE IF EXISTS `abonnement`;
CREATE TABLE IF NOT EXISTS `abonnement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num_user` int(11) NOT NULL,
  `abo_statut` varchar(50) COLLATE utf8_bin NOT NULL,
  `date_abonnement` date DEFAULT NULL,
  `date_fin` date NOT NULL,
  `num_type_abo` int(11) DEFAULT NULL,
  `nb_fact_restant` int(11) NOT NULL,
  `nb_devis_restant` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `num_user` (`num_user`),
  KEY `num_type_abo` (`num_type_abo`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- --------------------------------------------------------

--
-- Structure de la table `destinataire`
--

DROP TABLE IF EXISTS `destinataire`;
CREATE TABLE IF NOT EXISTS `destinataire` (
  `id_destinataire` int(11) NOT NULL AUTO_INCREMENT,
  `num_user_proprietaire` int(11) NOT NULL,
  `nom_denomination` text COLLATE utf8_bin NOT NULL,
  `adresse_rue` text COLLATE utf8_bin NOT NULL,
  `adresse_code_ville` text COLLATE utf8_bin NOT NULL,
  `tva_intra` text COLLATE utf8_bin,
  PRIMARY KEY (`id_destinataire`),
  KEY `num_user_proprietaire` (`num_user_proprietaire`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- --------------------------------------------------------

--
-- Structure de la table `facture_devis`
--

DROP TABLE IF EXISTS `facture_devis`;
CREATE TABLE IF NOT EXISTS `facture_devis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num_user` int(11) NOT NULL,
  `type` varchar(10) COLLATE utf8_bin NOT NULL,
  `num_fact_devis` varchar(150) COLLATE utf8_bin NOT NULL,
  `num_destinataire` int(11) NOT NULL,
  `date_facture` date NOT NULL,
  `total_ttc` double NOT NULL,
  `num_vendeur` int(11) NOT NULL,
  `model_facture_devis` int(11) NOT NULL,
  `tva_auto_liquidation` varchar(7) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `num_destinataire` (`num_destinataire`),
  KEY `num_user` (`num_user`),
  KEY `num_vendeur` (`num_vendeur`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



--
-- Déclencheurs `facture_devis`
--
DROP TRIGGER IF EXISTS `tr_maj_nb_convert_fact_update`;
DELIMITER $$
CREATE TRIGGER `tr_maj_nb_convert_fact_update` BEFORE UPDATE ON `facture_devis` FOR EACH ROW BEGIN
    IF(OLD.type = "devis") THEN
        IF(NEW.type = "facture") THEN
            UPDATE `abonnement` SET `nb_fact_restant`= abonnement.nb_fact_restant - 1 WHERE abonnement.num_user = NEW.num_user;
        END IF;
    END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `tr_maj_nb_fact_insert`;
DELIMITER $$
CREATE TRIGGER `tr_maj_nb_fact_insert` AFTER INSERT ON `facture_devis` FOR EACH ROW BEGIN
IF(NEW.type = "facture") THEN
    UPDATE `abonnement` SET `nb_fact_restant`= abonnement.nb_fact_restant - 1 WHERE abonnement.num_user = NEW.num_user;
ELSEIF(NEW.type = "devis") THEN
    UPDATE `abonnement` SET `nb_devis_restant`= abonnement.nb_devis_restant - 1 WHERE abonnement.num_user = NEW.num_user;
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `ligne_facture_devis`
--

DROP TABLE IF EXISTS `ligne_facture_devis`;
CREATE TABLE IF NOT EXISTS `ligne_facture_devis` (
  `id_ligne` int(11) NOT NULL AUTO_INCREMENT,
  `num_facture` int(150) NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `quantite` int(11) NOT NULL,
  `unité` varchar(50) COLLATE utf8_bin NOT NULL,
  `prix_unitaire_ht` double NOT NULL,
  `tva_ligne` double DEFAULT NULL,
  PRIMARY KEY (`id_ligne`),
  KEY `num_facture` (`num_facture`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- --------------------------------------------------------

--
-- Structure de la table `type_abonnement`
--

DROP TABLE IF EXISTS `type_abonnement`;
CREATE TABLE IF NOT EXISTS `type_abonnement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8_bin NOT NULL,
  `nb_max_facture` int(11) NOT NULL,
  `nb_max_devis` int(11) NOT NULL,
  `prix_abo` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;


-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identifiant` text COLLATE utf8_bin NOT NULL,
  `mdp` text COLLATE utf8_bin NOT NULL,
  `statut` varchar(40) COLLATE utf8_bin NOT NULL,
  `mail` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



--
-- Structure de la table `vendeur`
--

DROP TABLE IF EXISTS `vendeur`;
CREATE TABLE IF NOT EXISTS `vendeur` (
  `id_vendeur` int(11) NOT NULL AUTO_INCREMENT,
  `num_user_proprietaire` int(11) NOT NULL,
  `denomination` text COLLATE utf8_bin NOT NULL,
  `adresse_rue` text COLLATE utf8_bin NOT NULL,
  `adresse_code_ville` text COLLATE utf8_bin NOT NULL,
  `siret` varchar(14) COLLATE utf8_bin NOT NULL,
  `ape` text COLLATE utf8_bin NOT NULL,
  `tva_intra` text COLLATE utf8_bin NOT NULL,
  `RIB_NOM` text COLLATE utf8_bin,
  `RIB_IBAN` text COLLATE utf8_bin,
  `RIB_BIC` text COLLATE utf8_bin,
  `logo` text COLLATE utf8_bin,
  PRIMARY KEY (`id_vendeur`),
  KEY `num_user_proprietaire` (`num_user_proprietaire`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `abonnement`
--
ALTER TABLE `abonnement`
  ADD CONSTRAINT `abonnement_ibfk_1` FOREIGN KEY (`num_user`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `abonnement_ibfk_2` FOREIGN KEY (`num_type_abo`) REFERENCES `type_abonnement` (`id`);

--
-- Contraintes pour la table `destinataire`
--
ALTER TABLE `destinataire`
  ADD CONSTRAINT `destinataire_ibfk_1` FOREIGN KEY (`num_user_proprietaire`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `facture_devis`
--
ALTER TABLE `facture_devis`
  ADD CONSTRAINT `facture_devis_ibfk_1` FOREIGN KEY (`num_destinataire`) REFERENCES `destinataire` (`id_destinataire`),
  ADD CONSTRAINT `facture_devis_ibfk_2` FOREIGN KEY (`num_user`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `facture_devis_ibfk_3` FOREIGN KEY (`num_vendeur`) REFERENCES `vendeur` (`id_vendeur`);

--
-- Contraintes pour la table `ligne_facture_devis`
--
ALTER TABLE `ligne_facture_devis`
  ADD CONSTRAINT `ligne_facture_devis_ibfk_1` FOREIGN KEY (`num_facture`) REFERENCES `facture_devis` (`id`);

--
-- Contraintes pour la table `vendeur`
--
ALTER TABLE `vendeur`
  ADD CONSTRAINT `vendeur_ibfk_1` FOREIGN KEY (`num_user_proprietaire`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
