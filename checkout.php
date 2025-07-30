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

          // Include email script
include 'send_order_email.php'; // This should be at the root

// Prepare data for the email
$user_email = $name;  // from form
$user_name = $name;


// Send order confirmation to user and notification to admins
sendOrderEmails($order_id, $user_email, $user_name);

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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #4e73df;
      --secondary-color: #f8f9fc;
      --accent-color: #2e59d9;
      --light-gray: #f8f9fa;
      --dark-gray: #5a5c69;
    }
    
    body {
      background-color: #f5f7fb;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .container {
      max-width: 800px;
      margin-top: 30px;
      margin-bottom: 50px;
    }
    
    .checkout-header {
      background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
      color: white;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 30px;
      box-shadow: 0 4px 20px rgba(78, 115, 223, 0.3);
      position: relative;
      overflow: hidden;
    }
    
    .checkout-header h2 {
      position: relative;
      z-index: 2;
    }
    
    .checkout-header::after {
      content: "";
      position: absolute;
      top: -50px;
      right: -50px;
      width: 200px;
      height: 200px;
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      z-index: 1;
    }
    
    .checkout-card {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      padding: 30px;
      margin-bottom: 30px;
    }
    
    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
    
    .list-group-item {
      transition: all 0.3s ease;
      border-left: 3px solid transparent;
    }
    
    .list-group-item:hover {
      transform: translateX(5px);
      border-left: 3px solid var(--primary-color);
      background-color: var(--light-gray);
    }
    
    .btn-checkout {
      background-color: var(--primary-color);
      border: none;
      padding: 12px 30px;
      font-weight: 600;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
    }
    
    .btn-checkout:hover {
      background-color: var(--accent-color);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
    }
    
    .btn-secondary {
      padding: 12px 30px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(108, 117, 125, 0.2);
    }
    
    .empty-cart {
      text-align: center;
      padding: 40px;
      border-radius: 10px;
      background-color: white;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .empty-cart i {
      font-size: 50px;
      color: var(--primary-color);
      margin-bottom: 20px;
    }
    
    .total-item {
      background-color: var(--secondary-color);
      font-size: 1.1rem;
    }
    
    @media (max-width: 576px) {
      .container {
        padding: 15px;
      }
      
      .checkout-card {
        padding: 20px;
      }
    }
    
    /* Animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .animated {
      animation: fadeIn 0.6s ease forwards;
    }
    
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
  </style>
</head>
<body>
<div class="container my-5">
  <div class="checkout-header animated">
    <h2><i class="fas fa-receipt me-2"></i> Complete Your Order</h2>
  </div>

  <?php if (isset($error)): ?>
    <div class="alert alert-danger animated delay-1"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if (!empty($_SESSION['cart'])): ?>
  <div class="checkout-card animated delay-1">
    <form method="POST" action="">
      <div class="mb-4">
        <h4 class="mb-3"><i class="fas fa-user-circle me-2"></i> Personal Information</h4>
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" required placeholder="Enter your full name">
        </div>
        <div class="mb-3">
          <label class="form-label">Phone Number</label>
          <input type="text" name="phone" class="form-control" required placeholder="Enter your phone number">
        </div>
        <div class="mb-3">
          <label class="form-label">Delivery Address</label>
          <textarea name="address" class="form-control" required rows="3" placeholder="Enter your complete delivery address"></textarea>
        </div>
      </div>

      <div class="mb-4">
        <h4 class="mb-3"><i class="fas fa-shopping-basket me-2"></i> Order Summary</h4>
        <ul class="list-group mb-3">
          <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item):
              $subtotal = $item['price'] * $item['quantity'];
              $total += $subtotal;
          ?>
          <li class="list-group-item d-flex justify-content-between align-items-center animated delay-2">
            <div>
              <strong><?= htmlspecialchars($item['name']) ?></strong>
              <span class="text-muted">× <?= $item['quantity'] ?></span>
            </div>
            <span class="badge bg-primary rounded-pill">Ksh <?= number_format($subtotal) ?></span>
          </li>
          <?php endforeach; ?>
          <li class="list-group-item d-flex justify-content-between align-items-center fw-bold total-item animated delay-3">
            <span>Total Amount:</span>
            <span class="text-success">Ksh <?= number_format($total) ?></span>
          </li>
        </ul>
      </div>

      <div class="d-flex justify-content-between mt-4">
        <a href="view_cart.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i> Back to Cart</a>
        <button type="submit" name="checkout" class="btn btn-checkout">
          <i class="fas fa-paper-plane me-2"></i> Place Order
        </button>
      </div>
    </form>
  </div>
  <?php else: ?>
    <div class="empty-cart animated">
      <i class="fas fa-shopping-cart"></i>
      <h3 class="mb-3">Your cart is empty</h3>
      <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
      <a href="user.php" class="btn btn-primary"><i class="fas fa-store me-2"></i> Continue Shopping</a>
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Add simple animation to form elements
  document.addEventListener('DOMContentLoaded', function() {
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach((control, index) => {
      control.style.opacity = '0';
      control.style.transform = 'translateY(10px)';
      control.style.animation = `fadeIn 0.5s ease forwards ${index * 0.1 + 0.4}s`;
    });
  });
</script>
</body>
</html>
