CREATE DATABASE doctrine1;
use doctrine1;
CREATE TABLE users(id INT PRIMARY KEY AUTO_INCREMENT, username VARCHAR(20) UNIQUE, password VARCHAR(255), admin VARCHAR(255));
CREATE TABLE category(id INT PRIMARY KEY AUTO_INCREMENT, categoryName VARCHAR(255));
CREATE TABLE posts(id INT PRIMARY KEY AUTO_INCREMENT, user_id INT, title VARCHAR(255), content TEXT, imageName VARCHAR(255), imagePath VARCHAR(255), createAt DATETIME);
CREATE TABLE tags(id INT PRIMARY KEY AUTO_INCREMENT, tag VARCHAR(255) UNIQUE);
CREATE TABLE posts_tags(post_id INT, tag_id INT);
CREATE TABLE products(id INT PRIMARY KEY AUTO_INCREMENT, category_id INT, productName VARCHAR(255), description TEXT, price INT UNSIGNED, imageName VARCHAR(255), imagePath VARCHAR(255), discount VARCHAR(10));
