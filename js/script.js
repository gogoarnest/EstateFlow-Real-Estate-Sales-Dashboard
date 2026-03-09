///////////////// global variable /////////////////

const areaSelect = document.getElementById("area");
const compoundSelect = document.getElementById("compound");
const bedroomSelect = document.getElementById("bedrooms");
const statusSelect = document.getElementById("status");
const budgetSelect = document.getElementById("budget");
const tableRows = document.querySelectorAll("tbody tr");

///////////////// Search logic /////////////////
const searchInput = document.getElementById("search");
function checkSearch(row) {
    const searchTerm = searchInput.value.toLowerCase().trim();
    if (searchTerm === "") return true;

    const compoundText = row.cells[1].innerText.toLowerCase();
    const areaText = row.cells[2].innerText.toLowerCase();

    return compoundText.includes(searchTerm) || areaText.includes(searchTerm);
}

///////////////// filter logic  /////////////////
function checkFilters(row) {
    const areaFilter = areaSelect.value;
    const compoundFilter = compoundSelect.value;
    const bedroomFilter = bedroomSelect.value;
    const statusFilter = statusSelect.value;
    const budgetFilter = budgetSelect.value;

    const compoundText = row.cells[1].innerText;
    const areaText = row.cells[2].innerText;
    const priceText = row.cells[3].innerText;
    const bedroomsText = row.cells[4].innerText;
    const statusText = row.cells[5].innerText.trim();

    const matchesArea = areaFilter === "All Areas" || areaText === areaFilter;
    const matchesCompound = compoundFilter === "All Compounds" || compoundText === compoundFilter;
    const matchesStatus = statusFilter === "All Status" || statusText === statusFilter;

    let matchesBedrooms = true;
    if (bedroomFilter !== "Any") {
        const rowBeds = parseInt(bedroomsText);
        if (bedroomFilter === "4+ Bedrooms") {
            matchesBedrooms = rowBeds >= 4;
        } else {
            matchesBedrooms = rowBeds === parseInt(bedroomFilter);
        }
    }

    let matchesBudget = true;
    if (budgetFilter !== "Any Budget") {
        const priceValue = parseInt(priceText.replace(/,/g, '').replace(' EGP', ''));
        const m = 1000000; 

        if (budgetFilter === "Up to 5M") matchesBudget = priceValue <= 5 * m;
        else if (budgetFilter === "5M - 8M") matchesBudget = priceValue > 5 * m && priceValue <= 8 * m;
        else if (budgetFilter === "8M - 12M") matchesBudget = priceValue > 8 * m && priceValue <= 12 * m;
        else if (budgetFilter === "12M+") matchesBudget = priceValue > 12 * m;
    }

    return matchesArea && matchesCompound && matchesBedrooms && matchesStatus && matchesBudget;
}

// Master function that decides if a row is visible
function updateTable() {
    tableRows.forEach(row => {
        if (checkSearch(row) && checkFilters(row)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

// Attach listeners
searchInput.addEventListener("input", updateTable);
areaSelect.addEventListener("change", updateTable);
compoundSelect.addEventListener("change", updateTable);
bedroomSelect.addEventListener("change", updateTable);
statusSelect.addEventListener("change", updateTable);
budgetSelect.addEventListener("change", updateTable);