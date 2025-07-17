<?php
session_start();
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Fetch orders for the current user
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders | DayrohTech</title>
  <link rel="icon" href="assets/images/logo.png" type="image/png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
    
    /* Enhanced Navbar */
    .navbar {
      background: linear-gradient(135deg, var(--dark), #1a1a1a) !important;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      padding: 0.8rem 0;
    }
    
    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
      background: linear-gradient(45deg, var(--secondary), var(--primary));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      transition: all 0.3s ease;
    }
    
    .navbar-brand:hover {
      transform: scale(1.02);
    }
    
    .nav-link {
      font-weight: 500;
      padding: 0.5rem 1rem !important;
      margin: 0 0.25rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
      position: relative;
    }
    
    .nav-link:not(.active):before {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--secondary);
      transition: width 0.3s ease;
    }
    
    .nav-link:hover:not(.active):before {
      width: 100%;
    }
    
    .nav-link.active {
      background: rgba(255,255,255,0.1);
    }
    
    .user-greeting {
      color: white;
      font-weight: 500;
      margin-left: 1rem;
      display: flex;
      align-items: center;
    }
    
    /* Main Content */
    .container {
      max-width: 1200px;
      padding: 2rem 1.5rem;
    }
    
    h3 {
      color: var(--primary);
      font-weight: 700;
      margin-bottom: 2rem;
      position: relative;
      display: inline-block;
    }
    
    h3:after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 0;
      width: 60px;
      height: 4px;
      background: linear-gradient(to right, var(--primary), var(--secondary));
      border-radius: 2px;
    }
    
    /* Orders Table */
    .table-responsive {
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
    
    .table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(108, 92, 231, 0.03);
    }
    
    .table-hover tbody tr:hover {
      background-color: rgba(108, 92, 231, 0.1);
    }
    
    /* Buttons */
    .btn-outline-primary {
      border-color: var(--primary);
      color: var(--primary);
      transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
      background: var(--primary);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3);
    }
    
    /* Empty State */
    .alert-info {
      background-color: rgba(0, 206, 201, 0.1);
      border-color: rgba(0, 206, 201, 0.3);
      color: var(--dark);
      padding: 1.5rem;
      border-radius: 0.75rem;
      text-align: center;
    }
    
    /* Status Badges (you can add status to your orders) */
    .badge-status {
      padding: 0.5rem 0.75rem;
      border-radius: 0.5rem;
      font-weight: 600;
    }
    
    .badge-pending {
      background-color: #fff3cd;
      color: #856404;
    }
    
    .badge-processing {
      background-color: #cce5ff;
      color: #004085;
    }
    
    .badge-completed {
      background-color: #d4edda;
      color: #155724;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .navbar-collapse {
        background: rgba(44, 62, 80, 0.98);
        padding: 1rem;
        margin-top: 0.5rem;
        border-radius: 0.5rem;
      }
      
      .nav-link {
        margin: 0.25rem 0;
      }
      
      .user-greeting {
        margin-left: 0;
        padding: 0.75rem 1rem;
        border-top: 1px solid rgba(255,255,255,0.1);
      }
      
      .table-responsive {
        border-radius: 0.5rem;
      }
      
      .table thead {
        display: none;
      }
      
      .table, .table tbody, .table tr, .table td {
        display: block;
        width: 100%;
      }
      
      .table tr {
        margin-bottom: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      }
      
      .table td {
        text-align: right;
        padding-left: 50%;
        position: relative;
        border-bottom: 1px solid #f1f1f1;
      }
      
      .table td:before {
        content: attr(data-label);
        position: absolute;
        left: 1rem;
        width: 45%;
        padding-right: 1rem;
        text-align: left;
        font-weight: 600;
        color: var(--primary);
      }
      
      .table td:last-child {
        border-bottom: 0;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="users.php">
      <i class="fas fa-laptop-code me-2"></i>DayrohTech
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="user.php">
            <i class="fas fa-home me-1"></i> Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="cart.php">
            <i class="fas fa-shopping-cart me-1"></i> Cart
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="orders.php">
            <i class="fas fa-box-open me-1"></i> My Orders
          </a>
        </li>
      </ul>
      
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['user_name'])): ?>
          <li class="nav-item">
            <span class="user-greeting">
              <i class="fas fa-user-circle me-1"></i>
              <?= htmlspecialchars($_SESSION['user_name']) ?>
            </span>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">
            <i class="fas fa-sign-out-alt me-1"></i> Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Orders Content -->
<div class="container my-5">
  <h3 class="mb-4">
    <i class="fas fa-box-open me-2"></i>My Orders
  </h3>

  <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Total (Ksh)</th>
            <th>Status</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): 
            // You can add status to your orders table
            $status = $row['status'] ?? 'completed'; // Default for demo
          ?>
          <tr>
            <td data-label="Order ID">#<?= $row['id'] ?></td>
            <td data-label="Date"><?= date('M j, Y', strtotime($row['order_date'])) ?></td>
            <td data-label="Total">Ksh <?= number_format($row['total_price']) ?></td>
            <td data-label="Status">
              <span class="badge badge-status badge-<?= $status ?>">
                <?= ucfirst($status) ?>
              </span>
            </td>
            <td data-label="Details">
              <a href="order_detail.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-eye me-1"></i> View
              </a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info">
      <i class="fas fa-box-open fa-3x mb-3" style="color: var(--primary);"></i>
      <h4>No Orders Yet</h4>
      <p class="mb-0">You haven't placed any orders yet. Start shopping now!</p>
      <a href="user.php" class="btn btn-primary mt-3">
        <i class="fas fa-shopping-bag me-2"></i>Browse Products
      </a>
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>