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

        // ✅ Check if verified
        if ($user['is_verified'] != 1) {
            $error = "Please verify your email before logging in.";
        }
        // ✅ Check password
        elseif (password_verify($password, $user['password'])) {
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
      <style>
    :root {
      --primary: #4361ee;
      --primary-light: #4895ef;
      --secondary: #3f37c9;
      --dark: #1b263b;
      --light: #f8f9fa;
      --danger: #ef233c;
      --success: #4cc9f0;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', system-ui, -apple-system, sans-serif;
    }
    
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    
    .login-container {
      width: 100%;
      max-width: 420px;
      background: white;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: all 0.3s ease;
      animation: fadeIn 0.5s ease-out;
    }
    
    .login-header {
      background: linear-gradient(to right, var(--primary), var(--secondary));
      color: white;
      padding: 30px;
      text-align: center;
      position: relative;
    }
    
    .login-header h1 {
      font-size: 1.8rem;
      font-weight: 600;
      margin-bottom: 5px;
    }
    
    .login-header p {
      opacity: 0.9;
      font-size: 0.9rem;
    }
    
    .login-header::after {
      content: '';
      position: absolute;
      bottom: -20px;
      left: 50%;
      transform: translateX(-50%);
      width: 40px;
      height: 40px;
      background: white;
      border-radius: 50%;
      box-shadow: 0 -5px 10px rgba(0, 0, 0, 0.1);
    }
    
    .login-form {
      padding: 30px;
    }
    
    .form-group {
      margin-bottom: 25px;
      position: relative;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: var(--dark);
      font-size: 0.9rem;
    }
    
    .form-control {
      width: 100%;
      padding: 15px 20px;
      border: 2px solid #e9ecef;
      border-radius: 10px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background-color: #f8f9fa;
    }
    
    .form-control:focus {
      border-color: var(--primary-light);
      background: white;
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
      outline: none;
    }
    
    .password-container {
      position: relative;
    }
    
    .toggle-password {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: var(--dark);
      opacity: 0.6;
      transition: all 0.3s ease;
    }
    
    .toggle-password:hover {
      opacity: 1;
      color: var(--primary);
    }
    
    .btn {
      width: 100%;
      padding: 15px;
      border: none;
      border-radius: 10px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }
    
    .btn-primary {
      background: linear-gradient(to right, var(--primary), var(--secondary));
      color: white;
      box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
    }
    
    .btn-primary:active {
      transform: translateY(0);
    }
    
    .error-message {
      color: var(--danger);
      background: rgba(239, 35, 60, 0.1);
      padding: 12px 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      animation: shake 0.5s;
    }
    
    .error-message::before {
      content: '⚠';
      font-size: 1.2rem;
    }
    
    .login-footer {
      text-align: center;
      margin-top: 20px;
      font-size: 0.9rem;
      color: #6c757d;
    }
    
    .login-footer a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    
    .login-footer a:hover {
      color: var(--secondary);
      text-decoration: underline;
    }
    
    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      20%, 60% { transform: translateX(-5px); }
      40%, 80% { transform: translateX(5px); }
    }
    
    /* Responsive */
    @media (max-width: 480px) {
      .login-container {
        border-radius: 15px;
      }
      
      .login-header {
        padding: 25px 20px;
      }
      
      .login-form {
        padding: 25px 20px;
      }
    }
  </style>

</head>
<body>
  <div class="login-container">
    <div class="login-header">
      <h1>Welcome Back</h1>
      <p>Sign in to access your DayrohTech account</p>
    </div>
<?php if (isset($_SESSION['status'])): ?>
  <div style="color:red"><?= $_SESSION['status']; unset($_SESSION['status']); ?></div>
<?php endif; ?>



    <div class="login-form">
      <?php if (!empty($error)): ?>
        <div class="error-message">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>
      
   <form method="POST" action="" autocomplete="off">

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
     <p style="margin-top:10px;">
    Didn't get the verification email?
    <a href="resend-verification.php" style="color:blue;">Resend it here</a>
</p>

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
