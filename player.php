<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/includes/db_connection.php';
include __DIR__ . '/includes/session_check.php';

$error = '';
$success = '';

// Fetch players and player statistics for the current user
$user_id = $_SESSION['user_id'];
$players = [];
$stats = [];

// Handle delete request for player statistics
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_statistic_id'])) {
    $statistic_id = $_POST['delete_statistic_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM Player_Statistic WHERE Statistic_ID = ?");
        $stmt->execute([$statistic_id]);
        $success = "Player statistic with ID $statistic_id has been successfully deleted.";
    } catch (PDOException $e) {
        $error .= "Error deleting player statistic: " . $e->getMessage() . "<br>";
    }
}

// Check if the user is in search mode
$search_mode = isset($_GET['search_mode']) && $_GET['search_mode'] === '1';

if ($search_mode) {
    // Handle player and player statistics search based on Team_ID
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_team_id'])) {
        $search_team_id = $_POST['search_team_id'];
        if (!empty($search_team_id)) {
            try {
                // Fetch players for the given Team_ID
                $stmt = $pdo->prepare("
                    SELECT p.* 
                    FROM Player p
                    INNER JOIN Team t ON p.Team_ID = t.Team_ID
                    WHERE t.Owner = ? AND p.Team_ID = ?
                ");
                $stmt->execute([$user_id, $search_team_id]);
                $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (empty($players)) {
                    $error = "No players found for Team ID: $search_team_id.";
                }

                // Fetch player statistics for the given Team_ID
                $stmt = $pdo->prepare("
                    SELECT ps.*
                    FROM Player_Statistic ps
                    INNER JOIN Player p ON ps.Player_ID = p.Player_ID
                    INNER JOIN Team t ON p.Team_ID = t.Team_ID
                    WHERE t.Owner = ? AND p.Team_ID = ?
                ");
                $stmt->execute([$user_id, $search_team_id]);
                $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (empty($stats)) {
                    $error .= " No statistics found for Team ID: $search_team_id.";
                }
            } catch (PDOException $e) {
                $error .= "Error searching for players or statistics: " . $e->getMessage() . "<br>";
            }
        } else {
            $error = "Please provide a valid Team ID.";
        }
    }
} else {
    // Fetch all players for the user
    try {
        $stmt = $pdo->prepare("CALL GetPlayerFromUser(?)");
        $stmt->execute([$user_id]);
        $players = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error .= "Error fetching players: " . $e->getMessage() . "<br>";
    }

    // Fetch all player statistics for the user
    try {
        $stmt = $pdo->prepare("CALL GetStatsFromUser(?)");
        $stmt->execute([$user_id]);
        $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error .= "Error fetching player statistics: " . $e->getMessage();
    }
}

include __DIR__ . '/templates/player.html.php';
?>
