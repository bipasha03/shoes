<?php
// Assume the user is already authenticated
session_start();
// Check if the user is logged in and is an admin
if (!isset($_SESSION['is_logged_in'])) {
    // Redirect to a login page or show an access denied message
    header('Location: login.php');
    exit();
}
// Include your database connection code here
$hostname = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'inventory';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch shoes data
$sql = "SELECT * FROM shoes join inventory on shoes.shoe_id = inventory.shoe_id";
$result = $conn->query($sql);

// Close the database connection
$conn->close();

// Assume user authentication status (you can replace this with your actual authentication logic)
$isUserLoggedIn = true; // Change this based on your authentication status
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


        .card {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 25px;
            width: 200px;
            text-align: center;
            float: left;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            justify-content:center;
        }

        h3 {
            color: #333;
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
    padding-top: 60px;
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #ccc;
    max-width: 400px; /* Adjust the maximum width as needed */
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.close {
    color: #aaa;
    float: right;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s;
}

.close:hover {
    color: #333;
}

.order-form {
    margin-top: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    color: #333;
}

select,
input {
    width: 100%;
    padding: 8px;
    margin-bottom: 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 14px;
}

input[type="submit"] {
    background-color: #3498db;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #2980b9;
}
    </style>
</head>

<body>
    <!-- Navbar -->
   <!-- Navbar -->
   <div class="navbar">
        <a class="navbar-logo" ><i class="fas fa-shoe-prints"></i> Luxe Sole</a>
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

    <!-- Main Content -->
    <h2 style="text-align:center;">SHOES</h2>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='card' 
                    data-inventory-id='{$row['inventory_id']}' 
                    data-shoe-id='{$row['shoe_id']}' 
                    data-brand='{$row['brand']}' 
                    data-model='{$row['model']}' 
                    data-description='{$row['description']}' 
                    data-size='{$row['size']}' 
                    data-color='{$row['color']}' 
                    data-remaining='{$row['items_remaining']}' 
                    data-price='{$row['price']}' 
                    data-imgurl='{$row['imgurl']}'>
                <h3>{$row['brand']} - {$row['model']}</h3>
                <img src='{$row['imgurl']}' alt='{$row['brand']} {$row['model']}' style='max-width: 100%;'>
                <p>{$row['description']}</p>
                <p>Size: {$row['size']}</p>
                <p>Color: {$row['color']}</p>
                <p>Price: {$row['price']}</p>
                <p>Remaining: {$row['items_remaining']}</p>
              </div>";
        }
    } else {
        echo "No shoes available.";
    }
    ?>


    <div id="shoeDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeShoeDetailsModal()">&times;</span>
            <h3>Shoe Details</h3>
            <div id="shoeDetails"></div>
            <form id="orderForm" class="order-form" action="orders.php" method="post">
                <input type="hidden" id="inventoryId" name="inventoryId" value="" >
                <input type="hidden" id="shoeId" name="shoeId" value="">
                <!-- <label for="shoeSize">Size:</label>
                <select id="shoeSize" name="shoeSize"></select> -->
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="1" value="1" required>
                <input type="submit" id="submit" value="Place Order" >
            </form>
        </div>
    </div>

    <script>
        function attachCardClickListeners() {
            var shoeCards = document.getElementsByClassName('card');

            for (var i = 0; i < shoeCards.length; i++) {
                shoeCards[i].addEventListener('click', function (event) {
                    // Extract relevant information from the clicked card
                    var inventoryId = event.currentTarget.getAttribute('data-inventory-id');
                    var brand = event.currentTarget.getAttribute('data-brand');
                    var model = event.currentTarget.getAttribute('data-model');
                    var description = event.currentTarget.getAttribute('data-description');
                    var size = event.currentTarget.getAttribute('data-size');
                    var color = event.currentTarget.getAttribute('data-color');
                    var price = event.currentTarget.getAttribute('data-price');
                    var imgUrl = event.currentTarget.getAttribute('data-imgurl');
                    var shoeId = event.currentTarget.getAttribute('data-shoe-id');
                    var remaining = event.currentTarget.getAttribute('data-remaining');


                    // Open the modal with the extracted information
                    openShoeDetailsModal(inventoryId, brand, model, description, size, color, price, imgUrl, shoeId, remaining);
                });
            }
        }

        // Call the function to attach click event listeners when the page loads
        window.onload = function () {
            attachCardClickListeners();
        };

        function openShoeDetailsModal(inventoryId, brand, model, description, size, color, price, imgUrl, shoeId, remaining) {
            var modal = document.getElementById('shoeDetailsModal');
            var shoeDetails = document.getElementById('shoeDetails');
            var shoeSizeSelect = document.getElementById('shoeSize');
            var shoeIdInput = document.getElementById('shoeId');
            var inventoryIdInput = document.getElementById('inventoryId');

            shoeIdInput.value = shoeId;
            inventoryIdInput.value = inventoryId;

            // Populate modal content
            shoeDetails.innerHTML = `
                <h3>${brand} - ${model}</h3>
                <img src='${imgUrl}' alt='${brand} ${model}' style='max-width: 100%;'>
                <p>${description}</p>
                <p>Size: ${size}</p>
                <p>Color: ${color}</p>
                <p>Price: ${price}</p>
                <p>Remaining: ${remaining}</p>
            `;
            var quantityInput = modal.querySelector('#quantity');

            quantityInput.setAttribute('max', remaining);
            quantityInput.addEventListener('input', function() {
            // Parse the input value to an integer
            var quantity = parseInt(quantityInput.value);

            // If the entered quantity is greater than the maximum, set it to the maximum value
            if (quantity > remaining) {
                quantityInput.value = remaining;
            }
        });


            // Populate size dropdown (you may fetch available sizes from the server)
            // var availableSizes = [size, '7', '8', '9', '10']; // Example, replace with actual sizes
            // shoeSizeSelect.innerHTML = '';
            // availableSizes.forEach(function (option) {
            //     var sizeOption = document.createElement('option');
            //     sizeOption.value = option;
            //     sizeOption.text = option;
            //     shoeSizeSelect.appendChild(sizeOption);
            // });
            if (remaining == 0) {
                document.getElementById("submit").style.background = "grey";
                document.getElementById("submit").style.cursor = "not-allowed";
                document.getElementById("submit").disabled = true;
            }

            modal.style.display = 'block';
        }

        function closeShoeDetailsModal() {
            var modal = document.getElementById('shoeDetailsModal');
            modal.style.display = 'none';
        }

        // Close modal if clicked outside the modal content
        window.onclick = function (event) {
            var modal = document.getElementById('shoeDetailsModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        };
    </script>
</body>

</html>