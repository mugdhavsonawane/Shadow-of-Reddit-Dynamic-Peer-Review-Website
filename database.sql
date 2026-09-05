-- PostgreSQL database schema for Shadow of Reddit.
-- The PostgreSQL database itself is created through the
-- Alwaysdata administration interface.

CREATE TABLE IF NOT EXISTS quotations (
    id SERIAL PRIMARY KEY,
    added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    quote VARCHAR(2000) NOT NULL,
    author VARCHAR(100) NOT NULL,
    rating INTEGER NOT NULL DEFAULT 0,
    flagged BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(64) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);