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

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['purchaseId'])) {
    $deletePurchaseId = $_GET['purchaseId'];

    // Perform the delete
    $deleteCustomerProductSql = "DELETE FROM customer_shoes WHERE purchase_id = '$deletePurchaseId'";
    $conn->query($deleteCustomerProductSql);
}

// Redirect back to the purchase page
header('Location: purchase.php');
exit();
?>
