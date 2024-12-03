<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/includes/db_connection.php';
include __DIR__ . '/includes/session_check.php';

// Restrict access to admins
require_admin();

$error = '';
$success = '';
$users = [];

// Fetch all users
try {
    $stmt = $pdo->prepare("SELECT User_ID, FullName, Email, Username, Role FROM Users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching user data: " . $e->getMessage();
}

include __DIR__ . '/templates/users.html.php';
?>
