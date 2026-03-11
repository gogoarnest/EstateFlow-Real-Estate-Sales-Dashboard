<?php
include "php/auth/check-auth.php";
include "php/config/db.php";

/* Read filters */
$search = trim($_GET['search'] ?? '');
$area = trim($_GET['area'] ?? 'all');
$compound = trim($_GET['compound'] ?? 'all');
$bedrooms = trim($_GET['bedrooms'] ?? 'any');
$status = trim($_GET['status'] ?? 'all');
$budget = trim($_GET['budget'] ?? 'any');

/* Build query */
$sql = "SELECT * FROM units WHERE 1=1";

/* Search */
if (!empty($search)) {
    $search_escaped = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (
        title LIKE '%$search_escaped%' OR
        area LIKE '%$search_escaped%' OR
        compound LIKE '%$search_escaped%'
    )";
}

/* Area filter */
if ($area !== 'all' && $area !== '') {
    $area_escaped = mysqli_real_escape_string($conn, $area);
    $sql .= " AND area = '$area_escaped'";
}

/* Compound filter */
if ($compound !== 'all' && $compound !== '') {
    $compound_escaped = mysqli_real_escape_string($conn, $compound);
    $sql .= " AND compound = '$compound_escaped'";
}

/* Bedrooms filter */
if ($bedrooms !== 'any' && $bedrooms !== '') {
    if ($bedrooms === '5') {
        $sql .= " AND bedrooms >= 5";
    } else {
        $bedrooms_int = (int)$bedrooms;
        $sql .= " AND bedrooms = $bedrooms_int";
    }
}

/* Status filter */
if ($status !== 'all' && $status !== '') {
    $status_escaped = mysqli_real_escape_string($conn, $status);
    $sql .= " AND status = '$status_escaped'";
}

/* Budget filter */
if ($budget === '5m') {
    $sql .= " AND price <= 5000000";
} elseif ($budget === '5m-8m') {
    $sql .= " AND price >= 5000000 AND price <= 8000000";
} elseif ($budget === '8m-12m') {
    $sql .= " AND price >= 8000000 AND price <= 12000000";
} elseif ($budget === '12m+') {
    $sql .= " AND price >= 12000000";
}

$sql .= " ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

/* Areas list */
$areas_query = "SELECT DISTINCT area FROM units WHERE area IS NOT NULL AND area != '' ORDER BY area ASC";
$areas_result = mysqli_query($conn, $areas_query);

