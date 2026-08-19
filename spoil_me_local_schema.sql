CREATE DATABASE IF NOT EXISTS spoil_me_local CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE spoil_me_local;

CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) NULL,
    role ENUM('user', 'admin', 'vip') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE platform (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    logo VARCHAR(255) NULL
);

CREATE TABLE genre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE series (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tmdb_id INT UNIQUE NULL,
    title VARCHAR(150) NOT NULL,
    synopsis TEXT,
    release_date DATE NULL,
    image VARCHAR(255) NULL,
    average_rating DECIMAL(3,1) DEFAULT 0,
    status ENUM('en cours', 'terminée', 'annulée') NULL,
    format VARCHAR(50) NULL,
    creator VARCHAR(100) NULL,
    country VARCHAR(50) NULL,
    language VARCHAR(50) NULL,
    nb_saisons INT NULL,
    nb_episodes INT NULL,
    backdrop VARCHAR(255) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INT NULL,
    FOREIGN KEY (created_by) REFERENCES user(id)
);

CREATE TABLE review (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT,
    is_official TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    series_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (series_id) REFERENCES series(id),
    FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE rating (
    id INT AUTO_INCREMENT PRIMARY KEY,
    value TINYINT NOT NULL CHECK (value BETWEEN 1 AND 5),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    series_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (series_id) REFERENCES series(id),
    UNIQUE (user_id, series_id)
);

CREATE TABLE comment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT,
    is_valid TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    review_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (review_id) REFERENCES review(id),
    FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE user_like (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    review_id INT NULL,
    comment_id INT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (review_id) REFERENCES review(id),
    FOREIGN KEY (comment_id) REFERENCES comment(id),
    UNIQUE KEY unique_user_review_like (user_id, review_id)
);

CREATE TABLE watchlist (
    status ENUM('à voir', 'en cours', 'terminé', 'abandonné') NOT NULL,
    added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    series_id INT NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (user_id, series_id),
    FOREIGN KEY (series_id) REFERENCES series(id),
    FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE series_platform (
    series_id INT NOT NULL,
    platform_id INT NOT NULL,
    PRIMARY KEY (series_id, platform_id),
    FOREIGN KEY (series_id) REFERENCES series(id),
    FOREIGN KEY (platform_id) REFERENCES platform(id)
);

CREATE TABLE series_genre (
    series_id INT NOT NULL,
    genre_id INT NOT NULL,
    PRIMARY KEY (series_id, genre_id),
    FOREIGN KEY (series_id) REFERENCES series(id),
    FOREIGN KEY (genre_id) REFERENCES genre(id)
);
