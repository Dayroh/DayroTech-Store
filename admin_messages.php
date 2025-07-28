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
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | DayrohTech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #4e73df;
      --secondary-color: #f8f9fc;
      --accent-color: #2e59d9;
      --dark-color: #5a5c69;
    }
    
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .container {
      max-width: 1200px;
    }
    
    .card {
      border-radius: 0.5rem;
      box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
      border: none;
      margin-bottom: 2rem;
    }
    
    .card-header {
      background-color: var(--primary-color);
      color: white;
      border-radius: 0.5rem 0.5rem 0 0 !important;
      padding: 1rem 1.5rem;
    }
    
    .table-responsive {
      overflow-x: auto;
    }
    
    .table {
      margin-bottom: 0;
    }
    
    .table th {
      background-color: var(--secondary-color);
      color: var(--dark-color);
      font-weight: 600;
      border-bottom-width: 1px;
    }
    
    .badge {
      padding: 0.35em 0.65em;
      font-weight: 500;
    }
    
    .btn-action {
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
    }
    
    .message-preview {
      max-width: 300px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      cursor: pointer;
    }
    
    .message-full {
      display: none;
      white-space: pre-wrap;
    }
    
    .tab-content {
      padding: 0;
      background: white;
      border-left: 1px solid #dee2e6;
      border-right: 1px solid #dee2e6;
      border-bottom: 1px solid #dee2e6;
      border-radius: 0 0 0.5rem 0.5rem;
    }
    
    .nav-tabs .nav-link {
      color: var(--dark-color);
      border: none;
      padding: 0.75rem 1.5rem;
    }
    
    .nav-tabs .nav-link.active {
      color: var(--primary-color);
      background-color: white;
      border-bottom: 3px solid var(--primary-color);
    }
    
    .status-badge {
      display: inline-block;
      padding: 0.35em 0.65em;
      border-radius: 0.25rem;
    }
    
    .status-pending {
      background-color: #fff3cd;
      color: #856404;
    }
    
    .status-completed {
      background-color: #d4edda;
      color: #155724;
    }
    
    .search-box {
      position: relative;
      margin-bottom: 1rem;
    }
    
    .search-box input {
      padding-left: 2.5rem;
      border-radius: 0.25rem;
    }
    
    .search-box i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d;
    }
    
    .empty-state {
      text-align: center;
      padding: 3rem;
      color: #6c757d;
    }
    
    .empty-state i {
      font-size: 3rem;
      margin-bottom: 1rem;
      color: #dee2e6;
    }
    
    .pagination .page-item.active .page-link {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .pagination .page-link {
      color: var(--primary-color);
    }
    
    .action-buttons .btn {
      margin-right: 0.5rem;
    }
  </style>
</head>
<body>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h2>
      <a href="logout.php" class="btn btn-outline-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
    
    <ul class="nav nav-tabs" id="adminTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab">
          <i class="bi bi-envelope me-1"></i> Messages (<?= $messages_result->num_rows ?>)
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="plans-tab" data-bs-toggle="tab" data-bs-target="#plans" type="button" role="tab">
          <i class="bi bi-box-seam me-1"></i> Plan Requests (<?= $plans_result->num_rows ?>)
        </button>
      </li>
    </ul>
    
    <div class="tab-content" id="adminTabsContent">
      <div class="tab-pane fade show active" id="messages" role="tabpanel">
        <div class="p-4">
          <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="messageSearch" class="form-control" placeholder="Search messages...">
          </div>
          
          <?php if ($messages_result->num_rows > 0): ?>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th width="50">#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th width="120">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1; while ($row = $messages_result->fetch_assoc()): ?>
                    <tr>
                      <td><?= $i++ ?></td>
                      <td><?= htmlspecialchars($row['name']) ?></td>
                      <td><a href="mailto:<?= htmlspecialchars($row['email']) ?>"><?= htmlspecialchars($row['email']) ?></a></td>
                      <td>
                        <div class="message-preview" onclick="toggleMessage(this)">
                          <?= htmlspecialchars($row['message']) ?>
                        </div>
                        <div class="message-full">
                          <?= nl2br(htmlspecialchars($row['message'])) ?>
                        </div>
                      </td>
                      <td><?= date('M j, Y g:i A', strtotime($row['created_at'])) ?></td>
                      <td class="action-buttons">
                        <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="btn btn-sm btn-outline-primary btn-action" title="Reply">
                          <i class="bi bi-reply"></i>
                        </a>
                        <a href="delete.php?type=message&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger btn-action" title="Delete" onclick="return confirm('Delete this message?')">
                          <i class="bi bi-trash"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
            
            <nav aria-label="Messages pagination">
              <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                  <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                  <a class="page-link" href="#">Next</a>
                </li>
              </ul>
            </nav>
          <?php else: ?>
            <div class="empty-state">
              <i class="bi bi-envelope-open"></i>
              <h4>No messages yet</h4>
              <p>All contact messages will appear here when received.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
      
      <div class="tab-pane fade" id="plans" role="tabpanel">
        <div class="p-4">
          <div class="d-flex justify-content-between mb-3">
            <div class="search-box" style="flex-grow: 1; margin-right: 1rem;">
              <i class="bi bi-search"></i>
              <input type="text" id="planSearch" class="form-control" placeholder="Search plan requests...">
            </div>
            <div class="dropdown">
              <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                <i class="bi bi-funnel"></i> Filter
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">All Requests</a></li>
                <li><a class="dropdown-item" href="#">Pending</a></li>
                <li><a class="dropdown-item" href="#">Completed</a></li>
              </ul>
            </div>
          </div>
          
          <?php if ($plans_result->num_rows > 0): ?>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th width="50">#</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Package</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th width="150">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $j = 1; while ($row = $plans_result->fetch_assoc()): ?>
                    <tr>
                      <td><?= $j++ ?></td>
                      <td><?= htmlspecialchars($row['name']) ?></td>
                      <td>
                        <div><a href="mailto:<?= htmlspecialchars($row['email']) ?>"><?= htmlspecialchars($row['email']) ?></a></div>
                        <small class="text-muted"><?= htmlspecialchars($row['phone']) ?></small>
                      </td>
                      <td>
                        <span class="badge bg-primary"><?= htmlspecialchars($row['package']) ?></span>
                      </td>
                      <td><?= date('M j, Y', strtotime($row['request_date'])) ?></td>
                      <td>
                        <span class="status-badge status-pending">Pending</span>
                      </td>
                      <td class="action-buttons">
                        <button class="btn btn-sm btn-outline-success btn-action" title="Mark Complete">
                          <i class="bi bi-check-circle"></i>
                        </button>
                        <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="btn btn-sm btn-outline-primary btn-action" title="Contact">
                          <i class="bi bi-envelope"></i>
                        </a>
                        <a href="delete.php?type=plan&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger btn-action" title="Delete" onclick="return confirm('Delete this plan request?')">
                          <i class="bi bi-trash"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
            
            <nav aria-label="Plan requests pagination">
              <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                  <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                  <a class="page-link" href="#">Next</a>
                </li>
              </ul>
            </nav>
          <?php else: ?>
            <div class="empty-state">
              <i class="bi bi-box-seam"></i>
              <h4>No plan requests yet</h4>
              <p>All plan requests will appear here when received.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle message preview/full view
    function toggleMessage(element) {
      const preview = element;
      const full = element.nextElementSibling;
      
      if (preview.style.display === 'none') {
        preview.style.display = '';
        full.style.display = 'none';
      } else {
        preview.style.display = 'none';
        full.style.display = '';
      }
    }
    
    // Search functionality
    document.getElementById('messageSearch').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const rows = document.querySelectorAll('#messages tbody tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
      });
    });
    
    document.getElementById('planSearch').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const rows = document.querySelectorAll('#plans tbody tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
      });
    });
    
    // Mark plan as complete
    document.querySelectorAll('#plans .btn-outline-success').forEach(btn => {
      btn.addEventListener('click', function() {
        const statusBadge = this.closest('tr').querySelector('.status-badge');
        statusBadge.classList.remove('status-pending');
        statusBadge.classList.add('status-completed');
        statusBadge.textContent = 'Completed';
        this.disabled = true;
        this.classList.remove('btn-outline-success');
        this.classList.add('btn-success');
      });
    });
  </script>
</body>
</html>