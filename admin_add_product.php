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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Product | Admin - DayrohTech</title>
  <link rel="icon" href="assets/images/logo.png" type="image/png">
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
      --success: #00b894;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
      color: var(--dark);
    }

    .admin-container {
      max-width: 800px;
      margin: 2rem auto;
      background: white;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      overflow: hidden;
    }

    .admin-header {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
      padding: 1.5rem;
      position: relative;
    }

    .admin-header h2 {
      font-weight: 700;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .admin-header:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgZmlsbD0idXJsKCNwYXR0ZXJuKSIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIvPjwvc3ZnPg==');
      opacity: 0.2;
    }

    .admin-body {
      padding: 2rem;
    }

    .form-label {
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: var(--dark);
    }

    .form-control, .form-select {
      border-radius: 8px;
      padding: 12px 15px;
      border: 2px solid #e0e0e0;
      transition: all 0.3s ease;
      margin-bottom: 1.5rem;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.25rem rgba(108, 92, 231, 0.15);
    }

    textarea.form-control {
      min-height: 120px;
      resize: vertical;
    }

    .btn-submit {
      background: linear-gradient(135deg, var(--success) 0%, #00a884 100%);
      border: none;
      color: white;
      padding: 12px 24px;
      font-weight: 600;
      border-radius: 8px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 184, 148, 0.3);
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 184, 148, 0.4);
    }

    .btn-cancel {
      background: white;
      border: 2px solid #e0e0e0;
      color: var(--dark);
      padding: 12px 24px;
      font-weight: 600;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .btn-cancel:hover {
      background: #f8f9fa;
      border-color: #d0d0d0;
    }

    .image-preview {
      width: 100%;
      height: 200px;
      background-color: #f8f9fa;
      border-radius: 8px;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      border: 2px dashed #e0e0e0;
      transition: all 0.3s ease;
    }

    .image-preview img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
      display: none;
    }

    .image-preview-placeholder {
      text-align: center;
      color: #999;
    }

    .image-preview-placeholder i {
      font-size: 3rem;
      margin-bottom: 1rem;
      color: var(--primary);
      opacity: 0.5;
    }

    .input-group {
      position: relative;
    }

    .input-group .form-control {
      padding-left: 40px;
    }

    .input-icon {
      position: absolute;
      top: 50%;
      left: 15px;
      transform: translateY(-50%);
      color: var(--primary);
      z-index: 2;
    }

    .price-input {
      position: relative;
    }

    .price-input:before {
      content: 'Ksh';
      position: absolute;
      top: 50%;
      left: 15px;
      transform: translateY(-50%);
      color: var(--primary);
      font-weight: 600;
      z-index: 2;
    }

    .price-input input {
      padding-left: 60px !important;
    }

    @media (max-width: 768px) {
      .admin-container {
        margin: 1rem;
      }
      
      .admin-body {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="admin-container">
    <div class="admin-header">
      <h2><i class="fas fa-plus-circle"></i> Add New Product</h2>
    </div>
    
    <div class="admin-body">
      <form method="POST" action="">
        <div class="mb-3">
          <label class="form-label">Product Name</label>
          <div class="input-group">
            <i class="fas fa-tag input-icon"></i>
            <input type="text" name="name" class="form-control" required placeholder="Enter product name" />
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Brand</label>
            <select name="brand" class="form-select" required>
              <option value="">-- Select Brand --</option>
              <option value="HP">HP</option>
              <option value="Lenovo">Lenovo</option>
              <option value="Dell">Dell</option>
              <option value="Acer">Acer</option>
              <option value="Asus">Asus</option>
              <option value="Apple">Apple</option>
            </select>
          </div>
          
          <div class="col-md-6 mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select" required>
              <option value="">-- Select Category --</option>
              <option value="laptop">Laptop</option>
              <option value="desktop">Desktop</option>
              <option value="tablet">Tablet</option>
              <option value="accessory">Accessory</option>
              <option value="component">Component</option>
            </select>
          </div>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Price</label>
          <div class="price-input">
            <input type="number" name="price" class="form-control" required placeholder="Enter price" min="0" step="100" />
          </div>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Image URL</label>
          <div class="input-group">
            <i class="fas fa-image input-icon"></i>
            <input type="text" name="image" id="imageUrl" class="form-control" required placeholder="Paste image URL" />
          </div>
          
          <div class="image-preview" id="imagePreview">
            <div class="image-preview-placeholder">
              <i class="fas fa-image"></i>
              <p>Image preview will appear here</p>
            </div>
            <img id="previewImage" src="" alt="Preview">
          </div>
        </div>
        
        <div class="mb-4">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" required placeholder="Enter detailed product description"></textarea>
        </div>
        
        <div class="d-flex gap-3">
          <button type="submit" class="btn btn-submit flex-grow-1">
            <i class="fas fa-save me-2"></i> Add Product
          </button>
          <a href="admin_products.php" class="btn btn-cancel">
            <i class="fas fa-times me-2"></i> Cancel
          </a>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Image preview functionality
    const imageUrlInput = document.getElementById('imageUrl');
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    const placeholder = document.querySelector('.image-preview-placeholder');

    imageUrlInput.addEventListener('input', function() {
      const url = this.value.trim();
      
      if (url) {
        previewImage.src = url;
        previewImage.onload = function() {
          placeholder.style.display = 'none';
          previewImage.style.display = 'block';
          imagePreview.style.borderColor = '#00b894';
        };
        previewImage.onerror = function() {
          placeholder.style.display = 'flex';
          previewImage.style.display = 'none';
          imagePreview.style.borderColor = '#ff7675';
        };
      } else {
        placeholder.style.display = 'flex';
        previewImage.style.display = 'none';
        imagePreview.style.borderColor = '#e0e0e0';
      }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
      const price = document.querySelector('input[name="price"]').value;
      if (price <= 0) {
        alert('Price must be greater than 0');
        e.preventDefault();
      }
      
      const imageUrl = document.querySelector('input[name="image"]').value;
      if (!imageUrl) {
        alert('Please provide an image URL');
        e.preventDefault();
      }
    });

    // Add animation to form elements on focus
    const inputs = document.querySelectorAll('.form-control, .form-select');
    inputs.forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'translateY(-2px)';
      });
      
      input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'translateY(0)';
      });
    });
  </script>
</body>
</html>