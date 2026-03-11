<?php
include "php/auth/check-auth.php";
include "php/config/db.php";

$areas_query = "SELECT * FROM areas ORDER BY name ASC";
$areas_result = mysqli_query($conn, $areas_query);

$compounds_query = "SELECT * FROM compounds ORDER BY name ASC";
$compounds_result = mysqli_query($conn, $compounds_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EstateFlow | Add Unit</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="css/global.css" />
  <link rel="stylesheet" href="css/add-unit.css" />
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
          <li><a href="compounds.php">Compounds</a></li>
          <li><a href="units.php">Units</a></li>
          <li><a href="add-unit.php" class="active">Add Unit</a></li>
          <li><a href="teams.php">Teams</a></li>
          <li><a href="users.php">Users</a></li>
          <li><a href="php/auth/logout.php">Logout</a></li>
        </ul>
      </nav>
    </aside>

    <main class="main-content">
      <header class="topbar">
        <div class="topbar-text">
          <h1>Add Unit</h1>
          <p>Create a new property unit with its area, compound, price, and details</p>
        </div>

        <div class="user-box">
          <span class="user-role">Admin</span>
          <div class="user-avatar">G</div>
        </div>
      </header>

      <section class="form-section">
        <div class="section-header">
          <h2>Unit Information</h2>
          <p>Fill in the details below to create a new listing</p>
        </div>

        <form class="unit-form" action="php/units/add-unit.php" method="POST">
          <div class="form-grid">

            <div class="form-group">
              <label for="unit-title">Unit Title</label>
              <input type="text" id="unit-title" name="unit-title" placeholder="Enter unit title" />
            </div>

            <div class="form-group">
              <label for="area">Area</label>
              <select id="area" name="area" class="toggle-other" data-target="other-area-group">
                <option value="">Select Area</option>

                <?php if ($areas_result && mysqli_num_rows($areas_result) > 0): ?>
                  <?php while($area_row = mysqli_fetch_assoc($areas_result)): ?>
                    <option value="<?php echo htmlspecialchars($area_row['name']); ?>">
                      <?php echo htmlspecialchars($area_row['name']); ?>
                    </option>
                  <?php endwhile; ?>
                <?php endif; ?>

                <option value="other">+ Add New Area</option>
              </select>
            </div>

            <div class="form-group hidden" id="other-area-group">
              <label for="other-area">Add New Area</label>
              <input type="text" id="other-area" name="other-area" placeholder="Enter new area name" />
            </div>

            <div class="form-group">
              <label for="compound">Compound</label>
              <select id="compound" name="compound" class="toggle-other" data-target="other-compound-group">
                <option value="">Select Compound</option>

                <?php if ($compounds_result && mysqli_num_rows($compounds_result) > 0): ?>
                  <?php while($compound_row = mysqli_fetch_assoc($compounds_result)): ?>
                    <option
                      value="<?php echo htmlspecialchars($compound_row['name']); ?>"
                      data-area="<?php echo htmlspecialchars($compound_row['area']); ?>"
                    >
                      <?php echo htmlspecialchars($compound_row['name']); ?>
                    </option>
                  <?php endwhile; ?>
                <?php endif; ?>

                <option value="other">+ Add New Compound</option>
              </select>
            </div>

            <div class="form-group hidden" id="other-compound-group">
              <label for="other-compound">Add New Compound</label>
              <input type="text" id="other-compound" name="other-compound" placeholder="Enter new compound name" />
            </div>

            <div class="form-group">
              <label for="unit-type">Unit Type</label>
              <select id="unit-type" name="unit-type" class="toggle-other" data-target="other-type-group">
                <option value="">Select Type</option>
                <option value="Apartment">Apartment</option>
                <option value="Villa">Villa</option>
                <option value="Townhouse">Townhouse</option>
                <option value="Twin House">Twin House</option>
                <option value="Chalet">Chalet</option>
                <option value="Standalone">Standalone</option>
                <option value="other">+ Add New Type</option>
              </select>
            </div>

            <div class="form-group hidden" id="other-type-group">
              <label for="other-type">Add New Type</label>
              <input type="text" id="other-type" name="other-type" placeholder="Enter new unit type" />
            </div>

            <div class="form-group">
              <label for="price">Price</label>
              <input type="number" id="price" name="price" placeholder="Enter price" min="0" />
            </div>

            <div class="form-group">
              <label for="area-size">Area Size (m²)</label>
              <input type="number" id="area-size" name="area-size" placeholder="Enter area size" min="0" />
            </div>

            <div class="form-group">
              <label for="bedrooms">Bedrooms</label>
              <input type="number" id="bedrooms" name="bedrooms" placeholder="Enter bedrooms count" min="0" />
            </div>

            <div class="form-group">
              <label for="bathrooms">Bathrooms</label>
              <input type="number" id="bathrooms" name="bathrooms" placeholder="Enter bathrooms count" min="0" />
            </div>

            <div class="form-group">
              <label for="status">Status</label>
              <select id="status" name="status">
                <option value="">Select Status</option>
                <option value="Available">Available</option>
                <option value="Sold">Sold</option>
                <option value="Reserved">Reserved</option>
              </select>
            </div>

            <div class="form-group">
              <label for="developer">Developer Name</label>
              <input type="text" id="developer" name="developer" placeholder="Enter developer name" />
            </div>

            <div class="form-group full-width">
              <label for="description">Description</label>
              <textarea id="description" name="description" rows="5" placeholder="Write a short description about the unit"></textarea>
            </div>

          </div>

          <div class="form-actions">
            <button type="submit" class="primary-btn">Save Unit</button>
            <button type="reset" class="secondary-btn">Reset</button>
          </div>
        </form>
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

      const areaSelect = document.getElementById("area");
      const compoundSelect = document.getElementById("compound");

      function filterCompoundsByArea() {
        const selectedArea = areaSelect.value;
        const options = compoundSelect.querySelectorAll("option");

        compoundSelect.value = "";

        options.forEach(option => {
          const optionValue = option.value;
          const optionArea = option.getAttribute("data-area");

          if (optionValue === "" || optionValue === "other") {
            option.hidden = false;
            return;
          }

          if (selectedArea === "" || selectedArea === "other") {
            option.hidden = true;
          } else if (optionArea === selectedArea) {
            option.hidden = false;
          } else {
            option.hidden = true;
          }
        });
      }

      areaSelect.addEventListener("change", filterCompoundsByArea);

      filterCompoundsByArea();
    });
  </script>
</body>

</html>