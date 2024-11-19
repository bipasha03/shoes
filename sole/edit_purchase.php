<?php
// Include your database connection code here
$hostname = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'inventory';

// Create a connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $editCustomerId = $_POST['editCustomerId'];
    $editShoeId = $_POST['editShoeId'];
    $editPurchaseDate = $_POST['editPurchaseDate'];
    $editPurchaseQuantity = $_POST['editPurchaseQuantity'];

    // Perform the update
    $updateCustomerShoesSql = "UPDATE customer_shoes SET 
                                shoe_id = '$editShoeId', 
                                purchase_date = '$editPurchaseDate', 
                                purchase_quantity = '$editPurchaseQuantity' 
                                WHERE user_id = '$editCustomerId'";
    $conn->query($updateCustomerShoesSql);
}

// Redirect back to the customer_shoes page
header('Location: purchase.php');
exit();
?>