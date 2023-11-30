-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.31 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.3.0.6589
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Listage de la structure de table cv_theq. competences
CREATE TABLE IF NOT EXISTS `competences` (
  `id_comp` bigint NOT NULL AUTO_INCREMENT,
  `Nom` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id_comp`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cv_theq.competences : 0 rows
/*!40000 ALTER TABLE `competences` DISABLE KEYS */;
INSERT INTO `competences` (`id_comp`, `Nom`) VALUES
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
	(45, 'Commerce International');
/*!40000 ALTER TABLE `competences` ENABLE KEYS */;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
