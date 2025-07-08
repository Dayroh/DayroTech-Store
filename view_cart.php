<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>My Cart | DayrohTech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
  <h2 class="mb-4">🛒 My Cart</h2>

  <?php if (!empty($_SESSION['cart'])): ?>
    <table class="table table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Product</th>
          <th>Price (Ksh)</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Remove</th>
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
          <td><?= htmlspecialchars($item['name']) ?></td>
          <td><?= number_format($item['price']) ?></td>
          <td><?= $item['quantity'] ?></td>
          <td><?= number_format($subtotal) ?></td>
          <td><a href="cart.php?remove=<?= $item['id'] ?>" class="btn btn-danger btn-sm">Remove</a></td>
        </tr>
        <?php endforeach; ?>
        <tr>
          <th colspan="3">Total</th>
          <th colspan="2">Ksh <?= number_format($total) ?></th>
        </tr>
      </tbody>
    </table>
    <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
    <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
  <?php else: ?>
    <p class="alert alert-info">Your cart is empty. <a href="index.php">Go back to shop.</a></p>
  <?php endif; ?>
</div>
</body>
</html>
