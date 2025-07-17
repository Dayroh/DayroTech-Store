<?php
session_start();

// Add product to cart
if (isset($_POST['add_to_cart'])) {
    $id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $price = $_POST['price'];

    $cart_item = [
        'id' => $id,
        'name' => $name,
        'price' => $price,
        'quantity' => 1
    ];

    // Check if cart session exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [$id => $cart_item];
    } else {
        // If item exists, increase quantity
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$id] = $cart_item;
        }
    }

    header("Location: view_cart.php");
    exit();
}

// Remove product from cart
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
    header("Location: view_cart.php");
    exit();
}
