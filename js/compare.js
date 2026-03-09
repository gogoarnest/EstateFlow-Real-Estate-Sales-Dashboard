document.addEventListener("DOMContentLoaded", () => {
  const MAX_COMPARE_LIMIT = 3;
  let comparedUnits = [];

  // --- Selectors ---
  // Note: Updated these selectors to match the cleaned HTML structure
  const compareTopBtn = document.querySelector(".topbar-btn.compare-btn");
  const compareBadge = compareTopBtn.querySelector(".badge");
  const tableCompareBtns = document.querySelectorAll(".table-compare-btn");
  const filtersSection = document.querySelector(".filters-section");
  const tableSection = document.querySelector(".table-section");
  const mainContent = document.querySelector(".main-content");

  // Initialize Comparison Container
  const compareContainer = document.createElement("section");
  compareContainer.className = "compare-view-section";
  compareContainer.style.display = "none";
  mainContent.appendChild(compareContainer);

  // --- Event Listeners ---

  // 1. "Add to Compare" Table Buttons
  tableCompareBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const row = this.closest("tr");
      const unitId = row.cells[0].innerText.trim();

      // Validation Checks
      if (comparedUnits.some((u) => u.id === unitId)) {
        alert("This unit is already in your compare list.");
        return;
      }

      if (comparedUnits.length >= MAX_COMPARE_LIMIT) {
        alert(`You can only compare up to ${MAX_COMPARE_LIMIT} units at a time.`);
        return;
      }

      // Extract Data from Table Row
      const unitData = {
        id: unitId,
        compound: row.cells[1].innerText.trim(),
        area: row.cells[2].innerText.trim(),
        price: row.cells[3].innerText.trim(),
        bedrooms: row.cells[4].innerText.trim(),
        statusHtml: row.cells[5].innerHTML.trim(),
      };

      comparedUnits.push(unitData);
      updateUIOnAdd(this);
    });
  });

  // 2. Top "Compare" Button
  compareTopBtn.addEventListener("click", (e) => {
    e.preventDefault();

    if (comparedUnits.length === 0) {
      alert("Please add at least one unit to compare.");
      return;
    }

    toggleView("compare");
    renderCompareView();
  });

  // --- Helper Functions ---

  /** Updates the badge and disables the clicked table button */
  function updateUIOnAdd(clickedBtn) {
    compareBadge.innerText = comparedUnits.length;

    // Visual feedback
    clickedBtn.innerText = "Added";
    clickedBtn.style.backgroundColor = "#166534"; // Green success color
    clickedBtn.style.cursor = "default";
    clickedBtn.disabled = true; // Prevent duplicate clicks
  }

  /** Switches between the standard table view and the comparison view */
  function toggleView(viewType) {
    if (viewType === "compare") {
      filtersSection.style.display = "none";
      tableSection.style.display = "none";
      compareContainer.style.display = "block";
    } else {
      compareContainer.style.display = "none";
      filtersSection.style.display = "block";
      tableSection.style.display = "block";
    }
  }

  /** Generates and injects the HTML for the comparison cards */
  function renderCompareView() {
    // Build cards using array mapping instead of string concatenation loops
    const cardsHtml = comparedUnits.map((unit) => `
      <div class="compare-card">
        <h3>${unit.id}</h3>
        <ul>
          <li><strong>Compound:</strong> <span>${unit.compound}</span></li>
          <li><strong>Area:</strong> <span>${unit.area}</span></li>
          <li><strong>Price:</strong> <span>${unit.price}</span></li>
          <li><strong>Bedrooms:</strong> <span>${unit.bedrooms}</span></li>
          <li><strong>Status:</strong> ${unit.statusHtml}</li>
        </ul>
        <button class="table-btn remove-unit-btn" data-id="${unit.id}" style="margin-top: 16px; width: 100%; background-color: #ef4444;">
          Remove Unit
        </button>
      </div>
    `).join("");

    compareContainer.innerHTML = `
      <div class="table-header" style="margin-bottom: 20px;">
        <h2>Unit Comparison</h2>
        <p>Comparing your selected units side-by-side</p>
      </div>
      <div class="compare-cards-container">
        ${cardsHtml}
      </div>
      <button id="back-to-table" class="table-btn" style="margin-top: 24px; padding: 12px 24px;">
        Back to Units
      </button>
    `;

    attachCompareViewEvents();
  }

  /** Binds event listeners to the dynamically generated buttons in the compare view */
  function attachCompareViewEvents() {
    // Back to Table
    document.getElementById("back-to-table").addEventListener("click", () => {
      toggleView("table");
    });

    // Remove Unit
    const removeBtns = compareContainer.querySelectorAll(".remove-unit-btn");
    removeBtns.forEach((btn) => {
      btn.addEventListener("click", function () {
        const idToRemove = this.getAttribute("data-id");
        removeUnit(idToRemove);
      });
    });
  }

  /** Handles the logic for removing a unit from the comparison list */
  function removeUnit(id) {
    // Filter out the removed unit
    comparedUnits = comparedUnits.filter((u) => u.id !== id);
    compareBadge.innerText = comparedUnits.length;

    // Find the original button in the table and reset its state
    tableCompareBtns.forEach((btn) => {
      const row = btn.closest("tr");
      if (row && row.cells[0].innerText.trim() === id) {
        btn.innerText = "Add to Compare";
        btn.style.backgroundColor = ""; // Reverts to CSS default
        btn.style.cursor = "pointer";
        btn.disabled = false;
      }
    });

    // Check if we need to close the comparison view
    if (comparedUnits.length === 0) {
      toggleView("table");
    } else {
      renderCompareView(); // Re-render the UI with remaining units
    }
  }
});