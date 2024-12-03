<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ensure role is set
if (!isset($_SESSION['role'])) {
    header('Location: logout.php');
    exit();
}

// Function to restrict access to admins
function require_admin() {
    if ($_SESSION['role'] !== 'admin') {
        header('Location: unauthorized.php'); // Redirect non-admins
        exit();
    }
}
?>
