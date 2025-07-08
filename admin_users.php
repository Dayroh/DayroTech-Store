<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'config.php';

$sql = "SELECT id, name, email, role FROM users ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Users | DayrohTech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2 class="mb-4">👥 Registered Users</h2>
  <a href="admin_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows > 0): 
        while ($user = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $user['id'] ?></td>
          <td><?= htmlspecialchars($user['name']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= ucfirst($user['role']) ?></td>
        </tr>
      <?php endwhile; else: ?>
        <tr><td colspan="4" class="text-center">No users found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
