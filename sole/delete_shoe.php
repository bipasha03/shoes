<?php
// Include your database connection code here
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

// ... (previous code remains unchanged)

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['shoe_id'])) {
    $deleteShoeId = $_GET['shoe_id'];

    // Perform the delete
    $deleteShoeSql = "DELETE FROM shoes WHERE shoe_id = '$deleteShoeId'";
    $conn->query($deleteShoeSql);

}

// Redirect back to the shoes page
 header('Location: shoes.php');
exit();
?>
