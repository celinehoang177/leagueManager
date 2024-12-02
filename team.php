<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/includes/db_connection.php';
include __DIR__ . '/includes/session_check.php';

$error = '';
$success = '';

// Fetch teams for the current user
$user_id = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("SELECT * FROM Team WHERE Owner = ?");
    $stmt->execute([$user_id]);
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching teams: " . $e->getMessage();
}

// Handle form submission to add a new team
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $team_name = $_POST['team_name'] ?? '';
    $league_id = $_POST['league_id'] ?? '';

    if (!empty($team_name) && !empty($league_id)) {
        try {
            // Call the stored procedure to add the team
            $stmt = $pdo->prepare("CALL AddTeam(?, ?, ?)");
            $stmt->execute([$team_name, $user_id, $league_id]);
            $success = "Team added successfully!";

            // Refresh the team list after adding
            $stmt = $pdo->prepare("SELECT * FROM Team WHERE Owner = ?");
            $stmt->execute([$user_id]);
            $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "The specified League ID does not exist.";
            } else {
                $error = "Error adding team: " . $e->getMessage();
            }
        }
    } else {
        $error = "Team name and league are required.";
    }
}

include __DIR__ . '/templates/team.html.php';
