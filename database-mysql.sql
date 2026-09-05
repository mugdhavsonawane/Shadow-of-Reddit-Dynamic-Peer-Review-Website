-- Database setup for Dynamic Peer Review / Quotation Service
-- Import this file into MariaDB/MySQL (for example through phpMyAdmin in XAMPP).

CREATE DATABASE IF NOT EXISTS quotes
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE quotes;

CREATE TABLE IF NOT EXISTS quotations (
    id INT(20) NOT NULL AUTO_INCREMENT,
    added DATETIME,
    quote VARCHAR(2000),
    author VARCHAR(100),
    rating INT(11) DEFAULT 0,
    flagged TINYINT(1) DEFAULT 0,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT,
    username VARCHAR(64),
    password VARCHAR(255),
    PRIMARY KEY (id)
);