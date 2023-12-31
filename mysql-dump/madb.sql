-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 08 déc. 2023 à 09:07
-- Version du serveur : 8.0.35
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cv_theq`
-- --
-- CREATE DATABASE cv_theq;
USE cv_theq;

-- --------------------------------------------------------

--
-- Structure de la table `competences`
--

DROP TABLE IF EXISTS `cv_theq.competences`;
CREATE TABLE IF NOT EXISTS `cv_theq.competences` (
  `id_comp` bigint NOT NULL AUTO_INCREMENT,
  `Nom` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id_comp`)
) ENGINE=InnoDB  AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `competences`
--

INSERT INTO `cv_theq.competences` (`id_comp`, `Nom`) VALUES
(1, 'Anglais'),
(2, 'Marketing de contenu'),
(3, 'Adaptation'),
(4, 'Windows'),
(5, 'Commerce\r\n'),
(6, 'Bureautique'),
(7, 'Maitrise Caméra'),
(9, 'Communication Interne'),
(10, 'Communication\r\n'),
(11, 'Stratégie de social média'),
(12, 'Créativité'),
(13, 'Linux'),
(14, 'Montage'),
(15, 'Allemand'),
(16, 'Communication Externe'),
(17, 'Espagnol'),
(18, 'PAO'),
(19, 'Webmarketing'),
(20, 'Leadership\r\n'),
(21, 'Réseaux'),
(22, 'Management'),
(23, 'Français'),
(24, 'Service client'),
(25, 'Communication Institutionelle'),
(26, 'Italien\r\n'),
(27, 'Storytelling\r\n'),
(28, 'Infographie'),
(29, 'Relations Presse'),
(30, 'CRM'),
(31, 'Néerlandais'),
(32, 'Analytics\r\n'),
(33, 'Dynamisme'),
(34, 'Développement Web'),
(35, 'Belge\r\n'),
(36, 'BToB\r\n'),
(37, 'Social Média'),
(38, 'Rédaction'),
(39, 'Médias Sociaux'),
(40, 'Arabe'),
(41, 'Prestashop'),
(42, 'Wordpress'),
(43, 'E-commerce'),
(44, 'Salesforce'),
(45, 'Commerce International'),
(46, 'Commerce'),
(47, 'Communication');

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

DROP TABLE IF EXISTS `cv_theq.compte`;
CREATE TABLE IF NOT EXISTS `cv_theq.compte` (
  `id_compte` bigint NOT NULL AUTO_INCREMENT,
  `Mail` text NOT NULL,
  `Password` text NOT NULL,
  `profil_id` bigint NOT NULL,
  `role` bigint NOT NULL,
  PRIMARY KEY (`id_compte`),
  KEY `compte_profil_id_foreign` (`profil_id`)
) ENGINE=InnoDB  AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `compte`
--

