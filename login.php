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

        // For now, compare plain text password
        if ($password === $user['password']) {

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
            $error = "❌ Incorrect password.";
        }
    } else {
        $error = "❌ No account found with that email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | DayrohTech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5" style="max-width: 500px;">
  <h2 class="mb-4 text-center">🔐 Login to DayrohTech</h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" autocomplete="off">
    <div class="mb-3">
      <label>Email:</label>
      <input type="email" name="email" class="form-control" required />
    </div>
    <div class="mb-3">
      <label>Password:</label>
      <input type="password" name="password" class="form-control" required />
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
    <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register</a></p>
  </form>
</div>

</body>
</html>
