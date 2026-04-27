-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 27 avr. 2026 à 14:07
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecommerce`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id_cat` smallint(3) NOT NULL,
  `nom` varchar(35) NOT NULL,
  `descri` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_cat`, `nom`, `descri`) VALUES
(1, 'electronique', ''),
(2, 'musique', '');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(11) NOT NULL,
  `ref_uti` int(11) NOT NULL,
  `date_commande` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total` int(11) NOT NULL,
  `statut` enum('en_attente','validee','livree','annulee') DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `ref_uti`, `date_commande`, `total`, `statut`) VALUES
(7, 2, '2026-04-16 12:44:07', 85000, 'en_attente'),
(8, 4, '2026-04-18 19:09:38', 0, 'en_attente'),
(9, 4, '2026-04-18 19:18:46', 123500, 'en_attente');

-- --------------------------------------------------------

--
-- Structure de la table `commande_produit`
--

CREATE TABLE `commande_produit` (
  `id_commande_produit` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `idprod` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `commande_produit`
--

INSERT INTO `commande_produit` (`id_commande_produit`, `id_commande`, `idprod`, `quantite`, `prix_unitaire`) VALUES
(10, 7, 3, 4, 15000),
(11, 7, 5, 5, 5000);

-- --------------------------------------------------------

--
-- Structure de la table `detail_commande`
--

CREATE TABLE `detail_commande` (
  `id_detail` int(11) NOT NULL,
  `id_commande` int(11) DEFAULT NULL,
  `idprod` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `prix` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `detail_commande`
--

INSERT INTO `detail_commande` (`id_detail`, `id_commande`, `idprod`, `quantite`, `prix`) VALUES
(1, 9, 7, 1, 3500),
(2, 9, 6, 1, 120000);

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

CREATE TABLE `favoris` (
  `idfav` int(11) NOT NULL,
  `ref_uti` int(11) NOT NULL,
  `idprod` int(11) NOT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `favoris`
--

INSERT INTO `favoris` (`idfav`, `ref_uti`, `idprod`, `date_ajout`) VALUES
(3, 4, 4, '2026-04-27 11:32:26'),
(4, 4, 4, '2026-04-27 11:49:36');

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

CREATE TABLE `notification` (
  `idnotif` int(11) NOT NULL,
  `ref_uti` int(11) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) DEFAULT 'info',
  `lu` tinyint(1) DEFAULT 0,
  `date_envoi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `idpanier` int(11) NOT NULL,
  `ref_uti` int(11) NOT NULL,
  `idprod` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `panier`
--

INSERT INTO `panier` (`idpanier`, `ref_uti`, `idprod`, `quantite`, `date_ajout`) VALUES
(56, 4, 4, 1, '2026-04-27 12:03:46');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `idprod` int(11) NOT NULL,
  `nomprod` varchar(20) NOT NULL,
  `prix` int(4) NOT NULL,
  `image` varchar(255) NOT NULL,
  `typrod` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `stock` smallint(55) NOT NULL,
  `couleur` varchar(55) NOT NULL,
  `statut` enum('actif','archive') DEFAULT 'actif',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_cat` smallint(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`idprod`, `nomprod`, `prix`, `image`, `typrod`, `description`, `stock`, `couleur`, `statut`, `date_creation`, `id_cat`) VALUES
(2, 'piano', 455778, 'photo_2025-05-26_12-00-16.jpg', 'YAMAHA', 'piano yamaha', 30, 'noir', 'actif', '2026-04-17 06:22:27', 2),
(3, 'AirPod', 15000, 'airpods.jpeg', 'AirPod 2 pro de Apple', '', 0, 'blanc', 'archive', '2026-04-17 06:22:27', 1),
(4, 'casque', 15000, 'casque.jpeg', 'CASQUE AUDIO', '', 0, 'blanc', 'archive', '2026-04-17 06:22:27', 1),
(5, 'souri', 5000, 'souris.jpeg', 'souri levono', '', 0, 'gris', 'archive', '2026-04-17 06:22:27', 1),
(6, 'airpods TWS', 120000, 'airpods.jpeg', '', '', 7, 'noir', 'actif', '2026-04-17 16:39:05', 1),
(7, 'casque', 3500, 'casque.jpeg', '', '', 0, 'cafe', 'archive', '2026-04-17 16:39:05', 1);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `ref_uti` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','client') DEFAULT 'client',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`ref_uti`, `nom`, `email`, `password`, `role`, `date_creation`) VALUES
(4, 'uzumaki', 'joelnambigue360@gmail.com', 'MDjoel', 'client', '2026-04-18 02:35:12');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_cat`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`);

--
-- Index pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD PRIMARY KEY (`id_commande_produit`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_produit` (`idprod`);

--
-- Index pour la table `detail_commande`
--
ALTER TABLE `detail_commande`
  ADD PRIMARY KEY (`id_detail`);

--
-- Index pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD PRIMARY KEY (`idfav`),
  ADD KEY `ref_uti` (`ref_uti`),
  ADD KEY `idprod` (`idprod`);

--
-- Index pour la table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`idnotif`),
  ADD KEY `ref_uti` (`ref_uti`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`idpanier`),
  ADD KEY `ref_uti` (`ref_uti`),
  ADD KEY `idprod` (`idprod`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`idprod`),
  ADD KEY `fk` (`id_cat`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`ref_uti`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_cat` smallint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  MODIFY `id_commande_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `detail_commande`
--
ALTER TABLE `detail_commande`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `favoris`
--
ALTER TABLE `favoris`
  MODIFY `idfav` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `notification`
--
ALTER TABLE `notification`
  MODIFY `idnotif` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `idpanier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `idprod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `ref_uti` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD CONSTRAINT `commande_produit_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`),
  ADD CONSTRAINT `commande_produit_ibfk_2` FOREIGN KEY (`idprod`) REFERENCES `produit` (`idprod`);

--
-- Contraintes pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `favoris_ibfk_1` FOREIGN KEY (`ref_uti`) REFERENCES `utilisateur` (`ref_uti`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoris_ibfk_2` FOREIGN KEY (`idprod`) REFERENCES `produit` (`idprod`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`ref_uti`) REFERENCES `utilisateur` (`ref_uti`) ON DELETE CASCADE;

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`ref_uti`) REFERENCES `utilisateur` (`ref_uti`) ON DELETE CASCADE,
  ADD CONSTRAINT `panier_ibfk_2` FOREIGN KEY (`idprod`) REFERENCES `produit` (`idprod`) ON DELETE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `fk` FOREIGN KEY (`id_cat`) REFERENCES `categorie` (`id_cat`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