INSERT INTO `compte` (`id_compte`, `Mail`, `Password`, `profil_id`, `role`) VALUES
(1, 'damien@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$a1g5UjNhOTd3Z2R5WVh0cw$2ZQWHRIy2ridSBZpGPDOstHm1wrxuaL0zRbtLXWSR/4', 0, 1),
(2, 'amelie@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$dUlCbmpaYzBLU012dU15Yg$4gzCTD8m9x7SS9FXWdryYKC9I65PbiMdFqiczDfeaNc', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `tablename`
--

DROP TABLE IF EXISTS `cv_theq.tablename`;
CREATE TABLE IF NOT EXISTS `cv_theq.tablename` (
  `Id` bigint NOT NULL AUTO_INCREMENT,
  `Nom` varchar(512) DEFAULT NULL,
  `Prenom` varchar(512) DEFAULT NULL,
  `Age` varchar(512) DEFAULT NULL,
  `Date_naissance` varchar(512) DEFAULT NULL,
  `Adresse` varchar(512) DEFAULT NULL,
  `Adresse_1` varchar(512) DEFAULT NULL,
  `Code_postal` varchar(512) DEFAULT NULL,
  `ville` varchar(512) DEFAULT NULL,
  `tel_portable` int DEFAULT NULL,
  `tel_fixe` varchar(512) DEFAULT NULL,
  `Email` varchar(512) DEFAULT NULL,
  `Profil` varchar(512) DEFAULT NULL,
  `Competence_1` varchar(512) DEFAULT NULL,
  `Competence_2` varchar(512) DEFAULT NULL,
  `Competence_3` varchar(512) DEFAULT NULL,
  `Competence_4` varchar(512) DEFAULT NULL,
  `Competence_5` varchar(512) DEFAULT NULL,
  `Competence_6` varchar(512) DEFAULT NULL,
  `Competence_7` varchar(512) DEFAULT NULL,
  `Competence_8` varchar(512) DEFAULT NULL,
  `Competence_9` varchar(512) DEFAULT NULL,
  `Competence_10` varchar(512) DEFAULT NULL,
  `Site_Web` varchar(512) DEFAULT NULL,
  `Profil_Linkedin` varchar(512) DEFAULT NULL,
  `Profil_Viadeo` varchar(512) DEFAULT NULL,
  `Profil_facebook` varchar(512) DEFAULT NULL,
  `CV` text NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `tablename`
--

INSERT INTO `tablename` (`Id`, `Nom`, `Prenom`, `Age`, `Date_naissance`, `Adresse`, `Adresse_1`, `Code_postal`, `ville`, `tel_portable`, `tel_fixe`, `Email`, `Profil`, `Competence_1`, `Competence_2`, `Competence_3`, `Competence_4`, `Competence_5`, `Competence_6`, `Competence_7`, `Competence_8`, `Competence_9`, `Competence_10`, `Site_Web`, `Profil_Linkedin`, `Profil_Viadeo`, `Profil_facebook`, `CV`) VALUES
(1, 'TAMARIN', 'Margaux', NULL, '1965-10-10', NULL, NULL, NULL, NULL, 2147483647, NULL, 'marg.tamarin@gmail.com', 'Import/Export', 'Marketing de contenu', 'Stratégie de social media', 'Webmarketing', 'Storytelling', 'Analytics', 'Rédaction', 'Anglais', NULL, NULL, NULL, 'www.margauxtamarin.com', NULL, NULL, NULL, ''),
(2, 'MARVELLA', 'Claudia', NULL, '1990-10-06', NULL, NULL, NULL, 'GRENOBLE', 2147483647, NULL, 'claudia.marvella@hotmail.com', 'Digital Manager', 'Adaptation', 'Créativité', 'Leadership', 'Communication', 'Dynamisme', 'Médias sociaux', 'Prestashop', 'E-commerce', 'Salesforce', 'Commerce International', NULL, NULL, NULL, NULL, ''),
(3, 'ANIS', 'Nouri', NULL, '1993-07-08', 'Argoub Ezaatar Medjez', NULL, '9070', 'El Beb', 2147483647, NULL, 'nourianis1991@gmail.com', 'Technicien réseaux', 'Windows', 'Linux', 'Réseaux', 'Français', 'Anglais', 'Arabe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(4, 'MARTIN', 'Michel', NULL, '1978-09-04', '320 avenue de la Liberté', 'apt 7B', '75000', 'Paris', 600000000, '0100000000', 'michel.martin@primocv.com', 'Cadre commercial', 'Commerce', 'Bureautique', 'Anglais', 'Espagnol', NULL, NULL, NULL, NULL, NULL, NULL, 'www.monsite.com', NULL, NULL, NULL, ''),
(5, 'GIRAUD', 'Pierre', NULL, '1990-01-25', 'Impasse des Acacias', NULL, '83200', 'Toulon', 2147483647, NULL, 'pierre.giraud@edhec.com', 'Management', 'Bureautique', 'Anglais', 'Management', 'Infographie', 'Développement Web', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(6, 'MOREAU', 'Mélanie', NULL, '1975-02-24', NULL, NULL, NULL, 'PARIS', 2147483647, NULL, 'melaniemoreau@gmail.com', 'Responsable du Service Client', 'Bureautique', 'Anglais', 'Français', 'Espagnol', 'CRM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(7, 'BOUTEILLER', 'Fanny', NULL, '1986-09-03', NULL, NULL, NULL, NULL, 688179934, NULL, 'fanny.bouteiller@gmail.com', 'Réalisatrice Rédactrice', 'Maîtrise Caméras', 'Montage', 'Anglais', 'Français', 'Belge', NULL, NULL, NULL, NULL, NULL, 'https://fannybouteiller.wordpress.com', NULL, NULL, NULL, ''),
(8, 'CHERET', 'Laurent', '35', '1976-07-26', '8 impasse du Languedoc', NULL, 'F-57525', 'TALANGE', 664964404, '0387804868', 'laurent.cheret@yahoo.fr', 'Journaliste', 'Anglais', 'Allemand', 'Service client', 'Communication', 'BToB', 'BToC', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(10, 'QUEVAL PAROLA', 'Sophie', '26', '1990-04-01', '22 rue fodéré', NULL, '06300', 'NICE', 6, NULL, 'sophie.queval@gmail.com', 'Communication - Marketing', 'Communication Interne', 'Communication Externe', 'Communication Institutionelle', 'Relations Presse', NULL, NULL, NULL, NULL, NULL, NULL, 'sophiequevalparola.wordpress.com', NULL, NULL, NULL, ''),
(11, 'LAGARDERE', 'Alfred', NULL, '1975-03-03', NULL, NULL, NULL, 'PARIS', 2147483647, NULL, 'marinelagard@gmail.com', 'Français', 'Anglais', 'Espagnol', 'Italien', 'CRM', 'Bureautique', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(12, 'LEVEQUE', 'Emile', NULL, '1990-11-13', '84 rue du Bas-Coudray', NULL, '91100', 'CORBEIL-ESSONNES', 674928161, NULL, 'leveque.emile@live.fr', 'Communication', 'Anglais', 'PAO', 'Montage', 'Bureautique', 'Communication', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(13, 'FARRÉ', 'Aladin', '24', '1992-03-13', '3 rue du lieutenant Chauré', NULL, '75020', 'PARIS', 684944836, NULL, 'aladinfarre@gmail.com', 'Ingénieur Audiovisuel', 'Anglais', 'Montage', 'Maîtrise Caméras', 'Allemand', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(14, 'GUIOT', 'Laura', '25', '1991-02-14', NULL, NULL, NULL, 'BRUXELLES', 2147483647, NULL, 'lauraguiot@gmail.com', 'Editrice Web', 'Communication', 'Anglais', 'Français', 'Néerlandais', 'Social Médias', 'Rédaction', 'Wordpress', NULL, NULL, NULL, 'jefaisdubruit.com', NULL, NULL, NULL, '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
