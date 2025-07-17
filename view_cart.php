<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>My Cart | DayrohTech</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="assets/images/logo.png" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #6c5ce7;
      --primary-dark: #5649c0;
      --secondary: #00cec9;
      --accent: #fd79a8;
      --dark: #2d3436;
      --light: #f5f6fa;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
      color: var(--dark);
    }
    
    .container {
      max-width: 1200px;
      padding: 20px;
    }
    
    h2 {
      color: var(--primary);
      font-weight: 700;
      text-align: center;
      margin-bottom: 2rem !important;
      position: relative;
    }
    
    h2:after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: linear-gradient(to right, var(--primary), var(--secondary));
      border-radius: 2px;
    }
    
    .table {
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      margin-bottom: 2rem;
    }
    
    .table thead {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: white;
    }
    
    .table th {
      font-weight: 600;
      padding: 15px;
    }
    
    .table td {
      vertical-align: middle;
      padding: 12px 15px;
    }
    
    .btn-success {
      background-color: var(--success);
      border: none;
      padding: 10px 25px;
      font-weight: 600;
      transition: all 0.3s ease;
      background: linear-gradient(135deg, var(--success), #00a884);
    }
    
    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 184, 148, 0.3);
    }
    
    .btn-secondary {
      background-color: var(--secondary);
      border: none;
      padding: 10px 25px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 206, 201, 0.3);
    }
    
    .btn-danger {
      transition: all 0.3s ease;
    }
    
    .btn-danger:hover {
      transform: scale(1.05);
    }
    
    .action-buttons {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
      justify-content: center;
      margin-top: 2rem;
    }
    
    .alert-info {
      background-color: rgba(0, 206, 201, 0.1);
      border-color: rgba(0, 206, 201, 0.3);
      color: var(--dark);
      text-align: center;
      padding: 20px;
      border-radius: 10px;
    }
    
    .alert-info a {
      color: var(--primary);
      font-weight: 600;
      text-decoration: none;
    }
    
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
      .table thead {
        display: none;
      }
      
      .table, .table tbody, .table tr, .table td {
        display: block;
        width: 100%;
      }
      
      .table tr {
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: relative;
      }
      
      .table td {
        text-align: right;
        padding-left: 50%;
        position: relative;
        border-bottom: 1px solid #eee;
      }
      
      .table td:before {
        content: attr(data-label);
        position: absolute;
        left: 15px;
        width: 45%;
        padding-right: 15px;
        text-align: left;
        font-weight: 600;
        color: var(--primary);
      }
      
      .table td:last-child {
        border-bottom: 0;
      }
      
      .action-buttons {
        flex-direction: column;
        gap: 10px;
      }
      
      .action-buttons .btn {
        width: 100%;
      }
    }
    
    /* Animation for cart items */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .table tbody tr {
      animation: fadeIn 0.5s ease forwards;
    }
    
    .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
    .table tbody tr:nth-child(2) { animation-delay: 0.2s; }
    .table tbody tr:nth-child(3) { animation-delay: 0.3s; }
    .table tbody tr:nth-child(4) { animation-delay: 0.4s; }
  </style>
</head>
<body>
<div class="container my-5">
  <h2 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>My Shopping Cart</h2>

  <?php if (!empty($_SESSION['cart'])): ?>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Price (Ksh)</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $total = 0;
          foreach ($_SESSION['cart'] as $item):
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
          ?>
          <tr>
            <td data-label="Product"><?= htmlspecialchars($item['name']) ?></td>
            <td data-label="Price"><?= number_format($item['price']) ?></td>
            <td data-label="Quantity">
              <div class="d-flex align-items-center justify-content-end">
                <a href="cart.php?decrease=<?= $item['id'] ?>" class="btn btn-sm btn-outline-secondary me-2"><i class="fas fa-minus"></i></a>
                <span><?= $item['quantity'] ?></span>
                <a href="cart.php?increase=<?= $item['id'] ?>" class="btn btn-sm btn-outline-secondary ms-2"><i class="fas fa-plus"></i></a>
              </div>
            </td>
            <td data-label="Total"><?= number_format($subtotal) ?></td>
            <td data-label="Action">
              <a href="cart.php?remove=<?= $item['id'] ?>" class="btn btn-danger btn-sm" title="Remove">
                <i class="fas fa-trash-alt"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
          <tr class="table-active">
            <td colspan="3" class="text-end fw-bold">Grand Total</td>
            <td colspan="2" class="fw-bold">Ksh <?= number_format($total) ?></td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <div class="action-buttons">
      <a href="checkout.php" class="btn btn-outline-success">
        <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
      </a>
      <a href="index.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Continue Shopping
      </a>
      <a href="" class="btn btn-outline-danger">
        <i class="fas fa-broom me-2"></i>clear cart
      </a>
    </div>
  <?php else: ?>
    <div class="text-center py-5">
      <div class="alert alert-info">
        <i class="fas fa-shopping-cart fa-3x mb-3" style="color: var(--primary);"></i>
        <h4>Your cart is empty</h4>
        <p class="mb-0">Looks like you haven't added any items to your cart yet.</p>
        <a href="user.php" class="btn btn-primary mt-3">
          <i class="fas fa-shopping-cart me-2"></i>Start Shopping
        </a>
      </div>
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>