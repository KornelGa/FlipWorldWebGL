<?php
session_start();
if (!isset($_SESSION['user_id'])) exit("Not logged in");

// Betöltjük a te config.php kapcsolatodat
require "config.php"; // ← EZT HASZNÁLJUK

$user_id = $_SESSION['user_id'];
$new_level = intval($_POST['level']);

// jelenlegi szint lekérdezése
$stmt = $pdo->prepare("SELECT max_level FROM user_levels WHERE user_id = ?");
$stmt->execute([$user_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    if ($new_level > $row["max_level"]) {
        $stmt = $pdo->prepare("UPDATE user_levels SET max_level = ? WHERE user_id = ?");
        $stmt->execute([$new_level, $user_id]);
    }
} else {
    $stmt = $pdo->prepare("INSERT INTO user_levels (user_id, max_level) VALUES (?, ?)");
    $stmt->execute([$user_id, $new_level]);
}

echo "OK";
?>