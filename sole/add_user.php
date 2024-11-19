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

// Process add user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['addFirstName'];
    $lastname = $_POST['addLastName'];
    $password = $_POST['addPassword'];
    $dob = $_POST['addDob'];
    $address = $_POST['addAddress'];
    $email = $_POST['addEmail'];
    $phone = $_POST['addPhone'];
    $isStaff = $_POST['addIsStaff'];
    $isAdmin = $_POST['addIsAdmin'];

    // Sanitize input (optional, depending on your validation needs)
    $firstname = mysqli_real_escape_string($conn, $firstname);
    $lastname = mysqli_real_escape_string($conn, $lastname);
    $password = mysqli_real_escape_string($conn, $password);
    $dob = mysqli_real_escape_string($conn, $dob);
    $address = mysqli_real_escape_string($conn, $address);
    $email = mysqli_real_escape_string($conn, $email);
    $phone = mysqli_real_escape_string($conn, $phone);
    $isStaff = mysqli_real_escape_string($conn, $isStaff);
    $isAdmin = mysqli_real_escape_string($conn, $isAdmin);

    // Insert user into the database
    $sql = "INSERT INTO users (firstname, lastname, password, dob, address, email, phone, is_staff, is_admin)
            VALUES ('$firstname', '$lastname', '$password', '$dob', '$address', '$email', '$phone', '$isStaff', '$isAdmin')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to users.php after successful insertion
        header('Location: users.php');
        exit();
    } else {
        // Handle the case where the insertion failed
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>