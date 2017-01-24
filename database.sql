DROP DATABASE IF EXISTS BMS;
CREATE DATABASE BMS CHARACTER SET utf8;
USE BMS;
CREATE TABLE BMS_users(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_name` TEXT BINARY,
    `user_password` TEXT,
    `user_email` TEXT,
    `user_type` INT,
    `user_lent_books` INT,
    `user_allow_books` INT,
    `user_waiting_books` INT,
    `user_reg_time` DATETIME
);
CREATE TABLE BMS_books(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `book_id` TEXT,
    `book_name` TEXT BINARY,
    `book_author` TEXT,
    `book_type` TEXT,
    `book_pub` TEXT,
    `book_status` TEXT,
    `book_lent` INT,
    `book_left` INT,
    `book_desc` LONGTEXT
);
CREATE TABLE BMS_user_info(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `mybook_id` TEXT,
    `lenttime` DATETIME,
    `returntime` DATETIME,
    `bookstatus` TEXT
);