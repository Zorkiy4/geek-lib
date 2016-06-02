<?php
$db_host = 'localhost';
$db_database = 'geeklib';
$db_username = 'root';
$db_password = 'root';

$connection = mysqli_connect($db_host, $db_username, $db_password);
if(!$connection) {
    die('Error connecting DB.<br>' . mysqli_error());
}

$db_select = mysqli_select_db($db_database);
if(!$db_select) {
    die('Error selecting DB.<br>' . mysqli_error());
}

$query = 'CREATE TABLE `categories` (
`id`  int UNSIGNED NOT NULL AUTO_INCREMENT ,
`name`  varchar(255) NULL ,
`parent`  int NULL ,
PRIMARY KEY (`id`),
INDEX `id` (`id`) 
);';

$result = mysqli_query($query);
if(!$result) {
    die('Error creating `categories` table.<br>' . mysqli_error());
}

$query = 'CREATE TABLE `books` (
`id`  int UNSIGNED NOT NULL AUTO_INCREMENT ,
`title`  varchar(255) NULL ,
`descr`  varchar(255) NULL ,
`cover`  varchar(255) NULL ,
`file`  varchar(255) NULL ,
`category_id`  int NULL ,
PRIMARY KEY (`id`),
CONSTRAINT `category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
INDEX `id` (`id`)
);';

$result = mysqli_query($query);
if(!$result) {
    die('Error creating `books` table.<br>' . mysqli_error());
}

echo 'Database installed successfully.';