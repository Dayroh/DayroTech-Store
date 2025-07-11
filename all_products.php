<?php 
session_start();
include 'config.php';
?>
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
      --header-height: 70px;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      color: var(--dark);
      padding-top: var(--header-height);
    }
<<<<<<< HEAD

=======
    
    /* Enhanced Header/Navbar */
    .navbar {
      background: rgba(44, 62, 80, 0.95) !important;
      backdrop-filter: blur(10px);
      height: var(--header-height);
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1030;
      transition: all 0.3s ease;
    }
    
    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
      background: linear-gradient(45deg, var(--secondary), var(--primary));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      transition: all 0.3s ease;
    }
    
    .navbar-brand:hover {
      transform: scale(1.02);
    }
    
    .nav-link {
      font-weight: 500;
      padding: 0.5rem 1rem;
      margin: 0 0.25rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
      position: relative;
    }
    
    .nav-link:not(.dropdown-toggle):before {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--secondary);
      transition: width 0.3s ease;
    }
    
    .nav-link:hover:not(.dropdown-toggle):before {
      width: 100%;
    }
    
    .nav-link.active {
      background: rgba(255,255,255,0.1);
    }
    
    .nav-link.active:before {
      width: 100%;
    }
    
    .user-greeting {
      color: white;
      font-weight: 500;
      margin-right: 1rem;
      display: flex;
      align-items: center;
    }
    
    .user-greeting i {
      margin-right: 0.5rem;
      color: var(--secondary);
    }
    
    .dropdown-menu {
      border: none;
      border-radius: 0.5rem;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      background: rgba(44, 62, 80, 0.95);
    }
    
    .dropdown-item {
      color: white;
      padding: 0.5rem 1rem;
      transition: all 0.2s ease;
    }
    
    .dropdown-item:hover {
      background: var(--primary);
      color: white;
    }
    
    .dropdown-divider {
      border-color: rgba(255,255,255,0.1);
    }
    
    /* Main Content */
>>>>>>> friend/main
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
      transform: translateY(-2px);
    }

    .product-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      margin-bottom: 2rem;
      position: relative;
      overflow: hidden;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
<<<<<<< HEAD

=======
    
    .product-card:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(108, 92, 231, 0.1) 0%, rgba(0, 206, 201, 0.05) 100%);
      opacity: 0;
      transition: opacity 0.3s ease;
      z-index: 0;
    }
    
    .product-card:hover:before {
      opacity: 1;
    }
    
>>>>>>> friend/main
    .card {
      border: none;
      border-radius: 0.75rem;
      overflow: hidden;
      height: 100%;
    }

    .carousel-control-prev, .carousel-control-next {
      background: rgba(0,0,0,0.2);
      width: 40px;
      height: 40px;
      border-radius: 50%;
      top: 50%;
      transform: translateY(-50%);
      transition: all 0.3s ease;
    }
    
    .carousel-control-prev:hover, .carousel-control-next:hover {
      background: rgba(0,0,0,0.4);
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
      flex-wrap: wrap;
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
      position: relative;
      overflow: hidden;
      z-index: 1;
    }

    .btn-add-to-cart:hover {
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(108, 92, 231, 0.3);
    }
    
    .btn-add-to-cart:after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, var(--primary), var(--secondary));
      z-index: -1;
      transition: transform 0.5s ease;
      transform: scaleX(0);
      transform-origin: right;
    }
    
    .btn-add-to-cart:hover:after {
      transform: scaleX(1);
      transform-origin: left;
    }

    .badge-category {
      position: absolute;
      top: 10px;
      right: 10px;
      background: var(--secondary);
      color: white;
      font-weight: 600;
      z-index: 2;
    }
    
    /* Footer */
    footer {
      background: linear-gradient(135deg, var(--dark) 0%, #1a1a1a 100%);
      color: white;
      padding: 2rem 0;
      position: relative;
    }
    
    footer:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjAzKSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgZmlsbD0idXJsKCNwYXR0ZXJuKSIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIvPjwvc3ZnPg==');
      opacity: 0.3;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 992px) {
      .navbar-collapse {
        background: rgba(44, 62, 80, 0.98);
        padding: 1rem;
        margin-top: 0.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      }
      
      .nav-link {
        margin: 0.25rem 0;
        padding: 0.75rem 1rem;
      }
      
      .nav-link:before {
        display: none;
      }
      
      .user-greeting {
        margin-right: 0;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        width: 100%;
      }
    }
    
    @media (max-width: 768px) {
      .product-card {
        margin-bottom: 1.5rem;
      }
      
      .category-filter {
        padding: 1rem;
      }
      
      .category-btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
      }
    }
  </style>
</head>
<body>
  <!-- Enhanced Header -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <i class="fas fa-laptop-code me-2"></i>DayrohTech
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i> Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="all_products.php"><i class="fas fa-boxes me-1"></i> Products</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="plans.php"><i class="fas fa-wifi me-1"></i> Internet Plans</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php"><i class="fas fa-envelope me-1"></i> Contact</a>
          </li>
        </ul>
        
        <ul class="navbar-nav ms-auto">
          <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                <i class="fas fa-user-circle me-1"></i>
                <?= htmlspecialchars($_SESSION['username'] ?? 'Account') ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><span class="dropdown-item disabled">Welcome back!</span></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item" href="my_orders.php">
                    <i class="fas fa-shopping-bag me-2"></i>My Orders
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="profile.php">
                    <i class="fas fa-user-cog me-2"></i>Profile Settings
                  </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item text-danger" href="logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link position-relative" href="view_cart.php">
                <i class="fas fa-shopping-cart"></i>
                <?php if (!empty($_SESSION['cart'])): ?>
                  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?= count($_SESSION['cart']) ?>
                  </span>
                <?php endif; ?>
              </a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="register.php"><i class="fas fa-user-plus me-1"></i> Register</a>
            </li>
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
      <h4 class="mb-3"><i class="fas fa-filter me-2"></i>Filter by Category:</h4>
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
        echo '<div class="col-12 text-center py-5"><div class="alert alert-info"><i class="fas fa-box-open fa-3x mb-3"></i><h4>No products found</h4><p>Check back later for new arrivals!</p></div></div>';
      endif;
      $conn->close();
      ?>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-4 mt-5">
    <div class="container">
      <div class="mb-3">
        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
        <a href="#" class="text-white"><i class="fab fa-whatsapp"></i></a>
      </div>
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
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  </script>
</body>
</html>
