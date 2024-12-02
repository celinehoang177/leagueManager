<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/includes/db_connection.php';
include __DIR__ . '/includes/session_check.php';

$error = '';
$success = '';

// Fetch leagues owned by the current user
$user_id = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("CALL GetLeagueFromUser(?)");
    $stmt->execute([$user_id]);
    $owned_leagues = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching owned leagues: " . $e->getMessage();
}

// Determine if joined leagues should be displayed
$show_joined = isset($_GET['show_joined']) && $_GET['show_joined'] === '1';

// Fetch leagues joined by the current user if `show_joined` is true
$joined_leagues = [];
if ($show_joined) {
    try {
        $stmt = $pdo->prepare("
            SELECT L.*
            FROM League L
            JOIN Team T ON L.League_ID = T.League_ID
            WHERE T.Owner = ?
        ");
        $stmt->execute([$user_id]);
        $joined_leagues = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Error fetching joined leagues: " . $e->getMessage();
    }
}

// Fetch drafts for the leagues owned by the current user
$drafts = [];
try {
    $stmt = $pdo->prepare("
        SELECT D.*
        FROM Draft D
        JOIN League L ON D.League_ID = L.League_ID
        WHERE L.User_ID = ?
    ");
    $stmt->execute([$user_id]);
    $drafts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching drafts: " . $e->getMessage();
}

include __DIR__ . '/templates/league.html.php';
