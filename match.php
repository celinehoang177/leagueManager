<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/includes/db_connection.php';
include __DIR__ . '/includes/session_check.php';

$error = '';
$success = '';

// Get the current user ID
$user_id = $_SESSION['user_id'];

// Initialize matches array
$matches = [];

// Check if we are in search mode
$search_mode = isset($_GET['search_mode']) && $_GET['search_mode'] === '1';

if ($search_mode) {
    // Fetch matches based on the searched Team ID for the current user
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_team_id'])) {
        $search_team_id = $_POST['search_team_id'];
        if (!empty($search_team_id)) {
            try {
                $stmt = $pdo->prepare("
                    SELECT DISTINCT m.* 
                    FROM MatchInfo m
                    INNER JOIN Team t ON (m.Team1_ID = t.Team_ID OR m.Team2_ID = t.Team_ID)
                    WHERE t.Owner = ? AND (m.Team1_ID = ? OR m.Team2_ID = ?)
                ");
                $stmt->execute([$user_id, $search_team_id, $search_team_id]);
                $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (empty($matches)) {
                    $error = "No matches found for Team ID: $search_team_id.";
                }
            } catch (PDOException $e) {
                $error = "Error searching for matches: " . $e->getMessage();
            }
        } else {
            $error = "Please provide a valid Team ID to search.";
        }
    }
} else {
    // Fetch all matches for the user
    try {
        $stmt = $pdo->prepare("CALL GetMatchByUser(?)");
        $stmt->execute([$user_id]);
        $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Error fetching matches: " . $e->getMessage();
    }
}

include __DIR__ . '/templates/match.html.php';
