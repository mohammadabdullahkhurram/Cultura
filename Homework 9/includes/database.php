<?php
$host = 'localhost';
$dbname = 'db_malgatay';
$username = 'malgatay';
$password = 'b3CMxxaKEi8hSuAa';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Database connection failed.";
    exit;
}
