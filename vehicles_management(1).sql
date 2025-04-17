-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : sam. 21 déc. 2024 à 18:25
-- Version du serveur : 5.7.24
-- Version de PHP : 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `vehicles_management`
--

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `vehicule_id` int(11) DEFAULT NULL,
  `date_reservation` date DEFAULT NULL,
  `date_retour` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `role` enum('utilisateur','admin') DEFAULT 'utilisateur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `role`) VALUES
(3, 'toto', 'toto@toto.com', '$2y$10$DGQxoIuS6JJi7ashTuH0q.j7OUjtnL7MILSe7vTKsZWlUO/9DCsRC', 'utilisateur'),
(4, 'a', 'a@a.com', '$2y$10$SklKMzAlvRTjRKs73.At6eY7QovR6sweKikz4J5XO5mNrVRs7Y1gi', 'admin'),
(6, 't', 't@t.com', '$2y$10$BXYnUxCwbLk9PlI9hfTE9.XpGmz0bDqb1pM9pv2hwuLmEZXR8Wf1i', 'utilisateur'),
(7, 'miletic', 'r@r.com', '$2y$10$BjLLANuG1UBvDTINcq7oJ.aPpemgi7/TVATolYxp8pUkXJhSlPQ3W', 'utilisateur');

-- --------------------------------------------------------

--
-- Structure de la table `vehicules`
--

CREATE TABLE `vehicules` (
  `id` int(11) NOT NULL,
  `marque` varchar(255) DEFAULT NULL,
  `modele` varchar(255) DEFAULT NULL,
  `prix_par_jour` decimal(10,2) DEFAULT NULL,
  `disponible_de` date DEFAULT NULL,
  `disponible_jusqua` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `vehicules`
--

INSERT INTO `vehicules` (`id`, `marque`, `modele`, `prix_par_jour`, `disponible_de`, `disponible_jusqua`, `image`) VALUES
(1, 'Audi', 'A3 Allstreet', '50.00', '2025-01-01', '2026-01-01', 'uploads/audi_a3_allstreet_red.jpeg'),
(2, 'Audi', 'SQ8', '60.00', '2025-01-01', '2026-01-01', 'uploads/audi_sq8_black.jpeg'),
(3, 'Citroen', 'C3 Aircross', '50.00', '2025-01-01', '2026-01-01', 'uploads/Citroen_c3_aircross_red.jpeg'),
(4, 'Citroen', 'C5X', '50.00', '2025-01-01', '2026-01-01', 'uploads/citroen_c5x_blue.jpeg'),
(5, 'Citroen', 'C4', '50.00', '2025-01-01', '2026-01-01', 'uploads/citroen_e_c4_grey.jpeg'),
(6, 'Peugeot', '408', '45.00', '2025-01-01', '2025-01-01', 'uploads/peugeot_408_blue.jpeg');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `vehicule_id` (`vehicule_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `vehicules`
--
ALTER TABLE `vehicules`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `vehicules`
--
ALTER TABLE `vehicules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`vehicule_id`) REFERENCES `vehicules` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
