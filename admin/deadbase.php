<?php
$options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable exceptions for errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Set default fetch mode to associative array
    ];
$db_host = 'localhost';
$db_name = 'deaddrive_deadbase';
$db_user = 'deaddrive_deadbase';
$db_pass = '6@7A8a9a';

$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass,$options);
?>