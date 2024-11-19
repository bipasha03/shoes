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

// Fetch users data for the table
$usersSql = "SELECT * FROM users";
$usersResult = $conn->query($usersSql);
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
    <h2 style="color: black; font-size: 28px; margin-bottom: 20px;">users</h2>
        <button onclick="openAddUserModal()">Add New User</button>

       <!-- Table for displaying users with edit and delete buttons -->
    <table>
        <tr>
            <th>FirstName</th>
            <th>LastName</th>
            <th>Email</th>
            <th>DOB</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>

        <?php
        while ($userRow = $usersResult->fetch_assoc()) {
            echo "<tr>
                    <td>{$userRow['firstname']}</td>
                    <td>{$userRow['lastname']}</td>
                    <td>{$userRow['email']}</td>
                    <td>{$userRow['dob']}</td>
                    <td>{$userRow['address']}</td>
                    <td>{$userRow['phone']}</td>
                    <td>
                        <button onclick=\"openEditModal(
                            '{$userRow['firstname']}', 
                            '{$userRow['lastname']}', 
                            '{$userRow['email']}', 
                            '{$userRow['dob']}', 
                            '{$userRow['address']}', 
                            '{$userRow['phone']}', 
                        )\">Edit</button>
                        <a href=\"delete_user.php?userId={$userRow['user_id']}\">Delete</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditUserModal()">&times;</span>
            <h3>Edit User</h3>
            <form id="editUserForm" action="edit_user.php" method="post">
                <label for="editFirstName">First Name:</label>
                <input type="text" id="editFirstName" name="editFirstName" required>
                <label for="editLastName">Last Name:</label>
                <input type="text" id="editLastName" name="editLastName" required>
                <label for="editEmail">Email:</label>
                <input type="email" id="editEmail" name="editEmail">
                <label for="editDob">DOB:</label>
                <input type="text" id="editDob" name="editDob">
                <label for="editAddress">Address:</label>
                <input type="text" id="editAddress" name="editAddress">
                <label for="editPhone">Phone:</label>
                <input type="number" id="editPhone" name="editPhone">
                
                <input type="submit" value="Update User">
            </form>
        </div>
    </div>
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditUserModal()">&times;</span>
            <h3>Edit User</h3>
            <form id="addUserForm" action="add_user.php" method="post">
                <label for="addFirstName">First Name:</label>
                <input type="text" id="addFirstName" name="addFirstName" required>
                <label for="addLastName">Last Name:</label>
                <input type="text" id="addLastName" name="addLastName" required>
                <label for="addPassword">Password:</label>
                <input type="password" id="addPassword" name="addPassword" required>
                <label for="addEmail">Email:</label>
                <input type="email" id="addEmail" name="addEmail" required>
                <label for="addDob">DOB:</label>
                <input type="date" id="addDob" name="addDob" required>
                <label for="addAddress">Address:</label>
                <input type="text" id="addAddress" name="addAddress" required>
                <label for="addPhone">Phone:</label>
                <input type="number" id="addPhone" name="addPhone" required>
                
                <input type="submit" value="Add User">
            </form>
        </div>
    </div>

    <script>
        // JavaScript functions for handling modal
        function openEditModal(firstname, lastname, email, dob, address, phone) {
            document.getElementById('editFirstName').value = firstname;
            document.getElementById('editLastName').value = lastname;
            document.getElementById('editEmail').value = email;
            document.getElementById('editDob').value = dob;
            document.getElementById('editAddress').value = address;
            document.getElementById('editPhone').value = phone;
            document.getElementById('editUserModal').style.display = 'block';
        }

        function closeEditUserModal() {
            document.getElementById('editUserModal').style.display = 'none';
            document.getElementById('addUserModal').style.display = 'none';
        }

        function openAddUserModal() {
            document.getElementById('addUserModal').style.display = 'block';
        }

        function closeAddUserModal() {
            document.getElementById('addUserModal').style.display = 'none';
        }

        // Close modal if clicked outside the modal content
        window.onclick = function (event) {
            var modal = document.getElementById('editUserModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        };
    </script>
</body>

</html>