<?php
include __DIR__ . '/includes/db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user from the database
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE Username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['user_id'] = $user['User_ID'];
        $_SESSION['username'] = $user['Username'];
        $_SESSION['fullname'] = $user['FullName'];
        header('Location: dashboard.php'); // Redirect to dashboard on success
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}

// Set the title and include the login HTML template
$title = "Login";
include __DIR__ . '/templates/login.html.php';
?>
