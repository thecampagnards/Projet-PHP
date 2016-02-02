-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 08 Avril 2015 à 15:37
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `mini_projet`
--

-- --------------------------------------------------------

--
-- Structure de la table `auteur`
--

CREATE TABLE IF NOT EXISTS `auteur` (
  `nom_auteur` varchar(20) NOT NULL DEFAULT '',
  `prenom_auteur` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`nom_auteur`,`prenom_auteur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `auteur`
--

INSERT INTO `auteur` (`nom_auteur`, `prenom_auteur`) VALUES
('EiichirÅ', 'Oda'),
('HergÃ©', 'Georges Remi'),
('Muchamore', 'Robert'),
('Poivre d''Arvor', 'Olivier'),
('Poivre d''Arvor', 'Patrick'),
('Rowling', 'J. K.'),
('Tolkien', 'J.R.R.');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
  `categorie` varchar(30) NOT NULL,
  PRIMARY KEY (`categorie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`categorie`) VALUES
('Argumentatif'),
('Autre'),
('BD'),
('Descriptif'),
('Epistolaire'),
('ExpÃ©rimental'),
('Graphique'),
('Magazine'),
('Manga'),
('Narratif'),
('PoÃ©tique'),
('Roman'),
('ThÃ©Ã¢tral');

-- --------------------------------------------------------

--
-- Structure de la table `demande`
--

