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


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();

    // Retrieve data from the form
    $userId = $_SESSION['user_id'];
    $shoeId = $_POST['shoeId']; 
    $purchaseDate = date('Y-m-d'); // Current date
    $purchaseQuantity = $_POST['quantity'];
    $inventoryId = $_POST['inventoryId'];
   

    // Validate and sanitize input (you may need more thorough validation)
    $userId = mysqli_real_escape_string($conn, $userId);
    $shoeId = mysqli_real_escape_string($conn, $shoeId);
    $purchaseDate = mysqli_real_escape_string($conn, $purchaseDate);
    $purchaseQuantity = mysqli_real_escape_string($conn, $purchaseQuantity);

    // Perform the database insertion
    $insertSql = "INSERT INTO customer_shoes (user_id, shoe_id, purchase_date, purchase_quantity) 
                  VALUES ('$userId', '$shoeId', '$purchaseDate', '$purchaseQuantity')";
    
    // decrease quantity
    $decreaseSql = "Update inventory set items_remaining = items_remaining - $purchaseQuantity WHERE inventory_id = $inventoryId";
    if ($conn->query($insertSql) === TRUE) {
        // Insertion successful
        $conn->query($decreaseSql);
        $_SESSION['success_message'] = "Order placed successfully!";
        header("Location: dashboard.php");
        exit();
    } else {
        // Insertion failed
        $_SESSION['error_message'] = "Error placing the order. Please try again.";
        header("Location: dashboard.php");
        exit();
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
    <title>Order Placement</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-top: 10px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #3498db;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        .message {
            text-align: center;
            margin-top: 10px;
            color: #333;
        }

        .error {
            color: #d9534f;
        }

        .success {
            color: #5cb85c;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Order Placement Form</h1>

        <form method="POST" action="">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" required>

            <input type="submit" value="Place Order">
        </form>

        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<p class="message error">' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']);
        } elseif (isset($_SESSION['success_message'])) {
            echo '<p class="message success">' . $_SESSION['success_message'] . '</p>';
            unset($_SESSION['success_message']);
        }
        ?>
    </div>
</body>

</html>