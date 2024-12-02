<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/includes/db_connection.php';
include __DIR__ . '/includes/session_check.php';

$error = '';
$success = '';

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("CALL GetMatchByUser(?)");
    $stmt->execute([$user_id]);
    $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching matches: " . $e->getMessage();
}

include __DIR__ . '/templates/match.html.php';