-- Dataset Paylasim Sitesi - DDL

CREATE DATABASE IF NOT EXISTS dataset_site;
USE dataset_site;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(64) NOT NULL,
    created_at DATETIME DEFAULT NOW()
);

CREATE TABLE categories (
    cat_id INT AUTO_INCREMENT PRIMARY KEY,
    cat_name VARCHAR(100) NOT NULL
);

CREATE TABLE datasets (
    dataset_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    cat_id INT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    filename VARCHAR(255),
    filesize INT,
    upload_date DATETIME DEFAULT NOW(),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (cat_id) REFERENCES categories(cat_id)
);

CREATE TABLE tags (
    tag_id INT AUTO_INCREMENT PRIMARY KEY,
    tag_name VARCHAR(50) NOT NULL
);

CREATE TABLE dataset_tags (
    dataset_id INT,
    tag_id INT,
    PRIMARY KEY (dataset_id, tag_id),
    FOREIGN KEY (dataset_id) REFERENCES datasets(dataset_id),
    FOREIGN KEY (tag_id) REFERENCES tags(tag_id)
);

CREATE TABLE comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    dataset_id INT,
    user_id INT,
    comment_text TEXT NOT NULL,
    comment_date DATETIME DEFAULT NOW(),
    FOREIGN KEY (dataset_id) REFERENCES datasets(dataset_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE ratings (
    rating_id INT AUTO_INCREMENT PRIMARY KEY,
    dataset_id INT,
    user_id INT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    FOREIGN KEY (dataset_id) REFERENCES datasets(dataset_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE downloads (
    download_id INT AUTO_INCREMENT PRIMARY KEY,
    dataset_id INT,
    user_id INT,
    download_date DATETIME DEFAULT NOW(),
    FOREIGN KEY (dataset_id) REFERENCES datasets(dataset_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
