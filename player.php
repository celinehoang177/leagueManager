<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/includes/db_connection.php';
include __DIR__ . '/includes/session_check.php';


$error = '';
$success = '';

// Determine sorting order and column
//$sort_column = isset($_GET['sort_by']) && in_array($_GET['sort_by'], ['TotalPoints', 'Team_ID']) ? $_GET['sort_by'] : 'TotalPoints';
//$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'asc' ? 'ASC' : 'DESC';

// Fetch player for the current user
$user_id = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("CALL GetPlayerFromUser(?)");
    $stmt->execute([$user_id]);
    $player = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching teams: " . $e->getMessage();
}


// Fetch Player Statistics for the current user
$user_id = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("CALL GetStatsFromUser(?)");
    $stmt->execute([$user_id]);
    $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching teams: " . $e->getMessage();
}


include __DIR__ . '/templates/player.html.php';