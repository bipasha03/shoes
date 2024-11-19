<?php
// Include your database connection code here
$hostname = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'inventory';
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
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

// Fetch shoes data for the table
$shoesSql = "SELECT * FROM shoes";
$shoesResult = $conn->query($shoesSql);
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
    <h2 style="color: black; font-size: 28px; margin-bottom: 20px;">SHOES</h2>
        <button onclick="openAddShoeModal()">Add New Shoe</button>

       <!-- Table for displaying shoes with edit and delete buttons -->
    <table>
        <tr>
            <th>Brand</th>
            <th>Model</th>
            <th>Color</th>
            <th>Size</th>
            <th>Image</th>
            <th>Description</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>

        <?php
        while ($shoeRow = $shoesResult->fetch_assoc()) {
            echo "<tr>
                    <td>{$shoeRow['brand']}</td>
                    <td>{$shoeRow['model']}</td>
                    <td>{$shoeRow['color']}</td>
                    <td>{$shoeRow['size']}</td>
                    <td><img src='{$shoeRow['imgurl']}'></td>
                    <td>{$shoeRow['description']}</td>
                    <td>{$shoeRow['price']}</td>
                    <td>
                        <button onclick=\"openEditModal(
                            '{$shoeRow['shoe_id']}', 
                            '{$shoeRow['brand']}', 
                            '{$shoeRow['model']}', 
                            '{$shoeRow['color']}', 
                            '{$shoeRow['size']}', 
                            '{$shoeRow['imgurl']}', 
                            '{$shoeRow['description']}', 
                            '{$shoeRow['price']}'
                        )\">Edit</button>
                        <a href=\"delete_shoe.php?shoe_id={$shoeRow['shoe_id']}\">Delete</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>

    <!-- Edit Shoe Modal -->
    <div id="editShoeModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditShoeModal()">&times;</span>
            <h3>Edit Shoe</h3>
            <form id="editShoeForm" action="edit_shoe.php" method="post">
                <input type="hidden" id="editShoeId" name="editShoeId">
                <label for="editBrand">Brand:</label>
                <input type="text" id="editBrand" name="editBrand" required>
                <label for="editModel">Model:</label>
                <input type="text" id="editModel" name="editModel" required>
                <label for="editColor">Color:</label>
                <input type="text" id="editColor" name="editColor">
                <label for="editSize">Size:</label>
                <input type="number" id="editSize" name="editSize">
                <label for="editImgUrl">Image URL:</label>
                <input type="text" id="editImgUrl" name="editImgUrl">
                <label for="editDescription">Description:</label>
                <textarea id="editDescription" name="editDescription"></textarea>
                <label for="editPrice">Price:</label>
                <input type="number" id="editPrice" name="editPrice" required>
                <input type="submit" value="Update Shoe">
            </form>
        </div>
    </div>
    <div id="addShoeModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddShoeModal()">&times;</span>
            <h3>Add New Shoe</h3>
            <form id="addShoeForm" action="add_shoe.php" method="post">
                <!-- Add the necessary input fields for adding a new shoe -->
                <label for="addBrand">Brand:</label>
                <input type="text" id="addBrand" name="addBrand" required>
                <label for="addModel">Model:</label>
                <input type="text" id="addModel" name="addModel" required>
                <label for="addColor">Color:</label>
                <input type="text" id="addColor" name="addColor">
                <label for="addSize">Size:</label>
                <input type="number" id="addSize" name="addSize">
                <label for="addImgUrl">Image URL:</label>
                <input type="text" id="addImgUrl" name="addImgUrl">
                <label for="addDescription">Description:</label>
                <textarea id="addDescription" name="addDescription"></textarea>
                <label for="addPrice">Price:</label>
                <input type="number" id="addPrice" name="addPrice" required>
                <input type="submit" value="Add Shoe">
            </form>
        </div>
    </div>

    <script>
        // JavaScript functions for handling modal
        function openEditModal(shoeId, brand, model, color, size, imgUrl, description, price) {
            document.getElementById('editShoeId').value = shoeId;
            document.getElementById('editBrand').value = brand;
            document.getElementById('editModel').value = model;
            document.getElementById('editColor').value = color;
            document.getElementById('editSize').value = size;
            document.getElementById('editImgUrl').value = imgUrl;
            document.getElementById('editDescription').value = description;
            document.getElementById('editPrice').value = price;
            document.getElementById('editShoeModal').style.display = 'block';
        }

        function closeEditShoeModal() {
            document.getElementById('editShoeModal').style.display = 'none';
        }

        function openAddShoeModal() {
            document.getElementById('addShoeModal').style.display = 'block';
        }

        function closeAddShoeModal() {
            document.getElementById('addShoeModal').style.display = 'none';
        }

        // Close modal if clicked outside the modal content
        window.onclick = function (event) {
            var modal = document.getElementById('editShoeModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        };
    </script>
</body>

</html>