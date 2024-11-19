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

// Process add shoe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['addBrand'];
    $model = $_POST['addModel'];
    $color = $_POST['addColor'];
    $size = $_POST['addSize'];
    $imgUrl = $_POST['addImgUrl'];
    $description = $_POST['addDescription'];
    $price = $_POST['addPrice'];

    // Sanitize input (optional, depending on your validation needs)
    $brand = mysqli_real_escape_string($conn, $brand);
    $model = mysqli_real_escape_string($conn, $model);
    $color = mysqli_real_escape_string($conn, $color);
    $size = mysqli_real_escape_string($conn, $size);
    $imgUrl = mysqli_real_escape_string($conn, $imgUrl);
    $description = mysqli_real_escape_string($conn, $description);
    $price = mysqli_real_escape_string($conn, $price);

    // Insert new shoe into the database
    $insertShoeSql = "INSERT INTO shoes (brand, model, color, size, imgurl, description, price) 
                      VALUES ('$brand', '$model', '$color', '$size', '$imgUrl', '$description', '$price')";

    if ($conn->query($insertShoeSql) === TRUE) {
        // Shoe added successfully
        header('Location: shoes.php');
        exit();
    } else {
        // Error in adding shoe
        echo "Error: " . $insertShoeSql . "<br>" . $conn->error;
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
    <title>Add Shoe</title>
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
        <h1>Add New Shoe</h1>

        <form method="POST" action="">
            <label for="addBrand">Brand:</label>
            <input type="text" id="addBrand" name="addBrand" required>

            <label for="addModel">Model:</label>
            <input type="text" id="addModel" name="addModel" required>

            <label for="addColor">Color:</label>
            <input type="text" id="addColor" name="addColor" required>

            <label for="addSize">Size:</label>
            <input type="text" id="addSize" name="addSize" required>

            <label for="addImgUrl">Image URL:</label>
            <input type="text" id="addImgUrl" name="addImgUrl" required>

            <label for="addDescription">Description:</label>
            <textarea id="addDescription" name="addDescription" rows="4" required></textarea>

            <label for="addPrice">Price:</label>
            <input type="number" id="addPrice" name="addPrice" min="0" required>

            <button type="submit">Add Shoe</button>
        </form>

        <?php
        if (isset($insertShoeSql) && $conn->query($insertShoeSql) !== TRUE) {
            // Error in adding shoe
            echo '<p class="error">Error adding the shoe. Please try again.</p>';
        }
        ?>
    </div>
</body>

</html>
