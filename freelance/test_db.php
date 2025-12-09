<?php
try {
    $dsn = 'mysql:host=127.0.0.1;dbname=freelance';
    $username = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $username, $password);
    echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
