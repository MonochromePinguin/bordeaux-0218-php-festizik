-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mer 25 Avril 2018 à 11:41
-- Version du serveur :  5.7.22-0ubuntu0.16.04.1
-- Version de PHP :  7.0.28-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `festizik`
--

-- --------------------------------------------------------

--
-- Structure de la table `Administration`
--

CREATE TABLE `Administration` (
  `id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `Administration`
--

INSERT INTO `Administration` (`id`, `username`, `password`) VALUES
(3, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `Article`
--

CREATE TABLE `Article` (
  `id_article` int(10) UNSIGNED NOT NULL,
  `title` text NOT NULL,
  `keywords_list` text,
  `id_page` int(10) UNSIGNED NOT NULL,
  `picture` text,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `Article`
--

INSERT INTO `Article` (`id_article`, `title`, `keywords_list`, `id_page`, `picture`, `content`) VALUES
(6, 'Pourquoi nous rejoindre ?', NULL, 1, 'https://static.mmzstatic.com/wp-content/uploads/2015/01/benevole-festival-musique-temoignage.jpg', 'Depuis sa création, le Festizik est un rendez-vous festif et humain. Chaque année, plus de 300 bénévoles viennent intégrer l’équipe selon leurs envies et leurs disponibilités pour donner vie au festival. L’occasion de vivre le festival de l’intérieur, de retrouver les habitués ou de rencontrer une foule de gens intéressants ! Vous pourrez par exemple travailler à la brigade verte, au bar, à la technique, au merchandising... '),
(7, '27 AU 29 JUILLET 2018', NULL, 2, NULL, '\r\n\r\nTrois jours, plus de cent concerts, trois scènes différentes, un public venu du monde entier, beaucoup de bénévoles... Voilà ce qui vous attends au FestiZik.\r\n\r\nLe quai de plage accueillera le village officiel où vous pourrez manger, boire et acheter toutes sortes de souvenirs.\r\n\r\nLe camping se situe juste à coté du village. Vous trouverez toutes les informations de localisation sur la carte juste à droite.\r\n\r\nRéservez ICI .\r\n<i class="fa fa-plane"></i> : 11 minutes en voiture\r\n<i class="fa fa-train"></i> : 14 minutes en voiture\r\n');

-- --------------------------------------------------------

--
-- Structure de la table `Artist`
--

CREATE TABLE `Artist` (
  `id_artist` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL,
  `id_style` int(10) UNSIGNED NOT NULL,
  `about` text,
  `picture` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `Artist`
--

INSERT INTO `Artist` (`id_artist`, `name`, `id_style`, `about`, `picture`) VALUES
(1, 'Her', 4, 'Her est un groupe français de musique soul originaire de Rennes. Formé en 2015, il est composé de Simon Carpentier et Victor Solf. Le nom du groupe, qui signifie « Elle » en anglais, fut choisi par ses deux membres pour représenter la cause des femmes et du féminisme.', '/assets/DBimages/her.jpg'),
(2, 'Kygo', 2, 'Tipped by Billboard Magazine as the ‘the next EDM superstar’, Kyrre Gørvell-Dahll, a.k.a KYGO, has gone from bedroom producer to one of the most hyped electronic artists on the planet in unprecedented time.', '/assets/DBimages/kygo.jpg'),
(3, 'Popof', 2, 'Après la création d\'Heretik en 1996, Alexandre Paounov se rapproche du collectif et sort son premier EP sur le label Just Listen sous le nom de Popof. Il sera un acteur majeur des raves des années 90 avec toute la bande d\'Heretik. Petit à petit, sa musique évolue pour tendre vers une tech-house plus minimale.', '/assets/DBimages/popof.jpg'),
(4, 'Little Simz', 1, 'Simbi Ajikawo, plus connue sous le nom de Little Simz, est une rappeuse, musicienne et actrice anglaise.\r\nAprès avoir sortie quatre mixtapes et cinq EPs, elle sort son premier album A Curious Tale of Trials + Persons le 18 septembre 2015 sous son label, AGE: 101 Music.', '/assets/DBimages/littlesimz.jpg'),
(5, 'Joris Delacroix', 2, 'Depuis un an, sa techno mélodique et puissante retourne tous les endroits où il joue, comme le Social Club à Paris l\'été dernier, Joris Delacroix, à peine 24 ans au compteur, mérite bien le qualificatif pourtant galvaudé de producteur à suivre.', '/assets/DBimages/jorisdelacroix.jpg'),
(6, 'Leafdog', 1, 'Leaf Dog has been dabbling with wordplay for the best part of ten years. One third of underground heroes The Three Amigos and beat maker to the stars, the time has come for Leaf Dog to add his debut solo release to an already bulging back catalogue of crew releases, live plaudits, guest features and production credits.', '/assets/DBimages/leafdog.jpg'),
(7, 'Röyksopp', 2, 'Röyksopp est un groupe de musique norvégien formé en 1998 et composé de Svein Berge et Torbjørn Brundtland, originaires de Tromsø. Le groupe a affirmé sa place sur la scène électronique avec son premier album, Melody A.M.', '/assets/DBimages/royksopp.jpg'),
(8, 'Jorja Smith', 3, 'Jorja Smith est une autrice-compositrice-interprète RnB anglaise dont la cadence soul et jazz, évoque des noms comme Alunageorge, Lulu James et Amy Winehouse, que l\'artiste cite comme sa plus grande influence.  ', '/assets/DBimages/jorjasmith.jpg'),
(9, 'Dirty Dike', 1, 'Dirty Dike is an all-talking, all-breathing, fully-functioning rap FIEND.\r\nCatch him on a peaceful one and he will most likely be sucking on king prawns in the moonlight and throwing their discarded pink tails to Cretian street hounds. Catch him on a mental one and he will be seventeen quaddy voddys deep slurring freestyles in a pair of rusty Pelle Pelle boxers and an odd pair of his girlfriends socks to Cambridge University students…', '/assets/DBimages/dirtydyke.jpg'),
(10, 'FKJ', 2, 'French Kiwi Juice (FKJ) is a French multi-instrumentalist, singer, and musician from Tours. His debut album, French Kiwi Juice, was released on March 3, 2017. FKJ has performed at music festivals including Coachella, EUPHORIA, CRSSD, and Lightning in a Bottle.', '/assets/DBimages/fkj.jpg'),
(11, 'The Dead South', 5, 'Neo-Folk/Bluegrass four-piece The Dead South hail from the remote prairies of rural Canada. Having described themselves as ‘Mumford and Sons’ evil twins’, the band deliver a rich, compelling sound that is “chock full of banjo plucking, twang and impressive harmonies” (Canadian Beats) and tinged with tongue-in-cheek humour.', '/assets/DBimages/thedeadsouth.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `Concert`
--

CREATE TABLE `Concert` (
  `id_concert` int(10) UNSIGNED NOT NULL,
  `id_day` int(10) UNSIGNED NOT NULL,
  `hour` time NOT NULL,
  `id_scene` int(10) UNSIGNED NOT NULL,
  `id_artist` int(10) UNSIGNED NOT NULL,
  `cancelled` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `Concert`
--

INSERT INTO `Concert` (`id_concert`, `id_day`, `hour`, `id_scene`, `id_artist`, `cancelled`) VALUES
(1, 1, '21:00:00', 3, 1, 0),
(2, 1, '23:00:00', 3, 2, 0),
(3, 2, '20:30:00', 2, 3, 0),
(4, 3, '21:30:00', 4, 3, 0),
(5, 3, '21:30:00', 1, 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `Day`
--

CREATE TABLE `Day` (
  `id_day` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `Day`
--

INSERT INTO `Day` (`id_day`, `name`, `date`) VALUES
(1, 'Mon', '2018-06-15'),
(2, 'Wed', '2018-05-03'),
(3, 'LoveDay', '2018-03-13');

-- --------------------------------------------------------

--
-- Structure de la table `Page`
--

CREATE TABLE `Page` (
  `id_page` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `Page`
--

INSERT INTO `Page` (`id_page`, `name`) VALUES
(1, 'bénévoles'),
(2, 'Infos');

-- --------------------------------------------------------

--
-- Structure de la table `Scene`
--

CREATE TABLE `Scene` (
  `id_scene` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `Scene`
--

INSERT INTO `Scene` (`id_scene`, `name`) VALUES
(1, 'Grand-scène'),
(2, 'Sid'),
(3, 'Cavalera'),
(4, 'maxi'),
(5, 'funkyTown');

-- --------------------------------------------------------

--
-- Structure de la table `Style`
--

CREATE TABLE `Style` (
  `id_style` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `Style`
--

INSERT INTO `Style` (`id_style`, `name`) VALUES
(1, 'Hip-Hop/Rap'),
(2, 'Electronique'),
(3, 'R&B'),
(4, 'Indie'),
(5, 'Folk');

-- --------------------------------------------------------

--
-- Structure de la table `Volunteers`
--

CREATE TABLE `Volunteers` (
  `id_volunteer` int(10) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL,
  `surname` varchar(128) NOT NULL,
  `phone` varchar(13) DEFAULT NULL,
  `disponibility_start` date NOT NULL,
  `disponibility_end` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `Volunteers`
--

INSERT INTO `Volunteers` (`id_volunteer`, `name`, `surname`, `phone`, `disponibility_start`, `disponibility_end`) VALUES
(1, 'Jack', 'Chirac', NULL, '2018-06-15', '2018-07-30'),
(2, 'Éric', 'Dupont', '0671548456', '2018-03-01', '2018-03-21'),
(3, 'hrsin', ',feposg', '0555555555', '2018-04-20', '2018-04-21'),
(4, 'hrhrd', 'tjxdjt', 'jjtfjgf', '2018-04-19', '2018-04-18');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `Administration`
--
ALTER TABLE `Administration`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `Article`
--
ALTER TABLE `Article`
  ADD PRIMARY KEY (`id_article`),
  ADD KEY `id_page` (`id_page`);

--
-- Index pour la table `Artist`
--
ALTER TABLE `Artist`
  ADD PRIMARY KEY (`id_artist`),
  ADD KEY `id_style` (`id_style`);

--
-- Index pour la table `Concert`
--
ALTER TABLE `Concert`
  ADD PRIMARY KEY (`id_concert`),
  ADD KEY `id_artist` (`id_artist`),
  ADD KEY `id_scene` (`id_scene`),
  ADD KEY `id_day` (`id_day`);

--
-- Index pour la table `Day`
--
ALTER TABLE `Day`
  ADD PRIMARY KEY (`id_day`);

--
-- Index pour la table `Page`
--
ALTER TABLE `Page`
  ADD PRIMARY KEY (`id_page`);

--
-- Index pour la table `Scene`
--
ALTER TABLE `Scene`
  ADD PRIMARY KEY (`id_scene`);

--
-- Index pour la table `Style`
--
ALTER TABLE `Style`
  ADD PRIMARY KEY (`id_style`);

--
-- Index pour la table `Volunteers`
--
ALTER TABLE `Volunteers`
  ADD PRIMARY KEY (`id_volunteer`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `Administration`
--
ALTER TABLE `Administration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `Article`
--
ALTER TABLE `Article`
  MODIFY `id_article` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `Artist`
--
ALTER TABLE `Artist`
  MODIFY `id_artist` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT pour la table `Concert`
--
ALTER TABLE `Concert`
  MODIFY `id_concert` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `Day`
--
ALTER TABLE `Day`
  MODIFY `id_day` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `Page`
--
ALTER TABLE `Page`
  MODIFY `id_page` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `Scene`
--
ALTER TABLE `Scene`
  MODIFY `id_scene` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `Style`
--
ALTER TABLE `Style`
  MODIFY `id_style` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `Volunteers`
--
ALTER TABLE `Volunteers`
  MODIFY `id_volunteer` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Article`
--
ALTER TABLE `Article`
  ADD CONSTRAINT `Article_ibfk_1` FOREIGN KEY (`id_page`) REFERENCES `Page` (`id_page`);

--
-- Contraintes pour la table `Artist`
--
ALTER TABLE `Artist`
  ADD CONSTRAINT `Artist_ibfk_1` FOREIGN KEY (`id_style`) REFERENCES `Style` (`id_style`);

--
-- Contraintes pour la table `Concert`
--
ALTER TABLE `Concert`
  ADD CONSTRAINT `Concert_ibfk_1` FOREIGN KEY (`id_artist`) REFERENCES `Artist` (`id_artist`),
  ADD CONSTRAINT `Concert_ibfk_2` FOREIGN KEY (`id_scene`) REFERENCES `Scene` (`id_scene`),
  ADD CONSTRAINT `Concert_ibfk_3` FOREIGN KEY (`id_day`) REFERENCES `Day` (`id_day`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
