<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];  // store plain password


  $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
 $stmt->bind_param("sss", $name, $email, $password);
  if ($stmt->execute()) {
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['user_name'] = $name;
    header("Location: index.php");
    exit();
  } else {
    $error = "Email already exists.";
  }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
  <h3>Create an Account</h3>
  <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
  <form method="POST">
    <input type="text" name="name" placeholder="Name" class="form-control mb-2" required>
    <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
    <input type="password" name="password" placeholder="Password" class="form-control mb-3" required>
    <button class="btn btn-primary">Register</button>
  </form>
</div>
</body>
</html>
