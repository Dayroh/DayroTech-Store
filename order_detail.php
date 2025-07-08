<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
  header("Location: login.php");
  exit();
}

$order_id = $_GET['id'];
$sql = "SELECT * FROM order_items WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Order Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
  <h3>🧾 Order #<?= $order_id ?> Details</h3>

  <table class="table mt-4">
    <thead class="table-secondary">
      <tr>
        <th>Product</th>
        <th>Qty</th>
        <th>Price (each)</th>
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
        <td><?= $item['product_name'] ?></td>
        <td><?= $item['quantity'] ?></td>
        <td>Ksh <?= number_format($item['price']) ?></td>
        <td>Ksh <?= number_format($subtotal) ?></td>
      </tr>
      <?php endwhile; ?>
      <tr class="fw-bold">
        <td colspan="3">Total</td>
        <td>Ksh <?= number_format($total) ?></td>
      </tr>
    </tbody>
  </table>
</div>
</body>
</html>
