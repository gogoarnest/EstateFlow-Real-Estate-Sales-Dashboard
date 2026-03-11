<?php
include "php/auth/check-auth.php";
include "php/config/db.php";

$sql = "SELECT * FROM areas ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EstateFlow | Areas</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="css/global.css" />
  <link rel="stylesheet" href="css/areas.css" />
</head>

<body>
  <div class="page-layout">
    <aside class="sidebar">
      <div class="sidebar-top">
        <h2 class="logo">EstateFlow</h2>
        <p class="sidebar-subtitle">Real Estate Dashboard</p>
      </div>

      <nav class="sidebar-nav">
        <ul>
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="areas.php" class="active">Areas</a></li>
          <li><a href="compounds.php">Compounds</a></li>
          <li><a href="units.php">Units</a></li>
          <li><a href="add-unit.php">Add Unit</a></li>
          <li><a href="teams.php">Teams</a></li>
          <li><a href="users.php">Users</a></li>
          <li><a href="php/auth/logout.php">Logout</a></li>
        </ul>
      </nav>
    </aside>

    <main class="main-content">
      <header class="topbar">
        <div class="topbar-text">
          <h1>Areas</h1>
          <p>Manage all available real estate areas</p>
        </div>

        <div class="user-box">
          <span class="user-role">Admin</span>
          <div class="user-avatar">G</div>
        </div>
      </header>

      <section class="form-section">
        <div class="section-header">
          <h2>Add New Area</h2>
          <p>Create and manage areas dynamically</p>
        </div>

        <form class="area-form" action="php/areas/add-area.php" method="POST">
          <div class="form-group">
            <label for="area-name">Area Name</label>
            <input type="text" id="area-name" name="area-name" placeholder="Enter area name" required />
          </div>

          <button type="submit" class="primary-btn">Add Area</button>
        </form>
      </section>

      <section class="table-section">
        <div class="section-header">
          <h2>Areas List</h2>
          <p>All added areas will appear here</p>
        </div>

        <div class="table-wrapper">
          <table>
            <caption class="sr-only">List of all active real estate areas</caption>
            <thead>
              <tr>
                <th>ID</th>
                <th>Area Name</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>

            <tbody>
              <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                  <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><span class="status active-status">Active</span></td>
                    <td>
                      <button class="table-btn" type="button">Edit</button>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4" style="text-align:center;">No areas found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>
</body>

</html>