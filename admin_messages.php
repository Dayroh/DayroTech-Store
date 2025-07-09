<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: login.php");
  exit();
}

// Get contact messages
$messages_result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");

// Get plan requests
$plans_result = $conn->query("SELECT * FROM plan_requests ORDER BY request_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Messages | DayrohTech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
  <h3 class="mb-4">📩 Contact Messages</h3>

  <?php if ($messages_result->num_rows > 0): ?>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Message</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; while ($row = $messages_result->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
              <a href="delete.php?type=message&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this message?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No contact messages found.</div>
  <?php endif; ?>

  <hr class="my-5">

  <h3 class="mb-4">📦 Plan Requests</h3>

  <?php if ($plans_result->num_rows > 0): ?>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Package</th>
          <th>Date Requested</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $j = 1; while ($row = $plans_result->fetch_assoc()): ?>
          <tr>
            <td><?= $j++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['package']) ?></td>
            <td><?= $row['request_date'] ?></td>
            <td>
              <a href="delete.php?type=plan&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this plan request?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">No plan requests received yet.</div>
  <?php endif; ?>
</div>
</body>
</html>
