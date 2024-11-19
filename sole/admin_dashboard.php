<?php
// Include your database connection code here
$hostname = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'inventory';
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Check if the user is logged in
if (!isset($_SESSION['is_logged_in'])) {
    // Redirect to a login page or show an access denied message
    header('Location: login.php');
    exit();
}

// Create a connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users data
$usersSql = "SELECT * FROM users";
$usersResult = $conn->query($usersSql);

// Fetch shoes data
$shoesSql = "SELECT * FROM shoes";
$shoesResult = $conn->query($shoesSql);

// Fetch inventory data
$inventorySql = "SELECT i.inventory_id, s.model AS shoes_name, i.quantity, i.items_remaining
                FROM inventory i
                JOIN shoes s ON i.shoe_id = s.shoe_id";
$inventoryResult = $conn->query($inventorySql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            color: #333;
            background: linear-gradient(135deg, #fdfcfb 0%, #e2d1c3 100%);
        }

        #sidebar {
            width: 80px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #1e272e;
            text-align: center;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .navbar a {
            display: block;
            color: #f2f2f2;
            padding: 10px 0;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .navbar a:hover {
            background-color: #57606f;
        }

        .navbar a.active {
            background-color: #3A7CAS;
        }

        #content {
            margin-left: 80px;
            padding: 20px;
            position: relative;
        }

        h2, h3 {
            color: #333;
        }

        table {
            width: 100%;
            background-color: #d1d8e0;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: left;
        }

        th {
            background-color:#284B63;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #c8d6e5;
        }

        .container {
            text-align: center;
            margin-bottom: 40px;
        }

        .logout {
            position: absolute;
            bottom: 20px;
            left: 20px;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            background-color: #e44d26;
            padding: 10px 20px;
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .logout:hover {
            background-color: #d3381f;
        }

        .logout i {
            margin-right: 8px;
        }
        /* Table styles */
.table-container {
    overflow-x: auto;
}

.shoes-table {
    width: 100%;
    border-collapse: collapse;
}

.shoes-table th, .shoes-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.shoes-table th {
    background-color: #284B63;
    color: white;
}

.shoes-table td img {
    max-width: 100px;
    height: auto;
    display: block;
    margin: 0 auto;
}

/* Responsiveness */
@media screen and (max-width: 600px) {
    .shoes-table th, .shoes-table td {
        padding: 8px;
    }

    .shoes-table td img {
        max-width: 80px;
    }
}

    </style>
</head>
<body>
<div id="sidebar">
    <div class="navbar">
        <a href="admin_dashboard.php" class="active"><i class="fas fa-home"></i></a>
        <a href="users.php"><i class="fas fa-users"></i></a>
        <a href="shoes.php"><i class="fas fa-shoe-prints"></i></a>
        <a href="inventory.php"><i class="fas fa-box"></i></a>
        <a href="purchase.php"><i class="fas fa-shopping-cart"></i></a>
    </div>
</div>

<div id="content">
    <h2 style="color:black; font-size: 28px; margin-bottom: 20px;">Welcome to the Admin Dashboard</h2>

    <div class="container">
        <h3 style="color:black; font-size: 24px; margin-bottom: 20px;">Users</h3>
        <?php
        if ($usersResult->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>First</th>
                        <th>Last</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Staff</th>
                        <th>Admin</th>
                    </tr>";

            while ($userRow = $usersResult->fetch_assoc()) {
                echo "<tr>
                        <td>{$userRow['firstname']}</td>
                        <td>{$userRow['lastname']}</td>
                        <td>{$userRow['email']}</td>
                        <td>{$userRow['phone']}</td>
                        <td>{$userRow['is_staff']}</td>
                        <td>{$userRow['is_admin']}</td>
                      </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No users found.</p>";
        }
        ?>
<style>
    .shoes-table img {
        max-width: 100px; /* Adjust the maximum width of the images */
        max-height: 100px; /* Adjust the maximum height of the images */
        border-radius: 5px; /* Add rounded corners to the images */
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3); /* Add a subtle shadow effect */
    }
</style>

<div class="container">
    <h3 style="color:black; font-size: 24px; margin-bottom: 20px;">Shoes</h3>
    <?php
    if ($shoesResult->num_rows > 0) {
        echo "<div class='table-container'>";
        echo "<table class='shoes-table'>
                <tr>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Price</th>
                </tr>";

        while ($shoesRow = $shoesResult->fetch_assoc())
        {
            echo "<tr>
                    <td>{$shoesRow['brand']}</td>
                    <td>{$shoesRow['model']}</td>
                    <td>{$shoesRow['color']}</td>
                    <td>{$shoesRow['size']}</td>
                    <td><img src='{$shoesRow['imgurl']}' alt='Shoe Image'></td>
                    <td>{$shoesRow['description']}</td>
                    <td>{$shoesRow['price']}</td>
                  </tr>";
        }

        echo "</table>";
        echo "</div>";
    } else {
        echo "<p>No shoes found.</p>";
    }
    ?>
</div>


    <div class="container">
        <h3 style="color:black; font-size: 24px; margin-bottom: 20px;">Inventory</h3>
        <?php
        if ($inventoryResult->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>ShoeName</th>
                        <th>Quantity</th>
                        <th>Remaining</th>
                    </tr>";

            while ($inventoryRow = $inventoryResult->fetch_assoc()) {
                echo "<tr>
                        <td>{$inventoryRow['shoes_name']}</td>
                        <td>{$inventoryRow['quantity']}</td>
                        <td>{$inventoryRow['items_remaining']}</td>
                      </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No inventory items found.</p>";
        }
        ?>
    </div>

    <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
</div>

<script src="https://kit.fontawesome.com/your-fontawesome-kit-id.js" crossorigin="anonymous"></script>
</body>
</html>