<?php include 'config.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>DayrohTech Store</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css" />
  
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="whatsapp.css">
<script src="whatsapp.js" defer></script>
</head>
<body>
<!-- Background Video -->
<video autoplay muted loop playsinline id="indexVideo">
  <source src="assets/videos/index.mp4" type="video/mp4" />
  Your browser does not support HTML5 video.
</video>

  <!-- Header -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">DayrohTech Store</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
  <li class="nav-item"><a class="nav-link" href="index.php">Shop</a></li>
  <li class="nav-item"><a class="nav-link" href="plans.php">Internet Plans</a></li>
  <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

  <?php if (isset($_SESSION['user_id'])): ?>
    <li class="nav-item"><a class="nav-link" href="my_orders.php">My Orders</a></li>
    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
  <?php else: ?>
    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
  <?php endif; ?>
</ul>

      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="bg-light text-center py-5">
    <div class="container">
      <h1 class="display-5">Welcome to DayrohTech Store</h1>
      <p class="lead">Laptops. Accessories. Reliable Internet. All in one place.</p>
      <a href="#products" class="btn btn-primary mt-3">Browse Products</a>
    </div>
  </section>

  <!-- Filters -->
  <section class="container my-4" id="products">
    <h2 class="text-center mb-4">Our Products</h2>
    <div class="row mb-3">
      <div class="col-md-3">
        <select class="form-select" id="brandFilter">
          <option value="all">All Brands</option>
          <option value="hp">HP</option>
          <option value="lenovo">Lenovo</option>
          <option value="dell">Dell</option>
        </select>
      </div>
      <div class="col-md-3">
        <select class="form-select" id="categoryFilter">
          <option value="all">All Categories</option>
          <option value="laptop">Laptops</option>
          <option value="accessory">Accessories</option>
        </select>
      </div>
      <div class="col-md-3">
        <select class="form-select" id="priceFilter">
          <option value="all">All Prices</option>
          <option value="under20">Under Ksh 20,000</option>
          <option value="20to50">Ksh 20,000 - 50,000</option>
          <option value="over50">Over Ksh 50,000</option>
        </select>
      </div>
    </div>

    <!-- Product Grid -->
    <div class="row" id="productGrid">
      <?php
      $sql = "SELECT * FROM products";
      $result = $conn->query($sql);

      if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
          $priceValue = $row['price'];
          $priceRange = $priceValue < 20000 ? "under20" : ($priceValue <= 50000 ? "20to50" : "over50");
      ?>
      <div class="col-md-3 mb-4 product-card"
           data-brand="<?= strtolower($row['brand']) ?>"
           data-category="<?= strtolower($row['category']) ?>"
           data-price="<?= $priceRange ?>">
        <div class="card h-100">
          <img src="<?= $row['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>
            <p class="text-success">Ksh <?= number_format($row['price']) ?></p>
            <form action="cart.php" method="POST">
  <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
  <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']) ?>">
  <input type="hidden" name="price" value="<?= $row['price'] ?>">
  <button type="submit" name="add_to_cart" class="btn btn-outline-primary btn-sm">Add to Cart</button>
  <li class="nav-item"><a class="nav-link" href="view_cart.php">View Cart</a></li>

</form>

          </div>
        </div>
      </div>
      <?php
        endwhile;
      else:
        echo "<p class='text-center'>No products available at the moment.</p>";
      endif;
      $conn->close();
      ?>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center p-3 mt-5">
    <p>&copy; <?php echo date("Y"); ?> DayrohTech Store | All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="filter.js"></script>
</body>
</html>
