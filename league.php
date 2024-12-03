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

// Handle form submission for creating a new league
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['league_name'], $_POST['league_type'], $_POST['max_teams'], $_POST['draft_date'])) {
    $league_name = $_POST['league_name'];
    $league_type = $_POST['league_type'];
    $max_teams = (int)$_POST['max_teams'];
    $draft_date = $_POST['draft_date'];

    if (!empty($league_name) && !empty($league_type) && $max_teams > 0 && !empty($draft_date)) {
        try {
            // Fetch the current maximum League_ID
            $stmt = $pdo->query("SELECT IFNULL(MAX(League_ID), 0) + 1 AS NextLeagueID FROM League");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $next_league_id = $result['NextLeagueID'];

            // Insert the new league with the manually incremented League_ID
            $stmt = $pdo->prepare("INSERT INTO League (League_ID, LeagueName, LeagueType, MaxTeams, DraftDate, User_ID) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$next_league_id, $league_name, $league_type, $max_teams, $draft_date, $user_id]);

            $success = "League created successfully!";
            
            // Refresh the owned leagues list
            $stmt = $pdo->prepare("CALL GetLeagueFromUser(?)");
            $stmt->execute([$user_id]);
            $owned_leagues = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $error = "Error creating league: " . $e->getMessage();
        }
    } else {
        $error = "All fields are required to create a league.";
    }
}


include __DIR__ . '/templates/league.html.php';
