<?php
// Include your database connection code here
$hostname = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'inventory';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inventoryId = $_POST['inventoryId'];
    $action = $_POST['action'];

    // Fetch the current price
    $query = "SELECT price FROM inventory WHERE inventory_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $inventoryId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $currentPrice = $row['price'];

    if ($action === 'increase') {
        $newPrice = $currentPrice + 1;
    } elseif ($action === 'decrease') {
        $newPrice = $currentPrice - 1;
    } else {
        $newPrice = $currentPrice;
    }

    // Update the price
    $updateQuery = "UPDATE inventory SET price = ? WHERE inventory_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('di', $newPrice, $inventoryId);
    $updateStmt->execute();

    echo "Price updated successfully";
}
?>
