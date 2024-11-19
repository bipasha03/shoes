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

$userFullName = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : '';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

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
        .container {
    background-color: #f7f7f7;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    margin: 30px auto;
    max-width: 800px;
}

.dashboard-title {
    color: #333;
    font-size: 28px;
    margin-bottom: 20px;
}

.dashboard-description {
    color: #666;
    font-size: 18px;
    margin-bottom: 30px;
}

.section-title {
    color: #333;
    font-size: 24px;
    margin-bottom: 20px;
}

.order-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    grid-gap: 20px;
}

.order-item {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.shoe-details {
    font-weight: bold;
    color: #444;
    margin-bottom: 10px;
}

.order-info {
    color: #777;
    font-size: 14px;
}

.no-orders {
    color: #777;
    font-style: italic;
}
   

        /* Logout button style */
        .logout-btn {
            background-color: #e74c3c;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #c0392b;
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

    <!-- Main content -->
    <div class="container">
    <h2 class="dashboard-title">Welcome to Your Luxe Sole Dashboard, <?php echo $userFullName; ?>!</h2>
    <p class="dashboard-description">Manage your orders and explore our latest products.</p>

    <div class="recent-orders">
        <h3 class="section-title">Recent Orders</h3>

        <?php
        // Include your database connection code here

        // Fetch recent orders for the user
        $sql = "SELECT cs.shoe_id, s.brand, s.model, cs.purchase_date, cs.purchase_quantity
            FROM customer_shoes cs
            JOIN shoes s ON cs.shoe_id = s.shoe_id
            WHERE cs.user_id = '$userId'
            ORDER BY cs.purchase_date DESC
            LIMIT 5";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<div class='order-list'>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='order-item'>
                        <div class='shoe-details'>{$row['brand']} - {$row['model']}</div>
                        <div class='order-info'>({$row['purchase_quantity']} pairs) on {$row['purchase_date']}</div>
                      </div>";
            }
            echo "</div>";
        } else {
            echo "<p class='no-orders'>No recent orders found.</p>";
        }
        ?>
    </div>
</body>

</html>