<?php

$db_name = "deadtoons";
$db_host = "38.127.216.175";
$db_user = "deadtoons";
$db_pass = "6@7A8a9a";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable exceptions for errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Set default fetch mode to associative array
];
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass, $options);
$pdo->exec("SET NAMES 'utf8mb4'");

?>