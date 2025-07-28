<?php
session_start();
require 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    $confirm_password = $_POST["confirm_password"] ?? '';

    // Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['status'] = "All fields are required.";
        header("Location: register.php"); // adjust this to your actual form page
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Invalid email format.";
        header("Location: register.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['status'] = "Passwords do not match.";
        header("Location: register.php");
        exit();
    }

    // Check if user already exists
    $check_query = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_query->bind_param("s", $email);
    $check_query->execute();
    $check_query->store_result();

    if ($check_query->num_rows > 0) {
        $_SESSION['status'] = "Email already registered.";
        header("Location: register.php");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $verify_token = md5(uniqid($email, true));

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, verify_token, is_verified, email_verified) VALUES (?, ?, ?, ?, 0, 0)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $verify_token);

    if ($stmt->execute()) {
        // Send verification email
        $mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // your SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'kendayroh1@gmail.com'; // your Gmail address
            $mail->Password   = 'chej piuz elqp lxxu';     // your Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('youremail@gmail.com', 'DayrohTech');
            $mail->addAddress($email, $name);
            $mail->isHTML(true);

            $mail->Subject = "Verify your email address";
            $mail->Body    = "
                <h2>Welcome to DayrohTech</h2>
                <p>Please click the link below to verify your email:</p>
              <a href='https://dayrotech-store-production.up.railway.app/verify.php?token=$verify_token'>Verify Email</a>


            ";

            $mail->send();
            
           header("Location: register_success.php");
exit;

        } catch (Exception $e) {
            $_SESSION['status'] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            header("Location: register.php");
            exit();
        }
    } else {
        $_SESSION['status'] = "Registration failed. Please try again.";
        header("Location: register.php");
        exit();
    }
}
?>


<!-- Your HTML code remains unchanged below -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DayrohTech Store</title>
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
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }

        .register-container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        .register-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
        }

        .register-header h3 {
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .register-header:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgZmlsbD0idXJsKCNwYXR0ZXJuKSIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIvPjwvc3ZnPg==');
            opacity: 0.3;
        }

        .register-body {
            padding: 30px;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(108, 92, 231, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
            display: block;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: var(--primary);
            z-index: 2;
        }

        .input-with-icon {
            padding-left: 45px;
        }

        .btn-register {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: none;
            color: white;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 92, 231, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(108, 92, 231, 0.4);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .btn-register:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary) 100%);
            z-index: -1;
            transition: opacity 0.3s ease;
            opacity: 0;
        }

        .btn-register:hover:after {
            opacity: 1;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
            z-index: 2;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .login-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .alert-danger {
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            background-color: rgba(214, 48, 49, 0.1);
            border: 1px solid rgba(214, 48, 49, 0.2);
            color: var(--danger);
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .floating-shapes div {
            position: absolute;
            border-radius: 50%;
            background: rgba(108, 92, 231, 0.1);
            animation: float 15s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="register-container">
                    <div class="register-header">
                        <h3><i class="fas fa-user-plus me-2"></i>Create an Account</h3>
                    </div>
                    <div class="register-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                            </div>
                        <?php endif; ?>
<?php
if (isset($_SESSION['status'])) {
    echo '<div class="alert alert-success">'.$_SESSION['status'].'</div>';
    unset($_SESSION['status']);
}
?>

                        <form method="POST">
                            <div class="input-group">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" name="name" placeholder="Full Name" class="form-control input-with-icon" required>
                            </div>

                            <div class="input-group">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" name="email" placeholder="Email Address" class="form-control input-with-icon" required>
                            </div>

                            <div class="input-group">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" name="password" id="password" placeholder="Password" class="form-control input-with-icon" required>
                                <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                            </div>
<div class="input-group">
    <i class="fas fa-lock input-icon"></i>
    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-control input-with-icon" required>
</div>
                            <button type="submit" class="btn btn-register">
                                <i class="fas fa-user-plus me-2"></i>Register
                            </button>
                        </form>

                        <div class="login-link">
                            Already have an account? <a href="login.php">Login here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating background shapes -->
    <div class="floating-shapes">
        <div style="width: 80px; height: 80px; left: 5%; animation-duration: 20s;"></div>
        <div style="width: 150px; height: 150px; left: 70%; animation-duration: 25s;"></div>
        <div style="width: 60px; height: 60px; left: 40%; animation-duration: 18s;"></div>
        <div style="width: 100px; height: 100px; left: 30%; animation-duration: 22s;"></div>
        <div style="width: 120px; height: 120px; left: 80%; animation-duration: 30s;"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.querySelector('#password').value;
            if (password.length < 6) {
                alert('Password must be at least 6 characters long');
                e.preventDefault();
            }
        });

        // Floating shapes animation
        function createFloatingShapes() {
            const container = document.querySelector('.floating-shapes');
            const colors = ['rgba(108, 92, 231, 0.1)', 'rgba(0, 206, 201, 0.1)', 'rgba(253, 121, 168, 0.1)'];
            
            for (let i = 0; i < 8; i++) {
                const shape = document.createElement('div');
                const size = Math.random() * 100 + 50;
                const duration = Math.random() * 20 + 15;
                const delay = Math.random() * 5;
                const left = Math.random() * 100;
                
                shape.style.width = `${size}px`;
                shape.style.height = `${size}px`;
                shape.style.left = `${left}%`;
                shape.style.bottom = `-${size}px`;
                shape.style.animationDuration = `${duration}s`;
                shape.style.animationDelay = `${delay}s`;
                shape.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                
                container.appendChild(shape);
            }
        }

        createFloatingShapes();
    </script>
</body>
</html>
