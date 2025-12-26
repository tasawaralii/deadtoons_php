<?php
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable exceptions for errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Set default fetch mode to associative array
    ];
    $pdo = new PDO('mysql:host=localhost;dbname=deadtoons', 'deadtoons', '6@7A8a9a', $options);
    $pdo->exec("SET NAMES 'utf8mb4'");

?>
