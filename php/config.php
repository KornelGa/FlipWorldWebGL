<?php
$host = 'localhost';
$dbname = 'flipgame';
$username = 'root'; // Default XAMPP MySQL username
$password = ''; // Default XAMPP MySQL password (üres)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
