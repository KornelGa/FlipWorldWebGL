CREATE DATABASE IF NOT EXISTS flipgame;
USE flipgame;

CREATE TABLE IF NOT EXISTS users (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50) UNIQUE NOT NULL,
email VARCHAR(100) UNIQUE NOT NULL,
password VARCHAR(255) NOT NULL,
profile_pic VARCHAR(255) DEFAULT 'https://api.dicebear.com/7.x/avataaars/svg?seed=default'
);

CREATE TABLE IF NOT EXISTS user_levels (
user_id INT NOT NULL,
max_level INT NOT NULL DEFAULT 1,
PRIMARY KEY (user_id),
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);