<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>All Products - DayrohTech Store</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #6c5ce7;
      --primary-dark: #5649c0;
      --secondary: #00cec9;
      --accent: #fd79a8;
      --dark: #2d3436;
      --light: #f5f6fa;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      color: var(--dark);
    }

    .category-filter {
      background: white;
      padding: 1.5rem;
      border-radius: 0.75rem;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      margin-bottom: 2rem;
    }

    .category-btn {
      border: 2px solid var(--primary);
      color: var(--primary);
      font-weight: 600;
      margin: 0.25rem;
      transition: all 0.3s ease;
    }

    .category-btn:hover, .category-btn.active {
      background: var(--primary);
      color: white;
    }

    .product-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      margin-bottom: 2rem;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .card {
      border: none;
      border-radius: 0.75rem;
      overflow: hidden;
    }

    .carousel-control-prev, .carousel-control-next {
      background: rgba(0,0,0,0.2);
      width: 40px;
      height: 40px;
      border-radius: 50%;
      top: 50%;
      transform: translateY(-50%);
    }

    .carousel-indicators button {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      margin: 0 3px;
    }

    .related-thumbs {
      display: flex;
      gap: 8px;
      margin-top: 10px;
    }

    .related-thumbs img {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border: 1px solid #ddd;
      border-radius: 4px;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .related-thumbs img:hover {
      border-color: var(--primary);
      transform: scale(1.05);
    }

    .price-tag {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--primary);
    }

    .btn-add-to-cart {
      background: var(--primary);
      color: white;
      border: none;
      transition: all 0.3s ease;
    }

    .btn-add-to-cart:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
    }

    .badge-category {
      position: absolute;
      top: 10px;
      right: 10px;
      background: var(--secondary);
      color: white;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">DayrohTech Store</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link active" href="all_products.php">All Products</a></li>
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

  <!-- Main Content -->
  <div class="container py-5">
    <h1 class="text-center mb-5">All Products</h1>

    <!-- Category Filter -->
    <div class="category-filter">
      <h4 class="mb-3">Filter by Category:</h4>
      <div class="d-flex flex-wrap">
        <button class="btn category-btn active" data-category="all">All Products</button>
        <?php
        // Get distinct categories
        $categories_sql = "SELECT DISTINCT category FROM products WHERE category IS NOT NULL";
        $categories_result = $conn->query($categories_sql);

        if ($categories_result->num_rows > 0) {
          while ($category_row = $categories_result->fetch_assoc()) {
            echo '<button class="btn category-btn" data-category="'.htmlspecialchars(strtolower($category_row['category'])).'">'.
                 htmlspecialchars($category_row['category']).'</button>';
          }
        }
        ?>
      </div>
    </div>

    <!-- Product Grid -->
    <div class="row" id="productGrid">
      <?php
      // Updated: Simple query without related_images
      $sql = "SELECT * FROM products";
      $result = $conn->query($sql);

      if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
      ?>
      <div class="col-lg-3 col-md-4 col-sm-6 product-item" 
           data-category="<?= strtolower($row['category']) ?>">
        <div class="card product-card h-100">
          <img src="<?= $row['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>" style="height: 200px; object-fit: contain;">

          <span class="badge badge-category"><?= htmlspecialchars($row['category']) ?></span>

          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>
            <p class="price-tag">Ksh <?= number_format($row['price']) ?></p>

            <form action="cart.php" method="POST" class="mt-3">
              <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
              <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']) ?>">
              <input type="hidden" name="price" value="<?= $row['price'] ?>">
              <button type="submit" name="add_to_cart" class="btn btn-add-to-cart w-100">
                <i class="fas fa-cart-plus me-2"></i>Add to Cart
              </button>
            </form>
          </div>
        </div>
      </div>
      <?php
        endwhile;
      else:
        echo '<div class="col-12 text-center py-5"><h4>No products found.</h4></div>';
      endif;
      $conn->close();
      ?>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-4 mt-5">
    <div class="container">
      <p>&copy; <?php echo date("Y"); ?> DayrohTech Store | All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Category filter functionality
    document.querySelectorAll('.category-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        // Update active button
        document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const category = this.dataset.category;
        const productItems = document.querySelectorAll('.product-item');

        productItems.forEach(item => {
          if (category === 'all' || item.dataset.category === category) {
            item.style.display = 'block';
          } else {
            item.style.display = 'none';
          }
        });
      });
    });
  </script>
</body>
</html>
