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
    }
    
    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding-top: 50px;
    }
    
    .navbar {
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
      position: fixed;
  top: 0;
  left: 0;
  right: 0;
  width: 100%;
  z-index: 1030;
      background: linear-gradient(135deg, var(--dark-color), var(--primary-color)) !important;
    }
    
    .navbar-brand {
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    
    .container {
      max-width: 1200px;
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
    }
    
    .table {
      background-color: var(--light-color);
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
      border-radius: 0.35rem;
      overflow: hidden;
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
      padding: 1rem;
    }
    
    .table td {
      vertical-align: middle;
      padding: 1rem;
    }
    
    .table img {
      border-radius: 4px;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      transition: transform 0.2s;
    }
    
    .table img:hover {
      transform: scale(1.05);
    }
    
    .btn-success {
      background-color: var(--success-color);
      border-color: var(--success-color);
    }
    
    .btn-success:hover {
      background-color: #17a673;
      border-color: #17a673;
    }
    
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
      background-color: var(--accent-color);
      border-color: var(--accent-color);
    }
    
    .btn-danger {
      background-color: var(--danger-color);
      border-color: var(--danger-color);
    }
    
    .btn-danger:hover {
      background-color: #d52a1e;
      border-color: #d52a1e;
    }
    
    .btn-sm {
      padding: 0.25rem 0.5rem;
      font-size: 0.75rem;
      border-radius: 0.2rem;
    }
    
    .empty-state {
      text-align: center;
      padding: 2rem;
      color: #6c757d;
    }
    
    .empty-state i {
      font-size: 3rem;
      margin-bottom: 1rem;
      color: #dee2e6;
    }
    
    .price-cell {
      font-weight: 600;
      color: var(--primary-color);
    }
    
    .action-buttons {
      display: flex;
      gap: 0.5rem;
    }
    
    @media (max-width: 768px) {
      .table-responsive {
        overflow-x: auto;
      }
      
      .action-buttons {
        flex-direction: column;
      }
      
      .btn-sm {
        width: 100%;
      }
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="admin_dashboard.php">
      <i class="bi bi-laptop"></i> DayrohTech Admin
    </a>
    <div class="ms-auto">
      <a href="logout.php" class="btn btn-sm btn-outline-light">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  </div>
</nav>

<div class="container mt-4 mb-5">
  <div class="page-header">
    <h2 class="page-title">
      <i class="bi bi-laptop"></i> Manage Products
    </h2>
    <a href="admin_add_product.php" class="btn btn-success">
      <i class="bi bi-plus-lg"></i> Add New Product
    </a>
  </div>

  <div class="table-responsive rounded">
    <table class="table table-hover">
      <thead>
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
          <td><img src="<?= $row['image'] ?>" width="60" alt="<?= htmlspecialchars($row['name']) ?>"></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['brand']) ?></td>
          <td><?= htmlspecialchars($row['category']) ?></td>
          <td class="price-cell"><?= number_format($row['price']) ?></td>
          <td>
            <div class="action-buttons">
              <a href="admin_edit_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil"></i> Edit
              </a>
              <a href="admin_products.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                <i class="bi bi-trash"></i> Delete
              </a>
            </div>
          </td>
        </tr>
      <?php
        endwhile;
      else:
      ?>
        <tr>
          <td colspan="7">
            <div class="empty-state">
              <i class="bi bi-laptop"></i>
              <h5>No Products Found</h5>
              <p>Get started by adding your first product</p>
              <a href="admin_add_product.php" class="btn btn-success mt-2">
                <i class="bi bi-plus-lg"></i> Add Product
              </a>
            </div>
          </td>
        </tr>
      <?php
      endif;
      ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Add confirmation for delete action
  document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.btn-danger');
    
    deleteButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        if (!confirm('Are you sure you want to delete this product?\nThis action cannot be undone.')) {
          e.preventDefault();
        }
      });
    });
  });
</script>
</body>
</html>