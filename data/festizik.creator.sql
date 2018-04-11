-- handwritten script. I'll be glad you tell me if that works ok for you.
--

-- creation of the user and its rights
DROP USER IF EXISTS festiv@localhost;
CREATE USER festiv@localhost IDENTIFIED BY 'Fetiv?Ultra!';
GRANT SELECT, INSERT, DELETE, UPDATE, CREATE, ALTER, DROP ON festizik.* TO 'festiv'@'localhost';

-- TODO :
-- ● ¿ Would a "name" field be welcome (table «concert»)?
-- • a table "keywords" would accelerate search into the site
-- • a table for logging all admin changes ...


CREATE DATABASE IF NOT EXISTS festizik DEFAULT CHARACTER SET utf8mb4;
USE festizik;

DROP TABLE IF EXISTS Concert;
DROP TABLE IF EXISTS Artist;
DROP TABLE IF EXISTS Style;
DROP TABLE IF EXISTS Day;
DROP TABLE IF EXISTS Scenes;

DROP TABLE IF EXISTS Article;
DROP TABLE IF EXISTS Page;



CREATE TABLE Style (
    id_style int unsigned PRIMARY KEY AUTO_INCREMENT,
    name varchar(128) NOT NULL
);

-- champs «picture» de la table artist : un chemin vers le fichier local
-- champs «about» : une description sommaire
CREATE TABLE Artist (
    id_artist int unsigned PRIMARY KEY AUTO_INCREMENT,
    name varchar(128) NOT NULL,
    id_style int unsigned NOT NULL,
    about text,
    picture text,
	CONSTRAINT FOREIGN KEY(id_style) REFERENCES Style(id_style)
);

CREATE TABLE Scene(
    id_scene int unsigned PRIMARY KEY AUTO_INCREMENT,
    name varchar(128) NOT NULL
);

CREATE TABLE Day (
    id_day int  unsigned PRIMARY KEY AUTO_INCREMENT,
    name varchar(32) NOT NULL,
    date date NOT NULL
);

CREATE TABLE Concert (
    id_concert int unsigned PRIMARY KEY AUTO_INCREMENT,
    id_day int unsigned NOT NULL,
    hour time not null,
    id_scene int unsigned NOT NULL,
    id_artist int unsigned NOT NULL,
    cancelled bool NOT NULL,
    CONSTRAINT FOREIGN KEY(id_day) REFERENCES Day(id_day),
    CONSTRAINT FOREIGN KEY(id_scene) REFERENCES Scene(id_scene),
    CONSTRAINT FOREIGN KEY(id_artist) REFERENCES Artist(id_artist)
);

-- ● Will the table "page" ever be used ?
CREATE TABLE Page (
    id_page int unsigned PRIMARY KEY AUTO_INCREMENT,
    name varchar(128) NOT NULL
);

-- ● table "article" :
--   same as above – picture is a server local path
-- ● should the field "content" be native HTML, with only quotes escaped ?
-- ● keywords_list would better be replaced by an intermediary table linking to
--		a "tags" table
CREATE TABLE Article (
    id_article int unsigned PRIMARY KEY AUTO_INCREMENT,
    title text NOT NULL,
    creation_date datetime NOT NULL,
    last_edit_date datetime NOT NULL,
-- TODO : wanna make it its own table later
    keywords_list text,
    id_page int unsigned NOT NULL,
    picture text,
    content text NOT NULL,
	CONSTRAINT FOREIGN KEY(id_page) REFERENCES Page(id_page)
);

--   surname : « nom de famille »
--   ¿ is the second phone number redundant?
DROP TABLE IF EXISTS Volunteer;
CREATE TABLE Volunteer (
    id_volunteer int unsigned PRIMARY KEY AUTO_INCREMENT,
    name varchar(128)  NOT NULL,
    surname varchar(128)  NOT NULL,
    phone_number_fix varchar(13),
    phone_number_mobile varchar(13),
    disponibility_start date NOT NULL,
    disponibility_end date NOT NULL,
    constraint check( disponibility_end >= disponibility_start )
);

-- ● table "administration" :
--    the hash function need to be defined – after implementing the connection page ?
DROP TABLE IF EXISTS Administration;
CREATE TABLE Administration (
    username varchar(128) NOT NULL,
    host varchar(128) NOT NULL,
    password varchar(256) NOT NULL
);


-- REMPLISSAGE ...
LOCK TABLES `Style` WRITE;
INSERT INTO `Style`  VALUES (1,'Pop'),(2,'Heavy Metal'),(3,'Classical'),(4,'Jazz'),(5,'Fusion');
UNLOCK TABLES;

LOCK TABLES `Artist` WRITE;
INSERT INTO `Artist` VALUES (1,'Mozart',2,'the best hard metal melody writer ever','assets/DBimages/Mozart.jpg'),(2,'Iron Maiden',3,'lullabies','assets/DBimages/Iron Maiden.jpeg'),(3,'Sbooba',1,'What the fuck is THAT ?','assets/DBimages/Sbooba.jpeg'),(4,'the Beethlestooven',4,'I really need to go to bed','assets/DBimages/Beethlestooven.jpg');
UNLOCK TABLES;

LOCK TABLES `Day` WRITE;
INSERT INTO `Day` VALUES (1,'Mon','2018-06-15'),(2,'Wed','2018-05-03'),(3,'LoveDay','2018-03-13');

LOCK TABLES `Page` WRITE;
INSERT INTO `Page` VALUES (1,'accueil');
UNLOCK TABLES;

LOCK TABLES `Article` WRITE;
INSERT INTO `Article` VALUES (3,'Elvis is back!','2018-12-02 12:22:00','2018-12-02 12:22:00','Elvis,Presley,return',1,NULL,'blabblabla&nbsp;?<br>Bla&nbsp;!'),(4,'Elvis is back!','2018-12-02 12:22:00','2018-12-02 12:22:00','Elvis,Presley,return',1,NULL,'blabblabla&nbsp;?<br>Bla&nbsp;!'),(5,'test article 1','2018-03-11 14:20:00','2018-03-16 10:00:00','test,muffins,goat',1,NULL,'mselkjsemlfksje  elkf?<br>mfslekjfseml fj<br>mslejkfsemlfkjsmel fj<br>');
UNLOCK TABLES;

LOCK TABLES `Scene` WRITE;
INSERT INTO `Scene` VALUES (1,'Grand-scène'),(2,'Sid'),(3,'Cavalera'),(4,'maxi'),(5,'funkyTown');
UNLOCK TABLES;

LOCK TABLES `Concert` WRITE;
INSERT INTO `Concert` (id_concert, id_day, hour, id_artist, id_scene, cancelled)
VALUES
    (1,1,'21:00:00',3,1,0),
    (2,1,'23:00:00',3,2,0),
    (3,2,'20:30:00',2,3,0),
    (4,3,'21:30:00',4,3,0),
    (5,3,'21:30:00',1,4,1);
UNLOCK TABLES;

LOCK TABLES `Volunteer` WRITE;
INSERT INTO `Volunteer` VALUES
  (1,'Jack','Chirac','0554525447',NULL,'2018-06-15','2018-07-30'),
  (2,'Éric','Dupont',NULL,'0671548456','2018-03-01','2018-03-21');
UNLOCK TABLES;



