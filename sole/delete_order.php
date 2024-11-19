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

// Check if the request is a GET request with an 'id' parameter
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Retrieve the order ID from the GET parameter
    $orderId = $_GET['id'];
    $purchaseQuantity = $_GET['quantity'];
    $shoeId = $_GET['shoe_id'];

    session_start();

    // Retrieve data from the form
    $userId = $_SESSION['user_id'];

    // Validate and sanitize input
    $orderId = mysqli_real_escape_string($conn, $orderId);


    // Perform the database deletion
    $deleteSql = "DELETE FROM customer_shoes WHERE shoe_id = '$orderId' AND user_id = '$userId'";

    $updateSql = "Update inventory set items_remaining = items_remaining + $purchaseQuantity WHERE shoe_id = $shoeId";


    if ($conn->query($deleteSql) === TRUE) {
        // Deletion successful
        $conn->query($updateSql);
        header("Location: list_orders.php");
        exit();
    } else {
        // Deletion failed
        echo "Error deleting the order: " . $conn->error;
        exit();
    }
} else {
    // If it's not a GET request with an 'id' parameter, redirect to an error page or handle accordingly
    header("Location: error.php");
    exit();
}

// Close the database connection
$conn->close();
?>