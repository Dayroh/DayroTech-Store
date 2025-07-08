<?php
session_start();

// Redirect if not logged in or not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$adminName = $_SESSION['user_name'];
$hour = date('H');
$greeting = ($hour < 12) ? "Good morning" : (($hour < 18) ? "Good afternoon" : "Good evening");

if (isset($_SESSION['user_name'])) {
  echo "<p class='text-end me-3'>$greeting, <strong>" . htmlspecialchars($_SESSION['user_name']) . "</strong> 👋</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | DayrohTech</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark admin-navbar">
  <div class="container d-flex justify-content-between align-items-center">
    <a class="navbar-brand" href="#">DayrohTech Admin</a>
    <div class="d-flex align-items-center">
      <span class="text-white me-3">Welcome, <?= htmlspecialchars($adminName) ?></span>
      <a href="admin_messages.php" class="btn btn-sm btn-outline-light me-2">Messages</a>
      <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
    </div>
  </div>
</nav>


<div class="container my-5">
  <h2 class="mb-4">📊 Admin Dashboard</h2>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card shadow-sm border-primary">
        <div class="card-body">
          <h5 class="card-title">🖥️ Manage Products</h5>
          <p class="card-text">Add, edit or delete laptops and accessories.</p>
          <a href="admin_products.php" class="btn btn-primary">Go to Products</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm border-success">
        <div class="card-body">
          <h5 class="card-title">📦 View Orders</h5>
          <p class="card-text">See recent orders, filter by user or date.</p>
          <a href="admin_orders.php" class="btn btn-success">View Orders</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm border-warning">
        <div class="card-body">
          <h5 class="card-title">👤 Manage Users</h5>
          <p class="card-text">View registered users and assign roles.</p>
          <a href="admin_users.php" class="btn btn-warning">Manage Users</a>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-5 text-muted text-center">
    <small>&copy; <?= date('Y') ?> DayrohTech Store. Admin Panel.</small>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
