<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $user_id = $_SESSION['user_id'];

    if (!empty($name) && !empty($phone) && !empty($address) && !empty($_SESSION['cart'])) {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, phone, address, total_price, order_date, user_id) VALUES (?, ?, ?, ?, NOW(), ?)");
        $stmt->bind_param("sssdi", $name, $phone, $address, $total, $user_id);

        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;

            // Insert each item
            $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_name, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($_SESSION['cart'] as $item) {
                $stmt_item->bind_param("isid", $order_id, $item['name'], $item['quantity'], $item['price']);
                $stmt_item->execute();
            }

            // Clear cart
            unset($_SESSION['cart']);

            // Redirect to success page
            header("Location: order_success.php");
            exit();
        } else {
            $error = "Failed to place order. Please try again.";
        }
    } else {
        $error = "Please fill all fields and make sure your cart is not empty.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Checkout | DayrohTech</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
  <h2 class="mb-4">🧾 Checkout</h2>

  <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if (!empty($_SESSION['cart'])): ?>
  <form method="POST" action="">
    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Phone</label>
      <input type="text" name="phone" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Delivery Address</label>
      <textarea name="address" class="form-control" required></textarea>
    </div>

    <h5>Order Summary:</h5>
    <ul class="list-group mb-3">
      <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $item):
          $subtotal = $item['price'] * $item['quantity'];
          $total += $subtotal;
      ?>
      <li class="list-group-item d-flex justify-content-between">
        <?= htmlspecialchars($item['name']) ?> × <?= $item['quantity'] ?>
        <span>Ksh <?= number_format($subtotal) ?></span>
      </li>
      <?php endforeach; ?>
      <li class="list-group-item d-flex justify-content-between fw-bold">
        Total: <span>Ksh <?= number_format($total) ?></span>
      </li>
    </ul>

    <button type="submit" name="checkout" class="btn btn-success">Place Order</button>
    <a href="view_cart.php" class="btn btn-secondary">Back to Cart</a>
  </form>
  <?php else: ?>
    <p class="alert alert-info">Cart is empty. <a href="users.php">Go back to shop.</a></p>
  <?php endif; ?>
</div>
</body>
</html>
