<?php
// Include your database connection code here
$hostname = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'inventory';
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// Create a connection
$conn = new mysqli($hostname, $username, $password, $database);
// Check if the user is logged in and is an admin
if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_admin']) {
    // Redirect to a login page or show an access denied message
    header('Location: login.php');
    exit();
}
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch inventory data for the table
$inventorySql = "SELECT * FROM inventory JOIN shoes ON shoes.shoe_id = inventory.shoe_id";
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
            object-fit: contain;
            height: 60px;
            display: flex;
            justify-content: space-evenly;
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
        <h2 style="color:black; font-size: 28px; margin-bottom: 20px;">INVENTORY</h2>
        <button onclick="openAddInventoryModal()">Add New Inventory</button>

        <!-- Table for displaying inventory items with edit and delete buttons -->
        <table>
            <tr>
                <th>Shoe</th>
                <th>Shoe Brand</th>
                <th>Shoe Model</th>
                <th>Shoe Color</th>
                <th>Shoe Size</th>
                <th>Quantity</th>
                <th>Items Remaining</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            <?php while ($inventoryRow = $inventoryResult->fetch_assoc()): ?>
                <tr>
                    <td><img src="<?= $inventoryRow['imgurl'] ?>"></td>
                    <td><?= $inventoryRow['brand'] ?></td>
                    <td><?= $inventoryRow['model'] ?></td>
                    <td><?= $inventoryRow['color'] ?></td>
                    <td><?= $inventoryRow['size'] ?></td>
                    <td><?= $inventoryRow['quantity'] ?></td>
                    <td><?= $inventoryRow['items_remaining'] ?></td>
                    <td><span id="price-<?= $inventoryRow['inventory_id'] ?>"><?= $inventoryRow['price'] ?></span></td>
                    <td>
                        <button onclick="openEditModal(
                            '<?= $inventoryRow['inventory_id'] ?>',
                            '<?= $inventoryRow['shoe_id'] ?>',
                            '<?= $inventoryRow['quantity'] ?>',
                            '<?= $inventoryRow['items_remaining'] ?>',
                            '<?= $inventoryRow['price'] ?>'
                        )">Edit</button>
                        <a href="delete_inventory.php?inventoryId=<?= $inventoryRow['inventory_id'] ?>">Delete</a>
                        <button onclick="changePrice('<?= $inventoryRow['inventory_id'] ?>', 'increase')">+</button>
                        <button onclick="changePrice('<?= $inventoryRow['inventory_id'] ?>', 'decrease')">-</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <!-- Edit Inventory Modal -->
        <div id="editInventoryModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeEditModal()">&times;</span>
                <h3>Edit Inventory</h3>
                <form id="editInventoryForm" action="edit_inventory.php" method="post">
                    <input type="hidden" id="editInventoryId" name="editInventoryId">
                    <label for="editShoeId">Shoe ID:</label>
                    <input type="text" id="editShoeId" name="editShoeId" required>
                    <label for="editQuantity">Quantity:</label>
                    <input type="text" id="editQuantity" name="editQuantity" required>
                    <label for="editItemsRemaining">Items Remaining:</label>
                    <input type="text" id="editItemsRemaining" name="editItemsRemaining" required>
                    <label for="editPrice">Price:</label>
                    <input type="text" id="editPrice" name="editPrice" required>
                    <input type="submit" value="Update Inventory">
                </form>
            </div>
        </div>
    <div id="addInventoryModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddInventoryModal()">&times;</span>
            <h3>Add New Inventory</h3>
            <form id="addInventoryForm" action="add_inventory.php" method="post">

                <!-- Add the necessary fields for adding new inventory -->
                <label for="addShoe">Shoe:</label>
                <select id="addShoe" name="addShoe" required>
                    <?php
                    // Fetch shoe data for the dropdown
                    $shoeSql = "SELECT * FROM shoes";
                    $shoeResult = $conn->query($shoeSql);

                    while ($shoeRow = $shoeResult->fetch_assoc()) {
                        echo "<option value='{$shoeRow['shoe_id']}'>{$shoeRow['brand']} - {$shoeRow['model']}</option>";
                    }
                    ?>
                </select>
                <label for="addQuantity">Quantity:</label>
                <input type="number" id="addQuantity" name="addQuantity" required>
                <label for="addItemsRemaining">Items Remaining:</label>
                <input type="number" id="addItemsRemaining" name="addItemsRemaining" required>
                <input type="submit" value="Add Inventory">
            </form>
        </div>
    </div>

    <script>
        // JavaScript functions for handling modal
      // JavaScript functions for handling modal
function openEditModal(inventoryId, shoeId, quantity, itemsRemaining, price) {
    document.getElementById('editInventoryId').value = inventoryId;
    document.getElementById('editShoeId').value = shoeId;
    document.getElementById('editQuantity').value = quantity;
    document.getElementById('editItemsRemaining').value = itemsRemaining;
    document.getElementById('editPrice').value = price;
    document.getElementById('editInventoryModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editInventoryModal').style.display = 'none';
}

function openAddInventoryModal() {
    document.getElementById('addInventoryModal').style.display = 'block';
}

function closeAddInventoryModal() {
    document.getElementById('addInventoryModal').style.display = 'none';
}

// Close modal if clicked outside the modal content
window.onclick = function (event) {
    var editModal = document.getElementById('editInventoryModal');
    var addModal = document.getElementById('addInventoryModal');
    if (event.target == editModal) {
        editModal.style.display = 'none';
    }
    if (event.target == addModal) {
        addModal.style.display = 'none';
    }
};
function changePrice(inventoryId, action) {
    let priceElement = document.getElementById(`price-${inventoryId}`);
    let currentPrice = parseFloat(priceElement.innerText);

    if (action === 'increase') {
        currentPrice += 100; // Increase by 1, or adjust the value as needed
    } else if (action === 'decrease') {
        currentPrice -= 100; // Decrease by 1, or adjust the value as needed
    }

    priceElement.innerText = currentPrice.toFixed(2); // Update the price display
}

    </script>
</body>

</html>