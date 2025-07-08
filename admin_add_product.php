<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'];
    $brand    = $_POST['brand'];
    $category = $_POST['category'];
    $price    = $_POST['price'];
    $desc     = $_POST['description'];
    $image    = $_POST['image']; // for simplicity, paste image URL

    $stmt = $conn->prepare("INSERT INTO products (name, brand, category, price, description, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $name, $brand, $category, $price, $desc, $image);
    $stmt->execute();

    header("Location: admin_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product | Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h2 class="mb-4">➕ Add New Product</h2>
  <form method="POST" action="">
    <div class="mb-3">
      <label class="form-label">Product Name</label>
      <input type="text" name="name" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Brand</label>
      <select name="brand" class="form-select" required>
        <option value="">-- Select Brand --</option>
        <option value="HP">HP</option>
        <option value="Lenovo">Lenovo</option>
        <option value="Dell">Dell</option>
        <option value="Acer">Acer</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Category</label>
      <select name="category" class="form-select" required>
        <option value="">-- Select Category --</option>
        <option value="laptop">Laptop</option>
        <option value="accessory">Accessory</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Price (Ksh)</label>
      <input type="number" name="price" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Image URL</label>
      <input type="text" name="image" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" required></textarea>
    </div>
    <button type="submit" class="btn btn-success">Add Product</button>
    <a href="admin_products.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

</body>
</html>
