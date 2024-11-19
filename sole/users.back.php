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
// Create a connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $editUserId = $_POST['editUserId'];
    $editFirstname = $_POST['editFirstname'];
    $editLastname = $_POST['editLastname'];
    $editPassword = $_POST['editPassword'];
    $editDOB = $_POST['editDOB'];
    $editAddress = $_POST['editAddress'];
    $editEmail = $_POST['editEmail'];
    $editPhone = $_POST['editPhone'];
    $editIsStaff = $_POST['editIsStaff'];
    $editIsAdmin = $_POST['editIsAdmin'];

    // Perform the update
    $updateUserSql = "UPDATE users SET 
                      firstname = '$editFirstname', 
                      lastname = '$editLastname', 
                      password = '$editPassword', 
                      dob = '$editDOB', 
                      address = '$editAddress', 
                      email = '$editEmail', 
                      phone = '$editPhone', 
                      is_staff = '$editIsStaff', 
                      is_admin = '$editIsAdmin' 
                      WHERE user_id = '$editUserId'";
    $conn->query($updateUserSql);
}

// Redirect back to the dashboard
header('Location: users.php');
exit();