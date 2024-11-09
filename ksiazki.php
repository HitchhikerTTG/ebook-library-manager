<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ebook Library - Complete Collection</title>
    <link href="https://cdn.replit.com/agent/bootstrap-agent-dark-theme.min.css" rel="stylesheet">
    <link href="static/css/custom.css" rel="stylesheet">
    <style>
        .sort-btn.active { text-decoration: underline; }
        .sort-direction { margin-left: 4px; }
        .letter-filter { margin: 0 2px; cursor: pointer; }
        .letter-filter.active { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Complete Book Collection</h1>
        <div class="row mb-3">
            <div class="col">
                <div class="btn-group mb-2">
                    <button class="btn btn-secondary sort-btn" data-sort="date_added">
                        Date Added <span class="sort-direction">▼</span>
                    </button>
                    <button class="btn btn-secondary sort-btn" data-sort="title">
                        Title <span class="sort-direction">▼</span>
                    </button>
                    <button class="btn btn-secondary sort-btn" data-sort="genre">
                        Genre <span class="sort-direction">▼</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <div class="author-filter mb-2">
                    Author Last Name: 
                    <span class="letter-filter" data-filter="all">All</span> | <span class="letter-filter" data-filter="A">A</span> <span class="letter-filter" data-filter="B">B</span> <span class="letter-filter" data-filter="C">C</span> <span class="letter-filter" data-filter="D">D</span> <span class="letter-filter" data-filter="E">E</span> <span class="letter-filter" data-filter="F">F</span> <span class="letter-filter" data-filter="G">G</span> <span class="letter-filter" data-filter="H">H</span> <span class="letter-filter" data-filter="I">I</span> <span class="letter-filter" data-filter="J">J</span> <span class="letter-filter" data-filter="K">K</span> <span class="letter-filter" data-filter="L">L</span> <span class="letter-filter" data-filter="M">M</span> <span class="letter-filter" data-filter="N">N</span> <span class="letter-filter" data-filter="O">O</span> <span class="letter-filter" data-filter="P">P</span> <span class="letter-filter" data-filter="Q">Q</span> <span class="letter-filter" data-filter="R">R</span> <span class="letter-filter" data-filter="S">S</span> <span class="letter-filter" data-filter="T">T</span> <span class="letter-filter" data-filter="U">U</span> <span class="letter-filter" data-filter="V">V</span> <span class="letter-filter" data-filter="W">W</span> <span class="letter-filter" data-filter="X">X</span> <span class="letter-filter" data-filter="Y">Y</span> <span class="letter-filter" data-filter="Z">Z</span>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text" class="form-control" id="searchInput" placeholder="Search books...">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Books</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("searchInput");
        const bookRows = document.querySelectorAll(".book-row");
        const sortButtons = document.querySelectorAll(".sort-btn");
        const letterFilters = document.querySelectorAll(".letter-filter");
        
        let currentSort = { field: "date_added", ascending: false };
        let currentFilter = "all";

        // Sorting function
        function sortRows() {
            const rows = Array.from(bookRows);
            const sortField = currentSort.field;
            const ascending = currentSort.ascending;

            rows.sort((a, b) => {
                let aVal = a.dataset[sortField === "date_added" ? "date" : sortField].toLowerCase();
                let bVal = b.dataset[sortField === "date_added" ? "date" : sortField].toLowerCase();

                if (sortField === "date_added") {
                    return ascending ? aVal - bVal : bVal - aVal;
                }
                return ascending ? 
                    aVal.localeCompare(bVal) : 
                    bVal.localeCompare(aVal);
            });

            const tbody = document.querySelector("tbody");
            rows.forEach(row => tbody.appendChild(row));
        }

        // Filter function
        function filterRows() {
            const searchTerm = searchInput.value.toLowerCase();
            
            bookRows.forEach(row => {
                const title = row.dataset.title.toLowerCase();
                const author = row.dataset.author.toLowerCase();
                const authorLetter = row.dataset.authorLetter;
                
                const matchesSearch = title.includes(searchTerm) || 
                                    author.includes(searchTerm);
                const matchesFilter = currentFilter === "all" || 
                                    authorLetter === currentFilter;

                row.style.display = (matchesSearch && matchesFilter) ? "" : "none";
            });
        }

        // Sort button click handlers
        sortButtons.forEach(button => {
            button.addEventListener("click", () => {
                const field = button.dataset.sort;
                
                // Update sort direction
                if (currentSort.field === field) {
                    currentSort.ascending = !currentSort.ascending;
                } else {
                    currentSort.field = field;
                    currentSort.ascending = true;
                }

                // Update UI
                sortButtons.forEach(btn => {
                    const direction = btn.querySelector(".sort-direction");
                    if (btn === button) {
                        direction.textContent = currentSort.ascending ? "▲" : "▼";
                        btn.classList.add("active");
                    } else {
                        direction.textContent = "▼";
                        btn.classList.remove("active");
                    }
                });

                sortRows();
            });
        });

        // Letter filter click handlers
        letterFilters.forEach(filter => {
            filter.addEventListener("click", () => {
                currentFilter = filter.dataset.filter;
                
                // Update UI
                letterFilters.forEach(f => {
                    f.classList.toggle("active", f === filter);
                });

                filterRows();
            });
        });

        // Search input handler
        if (searchInput) {
            searchInput.addEventListener("input", filterRows);
        }

        // Initial sort
        sortRows();
    });
    </script>
</body>
</html>