<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'config.php';

// Delete product
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: admin_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Products | Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="admin_dashboard.php">DayrohTech Admin</a>
    <div class="ms-auto">
      <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h2 class="mb-4">🖥️ Manage Products</h2>
  <a href="admin_add_product.php" class="btn btn-success mb-3">+ Add New Product</a>

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Brand</th>
        <th>Category</th>
        <th>Price (Ksh)</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
    if ($result->num_rows > 0):
      while ($row = $result->fetch_assoc()):
    ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><img src="<?= $row['image'] ?>" width="60"></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['brand']) ?></td>
        <td><?= htmlspecialchars($row['category']) ?></td>
        <td><?= number_format($row['price']) ?></td>
        <td>
          <a href="admin_edit_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
          <a href="admin_products.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
        </td>
      </tr>
    <?php
      endwhile;
    else:
      echo "<tr><td colspan='7' class='text-center'>No products found.</td></tr>";
    endif;
    ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
