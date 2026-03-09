document.addEventListener("DOMContentLoaded", () => {
    const maxCompare = 3;
    let comparedUnits = [];

    // Selectors
    const compareBtnTop = document.querySelector(".add-unit-btn");
    const compareCounter = compareBtnTop.querySelector("p");
    const actionBtns = document.querySelectorAll(".action-btn");
    const filtersSection = document.querySelector(".filters-section");
    const tableSection = document.querySelector(".table-section");
    const mainContent = document.querySelector(".main-content");

    // Create a container for the comparison view and inject it into the main content
    const compareContainer = document.createElement("section");
    compareContainer.className = "compare-view-section";
    compareContainer.style.display = "none";
    mainContent.appendChild(compareContainer);

    // Handle "Add to Compare" clicks
    actionBtns.forEach((btn) => {
        btn.addEventListener("click", function () {
            const row = this.closest("tr");
            const unitId = row.cells[0].innerText;

            // Check if it is already added
            if (comparedUnits.find(u => u.id === unitId)) {
                alert("This unit is already in your compare list.");
                return;
            }

            // Check the maximum limit
            if (comparedUnits.length >= maxCompare) {
                alert("You have reached the maximum number of units to compare.");
                return;
            }

            // Store unit data
            const unitData = {
                id: unitId,
                compound: row.cells[1].innerText,
                area: row.cells[2].innerText,
                price: row.cells[3].innerText,
                bedrooms: row.cells[4].innerText,
                statusHtml: row.cells[5].innerHTML, 
            };

            comparedUnits.push(unitData);
            
            // Update the counter in the top right button
            compareCounter.innerText = comparedUnits.length;

            // Give visual feedback on the button
            this.innerText = "Added";
            this.style.backgroundColor = "#166534"; // Changes to green
            this.style.cursor = "default";
        });
    });

    // Handle the Top Right "Compare" Button Click
    compareBtnTop.addEventListener("click", (e) => {
        e.preventDefault(); // Stop it from navigating

        if (comparedUnits.length === 0) {
            alert("Please add at least one unit to compare.");
            return;
        }

        // Hide normal page content
        filtersSection.style.display = "none";
        tableSection.style.display = "none";

        // Show the compare view container
        compareContainer.style.display = "block";
        renderCompareView();
    });

    // Function to render the comparison layout
    function renderCompareView() {
        let cardsHtml = `
            <div class="table-header" style="margin-bottom: 20px;">
                <h2>Unit Comparison</h2>
                <p>Comparing your selected units side-by-side</p>
            </div>
            <div class="compare-cards-container">`;

        // Generate a card for each saved unit
        comparedUnits.forEach(unit => {
            cardsHtml += `
                <div class="compare-card">
                    <h3>${unit.id}</h3>
                    <ul>
                        <li><strong>Compound:</strong> <span>${unit.compound}</span></li>
                        <li><strong>Area:</strong> <span>${unit.area}</span></li>
                        <li><strong>Price:</strong> <span>${unit.price}</span></li>
                        <li><strong>Bedrooms:</strong> <span>${unit.bedrooms}</span></li>
                        <li><strong>Status:</strong> ${unit.statusHtml}</li>
                    </ul>
                    <button class="action-btn remove-unit-btn" data-id="${unit.id}" style="margin-top: 16px; width: 100%; background-color: #ef4444;">Remove Unit</button>
                </div>
            `;
        });

        // Add a "Back" button
        cardsHtml += `</div>
        <button id="back-to-table" class="action-btn" style="margin-top: 24px; padding: 12px 24px;">&larr; Back to Units</button>`;

        compareContainer.innerHTML = cardsHtml;

        // Handle the Back Button click
        document.getElementById("back-to-table").addEventListener("click", () => {
            compareContainer.style.display = "none";
            filtersSection.style.display = "block";
            tableSection.style.display = "block";
        });

        // Handle the Remove Unit buttons
        const removeBtns = compareContainer.querySelectorAll(".remove-unit-btn");
        removeBtns.forEach(btn => {
            btn.addEventListener("click", function() {
                const idToRemove = this.getAttribute("data-id");

                // Remove unit from the array
                comparedUnits = comparedUnits.filter(u => u.id !== idToRemove);

                // Update the top-right counter
                compareCounter.innerText = comparedUnits.length;

                // Find the original button in the table and reset its appearance
                actionBtns.forEach(actionBtn => {
                    const row = actionBtn.closest("tr");
                    if (row && row.cells[0].innerText === idToRemove) {
                        actionBtn.innerText = "Add to Compare";
                        actionBtn.style.backgroundColor = ""; // Resets to original CSS color
                        actionBtn.style.cursor = "pointer";
                    }
                });

                // If no units are left, go back to the table. Otherwise, re-draw the compare view.
                if (comparedUnits.length === 0) {
                    compareContainer.style.display = "none";
                    filtersSection.style.display = "block";
                    tableSection.style.display = "block";
                } else {
                    renderCompareView();
                }
            });
        });
    }
});