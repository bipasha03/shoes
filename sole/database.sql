-- Create User table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    dob DATE,
    address VARCHAR(255),
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    is_staff BOOLEAN DEFAULT false,
    is_admin BOOLEAN DEFAULT false
);

-- Create Product table for shoes
CREATE TABLE shoes (
    shoe_id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(255) NOT NULL,
    model VARCHAR(255) NOT NULL,
    color VARCHAR(255),
    size VARCHAR(10),
    imgurl VARCHAR(255),
    description TEXT,
    price DECIMAL(10, 2)
);

-- Create Inventory table
CREATE TABLE inventory (
    inventory_id INT AUTO_INCREMENT PRIMARY KEY,
    shoe_id INT,
    quantity INT,
    items_remaining INT,
    FOREIGN KEY (shoe_id) REFERENCES shoes(shoe_id)
);

-- Create Customer_Shoes table
CREATE TABLE customer_shoes (
    purchase_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    shoe_id INT,
    purchase_date DATE,
    purchase_quantity INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (shoe_id) REFERENCES shoes(shoe_id)
);
