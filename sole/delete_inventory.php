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

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['inventoryId'])) {
    $deleteInventoryId = $_GET['inventoryId'];

    // Perform the delete
    $deleteInventorySql = "DELETE FROM inventory WHERE inventory_id = '$deleteInventoryId'";
    $conn->query($deleteInventorySql);
}

// Close the database connection
$conn->close();

// Redirect back to the inventory page
header('Location: inventory.php');
exit();
?>
