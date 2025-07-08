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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="users.php">DayrohTech</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="users.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
        <li class="nav-item"><a class="nav-link active" href="orders.php">My Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php if (isset($_SESSION['user_name'])): ?>
          <li class="nav-item text-white ms-3 mt-2">
            👋 Hi, <?= htmlspecialchars($_SESSION['user_name']) ?>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Orders Content -->
<div class="container my-5">
  <h3 class="mb-4">📦 My Orders</h3>

  <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Total (Ksh)</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['order_date'] ?></td>
            <td>Ksh <?= number_format($row['total_price']) ?></td>
            <td><a href="order_detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">View</a></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">You have no orders yet. Go ahead and place one!</div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
