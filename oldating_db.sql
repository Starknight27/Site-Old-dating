-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 31 mai 2026 à 17:49
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `oldating_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `agenda_personnel`
--

DROP TABLE IF EXISTS `agenda_personnel`;
CREATE TABLE IF NOT EXISTS `agenda_personnel` (
  `id_agenda` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_evenement` date NOT NULL,
  `type_evenement` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_agenda`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `agenda_personnel`
--

INSERT INTO `agenda_personnel` (`id_agenda`, `id_utilisateur`, `titre`, `date_evenement`, `type_evenement`) VALUES
(1, 1, 'Rayaan', '2007-07-01', 'Anniversaire');

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

DROP TABLE IF EXISTS `evenement`;
CREATE TABLE IF NOT EXISTS `evenement` (
  `id_evenement` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `date_evenement` datetime NOT NULL,
  `lieu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_evenement` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_evenement`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `evenement`
--

INSERT INTO `evenement` (`id_evenement`, `titre`, `description`, `date_evenement`, `lieu`, `type_evenement`) VALUES
(1, 'Oral', NULL, '2026-06-01 15:30:00', 'Amphi', 'Jeux'),
(2, 'rendez vous ', NULL, '2026-06-02 11:00:00', 'Boite ', 'Promenade');

-- --------------------------------------------------------

--
-- Structure de la table `inscription_evenement`
--

DROP TABLE IF EXISTS `inscription_evenement`;
CREATE TABLE IF NOT EXISTS `inscription_evenement` (
  `id_inscription` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_evenement` int NOT NULL,
  `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP,
  `confirmation_sms` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_inscription`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_evenement` (`id_evenement`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `inscription_evenement`
--

INSERT INTO `inscription_evenement` (`id_inscription`, `id_utilisateur`, `id_evenement`, `date_inscription`, `confirmation_sms`) VALUES
(1, 1, 1, '2026-05-31 16:42:27', 0),
(2, 1, 2, '2026-05-31 19:10:15', 0);

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int NOT NULL,
  `id_destinataire` int NOT NULL,
  `contenu_message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_envoi` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_message`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id_message`, `id_utilisateur`, `id_destinataire`, `contenu_message`, `date_envoi`) VALUES
(1, 3, 4, 'bonjour', '2026-05-31 15:35:54'),
(2, 3, 4, 'bonjour', '2026-05-31 15:39:16'),
(3, 3, 4, 'bonjour', '2026-05-31 15:39:19'),
(4, 3, 4, 'bonjour', '2026-05-31 15:46:12'),
(5, 3, 4, 'bonjour', '2026-05-31 15:47:41'),
(6, 3, 4, 'hey', '2026-05-31 15:48:12'),
(7, 3, 2, 'what\'s up ?', '2026-05-31 15:48:29'),
(8, 1, 3, '???? Joyeux Anniversaire !', '2026-05-31 16:41:36'),
(9, 1, 3, '???? Bonnes Fêtes !', '2026-05-31 16:41:40'),
(10, 1, 2, 'coucou', '2026-05-31 19:08:55'),
(11, 1, 2, 'coucou', '2026-05-31 19:09:13');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_de_naissance` date DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'En ligne',
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `pseudo`, `email`, `mot_de_passe`, `date_de_naissance`, `adresse`, `statut`) VALUES
(1, 'Kujo', 'Jotaro', 'Jotaro', 'kujo.jotaro@gmail.com', '$2y$10$3fhRaTMwUMYyuiZ4NS4WO.pd0njGL7Li8pMXbU55wJas279wV8ddG', NULL, NULL, 'Absent'),
(2, 'Dupont', 'Vincent', 'Vincent', 'dupont.vincent@gmail.com', '$2y$10$3PzFuSdt7MwGWujLmexGf.cmY3qkZLG0ArZ6Frt8.SeWjq0MVhRtK', NULL, NULL, 'En ligne'),
(3, 'Joestar', 'Joseph', 'Joseph', 'jojo@gmail.com', '$2y$10$DRhcDMSt6iSrnmT30BX/a.DI6MP98Frak/9oPKk4wBGq/W6wqykJi', NULL, NULL, 'En ligne'),
(4, 'Khaled', 'DJ', 'DJ Khaled', 'djkhaled@oldating.com', '123', NULL, NULL, 'En ligne'),
(5, 'Gaster', 'WD', 'WD Gaster', 'gaster@oldating.com', '123', NULL, NULL, 'En ligne'),
(6, 'Thukuna', 'Sukuna', 'Thukuna', 'thukuna@oldating.com', '123', NULL, NULL, 'En ligne'),
(7, 'Mohammad', 'Rayaan', 'Rayaan', 'rayaan@gmail.com', '$2y$10$n87A61LHyveE3r.j80Puduudig7qT8.MgMgGq11ZuUsCadu3LKcEW', NULL, NULL, 'En ligne');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
