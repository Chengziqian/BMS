DROP DATABASE IF EXISTS BMS;
CREATE DATABASE BMS CHARACTER SET utf8;
USE BMS;
CREATE TABLE BMS_users(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_name` TEXT BINARY,
    `user_password` TEXT BINARY,
    `user_email` TEXT BINARY,
    `user_type` INT,
    `user_lent_books` INT,
    `user_allow_books` INT,
    `user_waiting_books` INT,
    `user_reg_time` DATETIME,
    `user_rel_name` TEXT,
    `user_school` TEXT
);
CREATE TABLE BMS_books(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `book_code` TEXT,
    `book_status` INT,
    `lent_time` DATETIME,
    `user_id` INT
);
CREATE TABLE BMS_books_history(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `book_code` TEXT,
    `lent_time` DATETIME,
    `apply_return_time` DATETIME,
    `return_time` DATETIME,
    `user_id` INT,
    `flag` INT
);
CREATE TABLE BMS_books_user_history(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `book_code` TEXT,
    `lent_time` DATETIME,
    `apply_return_time` DATETIME,
    `user_id` INT,
    `flag` INT
);
CREATE TABLE BMS_books_index(
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `book_code_index` TEXT,
    `book_name` TEXT BINARY,
    `book_num` INT,
    `book_lent` INT,
    `book_author` TEXT,
    `book_type` TEXT,
    `book_pub` TEXT,
    `book_desc` LONGTEXT,
    `book_cover` TEXT
);
INSERT INTO BMS_users (`user_name`,`user_password`,`user_email`,`user_type`,`user_allow_books`,`user_reg_time`,`user_lent_books`,`user_waiting_books`) VALUE('Admin','root','Admin@BMS.com','1','40',now(),0,0);
