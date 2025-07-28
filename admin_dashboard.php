<?php
session_start();

// Redirect if not logged in or not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require_once 'config.php';
$adminName = $_SESSION['user_name'];

// Check if we're exporting data
$export = isset($_GET['export']) ? $_GET['export'] : false;
$report_date = isset($_GET['report_date']) ? $_GET['report_date'] : date('Y-m-d');

// Get stats for dashboard
$stats = [];
$queries = [
    'users' => "SELECT COUNT(*) FROM users",
    'products' => "SELECT COUNT(*) FROM products",
    'orders' => "SELECT COUNT(*) FROM orders",
    'revenue' => "SELECT SUM(total_amount) FROM orders"
];

foreach ($queries as $key => $query) {
    try {
        $result = $conn->query($query);
        if ($result) {
            $stats[$key] = $result->fetch_row()[0] ?? 0;
        } else {
            $stats[$key] = 0;
            error_log("Query failed: " . $conn->error);
        }
    } catch (Exception $e) {
        $stats[$key] = 0;
        error_log("Database error: " . $e->getMessage());
    }
}

// Get sales data for the selected date
$dailySales = [];
$dailySalesQuery = $conn->prepare("
    SELECT 
        HOUR(order_date) AS hour,
        COUNT(*) AS order_count,
        SUM(total_price) AS total_amount
    FROM orders
    WHERE DATE(order_date) = ?
    GROUP BY HOUR(order_date)
    ORDER BY hour
");
if ($dailySalesQuery) {
    $dailySalesQuery->bind_param('s', $report_date);
    $dailySalesQuery->execute();
    $dailySalesResult = $dailySalesQuery->get_result();

    while ($row = $dailySalesResult->fetch_assoc()) {
        $dailySales[] = $row;
    }
} else {
    error_log("Error preparing daily sales query: " . $conn->error);
}

// Get recent activities
$activities = [];
try {
    $result = $conn->query("
        SELECT activity_type, description, created_at 
        FROM admin_activities 
        ORDER BY created_at DESC 
        LIMIT 8
    ");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }
    }
} catch (Exception $e) {
    error_log("Error fetching activities: " . $e->getMessage());
}

// Get recent orders
$recentOrders = [];
try {
    $result = $conn->query("
        SELECT o.order_id, u.username as name, o.total_amount, o.created_at 
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        ORDER BY o.created_at DESC 
        LIMIT 5
    ");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $recentOrders[] = $row;
        }
    }
} catch (Exception $e) {
    error_log("Error fetching recent orders: " . $e->getMessage());
}

// Handle Excel export
if ($export === 'excel') {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="sales_report_'.date('Y-m-d').'.xls"');
    
    // Create Excel data
    $excelData = "Hour\tOrders\tTotal Amount\n";
    foreach ($dailySales as $sale) {
        $excelData .= $sale['hour'].":00\t".$sale['order_count']."\t".$sale['total_amount']."\n";
    }
    
    echo $excelData;
    exit();
}

