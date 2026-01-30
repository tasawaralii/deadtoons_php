<?php

$db_name = $_ENV['DBNAME'];
$db_host = $_ENV['DBHOST'];
$db_user = $_ENV['DBUSER'];
$db_pass = $_ENV['DBPASS'];

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable exceptions for errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Set default fetch mode to associative array
];
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass, $options);
$pdo->exec("SET NAMES 'utf8mb4'");

?>