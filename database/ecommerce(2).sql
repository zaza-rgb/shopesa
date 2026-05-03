-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 03 mai 2026 à 16:50
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
  `descri` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_cat`, `nom`, `descri`) VALUES
(1, 'electronique', ''),
(2, 'musique', ''),
(3, 'informatique', 'pour les informaticien');

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
(12, 6, '2026-05-02 21:20:33', 475778, 'en_attente');

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

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

CREATE TABLE `notification` (
  `idnotif` int(11) NOT NULL,
  `ref_uti` int(11) NOT NULL,
  `message` varchar(500) NOT NULL,
  `lu` varchar(10) DEFAULT NULL,
  `date_envoi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `notification`
--

INSERT INTO `notification` (`idnotif`, `ref_uti`, `message`, `lu`, `date_envoi`) VALUES
(31, 5, 'pour tous', 'non_lu', '2026-05-03 14:11:01'),
(32, 6, 'pour tous', 'lu', '2026-05-03 14:11:01'),
(33, 8, 'pour tous', 'non_lu', '2026-05-03 14:11:01'),
(34, 7, 'pour tous', 'non_lu', '2026-05-03 14:11:01'),
(35, 6, 'pour jojo', 'lu', '2026-05-03 14:41:23'),
(36, 6, '1 jojo', 'lu', '2026-05-03 14:44:22'),
(37, 6, '2 jojo', 'lu', '2026-05-03 14:44:28');

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

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `idprod` int(11) NOT NULL,
  `nomprod` varchar(20) NOT NULL,
  `prix` int(4) NOT NULL,
  `image` varchar(255) NOT NULL,
  `seuil` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `stock` smallint(55) NOT NULL,
  `statut` enum('actif','archive') DEFAULT 'actif',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_cat` smallint(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`idprod`, `nomprod`, `prix`, `image`, `seuil`, `description`, `stock`, `statut`, `date_creation`, `id_cat`) VALUES
(8, 'casque', 5000, 'Casque-audio-TANBOW-C3-Gaming-filaire-1.webp', 0, 'casque audio', 55, 'actif', '2026-05-03 13:26:18', 1),
(9, 'airpod', 10000, 'airpod4.jpg', 45, 'airpod4', 30, 'actif', '2026-05-03 13:27:54', 1),
(12, 'montage 8', 4000000, 'yamaha-montage-8-back-view.webp', 5, 'yamaha montage 8', 18, 'actif', '2026-05-03 13:30:34', 2),
(13, 'moniteur', 250000, 'moniteur.png', 20, 'Moniteur', 45, 'actif', '2026-05-03 13:31:08', 3),
(14, 'clavier', 55000, 'clavier.jpg', 30, 'clavier lumiere', 50, 'actif', '2026-05-03 13:31:58', 3),
(15, 'lenovo', 500000, 'lenovopc.webp', 19, 'LENOVO', 29, 'actif', '2026-05-03 13:32:37', 3),
(16, 'motif xf8', 3000000, 'motifxf8.jpg', 20, 'best piano', 70, 'actif', '2026-05-03 13:35:01', 2),
(17, 'STAGE4', 3000000, 'srage4.webp', 34, 'STAGE4', 70, 'actif', '2026-05-03 13:38:41', 2);

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
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `statut` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`ref_uti`, `nom`, `email`, `password`, `role`, `date_creation`, `statut`) VALUES
(5, 'admin', 'admin@shop.com', 'admin123', 'admin', '2026-05-01 14:48:45', NULL),
(6, 'uzumaki joel', 'joelnambigue360@gmail.com', '$2y$10$4QBrUwp0RYEe1X.4fjY4wORvqfeD287B5/9XY51XMafUYD5F4fxEa', 'client', '2026-05-02 20:18:56', 'active'),
(7, 'wonipo', 'woniponambigue@gmail.com', '$2y$10$V3f.JW/By0uA2S4g9yVUce5t0DiwFUE8Q5dLDYQQdkXIDwJKQMCPC', 'client', '2026-05-03 13:45:00', 'active'),
(8, 'joel', 'uzn0921@gmail.com', '$2y$10$BQiero1HMJK3U3ghSnxzfOQlBmiFzm/15q9j8Rakd5X56jTRWbtW.', 'client', '2026-05-03 13:46:23', 'bloquer');

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
  MODIFY `id_cat` smallint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  MODIFY `id_commande_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `notification`
--
ALTER TABLE `notification`
  MODIFY `idnotif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `idpanier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `idprod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `ref_uti` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
