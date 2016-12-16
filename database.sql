DROP DATABASE IF EXISTS BMS;
CREATE DATABASE BMS CHARACTER SET utf8;
USE BMS;
CREATE TABLE BMS_users(
    `user_name` TEXT,
    `user_password` TEXT,
    `user_email` TEXT,
    `user_type` TINYINT
);
CREATE TABLE BMS_books(
    `book_name` TEXT,
    `book_id` TEXT,
    `book_pub` TEXT,
    `book_status` TEXT,
    `book_lent_time` DATETIME,
    `book_desc` LONGTEXT
);
CREATE TABLE BMS_user_info(
    `mybook` TEXT,
    `lenttime` DATETIME,
    `returntime` DATETIME,
    `bookstatus` TEXT
);
