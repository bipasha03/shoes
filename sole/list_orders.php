<?php
session_start();

// Include your database connection code here
$hostname = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'inventory';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch orders data for the specific user
$sql = "SELECT cs.user_id, u.firstname, cs.shoe_id, s.brand, s.model, i.items_remaining, cs.purchase_date, cs.purchase_quantity
        FROM customer_shoes cs
        JOIN users u ON cs.user_id = u.user_id
        JOIN shoes s ON cs.shoe_id = s.shoe_id
        JOIN inventory i ON cs.shoe_id = i.shoe_id
        WHERE cs.user_id = '$user_id'";
$result = $conn->query($sql);
$isUserLoggedIn = true;
// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoes Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="index.css">
    <style>
         body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        /* Navbar styles */
        .navbar {
            overflow: hidden;
            background-color: #333;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-evenly;
        }

        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            transition: background-color 0.3s;

        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .navbar-logo {
            float: left;
            display: block;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 24px;
            font-weight: bold;
            color: #fff;
        }

        .navbar-middle,
        .navbar-right {
            float: left;
        }

        .navbar-middle a,
        .navbar-right a {
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .navbar-middle a:hover,
        .navbar-right a:hover {
            background-color: #ddd;
            color: black;
        }

        h2 {
            color: #333;
            margin: 20px 0;
            text-align: center;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #284B63;
            color: #fff;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        td a {
            color: #3498db;
            text-decoration: none;
            margin-right: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
            text-align: left;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        #editOrderModal label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        #editOrderModal input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        #editOrderModal input[type="submit"] {
            background-color: #3498db;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #editOrderModal input[type="submit"]:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
   <!-- Navbar -->
   <div class="navbar">
        <a class="navbar-logo" href="#"><i class="fas fa-shoe-prints"></i> Luxe Sole</a>
        <div class="navbar-middle">
            <a href="dashboard.php"><i class="fas fa-home"></i> Home</a>
            <a href="products.php"><i class="fas fa-box"></i> Products</a>
            <a href="list_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
        </div>
        <div class="navbar-right">
            <?php
            if ($isUserLoggedIn) {
                echo "<a class='logout-btn' href='logout.php'><i class='fas fa-sign-out-alt'></i> Logout</a>";
            } else {
                echo "<a href='login.php'><i class='fas fa-sign-in-alt'></i> Login</a>";
            }
            ?>
        </div>
    </div>
        
    </div>

    <h2>My Orders</h2>

    <?php
    if ($result->num_rows > 0) {
        echo "<table>
            <tr>
                <th>Shoe Name</th>
                <th>Purchase Date</th>
                <th>Purchase Quantity</th>
                <th>Action</th>
            </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['brand']} - {$row['model']}</td>
                    <td>{$row['purchase_date']}</td>
                    <td>{$row['purchase_quantity']}</td>
                    <td>
                        <a href='#' onclick=\"openEditOrderModal('{$row['shoe_id']}', '{$row['purchase_quantity']}', '{$row['items_remaining']}')\">Edit</a>
                        <a href='delete_order.php?id={$row['shoe_id']}&quantity={$row['purchase_quantity']}&shoe_id={$row['shoe_id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No orders found.</p>";
    }
    ?>

    <div id="editOrderModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditOrderModal()">&times;</span>
            <h3>Edit Order</h3>
            <p>Remaining: <span id="items_remaining"></span></p>
            <form id="editOrderForm" action="update_order.php" method="post">
                <label for="editQuantity">New Quantity:</label>
                <input type="number" id="editQuantity" name="editQuantity" min="1" required>
                <input type="hidden" id="canOrder" name="canOrder">
                <input type="hidden" id="initialQuantity" name="initialQuantity">

                <input type="hidden" id="editOrderId" name="editOrderId">
                <input type="submit" value="Update Order">
            </form>
        </div>
        
    </div>

    <script>

        function openEditOrderModal(orderId, quantity, items_remaining) {
            document.getElementById('editOrderId').value = orderId;
            var quantityInput = document.getElementById('editQuantity');
            quantityInput.value = quantity;
            var canOrder = parseInt(quantity) + parseInt(items_remaining);
            document.getElementById('canOrder').value = canOrder;
           

            quantityInput.addEventListener('input', function() {
            // Parse the input value to an integer
            var quantity = parseInt(quantityInput.value);
            var remaining = parseInt(items_remaining);
           
            // If the entered quantity is greater than the maximum, set it to the maximum value
            if (quantity > remaining) {
                quantityInput.value = canOrder;
            } 
        });

            document.getElementById('items_remaining').innerHTML = items_remaining;

            document.getElementById('editOrderModal').style.display = 'block';
        }

        function closeEditOrderModal() {
            document.getElementById('editOrderModal').style.display = 'none';
        }
    </script>
</body>

</html>