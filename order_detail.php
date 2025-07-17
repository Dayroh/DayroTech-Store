<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
  header("Location: login.php");
  exit();
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Get order details (removed status from query)
$sql = "SELECT oi.*, o.order_date 
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.id
        WHERE oi.order_id = ? AND o.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if order exists
if ($result->num_rows === 0) {
  header("Location: orders.php");
  exit();
}

$first_row = $result->fetch_assoc();
$order_date = $first_row['order_date'];
$result->data_seek(0); // Reset pointer to beginning
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order #<?= $order_id ?> Details | DayrohTech</title>
  <link rel="icon" href="assets/images/logo.png" type="image/png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #6c5ce7;
      --primary-dark: #5649c0;
      --secondary: #00cec9;
      --dark: #2d3436;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
      color: var(--dark);
    }
    
    .container {
      max-width: 900px;
      padding: 2rem 1.5rem;
    }
    
    /* Print-specific styles */
    @media print {
      body * {
        visibility: hidden;
      }
      .print-section, .print-section * {
        visibility: visible;
      }
      .print-section {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 20px;
      }
      .no-print {
        display: none !important;
      }
      .table {
        width: 100% !important;
      }
    }
    
    /* Order Header */
    .order-header {
      background: white;
      border-radius: 0.75rem;
      padding: 1.5rem;
      margin-bottom: 2rem;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .order-title {
      color: var(--primary);
      font-weight: 700;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
    }
    
    .order-title i {
      margin-right: 0.75rem;
    }
    
    .order-meta {
      display: flex;
      gap: 2rem;
    }
    
    .order-meta-item strong {
      display: block;
      margin-bottom: 0.25rem;
      color: var(--primary);
    }
    
    /* Order Table */
    .order-table {
      background: white;
      border-radius: 0.75rem;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    
    .table {
      margin-bottom: 0;
    }
    
    .table thead {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: white;
    }
    
    .table th {
      font-weight: 600;
      padding: 1rem;
      border: none;
    }
    
    .table td {
      padding: 1rem;
      vertical-align: middle;
      border-color: #f1f1f1;
    }
    
    .total-row {
      font-weight: 700;
      background-color: rgba(0, 206, 201, 0.1) !important;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .order-meta {
        flex-direction: column;
        gap: 1rem;
      }
      
      .table td {
        padding: 0.75rem;
      }
    }
  </style>
</head>
<body>

<div class="container my-5">
  <!-- Printable Section -->
  <div class="print-section">
    <!-- Order Header -->
    <div class="order-header">
      <h1 class="order-title">
        <i class="fas fa-receipt"></i>Order Invoice #<?= $order_id ?>
      </h1>
      
      <div class="order-meta">
        <div class="order-meta-item">
          <strong>Order Date:</strong>
          <span><?= date('F j, Y', strtotime($order_date)) ?></span>
        </div>
        <div class="order-meta-item">
          <strong>Customer:</strong>
          <span><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
        </div>
      </div>
    </div>
    
    <!-- Order Items -->
    <div class="order-table">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $total = 0;
          while ($item = $result->fetch_assoc()):
            $subtotal = $item['quantity'] * $item['price'];
            $total += $subtotal;
          ?>
          <tr>
            <td><?= htmlspecialchars($item['product_name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>Ksh <?= number_format($item['price']) ?></td>
            <td>Ksh <?= number_format($subtotal) ?></td>
          </tr>
          <?php endwhile; ?>
          
          <tr class="total-row">
            <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
            <td><strong>Ksh <?= number_format($total) ?></strong></td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <!-- Footer Note (only visible in print) -->
    <div class="mt-4 text-center" style="display: none;">
      <p class="text-muted">Thank you for shopping with DayrohTech!</p>
    </div>
  </div>
  
  <!-- Action Buttons (not printed) -->
  <div class="action-buttons no-print mt-4 d-flex gap-2">
    <a href="my_orders.php" class="btn btn-outline-primary">
      <i class="fas fa-arrow-left me-2"></i>Back to Orders
    </a>
    <button onclick="window.print()" class="btn btn-primary">
      <i class="fas fa-print me-2"></i>Print Invoice
    </button>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Show footer note only when printing
  window.addEventListener('beforeprint', function() {
    document.querySelector('.text-center').style.display = 'block';
  });
  window.addEventListener('afterprint', function() {
    document.querySelector('.text-center').style.display = 'none';
  });
</script>
</body>
</html>