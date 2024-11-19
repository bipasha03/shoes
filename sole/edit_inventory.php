<?php
// Include your database connection code here

// ... (previous code remains unchanged)
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $editInventoryId = $_POST['editInventoryId'];
    $editProductId = $_POST['editProductId'];
    $editQuantity = $_POST['editQuantity'];
    $editItemsRemaining = $_POST['editItemsRemaining'];
    $price = $_POST['editPrice'];

    // Perform the update
    $updateInventorySql = "UPDATE inventory SET 
                           product_id = '$editProductId', 
                           quantity = '$editQuantity', 
                           items_remaining = '$editItemsRemaining', 
                           price='$editPrice'
                           WHERE inventory_id = '$editInventoryId'";
    $conn->query($updateInventorySql);
}

// Redirect back to the inventory page
header('Location: inventory.php');
exit();
