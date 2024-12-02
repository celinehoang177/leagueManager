<?php
include __DIR__ . '/includes/db_connection.php';
include __DIR__ . '/includes/session_check.php';

$error = '';
$success = '';

// Fetch teams for the current user
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM Team WHERE Owner = ?");
$stmt->execute([$user_id]);
$teams = $stmt->fetchAll();

// Handle form submission to add a new team
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $team_name = $_POST['team_name'];
    $league_id = $_POST['league_id'];

    if (!empty($team_name) && !empty($league_id)) {
        try {
            // Fetch the current maximum Team_ID
            $stmt = $pdo->query("SELECT MAX(Team_ID) AS MaxTeamID FROM Team");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $next_team_id = $result['MaxTeamID'] + 1;

            // If the table is empty, start Team_ID from 1
            if (is_null($result['MaxTeamID'])) {
                $next_team_id = 1;
            }

            // Insert the new team
            $stmt = $pdo->prepare("INSERT INTO Team (Team_ID, TeamName, Owner, League_ID, Status) VALUES (?, ?, ?, ?, 'A')");
            $stmt->execute([$next_team_id, $team_name, $user_id, $league_id]);
            $success = "Team added successfully!";
        } catch (PDOException $e) {
            $error = "Error adding team: " . $e->getMessage();
        }
    } else {
        $error = "Team name and league are required.";
    }
}

include __DIR__ . '/templates/team.html.php';
?>