/* Compounds list */
$compounds_query = "SELECT DISTINCT compound FROM units WHERE compound IS NOT NULL AND compound != '' ORDER BY compound ASC";
$compounds_result = mysqli_query($conn, $compounds_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EstateFlow | Units</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="css/global.css" />
  <link rel="stylesheet" href="css/units.css" />
</head>

<body>
  <div class="units-layout">
    <aside class="sidebar">
      <div class="sidebar-top">
        <h2 class="logo">EstateFlow</h2>
        <p class="sidebar-subtitle">Real Estate Dashboard</p>
      </div>

      <nav class="sidebar-nav">
        <ul>
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="areas.php">Areas</a></li>
          <li><a href="compounds.php">Compounds</a></li>
          <li><a href="units.php" class="active">Units</a></li>
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
          <h1>Units</h1>
          <p>Manage and search all property units</p>
        </div>

        <div class="topbar-btns">
          <a href="compare.php" class="topbar-btn compare-btn">
            Compare
            <span class="badge">0</span>
          </a>
          <a href="add-unit.php" class="topbar-btn add-unit-btn">+ Add Unit</a>
        </div>
      </header>

      <section class="filters-section">
        <form method="GET" action="units.php">
          <div class="filters-grid">
            <div class="filter-group search-group">
              <label for="search">Search</label>
              <input
                type="text"
                id="search"
                name="search"
                placeholder="Search by title, area, or compound"
                value="<?php echo htmlspecialchars($search); ?>"
              />
            </div>

            <div class="filter-group">
              <label for="area">Area</label>
              <select id="area" name="area">
                <option value="all" <?php echo ($area === 'all') ? 'selected' : ''; ?>>All Areas</option>
                <?php if ($areas_result && mysqli_num_rows($areas_result) > 0): ?>
                  <?php while($area_row = mysqli_fetch_assoc($areas_result)): ?>
                    <option value="<?php echo htmlspecialchars($area_row['area']); ?>" <?php echo ($area === $area_row['area']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($area_row['area']); ?>
                    </option>
                  <?php endwhile; ?>
                <?php endif; ?>
              </select>
            </div>

            <div class="filter-group">
              <label for="compound">Compound</label>
              <select id="compound" name="compound">
                <option value="all" <?php echo ($compound === 'all') ? 'selected' : ''; ?>>All Compounds</option>
                <?php if ($compounds_result && mysqli_num_rows($compounds_result) > 0): ?>
                  <?php while($compound_row = mysqli_fetch_assoc($compounds_result)): ?>
                    <option value="<?php echo htmlspecialchars($compound_row['compound']); ?>" <?php echo ($compound === $compound_row['compound']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($compound_row['compound']); ?>
                    </option>
                  <?php endwhile; ?>
                <?php endif; ?>
              </select>
            </div>

            <div class="filter-group">
              <label for="bedrooms">Bedrooms</label>
              <select id="bedrooms" name="bedrooms">
                <option value="any" <?php echo ($bedrooms === 'any') ? 'selected' : ''; ?>>Any</option>
                <option value="1" <?php echo ($bedrooms === '1') ? 'selected' : ''; ?>>1 Bedroom</option>
                <option value="2" <?php echo ($bedrooms === '2') ? 'selected' : ''; ?>>2 Bedrooms</option>
                <option value="3" <?php echo ($bedrooms === '3') ? 'selected' : ''; ?>>3 Bedrooms</option>
                <option value="4" <?php echo ($bedrooms === '4') ? 'selected' : ''; ?>>4 Bedrooms</option>
                <option value="5" <?php echo ($bedrooms === '5') ? 'selected' : ''; ?>>5+ Bedrooms</option>
              </select>
            </div>

            <div class="filter-group">
              <label for="status">Status</label>
              <select id="status" name="status">
                <option value="all" <?php echo ($status === 'all') ? 'selected' : ''; ?>>All Status</option>
                <option value="Available" <?php echo ($status === 'Available') ? 'selected' : ''; ?>>Available</option>
                <option value="Sold" <?php echo ($status === 'Sold') ? 'selected' : ''; ?>>Sold</option>
                <option value="Reserved" <?php echo ($status === 'Reserved') ? 'selected' : ''; ?>>Reserved</option>
              </select>
            </div>

            <div class="filter-group">
              <label for="budget">Budget</label>
              <select id="budget" name="budget">
                <option value="any" <?php echo ($budget === 'any') ? 'selected' : ''; ?>>Any Budget</option>
                <option value="5m" <?php echo ($budget === '5m') ? 'selected' : ''; ?>>Up to 5M</option>
                <option value="5m-8m" <?php echo ($budget === '5m-8m') ? 'selected' : ''; ?>>5M - 8M</option>
                <option value="8m-12m" <?php echo ($budget === '8m-12m') ? 'selected' : ''; ?>>8M - 12M</option>
                <option value="12m+" <?php echo ($budget === '12m+') ? 'selected' : ''; ?>>12M+</option>
              </select>
            </div>
          </div>

          <div class="filter-actions" style="margin-top: 16px; display: flex; gap: 12px; flex-wrap: wrap;">
            <button type="submit" class="topbar-btn add-unit-btn">Apply Filters</button>
            <a href="units.php" class="topbar-btn compare-btn">Reset</a>
          </div>
        </form>
      </section>

      <section class="table-section">
        <div class="table-header">
          <h2>Property Units</h2>
          <p>Showing the latest available units</p>
        </div>

        <div class="table-wrapper">
          <table>
            <caption class="sr-only">List of available property units with details and actions</caption>
            <thead>
              <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Area</th>
                <th>Compound</th>
                <th>Price</th>
                <th>Bedrooms</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>

            <tbody>
              <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                  <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['area']); ?></td>
                    <td><?php echo htmlspecialchars($row['compound']); ?></td>
                    <td><?php echo number_format((float)$row['price']); ?> EGP</td>
                    <td><?php echo htmlspecialchars($row['bedrooms']); ?></td>
                    <td>
                      <span class="status <?php echo strtolower($row['status']); ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                      </span>
                    </td>
                    <td>
                      <button class="action-btn" type="button">View</button>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8" style="text-align:center;">No units found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>

  <script src="js/script.js"></script>
  <script src="js/compare.js"></script>
</body>

</html>