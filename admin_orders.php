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
</head>
<body>
<div class="container my-5">
  <h3>📦 Customer Orders</h3>

  <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">✅ Order deleted successfully.</div>
  <?php endif; ?>

  <?php if ($result->num_rows > 0): ?>
  <table class="table table-bordered mt-4">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Customer</th>
        <th>Email</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Total (Ksh)</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php $count = 1; ?>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $count++ ?></td>
        <td><?= htmlspecialchars($row['customer_name']) ?></td>
        <td><?= htmlspecialchars($row['customer_email']) ?></td>
        <td><?= htmlspecialchars($row['product_name'] ?? 'N/A') ?></td>
        <td><?= $row['quantity'] ?? 'N/A' ?></td>
        <td>Ksh <?= number_format($row['total_price']) ?></td>
        <td><?= $row['order_date'] ?></td>
        <td>
          <a href="delete.php?type=order&id=<?= $row['order_id'] ?>" 
             class="btn btn-sm btn-danger"
             onclick="return confirm('Delete this order?')">
            Delete
          </a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php else: ?>
    <div class="alert alert-info text-center">No orders placed yet.</div>
  <?php endif; ?>
</div>
</body>
</html>
