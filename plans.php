<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: login.php");
  exit();
}

$result = $conn->query("SELECT * FROM plan_requests ORDER BY request_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Plan Requests</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
  <h3>📥 User Plan Requests</h3>

  <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">✅ Plan request deleted successfully.</div>
  <?php endif; ?>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered mt-4">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Package</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['package']) ?></td>
            <td><?= $row['request_date'] ?></td>
            <td>
              <a href="delete.php?type=plan&id=<?= $row['id'] ?>" 
                 class="btn btn-sm btn-danger" 
                 onclick="return confirm('Delete this request?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info text-center">No plan requests found.</div>
  <?php endif; ?>
</div>
</body>
</html>
