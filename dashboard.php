<?php
include "php/auth/check-auth.php";
include "php/config/db.php";

/* Logged in user info */
$user_name = $_SESSION['user_name'] ?? 'User';
$user_role = $_SESSION['role'] ?? 'sales';

/* Dashboard statistics */
$total_units_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM units");
$total_units = mysqli_fetch_assoc($total_units_result)['total'] ?? 0;

$available_units_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM units WHERE status = 'Available'");
$available_units = mysqli_fetch_assoc($available_units_result)['total'] ?? 0;

$sold_units_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM units WHERE status = 'Sold'");
$sold_units = mysqli_fetch_assoc($sold_units_result)['total'] ?? 0;

$total_areas_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM areas");
$total_areas = mysqli_fetch_assoc($total_areas_result)['total'] ?? 0;

/* Latest units */
$latest_units_query = "SELECT title, price, status, created_at FROM units ORDER BY id DESC LIMIT 4";
$latest_units_result = mysqli_query($conn, $latest_units_query);

/* Format role label */
function formatRole($role) {
    switch ($role) {
        case 'admin':
            return 'Admin';
        case 'leader':
            return 'Team Leader';
        case 'senior':
            return 'Senior';
        case 'sales':
            return 'Sales';
        default:
            return ucfirst($role);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EstateFlow | Dashboard</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="css/global.css" />
  <link rel="stylesheet" href="css/dashboard.css" />
</head>

<body>
  <div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-top">
        <h2 class="logo">EstateFlow</h2>
        <p class="sidebar-subtitle">Real Estate Dashboard</p>
      </div>

      <nav class="sidebar-nav">
        <ul>
          <li><a href="dashboard.php" class="active">Dashboard</a></li>
          <li><a href="areas.php">Areas</a></li>
          <li><a href="compounds.php">Compounds</a></li>
          <li><a href="units.php">Units</a></li>
          <li><a href="add-unit.php">Add Unit</a></li>
          <li><a href="teams.php">Teams</a></li>
          <li><a href="users.php">Users</a></li>
          <li><a href="php/auth/logout.php">Logout</a></li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <header class="topbar">
        <div>
          <h1>Dashboard</h1>
          <p>Overview of your real estate operations</p>
        </div>

        <div class="topbar-right">
          <div class="user-box">
            <span class="user-role"><?php echo formatRole(htmlspecialchars($user_role)); ?></span>
            <div class="user-avatar">
              <?php echo strtoupper(substr($user_name, 0, 1)); ?>
            </div>
            <span class="user-name"><?php echo htmlspecialchars($user_name); ?></span>
          </div>
        </div>
      </header>

      <!-- Stats Cards -->
      <section class="stats-grid">
        <div class="stat-card">
          <h3>Total Units</h3>
          <p><?php echo $total_units; ?></p>
        </div>

        <div class="stat-card">
          <h3>Available Units</h3>
          <p><?php echo $available_units; ?></p>
        </div>

        <div class="stat-card">
          <h3>Sold Units</h3>
          <p><?php echo $sold_units; ?></p>
        </div>

        <div class="stat-card">
          <h3>Total Areas</h3>
          <p><?php echo $total_areas; ?></p>
        </div>
      </section>

      <!-- Main Dashboard Sections -->
      <section class="dashboard-grid">
        <div class="panel recent-updates">
          <div class="panel-header">
            <h2>Recent Units</h2>
          </div>

          <div class="update-list">
            <?php if ($latest_units_result && mysqli_num_rows($latest_units_result) > 0): ?>
              <?php while ($unit = mysqli_fetch_assoc($latest_units_result)): ?>
                <div class="update-item">
                  <div>
                    <h4><?php echo htmlspecialchars($unit['title']); ?></h4>
                    <p>
                      Price: <?php echo number_format((float)$unit['price']); ?> EGP
                      — Status: <?php echo htmlspecialchars($unit['status']); ?>
                    </p>
                  </div>
                  <span><?php echo date("d M", strtotime($unit['created_at'])); ?></span>
                </div>
              <?php endwhile; ?>
            <?php else: ?>
              <div class="update-item">
                <div>
                  <h4>No units yet</h4>
                  <p>Start by adding your first property unit.</p>
                </div>
                <span>--</span>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="panel quick-actions">
          <div class="panel-header">
            <h2>Quick Actions</h2>
          </div>

          <div class="actions-list">
            <a href="add-unit.php" class="action-link"><button type="button">Add New Unit</button></a>
            <a href="areas.php" class="action-link"><button type="button">Manage Areas</button></a>
            <a href="compounds.php" class="action-link"><button type="button">Manage Compounds</button></a>
            <a href="units.php" class="action-link"><button type="button">View Units</button></a>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>

</html>