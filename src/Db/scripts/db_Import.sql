-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 13 juil. 2026 à 13:23
-- Version du serveur : 12.3.2-MariaDB
-- Version de PHP : 8.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données : `TouchePasAuKlaxon_DB`
--
USE `TouchePasAuKlaxon_DB`;
-- --------------------------------------------------------

--
-- Déchargement des données de la table `agencies`
--

INSERT INTO `agencies` (`id`, `name`) VALUES
(9, 'Bordeaux'),
(10, 'Lille'),
(2, 'Lyon'),
(3, 'Marseille'),
(8, 'Montpellier'),
(6, 'Nantes'),
(5, 'Nice'),
(1, 'Paris'),
(12, 'Reims'),
(11, 'Rennes'),
(7, 'Strasbourg'),
(4, 'Toulouse');

-- --------------------------------------------------------

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `phoneNumber`, `email`) VALUES
(1, 'Alexandre', 'Martin', '0612345678', 'alexandre.martin@email.fr'),
(2, 'Sophie', 'Dubois', '0698765432', 'sophie.dubois@email.fr'),
(3, 'Julien', 'Bernard', '0622446688', 'julien.bernard@email.fr'),
(4, 'Camille', 'Moreau', '0611223344', 'camille.moreau@email.fr'),
(5, 'Lucie', 'Lefèvre', '0777889900', 'lucie.lefevre@email.fr'),
(6, 'Thomas', 'Leroy', '0655443322', 'thomas.leroy@email.fr'),
(7, 'Chloé', 'Roux', '0633221199', 'chloe.roux@email.fr'),
(8, 'Maxime', 'Petit', '0766778899', 'maxime.petit@email.fr'),
(9, 'Laura', 'Garnier', '0688776655', 'laura.garnier@email.fr'),
(10, 'Antoine', 'Dupuis', '0744556677', 'antoine.dupuis@email.fr'),
(11, 'Emma', 'Lefebvre', '0699887766', 'emma.lefebvre@email.fr'),
(12, 'Louis', 'Fontaine', '0655667788', 'louis.fontaine@email.fr'),
(13, 'Clara', 'Chevalier', '0788990011', 'clara.chevalier@email.fr'),
(14, 'Nicolas', 'Robin', '0644332211', 'nicolas.robin@email.fr'),
(15, 'Marine', 'Gauthier', '0677889922', 'marine.gauthier@email.fr'),
(16, 'Pierre', 'Fournier', '0722334455', 'pierre.fournier@email.fr'),
(17, 'Sarah', 'Girard', '0688665544', 'sarah.girard@email.fr'),
(18, 'Hugo', 'Lambert', '0611223366', 'hugo.lambert@email.fr'),
(19, 'Julie', 'Masson', '0733445566', 'julie.masson@email.fr'),
(20, 'Arthur', 'Henry', '0666554433', 'arthur.henry@email.fr');

UPDATE `users` SET `admin` = 1 WHERE `id` = 1;

COMMIT;