<?php

$hostname = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'inventory';
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Include your database connection code here
// Check if the user is logged in and is an admin
if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_admin']) {
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

// Fetch customer_shoes data for the table
$customerShoesSql = "SELECT * FROM customer_shoes join shoes on shoes.shoe_id = customer_shoes.shoe_id join users on users.user_id = customer_shoes.user_id";
$customerShoesResult = $conn->query($customerShoesSql);
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
        }

        #sidebar {
            width: 80px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #333;
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
            background-color: #555;
        }

        .navbar a.active {
            background-color: #3A7CAS;
        }

        #content {
            margin-left: 80px;
            padding: 20px;
            position: relative;
        }

        h2,
        h3 {
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

        th,
        td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #284B63;
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

        /* Style for modal */
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
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
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
        img {
    width: 100%;
    /* aspect-ratio:3/2;*/
    object-fit:contain;
    height:60px;
    display:flex;
    justify-self:space-evenly;
    /* mix-blend-mode:color-burn; */
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
    <h2 style="color:black; font-size: 28px; margin-bottom: 20px;">CUSTOMER-SHOES</h2>

        <!-- Table for displaying customer-shoes relationships with edit and delete buttons -->
        <table>
            <tr>
                <th>User Name</th>
                <th>Shoe Model</th>
                <th>Shoe Color</th>
                <th>Shoe Size</th>
                <th>Date</th>
                <th>Quantity</th>
                <th>Image</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>

            <?php
            while ($customerShoesRow = $customerShoesResult->fetch_assoc()) {
                $totalPrice = floatval($customerShoesRow['price']) * intval($customerShoesRow['purchase_quantity']);
                echo "<tr>
                        <td>{$customerShoesRow['firstname']}-{$customerShoesRow['lastname']}</td>
                        <td>{$customerShoesRow['brand']}-{$customerShoesRow['model']}</td>
                        <td>{$customerShoesRow['color']}</td>
                        <td>{$customerShoesRow['size']}</td>
                        <td>{$customerShoesRow['purchase_date']}</td>
                        <td>{$customerShoesRow['purchase_quantity']}</td>
                        <td><img src={$customerShoesRow['imgurl']}></td>
                        <td>{$totalPrice}</td>
                        <td>
                            <a href=\"delete_purchase.php?purchaseId={$customerShoesRow['purchase_id']}\">Delete</a>
                        </td>
                      </tr>";
            }
            ?>
        </table>

        <!-- Edit Customer-Shoes Modal -->
        <!-- <div id="editCustomerShoesModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeEditModal()">&times;</span>
                <h3>Edit Customer-Shoes</h3>
                <form id="editCustomerShoesForm" action="edit_purchase.php" method="post">
                    <input type="hidden" id="editCustomerId" name="editCustomerId">
                    <label for="editShoeId">Shoe ID:</label>
                    <input type="text" id="editShoeId" name="editShoeId" required>
                    <label for="editPurchaseDate">Purchase Date:</label>
                    <input type="date" id="editPurchaseDate" name="editPurchaseDate" required>
                    <label for="editPurchaseQuantity">Purchase Quantity:</label>
                    <input type="text" id="editPurchaseQuantity" name="editPurchaseQuantity" required>
                    <input type="submit" value="Update Customer-Shoes">
                </form>
            </div>
        </div> -->
    </div>

    <!-- JavaScript for handling modal -->
    <script>
        // JavaScript functions for handling modal
        function openEditModal(userId, shoeId, purchaseDate, purchaseQuantity) {
            document.getElementById('editCustomerId').value = userId;
            document.getElementById('editShoeId').value = shoeId;
            document.getElementById('editPurchaseDate').value = purchaseDate;
            document.getElementById('editPurchaseQuantity').value = purchaseQuantity;
            document.getElementById('editCustomerShoesModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editCustomerShoesModal').style.display = 'none';
        }

        // Close modal if clicked outside the modal content
        window.onclick = function (event) {
            var modal = document.getElementById('editCustomerShoesModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        };
    </script>
</body>

</html>