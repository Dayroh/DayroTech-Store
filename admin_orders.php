<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: login.php");
  exit();
}

$sql = "
  SELECT 
    o.id AS order_id,
    u.name AS customer_name,
    u.email AS customer_email,
    oi.product_name,
    oi.quantity,
    o.total_price,
    o.order_date
  FROM orders o
  JOIN users u ON o.user_id = u.id
  LEFT JOIN order_items oi ON oi.order_id = o.id
  ORDER BY o.order_date DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Orders | DayrohTech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #4e73df;
      --secondary-color: #f8f9fc;
      --accent-color: #2e59d9;
      --dark-color: #2c3e50;
      --light-color: #ffffff;
      --danger-color: #e74a3b;
      --success-color: #1cc88a;
      --warning-color: #f6c23e;
      --info-color: #36b9cc;
    }
    
    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #4a4a4a;
    }
    
    .container {
      max-width: 1400px;
      padding: 20px;
    }
    
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid #e3e6f0;
    }
    
    .page-title {
      font-weight: 600;
      color: var(--dark-color);
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 1.75rem;
    }
    
    .table-container {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
      overflow: hidden;
      margin-top: 20px;
    }
    
    .table {
      margin-bottom: 0;
    }
    
    .table thead {
      background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
      color: white;
    }
    
    .table th {
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
      padding: 15px;
      vertical-align: middle;
    }
    
    .table td {
      vertical-align: middle;
      padding: 15px;
      border-color: #f0f2f5;
    }
    
    .table tr:nth-child(even) {
      background-color: #f9fafc;
    }
    
    .table tr:hover {
      background-color: #f0f5ff;
    }
    
    .badge-status {
      padding: 6px 10px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .alert-success {
      background-color: rgba(28, 200, 138, 0.1);
      border-color: rgba(28, 200, 138, 0.2);
      color: var(--success-color);
    }
    
    .alert-info {
      background-color: rgba(54, 185, 204, 0.1);
      border-color: rgba(54, 185, 204, 0.2);
      color: var(--info-color);
    }
    
    .btn-danger {
      background-color: var(--danger-color);
      border-color: var(--danger-color);
      transition: all 0.2s;
    }
    
    .btn-danger:hover {
      background-color: #d52a1e;
      border-color: #d52a1e;
      transform: translateY(-1px);
      box-shadow: 0 2px 6px rgba(231, 74, 59, 0.3);
    }
    
    .btn-sm {
      padding: 0.35rem 0.75rem;
      font-size: 0.8rem;
      border-radius: 4px;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }
    
    .price-cell {
      font-weight: 600;
      color: var(--primary-color);
    }
    
    .date-cell {
      white-space: nowrap;
    }
    
    .empty-state {
      padding: 3rem;
      text-align: center;
    }
    
    .empty-state-icon {
      font-size: 4rem;
      color: #e9ecef;
      margin-bottom: 1rem;
    }
    
    .empty-state-title {
      font-weight: 600;
      margin-bottom: 0.5rem;
    }
    
    .empty-state-text {
      color: #6c757d;
      max-width: 500px;
      margin: 0 auto 1.5rem;
    }
    
    @media (max-width: 768px) {
      .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
      
      .table th, .table td {
        padding: 10px 8px;
        font-size: 0.85rem;
      }
      
      .page-title {
        font-size: 1.5rem;
      }
    }
    
    .customer-cell {
      display: flex;
      flex-direction: column;
    }
    
    .customer-name {
      font-weight: 600;
    }
    
    .customer-email {
      font-size: 0.8rem;
      color: #6c757d;
    }
  </style>
</head>
<body>

<div class="container my-4">
  <div class="page-header">
    <h3 class="page-title">
      <i class="bi bi-box-seam"></i> Customer Orders
    </h3>
  </div>

  <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success d-flex align-items-center">
      <i class="bi bi-check-circle-fill me-2"></i>
      Order deleted successfully.
    </div>
  <?php endif; ?>

  <div class="table-container">
    <?php if ($result->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Customer</th>
              <th>Product</th>
              <th>Qty</th>
              <th>Total</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $count = 1; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $count++ ?></td>
              <td class="customer-cell">
                <span class="customer-name"><?= htmlspecialchars($row['customer_name']) ?></span>
                <span class="customer-email"><?= htmlspecialchars($row['customer_email']) ?></span>
              </td>
              <td><?= htmlspecialchars($row['product_name'] ?? 'N/A') ?></td>
              <td><?= $row['quantity'] ?? 'N/A' ?></td>
              <td class="price-cell">Ksh <?= number_format($row['total_price']) ?></td>
              <td class="date-cell"><?= date('M j, Y', strtotime($row['order_date'])) ?></td>
              <td>
                <a href="delete.php?type=order&id=<?= $row['order_id'] ?>" 
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Are you sure you want to delete this order?\nThis action cannot be undone.')">
                  <i class="bi bi-trash"></i> Delete
                </a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <div class="empty-state-icon">
          <i class="bi bi-box"></i>
        </div>
        <h5 class="empty-state-title">No Orders Found</h5>
        <p class="empty-state-text">There are currently no orders placed by customers.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Enhanced confirmation dialog
  document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.btn-danger');
    
    deleteButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        if (!confirm('Are you sure you want to delete this order?\n\nOrder ID: ' + 
                     this.getAttribute('href').split('id=')[1] + 
                     '\nThis action cannot be undone.')) {
          e.preventDefault();
        }
      });
    });
  });
</script>
</body>
</html>