<?php
// Include your database connection code here

// Replace these with your actual database credentials
$hostname = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'inventory';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['userId'])) {
    $deleteUserId = $_GET['userId'];

    // Perform the delete
    $deleteUserSql = "DELETE FROM users WHERE user_id = '$deleteUserId'";
    $conn->query($deleteUserSql);
}

// Redirect back to the dashboard
header('Location: users.php');
exit();
?>
