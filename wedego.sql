-- Active: 1726762287669@@127.0.0.1@3306@wedegoo
use wedego;
CREATE TABLE tours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255),
    price DECIMAL(10, 2) NOT NULL,
    available_places INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE trips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    available_places INT NOT NULL,
    image VARCHAR(255),
    country ENUM('rwanda', 'uganda', 'kenya', 'tanzanie') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Index pour améliorer les performances
CREATE INDEX country ON trips(country);



SHOW TABLES;
SELECT * FROM trips;
SELECT * FROM articles;
DESC tours;
DELETE FROM `users` WHERE `id`=8;
ALTER TABLE tours DROP COLUMN start_date ;
SELECT * FROM trips;
ALTER TABLE sorties DROP COLUMN start_date ;
ALTER TABLE sorties DROP COLUMN end_date ;
DROP TABLE events ;

DESC reservations;
ALTER TABLE users ADD COLUMN last_login TIMESTAMP NULL;

ALTER TABLE users
DROP COLUMN last_login;