// Handle print view
if (isset($_GET['print'])) {
    // Print-specific view
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Sales Report | DayrohTech</title>
        <link rel="icon" href="assets/images/logo.png" type="image/png">
        <style>
            body { font-family: Arial, sans-serif; }
            .print-header { text-align: center; margin-bottom: 20px; }
            .print-header h1 { margin: 0; }
            .print-header p { margin: 5px 0; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .total-row { font-weight: bold; }
            .no-data { text-align: center; padding: 20px; }
        </style>
    </head>
    <body>
        <div class="print-header">
            <h1>DayrohTech - Daily Sales Report</h1>
            <p>Date: <?= date('F j, Y', strtotime($report_date)) ?></p>
            <p>Generated on: <?= date('F j, Y h:i A') ?></p>
        </div>
        
        <?php if (!empty($dailySales)): ?>
        <table>
            <thead>
                <tr>
                    <th>Hour</th>
                    <th>Orders</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalOrders = 0;
                $totalAmount = 0;
                foreach ($dailySales as $sale): 
                    $totalOrders += $sale['order_count'];
                    $totalAmount += $sale['total_amount'];
                ?>
                <tr>
                    <td><?= $sale['hour'] ?>:00</td>
                    <td><?= $sale['order_count'] ?></td>
                    <td>$<?= number_format($sale['total_amount'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td>Total</td>
                    <td><?= $totalOrders ?></td>
                    <td>$<?= number_format($totalAmount, 2) ?></td>
                </tr>
            </tbody>
        </table>
        <?php else: ?>
        <div class="no-data">No sales data available for this date</div>
        <?php endif; ?>
        
        <script>
            window.onload = function() {
                window.print();
                setTimeout(function() {
                    window.close();
                }, 1000);
            };
        </script>
    </body>
    </html>
    <?php
    exit();
}

$conn->close();

// Time-based greeting
$hour = date('H');
$greeting = ($hour < 12) ? "Good morning" : (($hour < 18) ? "Good afternoon" : "Good evening");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | DayrohTech</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css">
  <style>
    :root {
      --sidebar-width: 250px;
      --sidebar-collapsed-width: 80px;
      --top-navbar-height: 56px;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      overflow-x: hidden;
      background-color: #f5f7fa;
    }
    
    /* Sidebar Styles */
    .sidebar {
      width: var(--sidebar-width);
      height: calc(100vh - var(--top-navbar-height));
      position: fixed;
      left: 0;
      top: var(--top-navbar-height);
      background: #fff;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      transition: all 0.3s ease;
      z-index: 1000;
      overflow-y: auto;
    }
    
    .sidebar.collapsed {
      width: var(--sidebar-collapsed-width);
    }
    
    .sidebar.collapsed .nav-link {
      justify-content: center;
    }
    
    .sidebar.collapsed .nav-link span {
      display: none;
    }
    
    .sidebar.collapsed .nav-link i {
      margin-right: 0;
    }
    
    .sidebar-backdrop {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 999;
      display: none;
    }
    
    .sidebar-backdrop.show {
      display: block;
    }
    
    /* Main Content Styles */
    .main-content {
      margin-left: var(--sidebar-width);
      transition: all 0.3s ease;
    }
    
    .sidebar.collapsed ~ .main-content {
      margin-left: var(--sidebar-collapsed-width);
    }
    
    @media (max-width: 992px) {
      .sidebar {
        transform: translateX(-100%);
      }
      
      .sidebar.show {
        transform: translateX(0);
      }
      
      .main-content {
        margin-left: 0;
      }
      
      .sidebar.collapsed ~ .main-content {
        margin-left: 0;
      }
    }
    
    /* Navbar Styles */
    .admin-navbar {
      height: var(--top-navbar-height);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1030;
    }
    
    /* Card Styles */
    .stat-card {
      border: none;
      border-radius: 10px;
      overflow: hidden;
      transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
    }
    
    .activity-icon {
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    /* Mobile Optimization */
    @media (max-width: 768px) {
      .stat-card {
        margin-bottom: 15px;
      }
      
      .report-controls .col-md-4 {
        margin-bottom: 15px;
      }
      
      .hourly-sales-table {
        max-height: none;
        overflow-y: visible;
      }
      
      .table-responsive {
        overflow-x: auto;
      }
    }
    
    /* Print Styles */
    .print-only { display: none; }
    @media print {
      .no-print { display: none; }
      .print-only { display: block; }
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: #555;
    }
  </style>
</head>
<body class="admin-dashboard">
  <!-- Top Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary admin-navbar shadow-sm">
    <div class="container-fluid">
      <button class="navbar-toggler me-2" type="button" id="sidebarToggle">
        <i class="bi bi-list"></i>
      </button>
      <a class="navbar-brand fw-bold" href="#">
        <i class="bi bi-speedometer2 me-2"></i>DayrohTech Admin
      </a>
      <div class="d-flex align-items-center">
        <div class="dropdown">
          <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle me-1"></i>
            <?= htmlspecialchars($adminName) ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>Profile</a></li>
            <li><a class="dropdown-item" href="settings.php"><i class="bi bi-gear me-2"></i>Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <!-- Main Content -->
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav id="sidebar" class="sidebar">
        <div class="position-sticky pt-3">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="admin_dashboard.php">
                <i class="bi bi-speedometer2 me-2"></i><span>Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin_products.php">
                <i class="bi bi-laptop me-2"></i><span>Products</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin_orders.php">
                <i class="bi bi-cart-check me-2"></i><span>Orders</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin_users.php">
                <i class="bi bi-people me-2"></i><span>Users</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin_messages.php">
                <i class="bi bi-envelope me-2"></i><span>Messages</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin_reports.php">
                <i class="bi bi-graph-up me-2"></i><span>Reports</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin_settings.php">
                <i class="bi bi-gear me-2"></i><span>Settings</span>
              </a>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Main Panel -->
      <main class="main-content col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <!-- Welcome Banner -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2">
            <i class="bi bi-speedometer2 text-primary me-2"></i>
            <?= $greeting ?>, <?= htmlspecialchars($adminName) ?> 👋
          </h1>
          <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
              <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                Export
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="?export=excel&report_date=<?= $report_date ?>"><i class="bi bi-file-earmark-excel me-2"></i>Excel</a></li>
                <li><a class="dropdown-item" href="?export=csv&report_date=<?= $report_date ?>"><i class="bi bi-filetype-csv me-2"></i>CSV</a></li>
              </ul>
              <a href="?print=true&report_date=<?= $report_date ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
                <i class="bi bi-printer me-1"></i> Print
              </a>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-calendar me-1"></i> <?= date('F j, Y') ?>
            </button>
          </div>
        </div>

        <!-- Report Controls -->
        <div class="report-controls mb-4">
          <form method="get" class="row g-3">
            <div class="col-md-4">
              <label for="report_date" class="form-label">Select Date</label>
              <input type="date" class="form-control" id="report_date" name="report_date" 
                     value="<?= $report_date ?>" max="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-filter me-1"></i> Filter
              </button>
              <a href="?" class="btn btn-outline-secondary ms-2">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
              </a>
            </div>
          </form>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
          <div class="col-md-3 col-sm-6">
            <div class="card stat-card bg-primary text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="card-subtitle mb-2">Total Users</h6>
                    <h2 class="card-title mb-0"><?= $stats['users'] ?></h2>
                  </div>
                  <i class="bi bi-people-fill display-6 opacity-50"></i>
                </div>
                <div class="mt-3">
                  <span class="badge bg-light text-primary">+<?= rand(5, 15) ?>% this month</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="card stat-card bg-success text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="card-subtitle mb-2">Total Products</h6>
                    <h2 class="card-title mb-0"><?= $stats['products'] ?></h2>
                  </div>
                  <i class="bi bi-laptop display-6 opacity-50"></i>
                </div>
                <div class="mt-3">
                  <span class="badge bg-light text-success">+<?= rand(1, 8) ?> new this week</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="card stat-card bg-warning text-dark">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="card-subtitle mb-2">Total Orders</h6>
                    <h2 class="card-title mb-0"><?= $stats['orders'] ?></h2>
                  </div>
                  <i class="bi bi-cart-check display-6 opacity-50"></i>
                </div>
                <div class="mt-3">
                  <span class="badge bg-light text-warning">+<?= rand(5, 20) ?>% from last month</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="card stat-card bg-info text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="card-subtitle mb-2">Total Revenue</h6>
                    <h2 class="card-title mb-0">$<?= number_format($stats['revenue'] ?? 0, 2) ?></h2>
                  </div>
                  <i class="bi bi-currency-dollar display-6 opacity-50"></i>
                </div>
                <div class="mt-3">
                  <span class="badge bg-light text-info">+<?= rand(10, 25) ?>% growth</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sales Analysis Section -->
        <div class="row mb-4">
          <div class="col-lg-8">
            <div class="card shadow-sm h-100">
              <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daily Sales Analysis - <?= date('F j, Y', strtotime($report_date)) ?></h5>
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-1"></i> Export
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="?export=excel&report_date=<?= $report_date ?>"><i class="bi bi-file-earmark-excel me-2"></i>Excel</a></li>
                    <li><a class="dropdown-item" href="?print=true&report_date=<?= $report_date ?>" target="_blank"><i class="bi bi-printer me-2"></i>Print</a></li>
                  </ul>
                </div>
              </div>
              <div class="card-body">
                <div class="chart-container" style="position: relative; height:250px;">
                  <canvas id="salesChart"></canvas>
                </div>
                
                <div class="mt-4">
                  <h6>Hourly Sales Breakdown</h6>
                  <div class="table-responsive">
                    <table class="table table-sm table-hover">
                      <thead>
                        <tr>
                          <th>Hour</th>
                          <th>Orders</th>
                          <th>Total Amount</th>
                          <th>Percentage</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $totalAmount = array_sum(array_column($dailySales, 'total_amount'));
                        foreach ($dailySales as $sale): 
                          $percentage = $totalAmount > 0 ? ($sale['total_amount'] / $totalAmount) * 100 : 0;
                        ?>
                        <tr>
                          <td><?= $sale['hour'] ?>:00</td>
                          <td><?= $sale['order_count'] ?></td>
                          <td>$<?= number_format($sale['total_amount'], 2) ?></td>
                          <td>
                            <div class="progress" style="height: 20px;">
                              <div class="progress-bar bg-success" role="progressbar" 
                                   style="width: <?= $percentage ?>%" 
                                   aria-valuenow="<?= $percentage ?>" 
                                   aria-valuemin="0" 
                                   aria-valuemax="100">
                                <?= round($percentage, 1) ?>%
                              </div>
                            </div>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($dailySales)): ?>
                        <tr>
                          <td colspan="4" class="text-center text-muted">No sales data available for this date</td>
                        </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card shadow-sm h-100">
              <div class="card-header bg-white">
                <h5 class="mb-0">Traffic Sources</h5>
              </div>
              <div class="card-body">
                <div class="chart-container" style="position: relative; height:250px;">
                  <canvas id="trafficChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activities and Orders -->
        <div class="row">
          <div class="col-md-6">
            <div class="card shadow-sm">
              <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Activities</h5>
                <a href="admin_activities.php" class="btn btn-sm btn-outline-primary">View All</a>
              </div>
              <div class="card-body p-0">
                <div class="list-group list-group-flush">
                  <?php foreach ($activities as $activity): ?>
                  <div class="list-group-item border-0 py-3">
                    <div class="d-flex align-items-center">
                      <div class="activity-icon bg-<?= getActivityColor($activity['activity_type']) ?> text-white rounded-circle me-3">
                        <i class="bi bi-<?= getActivityIcon($activity['activity_type']) ?>"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-1"><?= htmlspecialchars($activity['description']) ?></h6>
                        <small class="text-muted"><?= time_elapsed_string($activity['created_at']) ?></small>
                      </div>
                    </div>
                  </div>
                  <?php endforeach; ?>
                  <?php if (empty($activities)): ?>
                  <div class="list-group-item border-0 py-3 text-center text-muted">
                    No recent activities found
                  </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card shadow-sm">
              <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Orders</h5>
                <a href="admin_orders.php" class="btn btn-sm btn-outline-primary">View All</a>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($recentOrders as $order): ?>
                      <tr>
                        <td>#<?= $order['order_id'] ?></td>
                        <td><?= htmlspecialchars($order['name']) ?></td>
                        <td>$<?= number_format($order['total_amount'], 2) ?></td>
                        <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                      </tr>
                      <?php endforeach; ?>
                      <?php if (empty($recentOrders)): ?>
                      <tr>
                        <td colspan="4" class="text-center text-muted">No recent orders found</td>
                      </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer mt-auto py-3 bg-light border-top no-print">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-md-6 text-muted">
          &copy; <?= date('Y') ?> DayrohTech Store. All rights reserved.
        </div>
        <div class="col-md-6 text-end text-muted">
          <small>Admin Panel v2.1.0 | Server Time: <?= date('h:i A') ?></small>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
  <script>
    // Initialize sales chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
      type: 'bar',
      data: {
        labels: [<?= implode(',', array_map(function($sale) { return "'".$sale['hour'].":00'"; }, $dailySales)) ?>],
        datasets: [{
          label: 'Orders',
          data: [<?= implode(',', array_column($dailySales, 'order_count')) ?>],
          backgroundColor: 'rgba(54, 162, 235, 0.7)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }, {
          label: 'Revenue ($)',
          data: [<?= implode(',', array_column($dailySales, 'total_amount')) ?>],
          backgroundColor: 'rgba(75, 192, 192, 0.7)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1,
          type: 'line',
          yAxisID: 'y1'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: 'Hourly Sales Performance'
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                let label = context.dataset.label || '';
                if (label) {
                  label += ': ';
                }
                if (context.datasetIndex === 1) {
                  label += '$' + context.raw.toLocaleString();
                } else {
                  label += context.raw;
                }
                return label;
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Number of Orders'
            }
          },
          y1: {
            position: 'right',
            beginAtZero: true,
            title: {
              display: true,
              text: 'Revenue ($)'
            },
            grid: {
              drawOnChartArea: false
            }
          }
        }
      }
    });

    // Initialize traffic chart (example data)
    const trafficCtx = document.getElementById('trafficChart').getContext('2d');
    const trafficChart = new Chart(trafficCtx, {
      type: 'doughnut',
      data: {
        labels: ['Direct', 'Social', 'Email', 'Referral', 'Organic'],
        datasets: [{
          data: [35, 20, 15, 15, 15],
          backgroundColor: [
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 99, 132, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
          },
          title: {
            display: true,
            text: 'Traffic Sources Distribution'
          }
        }
      }
    });

    // Mobile sidebar toggle
    const sidebar = document.getElementById('sidebar');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const sidebarToggle = document.getElementById('sidebarToggle');
    
    function toggleSidebar() {
      if (window.innerWidth < 992) {
        sidebar.classList.toggle('show');
        sidebarBackdrop.classList.toggle('show');
      } else {
        sidebar.classList.toggle('collapsed');
      }
    }
    
    sidebarToggle.addEventListener('click', toggleSidebar);
    sidebarBackdrop.addEventListener('click', toggleSidebar);
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
      if (window.innerWidth < 992 && 
          !sidebar.contains(event.target) && 
          !sidebarToggle.contains(event.target) &&
          sidebar.classList.contains('show')) {
        toggleSidebar();
      }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
      if (window.innerWidth >= 992) {
        sidebar.classList.remove('show');
        sidebarBackdrop.classList.remove('show');
      }
    });
  </script>
</body>
</html>

<?php
// Helper functions
function getActivityColor($type) {
  $colors = [
    'login' => 'info',
    'create' => 'success',
    'update' => 'primary',
    'delete' => 'danger',
    'system' => 'secondary'
  ];
  return $colors[$type] ?? 'primary';
}

function getActivityIcon($type) {
  $icons = [
    'login' => 'box-arrow-in-right',
    'create' => 'plus-circle',
    'update' => 'pencil-square',
    'delete' => 'trash',
    'system' => 'gear'
  ];
  return $icons[$type] ?? 'activity';
}

function time_elapsed_string($datetime, $full = false) {
  $now = new DateTime;
  $ago = new DateTime($datetime);
  $diff = $now->diff($ago);

  $diff->w = floor($diff->d / 7);
  $diff->d -= $diff->w * 7;

  $string = array(
    'y' => 'year',
    'm' => 'month',
    'w' => 'week',
    'd' => 'day',
    'h' => 'hour',
    'i' => 'minute',
    's' => 'second',
  );
  
  foreach ($string as $k => &$v) {
    if ($diff->$k) {
      $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
    } else {
      unset($string[$k]);
    }
  }

  if (!$full) $string = array_slice($string, 0, 1);
  return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>