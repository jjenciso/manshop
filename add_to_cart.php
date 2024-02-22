<?php
session_start();
include_once("db.php"); // Include the database connection file

// Function to add product to the cart
function addToCart($product_id, $user_id)
{
    global $conn;

    // Prepare the SQL query to fetch product details
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'])) {
        $found = false;
        foreach ($_SESSION['cart'] as $item) {
            if ($item['product_id'] == $product_id) {
                $found = true;
                $item['quantity']++;
                break;
            }
        }
        if (!$found) {
            $item = array(
                'product_id' => $product_id,
                'image_path' => $product['image_path'],
                'description' => $product['description'],
                'price' => $product['price'],
                'quantity' => 1,
                'subtotal' => $product['price']
            );
            $_SESSION['cart'][] = $item;
        }
    } else {
        $item = array(
            'product_id' => $product_id,
            'image_path' => $product['image_path'],
            'description' => $product['description'],
            'price' => $product['price'],
            'quantity' => 1,
            'subtotal' => $product['price']
        );
        $_SESSION['cart'] = array($item);
    }

    // Add the item to the cart in the database
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE quantity = quantity + 1");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();

    // Redirect to cart page
    header("Location: cart.php");
    exit(); // Ensure script termination after redirection
}

// Check if product ID is set in POST request
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Check if user is logged in
    if (isset($_SESSION['user'])) {
        // User is logged in, proceed to add the product to the cart
        $user_id = $_SESSION['user'];
        addToCart($product_id, $user_id);
    } else {
        // User is not logged in, redirect to login page
        header("Location: login.php");
        exit();
    }
}
