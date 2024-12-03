<?php
$servername = "sql5.freemysqlhosting.net";
$username = "sql5749292";
$password = "Kwp2F5753w";
$dbname = "sql5749292";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
