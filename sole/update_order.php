<?php
session_start();

// Include your database connection code here
$hostname = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'inventory';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user ID from the session
$user_id = $_SESSION['user_id'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form
    $order_id = $_POST['editOrderId'];

    // Retrieve new quantity from the form
    $newQuantity = mysqli_real_escape_string($conn, $_POST['editQuantity']);

    $checkSql = "SELECT purchase_quantity  from customer_shoes  WHERE user_id = '$user_id' AND shoe_id = '$order_id'";
    $exec_items = $conn->query($checkSql);
    $items = $exec_items->fetch_assoc();

    $initialQuantity = $items['purchase_quantity'];

    $changed_quantity =  $newQuantity- $initialQuantity ;


    // Update the order in the database
    $sql = "UPDATE customer_shoes SET purchase_quantity = '$newQuantity' WHERE user_id = '$user_id' AND shoe_id = '$order_id'";
    $updateSql = "Update inventory set items_remaining = items_remaining - $changed_quantity WHERE shoe_id = '$order_id'";

    $result = $conn->query($sql);


    // Check if the query was successful
    if ($result) {
        $conn->query($updateSql);
        // Redirect back to the list_orders.php page
        header('Location: list_orders.php');
        exit();
    } else {
        // Handle the error, e.g., display an error message
        echo "Error updating order: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>