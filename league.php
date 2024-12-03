<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/includes/db_connection.php';
include __DIR__ . '/includes/session_check.php';

$error = '';
$success = '';

// Fetch leagues and drafts based on user role
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

$owned_leagues = [];
$joined_leagues = [];
$drafts = [];

try {
    if ($user_role === 'admin') {
        // Admin: Fetch all leagues
        $stmt = $pdo->query("SELECT * FROM League");
        $owned_leagues = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Admin: Fetch all drafts
        $stmt = $pdo->query("SELECT * FROM Draft");
        $drafts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // User: Fetch owned leagues
        $stmt = $pdo->prepare("CALL GetLeagueFromUser(?)");
        $stmt->execute([$user_id]);
        $owned_leagues = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Determine if joined leagues should be displayed
        $show_joined = isset($_GET['show_joined']) && $_GET['show_joined'] === '1';

        // Fetch leagues joined by the current user if `show_joined` is true
        if ($show_joined) {
            $stmt = $pdo->prepare("
                SELECT DISTINCT L.*
                FROM League L
                JOIN Team T ON L.League_ID = T.League_ID
                WHERE T.Owner = ?
            ");
            $stmt->execute([$user_id]);
            $joined_leagues = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Fetch drafts for the leagues owned by the current user
        $stmt = $pdo->prepare("
            SELECT D.*
            FROM Draft D
            JOIN League L ON D.League_ID = L.League_ID
            WHERE L.User_ID = ?
        ");
        $stmt->execute([$user_id]);
        $drafts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

include __DIR__ . '/templates/league.html.php';
