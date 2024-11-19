<?php
// Include your database connection code here

// ... (previous code remains unchanged)
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

// Process registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize input (optional, depending on your validation needs)
    $firstname = mysqli_real_escape_string($conn, $firstname);
    $lastname = mysqli_real_escape_string($conn, $lastname);
    $dob = mysqli_real_escape_string($conn, $dob);
    $address = mysqli_real_escape_string($conn, $address);
    $phone = mysqli_real_escape_string($conn, $phone);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Perform validation and insert new user into the database
    // ...
    $insertSql = "INSERT INTO users (firstname, lastname, dob, address, phone, email, password) VALUES ('$firstname', '$lastname', '$dob', '$address', '$phone', '$email', '$password')";
    if ($conn->query($insertSql) === TRUE) {
        // Redirect to the login page after successful registration
        header('Location: admin_dashboard.php');
        exit();
    } else {

        echo "Error: " . $insertSql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* background: linear-gradient(to right, #2980b9, #2c3e50); */
            background-color:#e0e1dd;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            width: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            text-align: center;
            color: #0d3b66;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #3498db;
        }

        .error {
            onclick="toggleForm()"
            margin-top: 5px;
            transition: color 0.3s;
        }

        input[type="submit"],
        .switch-button {
            background-color: #0d3b66;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover,
        .switch-button:hover {
            background-color: #0d3b66;
        }
    </style>
</head>

<body>
    <div id="registerContainer">
        <form id="registerForm" action="registration.php" method="post">
            <h2>Register</h2>
            <label for="registerFirstname">First Name:</label>
            <input type="text" id="registerFirstname" name="firstname" required>
            <div id="registerFirstnameError" class="error"></div>

            <label for="registerLastname">Last Name:</label>
            <input type="text" id="registerLastname" name="lastname" required>
            <div id="registerLastnameError" class="error"></div>
            <label for="registerDOB">Date of Birth:</label>
            <input type="date" id="registerDOB" name="dob" required>

            <label for="registerAddress">Address:</label>
            <input type="text" id="registerAddress" name="address" required>

            <label for="registerPhone">Phone:</label>
            <input type="text" id="registerPhone" name="phone" required>

            <label for="registerEmail">Email:</label>
            <input type="text" id="registerEmail" name="email" required>
            <div id="registerEmailError" class="error"></div>

            <label for="registerPassword">Password:</label>
            <input type="password" id="registerPassword" name="password" required>
            <div id="registerPasswordError" class="error"></div>

            <input type="submit" value="Register">
            <a href="login.php">
                <button type="button" class="switch-button">Switch to Login</button></a>
        </form>
    </div>

    <script>
        // Add your JavaScript code here
        // ... (same as the login page or customize as needed)
    </script>
</body>

</html>