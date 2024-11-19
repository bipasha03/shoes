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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $shoeId = $_POST['addShoe'];
    $quantity = $_POST['addQuantity'];
    $itemsRemaining = $_POST['addItemsRemaining'];

    // Validate and sanitize input (you may need more thorough validation)
    $shoeId = mysqli_real_escape_string($conn, $shoeId);
    $quantity = mysqli_real_escape_string($conn, $quantity);
    $itemsRemaining = mysqli_real_escape_string($conn, $itemsRemaining);

    // Perform the database insertion
    $insertSql = "INSERT INTO inventory (shoe_id, quantity, items_remaining) VALUES ('$shoeId', '$quantity', '$itemsRemaining')";

    if ($conn->query($insertSql) === TRUE) {
        // Insertion successful
        header('Location: inventory.php'); // Redirect back to the inventory page
        exit();
    } else {
        // Insertion failed
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
    <title>Add Inventory</title>
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

        .error {
            color: #d9534f;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Add Inventory</h1>

        <form method="POST" action="">
            <label for="addShoe">Shoe ID:</label>
            <input type="text" id="addShoe" name="addShoe" required>

            <label for="addQuantity">Quantity:</label>
            <input type="number" id="addQuantity" name="addQuantity" min="1" required>

            <label for="addItemsRemaining">Items Remaining:</label>
            <input type="number" id="addItemsRemaining" name="addItemsRemaining" min="0" required>

            <button type="submit">Add Inventory</button>
        </form>

        <?php
        if (isset($insertSql) && $conn->query($insertSql) !== TRUE) {
            // Insertion failed
            echo '<p class="error">Error adding inventory. Please try again.</p>';
        }
        ?>
    </div>
</body>

</html>
