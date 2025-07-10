<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>DayrohTech Store</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css" />
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

  <!-- Featured Products -->
  <section class="container my-4" id="products">
    <h2 class="text-center mb-4">Featured Products</h2>
    
    <!-- Product Grid -->
    <div class="row" id="productGrid">
      <?php
      // Get 4 featured products
     $sql = "SELECT * FROM products LIMIT 4";

      $result = $conn->query($sql);

      if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
          // Get related images for this product
        $related_images = []; // no related images
          $priceValue = $row['price'];
          $priceRange = $priceValue < 20000 ? "under20" : ($priceValue <= 50000 ? "20to50" : "over50");
      ?>
      <div class="col-md-3 mb-4 product-card"
           data-brand="<?= strtolower($row['brand']) ?>"
           data-category="<?= strtolower($row['category']) ?>"
           data-price="<?= $priceRange ?>">
        <div class="card h-100">
          <!-- Main Product Image with Carousel -->
          <div id="productCarousel<?= $row['id'] ?>" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="<?= $row['image'] ?>" class="d-block w-100 card-img-top" alt="<?= htmlspecialchars($row['name']) ?>">
              </div>
              <?php foreach ($related_images as $index => $image): ?>
              <div class="carousel-item">
                <img src="<?= $image ?>" class="d-block w-100 card-img-top" alt="Related image <?= $index + 1 ?>">
              </div>
              <?php endforeach; ?>
            </div>
            <?php if (count($related_images) > 0): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel<?= $row['id'] ?>" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel<?= $row['id'] ?>" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
            <?php endif; ?>
          </div>
          
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>
            <p class="text-success">Ksh <?= number_format($row['price']) ?></p>
            
            <!-- Thumbnail navigation for related images -->
            <?php if (count($related_images) > 0): ?>
            <div class="related-images-thumbs d-flex flex-wrap gap-2 mb-3">
              <img src="<?= $row['image'] ?>" class="img-thumbnail" width="50" height="50" 
                   onclick="document.querySelector('#productCarousel<?= $row['id'] ?>').carousel.to(0)">
              <?php foreach ($related_images as $index => $image): ?>
              <img src="<?= $image ?>" class="img-thumbnail" width="50" height="50" 
                   onclick="document.querySelector('#productCarousel<?= $row['id'] ?>').carousel.to(<?= $index + 1 ?>)">
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <form action="cart.php" method="POST">
              <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
              <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']) ?>">
              <input type="hidden" name="price" value="<?= $row['price'] ?>">
              <button type="submit" name="add_to_cart" class="btn btn-outline-primary btn-sm">Add to Cart</button>
              <a href="view_cart.php" class="btn btn-outline-success btn-sm">View Cart</a>
            </form>
          </div>
        </div>
      </div>
      <?php
        endwhile;
      else:
        // Fallback to show 4 random products if no featured products are set
        $sql = "SELECT * FROM products ORDER BY RAND() LIMIT 4";
        $result = $conn->query($sql);
        if ($result->num_rows > 0):
          while ($row = $result->fetch_assoc()):
      ?>
      <div class="col-md-3 mb-4 product-card">
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
              <a href="view_cart.php" class="btn btn-outline-success btn-sm">View Cart</a>
            </form>
          </div>
        </div>
      </div>
      <?php
          endwhile;
        else:
          echo "<p class='text-center'>No products available at the moment.</p>";
        endif;
      endif;
      $conn->close();
      ?>
    </div>
    
    <!-- View All Products Button -->
    <div class="text-center mt-4">
      <a href="all_products.php" class="btn btn-primary">View All Products</a>
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
