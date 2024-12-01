<?php
include 'includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Retrieve the current maximum User_ID
        $stmt = $pdo->query("SELECT MAX(User_ID) AS MaxUserID FROM Users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Calculate the next User_ID
        $nextUserID = $result['MaxUserID'] + 1;

        // If the table is empty, start User_ID from 1
        if (is_null($result['MaxUserID'])) {
            $nextUserID = 1;
        }

        // Insert the user into the database
        $stmt = $pdo->prepare(
            "INSERT INTO Users (User_ID, FullName, Email, Username, Password, ProfileSettings) 
            VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $nextUserID, 
            $fullname, 
            $email, 
            $username, 
            $hashed_password, 
            '{"theme":"light","notifications":true}'
        ]);

        // Redirect to login page on success
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        // Handle errors
        $error = "Error: " . $e->getMessage();
    }
}

// Set the title and include the signup HTML template
$title = "Signup";
include __DIR__ . '/templates/signup.html.php';
?>