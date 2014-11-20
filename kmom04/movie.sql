USE rorb09;

SET NAMES 'utf8';

--
-- Drop all tables in the right order.
--
DROP TABLE IF EXISTS Movie2Genre;
DROP TABLE IF EXISTS Genre;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Movie;


--
-- Create table for my own movie database
--
DROP TABLE IF EXISTS Movie;
CREATE TABLE Movie
(
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    title VARCHAR(100) NOT NULL,
    director VARCHAR(100),
    length INT DEFAULT NULL, -- Length in minutes
    year INT NOT NULL DEFAULT 1900,
    plot TEXT, -- Short intro to the movie
    image VARCHAR(100) DEFAULT NULL, -- Link to an image
    subtext CHAR(3) DEFAULT NULL, -- swe, fin, en, etc
    speech CHAR(3) DEFAULT NULL, -- swe, fin, en, etc
    quality CHAR(3) DEFAULT NULL,
    format CHAR(3) DEFAULT NULL -- mp4, divx, etc
) ENGINE INNODB CHARACTER SET utf8;


INSERT INTO Movie (title, year, image) VALUES
    ('Pulp fiction', 1994, 'img/movie/pulp-fiction.jpg'),
    ('American Pie', 1999, 'img/movie/american-pie.jpg'),
    ('Pokémon The Movie 2000', 1999, 'img/movie/pokemon.jpg'),    
    ('Kopps', 2003, 'img/movie/kopps.jpg'),
    ('From Dusk Till Dawn', 1996, 'img/movie/from-dusk-till-dawn.jpg')
;


--
-- Add tables for genre
--
DROP TABLE IF EXISTS Genre;
CREATE TABLE Genre
(
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name CHAR(20) NOT NULL -- crime, svenskt, college, drama, etc
) ENGINE INNODB CHARACTER SET utf8;

INSERT INTO Genre (name) VALUES 
    ('comedy'), ('romance'), ('college'), 
    ('crime'), ('drama'), ('thriller'), 
    ('animation'), ('adventure'), ('family'), 
    ('svenskt'), ('action'), ('horror')
;

DROP TABLE IF EXISTS Movie2Genre;
CREATE TABLE Movie2Genre
(
    idMovie INT NOT NULL,
    idGenre INT NOT NULL,

    FOREIGN KEY (idMovie) REFERENCES Movie (id),
    FOREIGN KEY (idGenre) REFERENCES Genre (id),

    PRIMARY KEY (idMovie, idGenre)
) ENGINE INNODB;


INSERT INTO Movie2Genre (idMovie, idGenre) VALUES
    (1, 1),
    (1, 5),
    (1, 6),
    (2, 1),
    (2, 2),
    (2, 3),
    (3, 7),    
    (3, 8),    
    (3, 9),    
    (4, 11),
    (4, 1),
    (4, 10),
    (4, 9),
    (5, 11),
    (5, 4),
    (5, 12)
;

DROP VIEW IF EXISTS VMovie;

CREATE VIEW VMovie
AS
SELECT 
    M.*,
    GROUP_CONCAT(G.name) AS genre
FROM Movie AS M
    LEFT OUTER JOIN Movie2Genre AS M2G
        ON M.id = M2G.idMovie

    LEFT OUTER JOIN Genre AS G
         ON M2G.idGenre = G.id
GROUP BY M.id
;


--
-- Table for user
--
DROP TABLE IF EXISTS User;

CREATE TABLE User
(
  id INT AUTO_INCREMENT PRIMARY KEY,
  acronym CHAR(12) UNIQUE NOT NULL,
  name VARCHAR(80),
  password CHAR(32),
  salt INT NOT NULL
) ENGINE INNODB CHARACTER SET utf8;

INSERT INTO User (acronym, name, salt) VALUES 
  ('burnie', 'Robin Rietz', unix_timestamp()),
  ('admin', 'Administrator', unix_timestamp())
;

UPDATE User SET password = md5(concat('burnie', salt)) WHERE acronym = 'burnie';
UPDATE User SET password = md5(concat('admin', salt)) WHERE acronym = 'admin';