CREATE TABLE IF NOT EXISTS `demande` (
  `pseudo` varchar(20) NOT NULL DEFAULT '',
  `num_exemplaire` int(11) NOT NULL AUTO_INCREMENT,
  `date_demande` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `duree` int(11) DEFAULT NULL,
  PRIMARY KEY (`pseudo`,`num_exemplaire`),
  KEY `duree` (`duree`),
  KEY `num_exemplaire` (`num_exemplaire`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- Structure de la table `duree`
--

CREATE TABLE IF NOT EXISTS `duree` (
  `duree` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`duree`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `duree`
--

INSERT INTO `duree` (`duree`) VALUES
(3),
(7),
(14),
(21),
(31);

-- --------------------------------------------------------

--
-- Structure de la table `emprunt`
--

CREATE TABLE IF NOT EXISTS `emprunt` (
  `date_fin_emprunt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pseudo` varchar(20) NOT NULL DEFAULT '',
  `num_exemplaire` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`pseudo`,`num_exemplaire`),
  KEY `num_exemplaire` (`num_exemplaire`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=78 ;

-- --------------------------------------------------------

--
-- Structure de la table `exemplaire`
--

CREATE TABLE IF NOT EXISTS `exemplaire` (
  `num_exemplaire` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` int(11) DEFAULT NULL,
  `disponible` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`num_exemplaire`),
  KEY `isbn` (`isbn`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=83 ;

--
-- Contenu de la table `exemplaire`
--

INSERT INTO `exemplaire` (`num_exemplaire`, `isbn`, `disponible`) VALUES
(56, 747532699, 1),
(57, 747532699, 1),
(58, 747532699, 1),
(59, 2147483647, 1),
(60, 2147483647, 1),
(61, 2147483647, 1),
(62, 98787645, 1),
(63, 98787645, 1),
(64, 98787645, 1),
(65, 2147483647, 1),
(66, 2147483647, 1),
(67, 8976465, 1),
(68, 8976465, 1),
(69, 8976465, 1),
(70, 8778987, 1),
(71, 797795, 1),
(72, 797795, 1),
(73, 797795, 1),
(74, 797795, 1),
(75, 797795, 1),
(76, 797795, 1),
(77, 797795, 1);

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

CREATE TABLE IF NOT EXISTS `genre` (
  `genre` varchar(45) NOT NULL,
  PRIMARY KEY (`genre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `genre`
--

INSERT INTO `genre` (`genre`) VALUES
('Autre'),
('Biographie'),
('Conte'),
('EpopÃ©e'),
('Fantastique'),
('Fiction'),
('Nouvelle'),
('Polar'),
('Science-Fiction'),
('TÃ©moignage');

-- --------------------------------------------------------

--
-- Structure de la table `livre`
--

CREATE TABLE IF NOT EXISTS `livre` (
  `isbn` int(20) NOT NULL DEFAULT '0',
  `titre` varchar(45) NOT NULL,
  `description` longtext NOT NULL,
  `categorie` varchar(30) NOT NULL,
  `genre` varchar(45) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`isbn`),
  KEY `categorie` (`categorie`),
  KEY `genre` (`genre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `livre`
--

INSERT INTO `livre` (`isbn`, `titre`, `description`, `categorie`, `genre`, `date`) VALUES
(797795, 'One Piece d''ici', 'One Piece relate les aventures d''un Ã©quipage de pirates ayant pour capitaine Monkey D. Luffy dont le rÃªve est d''obtenir le One Piece, fameux trÃ©sor inestimable qui appartenait au seigneur des pirates Gol D. Roger (ou Gold Roger) exÃ©cutÃ© il y a de cela plus de vingt ans ; celui qui dÃ©couvrira ce trÃ©sor pourra devenir Ã  son tour le seigneur des pirates.', 'Manga', 'Fantastique', '2015-04-01 13:02:13'),
(8778987, 'Le Roman de Virginie', 'MalgrÃ© dix ans d''Ã©cart, Patrick et Olivier sont des frÃ¨res unis et complices, frÃ¨res en rÃªveries, en poÃ©tiques demi-mensonges. Et, surtout, frÃ¨res en nostalgie de Virginie, leur jeune sueur fugueuse, portÃ©e disparue au large de l''Ã®le Maurice. LÃ  mÃªme oÃ¹, il y a deux siÃ¨cles, la Virginie de Bernardin de Saint-Pierre pÃ©rissait dans les flots dÃ©chaÃ®nÃ©s... Pure coÃ¯ncidence ? Rendez-vous symbolique\r\nUn jour, ce mystÃ¨re leur pÃ¨se trop. Ils veulent connaÃ®tre la vÃ©ritÃ©, percer le secret...\r\nIls vont suivre la piste du trop romanesque prÃ©nom, retournant sur les lieux de leur enfance Ã  trois, se dÃ©couvrant au passage des ancÃªtres aventuriers et poÃ¨tes, tous amoureux du grand large.\r\nIls iront jusqu''Ã  l''Ã®le lointaine. Elle seule - peut-Ãªtre - leur livrera une rÃ©ponseâ€¦', 'Roman', 'Nouvelle', '2015-04-05 13:02:13'),
(8976465, 'Tintin au pays des Soviets', 'Tintin au Pays des Soviets raconte les tribulations de Tintin, reporter au petit vingtiÃ¨me, et de son fidÃ¨le cabot Milou Ã  travers l''Allemagne et surtout l''URSS. Chacun en prend pour son grade : le rÃ©gime soviÃ©tique en premier lieu mais aussi les voisins allemands.', 'BD', 'Polar', '2015-04-04 13:02:13'),
(98787645, 'Cherub, Tome 1 : 100 jours en enfer', 'James, placÃ© dans un orphelinat sordide Ã  la mort de sa mÃ¨re, ne tarde pas Ã  tomber dans la dÃ©linquance. Il est alors recrutÃ© par CHERUB et va suivre un Ã©prouvant programme d''entraÃ®nement avant de se voir confier sa premiÃ¨re mission d''agent secret. Sera t-il capable de rÃ©sister 100 jours ? 100 jours en enfer...', 'Roman', 'Polar', '2015-04-05 13:02:13'),
(747532699, 'Harry Potter Ã  l''Ã©cole des sorciers', 'Depuis la mort de ses parents, Harry vit chez son oncle et sa tante, les horribles Dursmley. Le jour de ses onze ans, il reÃ§oit une lettre, deux lettres, des milliers de lettres qu''ils lui interdisent de lire. Le gÃ©ant Hagrid se dÃ©place donc en personne pour lui apprendre qu''il est attendu Ã  l''Ã©cole des sorciers... Un phÃ©nomÃ¨ne de librairie qui apporte un peu de magie au quotidien. Mais attention, ce livre exerce bien un malÃ©fice sur ses lecteurs : quand on le commence, on ne le lÃ¢che plus !', 'Roman', 'Fantastique', '2015-04-05 13:02:13'),
(2147483647, 'Le seigneur des anneaux', 'Aux temps reculÃ©s de ce rÃ©cit, la Terre est peuplÃ©e d''innombrables crÃ©atures : les Hobbits, apparentÃ©s Ã  l''Homme, les Elfes et les Nains vivent en paix dans la ComtÃ©. Une paix menacÃ©e depuis que l''Anneau de Puissance, forgÃ© par Sauron de Mordor, a Ã©tÃ© dÃ©robÃ©. Or cet anneau est dotÃ© d''un pouvoir malÃ©fique qui confÃ¨re Ã  son dÃ©tenteur une autoritÃ© sans limite et fait de lui le MaÃ®tre du monde. Sauron s''est donc jurÃ© de le reconquÃ©rir...', 'Roman', 'Fantastique', '2015-04-05 13:02:13');

-- --------------------------------------------------------

--
-- Structure de la table `livre_auteur`
--

CREATE TABLE IF NOT EXISTS `livre_auteur` (
  `isbn` int(11) NOT NULL DEFAULT '0',
  `nom_auteur` varchar(20) NOT NULL DEFAULT '',
  `prenom_auteur` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`isbn`,`nom_auteur`,`prenom_auteur`),
  KEY `fk_auteur` (`nom_auteur`,`prenom_auteur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `livre_auteur`
--

INSERT INTO `livre_auteur` (`isbn`, `nom_auteur`, `prenom_auteur`) VALUES
(797795, 'EiichirÅ', 'Oda'),
(8976465, 'HergÃ©', 'Georges Remi'),
(98787645, 'Muchamore', 'Robert'),
(8778987, 'Poivre d''Arvor', 'Olivier'),
(8778987, 'Poivre d''Arvor', 'Patrick'),
(747532699, 'Rowling', 'J. K.'),
(2147483647, 'Tolkien', 'J.R.R.');

-- --------------------------------------------------------

--
-- Structure de la table `niveau`
--

CREATE TABLE IF NOT EXISTS `niveau` (
  `niveau` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`niveau`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `niveau`
--

INSERT INTO `niveau` (`niveau`) VALUES
(0),
(1),
(2);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `pseudo` varchar(20) NOT NULL DEFAULT '',
  `mot_de_passe` varchar(60) NOT NULL,
  `niveau` int(11) DEFAULT '0',
  PRIMARY KEY (`pseudo`),
  KEY `niveau` (`niveau`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`pseudo`, `mot_de_passe`, `niveau`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', 2),
('konstantin', '9115b9aab6fd005f51e429a39e9e9618', 0),
('stagiaire', '3fb7cdc2547db4fc6740df0cc7cd6532 ', 1);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `demande`
--
ALTER TABLE `demande`
  ADD CONSTRAINT `demande_ibfk_1` FOREIGN KEY (`duree`) REFERENCES `duree` (`duree`),
  ADD CONSTRAINT `demande_ibfk_2` FOREIGN KEY (`num_exemplaire`) REFERENCES `exemplaire` (`num_exemplaire`),
  ADD CONSTRAINT `demande_ibfk_3` FOREIGN KEY (`pseudo`) REFERENCES `utilisateur` (`pseudo`);

--
-- Contraintes pour la table `emprunt`
--
ALTER TABLE `emprunt`
  ADD CONSTRAINT `emprunt_ibfk_1` FOREIGN KEY (`num_exemplaire`) REFERENCES `exemplaire` (`num_exemplaire`),
  ADD CONSTRAINT `emprunt_ibfk_2` FOREIGN KEY (`pseudo`) REFERENCES `utilisateur` (`pseudo`);

--
-- Contraintes pour la table `exemplaire`
--
ALTER TABLE `exemplaire`
  ADD CONSTRAINT `exemplaire_ibfk_1` FOREIGN KEY (`isbn`) REFERENCES `livre` (`isbn`);

--
-- Contraintes pour la table `livre`
--
ALTER TABLE `livre`
  ADD CONSTRAINT `livre_ibfk_1` FOREIGN KEY (`categorie`) REFERENCES `categorie` (`categorie`),
  ADD CONSTRAINT `livre_ibfk_2` FOREIGN KEY (`genre`) REFERENCES `genre` (`genre`);

--
-- Contraintes pour la table `livre_auteur`
--
ALTER TABLE `livre_auteur`
  ADD CONSTRAINT `fk_auteur` FOREIGN KEY (`nom_auteur`, `prenom_auteur`) REFERENCES `auteur` (`nom_auteur`, `prenom_auteur`),
  ADD CONSTRAINT `livre_auteur_ibfk_1` FOREIGN KEY (`isbn`) REFERENCES `livre` (`isbn`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`niveau`) REFERENCES `niveau` (`niveau`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
