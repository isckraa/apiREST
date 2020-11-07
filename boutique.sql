-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : Dim 01 nov. 2020 à 18:47
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
-- Base de données : `api_rest`
--

-- --------------------------------------------------------

--
-- Structure de la table `boutique`
--

DROP TABLE IF EXISTS `boutique`;
CREATE TABLE IF NOT EXISTS `boutique` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_postal` int(11) DEFAULT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` int(11) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avis` int(11) DEFAULT NULL,
  `note` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `boutique`
--

INSERT INTO `boutique` (`id`, `nom`, `adresse`, `code_postal`, `ville`, `telephone`, `description`, `avis`, `note`) VALUES
(1, 'Columbia Sportswear', '40 Boulevard de Sébastopol', 75004, 'Paris', 142748021, 'Magasin de vêtements de sport à Paris', 15, '4,3'),
(2, 'Wolford', '36 Rue Vieille du Temple', 75004, 'Paris', 101010101, 'Spécialiste de la lingerie de luxe proposant aussi vêtements, maillots de bain et dessous sculptants.', 30, '3,9'),
(6, 'Test creation', '3 Rue Paris', 75001, 'Paris', NULL, NULL, 0, NULL),
(7, 'Wolford 2', '37 Rue Vieille du Temple', 75004, 'Paris', 101010101, 'Spécialiste de la lingerie de luxe proposant aussi vêtements, maillots de bain et dessous sculptants.', 100, '4,0');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
