<?php
include "php/auth/check-auth.php";
include "php/config/db.php";

$sql = "SELECT * FROM compounds ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EstateFlow | Compounds</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="css/global.css" />
  <link rel="stylesheet" href="css/compounds.css" />
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
          <li><a href="areas.php">Areas</a></li>
          <li><a href="compounds.php" class="active">Compounds</a></li>
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
          <h1>Compounds</h1>
          <p>Manage compounds and link them to areas</p>
        </div>

        <div class="user-box">
          <span class="user-role">Admin</span>
          <div class="user-avatar">G</div>
        </div>
      </header>

      <section class="form-section">
        <div class="section-header">
          <h2>Add New Compound</h2>
          <p>Create compounds dynamically for future search and filtering</p>
        </div>

        <form class="compound-form" action="php/compounds/add-compound.php" method="POST">
          <div class="form-grid">
            <div class="form-group">
              <label for="compound-name">Compound Name</label>
              <input type="text" id="compound-name" name="compound-name" placeholder="Enter compound name" required />
            </div>

            <div class="form-group">
              <label for="area">Area</label>
              <select id="area" name="area" class="toggle-other" data-target="other-area-group" required>
                <option value="">Select Area</option>

                <?php
                $areasQuery = "SELECT * FROM areas ORDER BY name ASC";
                $areasResult = mysqli_query($conn, $areasQuery);

                if ($areasResult && mysqli_num_rows($areasResult) > 0) {
                    while ($areaRow = mysqli_fetch_assoc($areasResult)) {
                        echo '<option value="' . htmlspecialchars($areaRow['name']) . '">' . htmlspecialchars($areaRow['name']) . '</option>';
                    }
                }
                ?>

                <option value="other">+ Add New Area</option>
              </select>
            </div>
          </div>

          <div class="form-group hidden" id="other-area-group">
            <label for="other-area">Add New Area</label>
            <input type="text" id="other-area" name="other-area" placeholder="Enter new area name" />
          </div>

          <button type="submit" class="primary-btn">Add Compound</button>
        </form>
      </section>

      <section class="table-section">
        <div class="section-header">
          <h2>Compounds List</h2>
          <p>All compounds linked to their related areas</p>
        </div>

        <div class="table-wrapper">
          <table>
            <caption class="sr-only">List of all active real estate compounds and their associated areas</caption>
            <thead>
              <tr>
                <th>ID</th>
                <th>Compound Name</th>
                <th>Area</th>
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
                    <td><?php echo htmlspecialchars($row['area']); ?></td>
                    <td><span class="status active-status">Active</span></td>
                    <td><button class="table-btn" type="button">Edit</button></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" style="text-align:center;">No compounds found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const toggleSelects = document.querySelectorAll(".toggle-other");

      toggleSelects.forEach(select => {
        select.addEventListener("change", (e) => {
          const targetId = e.target.getAttribute("data-target");
          const targetElement = document.getElementById(targetId);

          if (e.target.value === "other") {
            targetElement.classList.remove("hidden");
          } else {
            targetElement.classList.add("hidden");
          }
        });
      });
    });
  </script>
</body>

</html>