-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 10 déc. 2024 à 11:13
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `immatriculation`
--

-- --------------------------------------------------------

--
-- Structure de la table `attestations`
--

CREATE TABLE `attestations` (
  `nom` varchar(75) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `telephone` varchar(17) NOT NULL,
  `immatriculation` varchar(255) NOT NULL,
  `date` varchar(50) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `carrosseries`
--

CREATE TABLE `carrosseries` (
  `id` int(11) NOT NULL,
  `type` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `carrosseries`
--

INSERT INTO `carrosseries` (`id`, `type`) VALUES
(1, 'PR SEMI RE'),
(2, 'P R'),
(3, 'CITERNE'),
(4, 'BENNE'),
(5, 'PLATEAU NU'),
(6, 'PLATEAU RID');

-- --------------------------------------------------------

--
-- Structure de la table `carte_grise`
--

CREATE TABLE `carte_grise` (
  `id` int(11) NOT NULL,
  `immat` varchar(20) NOT NULL,
  `nom` varchar(25) NOT NULL,
  `prenom` varchar(25) NOT NULL,
  `chassis` varchar(17) NOT NULL,
  `marque` varchar(15) NOT NULL,
  `genre` varchar(10) NOT NULL,
  `carrosserie` varchar(10) NOT NULL,
  `telephone` int(11) NOT NULL,
  `model` varchar(15) NOT NULL,
  `couleur` varchar(10) NOT NULL,
  `puissance` int(11) DEFAULT NULL,
  `energie` varchar(10) NOT NULL,
  `amc` date NOT NULL,
  `place` int(11) DEFAULT NULL,
  `pc` int(11) DEFAULT NULL,
  `cu` int(11) DEFAULT NULL,
  `ptac` int(11) DEFAULT NULL,
  `date_cg` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `energies`
--

CREATE TABLE `energies` (
  `id` int(11) NOT NULL,
  `nom` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `energies`
--

INSERT INTO `energies` (`id`, `nom`) VALUES
(1, 'ESSENCE'),
(2, 'DIESEL');

-- --------------------------------------------------------

--
-- Structure de la table `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `libelle` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `genres`
--

INSERT INTO `genres` (`id`, `libelle`) VALUES
(1, 'TRAC ROUT'),
(2, 'CAMION'),
(3, 'VEHIC PART'),
(4, 'CAMTTE'),
(5, 'SEMI REMOR');

-- --------------------------------------------------------

--
-- Structure de la table `marques`
--

CREATE TABLE `marques` (
  `id` int(11) NOT NULL,
  `nom` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `marques`
--

INSERT INTO `marques` (`id`, `nom`) VALUES
(1, 'MERCEDES'),
(2, 'RENAULT'),
(3, 'TOYOTA'),
(4, 'HONDA');

-- --------------------------------------------------------

--
-- Structure de la table `permis`
--

CREATE TABLE `permis` (
  `id` int(11) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(25) NOT NULL,
  `date_naissance` date NOT NULL,
  `lieu_naissance` varchar(25) NOT NULL,
  `domicile` varchar(10) NOT NULL,
  `categorie` varchar(4) NOT NULL,
  `telephone` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `copie_pc` varchar(255) NOT NULL,
  `copie_permis` varchar(255) DEFAULT NULL,
  `date_pc` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pvchassis`
--

CREATE TABLE `pvchassis` (
  `id` int(11) NOT NULL,
  `immat` text NOT NULL,
  `nom` varchar(60) NOT NULL,
  `marque` varchar(15) NOT NULL,
  `genre` varchar(15) NOT NULL,
  `carrosserie` varchar(15) NOT NULL,
  `type` varchar(10) NOT NULL,
  `energie` varchar(10) NOT NULL,
  `chassis` varchar(17) NOT NULL,
  `pv` int(11) DEFAULT NULL,
  `cu` int(11) DEFAULT NULL,
  `ptac` int(11) DEFAULT NULL,
  `date_pv` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(30) NOT NULL DEFAULT 'admin@gmail.com',
  `password` varchar(255) NOT NULL DEFAULT '$2y$12$4jPFIheAfecnS4EO9bSXKelZNUjbOuynjSQ7LLDIrJMR0BEFxn.yW'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(1, 'admin@gmail.com', '$2y$12$4jPFIheAfecnS4EO9bSXKelZNUjbOuynjSQ7LLDIrJMR0BEFxn.yW');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `attestations`
--
ALTER TABLE `attestations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `carrosseries`
--
ALTER TABLE `carrosseries`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `carte_grise`
--
ALTER TABLE `carte_grise`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `energies`
--
ALTER TABLE `energies`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `marques`
--
ALTER TABLE `marques`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `permis`
--
ALTER TABLE `permis`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pvchassis`
--
ALTER TABLE `pvchassis`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `attestations`
--
ALTER TABLE `attestations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `carrosseries`
--
ALTER TABLE `carrosseries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `carte_grise`
--
ALTER TABLE `carte_grise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `energies`
--
ALTER TABLE `energies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `marques`
--
ALTER TABLE `marques`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `permis`
--
ALTER TABLE `permis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `pvchassis`
--
ALTER TABLE `pvchassis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
