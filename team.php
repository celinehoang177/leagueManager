<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/includes/db_connection.php';
include __DIR__ . '/includes/session_check.php';

$error = '';
$success = '';
$teamerror = '';
$teamsuccess = '';
$tradeerror = '';
$tradesuccess = '';

$user_id = $_SESSION['user_id']; // Assign the user ID from the session

// Determine sorting order and column
$sort_column = isset($_GET['sort_by']) && in_array($_GET['sort_by'], ['TotalPoints', 'Team_ID']) ? $_GET['sort_by'] : 'TotalPoints';
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'asc' ? 'ASC' : 'DESC';

// Check if the user is an admin
$is_admin = $_SESSION['role'] === 'admin';

// Fetch teams
try {
    if ($is_admin) {
        // Admin sees all teams
        $stmt = $pdo->query("SELECT * FROM Team ORDER BY $sort_column $sort_order");
    } else {
        // Regular user sees only their teams
        $stmt = $pdo->prepare("SELECT * FROM Team WHERE Owner = ? ORDER BY $sort_column $sort_order");
        $stmt->execute([$_SESSION['user_id']]);
    }
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $teamerror = "Error fetching teams: " . $e->getMessage();
}

// Fetch waivers
try {
    if ($is_admin) {
        // Admin sees all waivers
        $stmt = $pdo->query("SELECT * FROM Waiver");
    } else {
        // Regular user sees waivers for their teams
        $stmt = $pdo->prepare("
            SELECT W.*
            FROM Waiver W
            JOIN Team T ON W.Team_ID = T.Team_ID
            WHERE T.Owner = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
    }
    $waivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching waivers: " . $e->getMessage();
}

// Fetch trades
try {
    if ($is_admin) {
        // Admin sees all trades
        $stmt = $pdo->query("SELECT * FROM Trade");
    } else {
        // Regular user sees trades involving their teams
        $stmt = $pdo->prepare("
            SELECT Tr.*
            FROM Trade Tr
            JOIN Team T1 ON Tr.Team1_ID = T1.Team_ID
            JOIN Team T2 ON Tr.Team2_ID = T2.Team_ID
            WHERE T1.Owner = ? OR T2.Owner = ?
        ");
        $stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
    }
    $trades = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $tradeerror = "Error fetching trades: " . $e->getMessage();
}

// Handle form submissions (e.g., adding teams or trades) remains unchanged
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle "Add Team" form
    if (isset($_POST['team_name']) && isset($_POST['league_id'])) {
        $team_name = $_POST['team_name'] ?? '';
        $league_id = $_POST['league_id'] ?? '';

        if (!empty($team_name) && !empty($league_id)) {
            try {
                // Call the stored procedure to add the team
                $stmt = $pdo->prepare("CALL AddTeam(?, ?, ?)");
                $stmt->execute([$team_name, $user_id, $league_id]);
                $teamsuccess = "Team added successfully!";

                // Refresh the team list
                $stmt = $pdo->prepare("SELECT * FROM Team WHERE Owner = ? ORDER BY $sort_column $sort_order");
                $stmt->execute([$user_id]);
                $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $teamerror = "The specified League ID does not exist.";
                } else {
                    $teamerror = "Error adding team: " . $e->getMessage();
                }
            }
        } else {
            $teamerror = "Team name and league are required.";
        }
    }

    // Handle "Add Trade" form
    if (isset($_POST['add_trade'])) {
        $team1_id = $_POST['team1_id'] ?? '';
        $team2_id = $_POST['team2_id'] ?? '';
        $player1_id = $_POST['player1_id'] ?? '';
        $player2_id = $_POST['player2_id'] ?? '';
        $trade_date = $_POST['trade_date'] ?? '';

        if (!empty($team1_id) && !empty($team2_id) && !empty($player1_id) && !empty($player2_id) && !empty($trade_date)) {
            try {
                // Validate Player-Team Relationship
                $stmt = $pdo->prepare("SELECT Player_ID FROM Player WHERE Player_ID = ? AND Team_ID = ?");
                $stmt->execute([$player1_id, $team1_id]);
                $player1_valid = $stmt->fetch(PDO::FETCH_ASSOC);

                $stmt = $pdo->prepare("SELECT Player_ID FROM Player WHERE Player_ID = ? AND Team_ID = ?");
                $stmt->execute([$player2_id, $team2_id]);
                $player2_valid = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$player1_valid || !$player2_valid) {
                    $tradeerror = "Invalid player IDs or mismatched team-player relationship.";
                } else {
                    // Insert Trade into the Trade Table
                    $stmt = $pdo->query("SELECT IFNULL(MAX(Trade_ID), 0) + 1 AS NextTradeID FROM Trade");
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $next_trade_id = $result['NextTradeID'];

                    $stmt = $pdo->prepare("
                        INSERT INTO Trade (Trade_ID, Team1_ID, Team2_ID, TradedPlayer1_ID, TradedPlayer2_ID, TradeDate)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$next_trade_id, $team1_id, $team2_id, $player1_id, $player2_id, $trade_date]);

                    // Update Player Table
                    $stmt = $pdo->prepare("UPDATE Player SET Team_ID = ? WHERE Player_ID = ?");
                    $stmt->execute([$team2_id, $player1_id]); // Player1 moves to Team2
                    $stmt->execute([$team1_id, $player2_id]); // Player2 moves to Team1

                    // Refetch Updated Trades
                    if ($is_admin) {
                        // Admin sees all trades
                        $stmt = $pdo->query("SELECT * FROM Trade");
                    } else {
                        // Regular user sees trades involving their teams
                        $stmt = $pdo->prepare("
                            SELECT Tr.*
                            FROM Trade Tr
                            JOIN Team T1 ON Tr.Team1_ID = T1.Team_ID
                            JOIN Team T2 ON Tr.Team2_ID = T2.Team_ID
                            WHERE T1.Owner = ? OR T2.Owner = ?
                        ");
                        $stmt->execute([$user_id, $user_id]);
                    }
                    $trades = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $tradesuccess = "Trade added successfully!";
                }
            } catch (PDOException $e) {
                $tradeerror = "Error adding trade: " . $e->getMessage();
            }
        } else {
            $tradeerror = "All fields are required to add a trade.";
        }
    }
}

include __DIR__ . '/templates/team.html.php';
?>
