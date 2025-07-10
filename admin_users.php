<?php
session_start();
include 'config.php';

// Ensure only admin can access
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Unauthorized access.");
}

$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>All Users | Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    :root {
      --primary: #4361ee;
      --secondary: #3f37c9;
      --success: #4cc9f0;
      --danger: #f72585;
      --dark: #212529;
      --light: #f8f9fa;
      --admin: #7209b7;
      --user: #4895ef;
    }
    
    body {
      background-color: #f3f6fd;
      font-family: 'Segoe UI', Roboto, system-ui, sans-serif;
    }
    
    .container {
      max-width: 1200px;
      padding: 2rem;
    }
    
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid rgba(0,0,0,0.1);
    }
    
    .page-title {
      font-weight: 700;
      color: var(--dark);
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 1.8rem;
    }
    
    .table-container {
      background: white;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.05);
      overflow: hidden;
    }
    
    .table {
      margin-bottom: 0;
      border-collapse: separate;
      border-spacing: 0;
    }
    
    .table thead {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
    }
    
    .table th {
      font-weight: 600;
      letter-spacing: 0.5px;
      padding: 1.2rem 1rem;
      color: white;
      text-transform: uppercase;
      font-size: 0.75rem;
      border: none;
    }
    
    .table td {
      padding: 1.2rem 1rem;
      vertical-align: middle;
      border-bottom: 1px solid #f0f2f7;
    }
    
    .table tbody tr:last-child td {
      border-bottom: none;
    }
    
    .table tbody tr:hover {
      background-color: #f8faff;
      transform: scale(1.002);
      box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
      transition: all 0.2s ease;
    }
    
    .role-badge {
      padding: 0.35rem 0.7rem;
      border-radius: 50px;
      font-weight: 600;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .role-admin {
      background-color: rgba(114, 9, 183, 0.1);
      color: var(--admin);
      border: 1px solid rgba(114, 9, 183, 0.2);
    }
    
    .role-user {
      background-color: rgba(72, 149, 239, 0.1);
      color: var(--user);
      border: 1px solid rgba(72, 149, 239, 0.2);
    }
    
    .password-cell {
      font-family: monospace;
      font-size: 0.85rem;
      color: #6c757d;
      max-width: 150px;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    
    .action-btns {
      display: flex;
      gap: 0.5rem;
    }
    
    .btn-sm {
      padding: 0.4rem 0.8rem;
      font-size: 0.8rem;
      border-radius: 6px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
    }
    
    .btn-danger {
      background-color: var(--danger);
      border-color: var(--danger);
      box-shadow: 0 2px 6px rgba(247, 37, 133, 0.2);
    }
    
    .btn-danger:hover {
      background-color: #e51778;
      border-color: #e51778;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(247, 37, 133, 0.3);
    }
    
    .alert-success {
      background-color: rgba(76, 201, 240, 0.1);
      border-color: rgba(76, 201, 240, 0.2);
      color: #0a9396;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .empty-state {
      padding: 3rem;
      text-align: center;
    }
    
    .empty-state-icon {
      font-size: 4rem;
      color: #e9ecef;
      margin-bottom: 1rem;
    }
    
    .empty-state-title {
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: var(--dark);
    }
    
    .empty-state-text {
      color: #6c757d;
      max-width: 500px;
      margin: 0 auto 1.5rem;
    }
    
    .date-cell {
      white-space: nowrap;
    }
    
    @media (max-width: 768px) {
      .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
      
      .container {
        padding: 1rem;
      }
      
      .page-title {
        font-size: 1.4rem;
      }
      
      .action-btns {
        flex-direction: column;
      }
      
      .btn-sm {
        width: 100%;
        justify-content: center;
      }
    }
    
    /* Animation for new elements */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .table tbody tr {
      animation: fadeIn 0.3s ease forwards;
    }
    
    .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
    .table tbody tr:nth-child(2) { animation-delay: 0.2s; }
    .table tbody tr:nth-child(3) { animation-delay: 0.3s; }
    /* Continue as needed... */
  </style>
</head>
<body>
<div class="container">
  <div class="page-header">
    <h2 class="page-title">
      <i class="bi bi-people-fill"></i> Registered Users
    </h2>
  </div>

  <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">
      <i class="bi bi-check-circle-fill"></i>
      User deleted successfully.
    </div>
  <?php endif; ?>

  <div class="table-container">
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Created At</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td class="password-cell" title="<?= htmlspecialchars($row['password']) ?>">
                  ••••••••
                </td>
                <td class="date-cell"><?= date('M j, Y', strtotime($row['created_at'])) ?></td>
                <td>
                  <span class="role-badge role-<?= $row['role'] === 'admin' ? 'admin' : 'user' ?>">
                    <?= htmlspecialchars($row['role']) ?>
                  </span>
                </td>
                <td>
                  <div class="action-btns">
                    <a href="delete.php?type=user&id=<?= $row['id'] ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to permanently delete user:\n\nName: <?= addslashes(htmlspecialchars($row['name'])) ?>\nEmail: <?= addslashes(htmlspecialchars($row['email'])) ?>\n\nThis action cannot be undone!')">
                      <i class="bi bi-trash"></i> Delete
                    </a>
                  </div>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="7">
                <div class="empty-state">
                  <div class="empty-state-icon">
                    <i class="bi bi-people"></i>
                  </div>
                  <h5 class="empty-state-title">No Users Found</h5>
                  <p class="empty-state-text">There are currently no registered users in the system.</p>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Enhanced interactive elements
  document.addEventListener('DOMContentLoaded', function() {
    // Password reveal on hover
    const passwordCells = document.querySelectorAll('.password-cell');
    passwordCells.forEach(cell => {
      cell.addEventListener('mouseenter', function() {
        this.textContent = this.getAttribute('title');
      });
      cell.addEventListener('mouseleave', function() {
        this.textContent = '••••••••';
      });
    });
    
    // Enhanced delete confirmation
    const deleteButtons = document.querySelectorAll('.btn-danger');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        const userName = this.closest('tr').querySelector('td:nth-child(2)').textContent;
        const userEmail = this.closest('tr').querySelector('td:nth-child(3)').textContent;
        
        if (!confirm(`WARNING: User Deletion\n\nName: ${userName}\nEmail: ${userEmail}\n\nThis will permanently delete the user account and all associated data.\n\nAre you absolutely sure?`)) {
          e.preventDefault();
        }
      });
    });
    
    // Add animation to table rows
    const rows = document.querySelectorAll('.table tbody tr');
    rows.forEach((row, index) => {
      row.style.animationDelay = `${index * 0.1}s`;
    });
  });
</script>
</body>
</html>