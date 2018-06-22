-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 21 Septembre 2017 à 11:00
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `shishimai`
--

-- --------------------------------------------------------

--
-- Structure de la table `ssm_site_users`
--

CREATE TABLE IF NOT EXISTS `ssm_site_users` (
  `id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `ssm_site_users`
--

INSERT INTO `ssm_site_users` (`id`, `site_id`, `user_id`, `created`, `modified`) VALUES
(1, 1, 13, '2017-09-21 10:54:18', '2017-09-21 10:54:18');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `ssm_site_users`
--
ALTER TABLE `ssm_site_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `ssm_site_users`
--
ALTER TABLE `ssm_site_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
