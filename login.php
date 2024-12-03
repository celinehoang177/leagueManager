<?php
include __DIR__ . '/includes/db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE Username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['Password'])) {
            $_SESSION['user_id'] = $user['User_ID'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['fullname'] = $user['FullName'];
            $_SESSION['role'] = $user['Role']; // Store user role in session
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Invalid username or password!";
        }
    } catch (PDOException $e) {
        $error = "Error logging in: " . $e->getMessage();
    }
}

$title = "Login";
include __DIR__ . '/templates/login.html.php';
?>
