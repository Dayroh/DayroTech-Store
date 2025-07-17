<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'config.php';

$id = (int) $_GET['id'];

$result = $conn->query("SELECT * FROM products WHERE id = $id");
if ($result->num_rows != 1) {
    die("Product not found.");
}
$product = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'];
    $brand    = $_POST['brand'];
    $category = $_POST['category'];
    $price    = $_POST['price'];
    $desc     = $_POST['description'];
    $image    = $_POST['image'];

    $stmt = $conn->prepare("UPDATE products SET name=?, brand=?, category=?, price=?, description=?, image=? WHERE id=?");
    $stmt->bind_param("sssissi", $name, $brand, $category, $price, $desc, $image, $id);
    $stmt->execute();

    header("Location: admin_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product | Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h2 class="mb-4">✏️ Edit Product</h2>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Product Name</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Brand</label>
      <select name="brand" class="form-select" required>
        <option value="HP" <?= $product['brand'] === 'HP' ? 'selected' : '' ?>>HP</option>
        <option value="Lenovo" <?= $product['brand'] === 'Lenovo' ? 'selected' : '' ?>>Lenovo</option>
        <option value="Dell" <?= $product['brand'] === 'Dell' ? 'selected' : '' ?>>Dell</option>
        <option value="Acer" <?= $product['brand'] === 'Acer' ? 'selected' : '' ?>>Acer</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Category</label>
      <select name="category" class="form-select" required>
        <option value="laptop" <?= $product['category'] === 'laptop' ? 'selected' : '' ?>>Laptop</option>
        <option value="accessory" <?= $product['category'] === 'accessory' ? 'selected' : '' ?>>Accessory</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Price (Ksh)</label>
      <input type="number" name="price" class="form-control" value="<?= $product['price'] ?>" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Image URL</label>
      <input type="text" name="image" class="form-control" value="<?= htmlspecialchars($product['image']) ?>" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" required><?= htmlspecialchars($product['description']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update Product</button>
    <a href="admin_products.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

</body>
</html>
