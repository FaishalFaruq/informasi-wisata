CREATE DATABASE IF NOT EXISTS pwd;

USE pwd;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,         -- Kolom ID, auto increment
    name VARCHAR(100) NOT NULL,                -- Kolom Nama, wajib diisi
    email VARCHAR(100) UNIQUE NOT NULL,        -- Kolom Email, wajib diisi dan harus unik
    password VARCHAR(255) NOT NULL             -- Kolom Password (hashed), wajib diisi
);
