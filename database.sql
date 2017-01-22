DROP DATABASE IF EXISTS BMS;
CREATE DATABASE BMS CHARACTER SET utf8;
USE BMS;
CREATE TABLE BMS_users(
    `user_name` TEXT,
    `user_password` TEXT,
    `user_email` TEXT,
    `user_type` INT,
    `user_lent_books` INT,
    `user_allow_books` INT,
    `user_waiting_books` INT
);
CREATE TABLE BMS_books(
    `book_id` TEXT,
    `book_name` TEXT,
    `book_author` TEXT,
    `book_type` TEXT,
    `book_pub` TEXT,
    `book_status` TEXT,
    `book_lent` INT,
    `book_left` INT,
    `book_desc` LONGTEXT
);
CREATE TABLE BMS_user_info(
    `mybook_id` TEXT,
    `lenttime` DATETIME,
    `returntime` DATETIME,
    `bookstatus` TEXT
);
