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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $editShoeId = $_POST['editShoeId'];
    $editBrand = $_POST['editBrand'];
    $editModel = $_POST['editModel'];
    $editColor = $_POST['editColor'];
    $editSize = $_POST['editSize'];
    $editImgUrl = $_POST['editImgUrl'];
    $editDescription = $_POST['editDescription'];
    $editPrice = $_POST['editPrice'];

    // Perform the update
    $updateShoeSql = "UPDATE shoes SET 
                      brand = '$editBrand', 
                      model = '$editModel', 
                      color = '$editColor', 
                      size = '$editSize', 
                      imgurl = '$editImgUrl', 
                      description = '$editDescription', 
                      price = '$editPrice' 
                      WHERE shoe_id = '$editShoeId'";
    $conn->query($updateShoeSql);
}

// Redirect back to the shoes page
header('Location: shoes.php');
exit();
