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

// Process login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize input (optional, depending on your validation needs)
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Fetch user from the database
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, perform login logic (e.g., set session variables)
        session_start();

        $user = $result->fetch_assoc();

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['lastname'] = $user['lastname'];
        $_SESSION['is_logged_in'] = true;
        $_SESSION['is_admin'] = $user['is_admin'];

        if ($user['is_admin']) {
            // Redirect to an admin dashboard or home page
            $error_message="Admin Login Success.";
            header('Location: admin_dashboard.php');
            exit();
        } else {
            // Redirect to a user dashboard or home page
            $error_message="User Login Success.";

            header('Location: dashboard.php');
            exit();
        }
    } else {
        // User not found or incorrect credentials
        $error_message = "Invalid username or password";
    }
    echo "<script type='text/javascript'>alert('$error_message');</script>";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            color: #e74c3c;
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
    <div id="loginContainer">
        <form id="loginForm" action="login.php" method="post">
            <h2>Login of Luxe Sole</h2>
            <label for="loginUsername">Email:</label>
            <input type="email" id="loginUsername" name="email" required>
            <div id="loginUsernameError" class="error" required></div>

            <label for="loginPassword">Password:</label>
            <input type="password" id="loginPassword" name="password" required>
            <div id="loginPasswordError" class="error" required></div>

            <input type="submit" value="Login">
            <a href="registration.php">
                <button type="button" class="switch-button">Switch to Register</button></a>
        </form>
    </div>

    <script>
          Define your email-password pairs here
          const user = {
            "bipashakc96@gmail.com": "bipasha123",
            "mikasa@gmail.com": "mikasa123",
            // Add more email-password pairs as needed
        };


      document.getElementById('loginForm').addEventListener('submit', function (event) {
            var username = document.getElementById('loginUsername').value;
            var password = document.getElementById('loginPassword').value;

            if (!username || !password) {
                event.preventDefault();
                document.getElementById('loginUsernameError').innerText = username ? '' : 'Username is required';
                document.getElementById('loginPasswordError').innerText = password ? '' : 'Password is required';
            }
        });
    </script>
</body>

</html>
