<?php
session_start();
include 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Normalize inputs
    $email = strtolower(trim($_POST['email']));
    $password = trim($_POST['password']);

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM users WHERE LOWER(email) = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    // Check if user exists
    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();

        // Compare plain text password (change to password_verify in future)
        if ($password === $user['password']) {
            if ($user['is_verified'] == 0) {
                $error = "Please verify your email address before logging in.";
            } else {
                // Store session values
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect by role
                if ($user['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: user.php");
                }
                exit();
            }
        } else {
            $error = "Incorrect password";
        }
    } else {
        $error = "No account found with that email";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | DayrohTech</title>
  <link rel="icon" href="assets/images/logo.png" type="image/png">
  <!-- (Your existing CSS styles stay unchanged here...) -->
</head>
<body>
  <div class="login-container">
    <div class="login-header">
      <h1>Welcome Back</h1>
      <p>Sign in to access your DayrohTech account</p>
    </div>
    
    <div class="login-form">
      <?php if (!empty($error)): ?>
        <div class="error-message">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>
      
      <form method="POST" autocomplete="off">
        <div class="form-group">
          <label for="email">Email Address</label>
          <input 
            type="email" 
            id="email" 
            name="email" 
            class="form-control" 
            placeholder="Enter your email"
            required
          >
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <div class="password-container">
            <input 
              type="password" 
              id="password" 
              name="password" 
              class="form-control" 
              placeholder="Enter your password"
              required
            >
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
          </div>
        </div>
        
        <button type="submit" class="btn btn-primary">
          <span>Login</span>
          <span>→</span>
        </button>
      </form>
      
      <div class="login-footer">
        <p>Don't have an account? <a href="register.php">Create one</a></p>
        <p><a href="forgot-password.php">Forgot password?</a></p>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      const passwordField = document.getElementById('password');
      const toggleIcon = document.querySelector('.toggle-password');
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.textContent = '👁️';
      } else {
        passwordField.type = 'password';
        toggleIcon.textContent = '👁️';
      }
    }

    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.querySelector('label').style.color = 'var(--primary)';
      });
      input.addEventListener('blur', function() {
        this.parentElement.querySelector('label').style.color = 'var(--dark)';
      });
    });
  </script>
</body>
</html>
