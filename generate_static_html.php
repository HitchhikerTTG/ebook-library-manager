<?php
require_once 'utils.php';

function generate_static_html_for_book($book_id, $metadata) {
    // Individual book generation is no longer needed
    return;
}

function generate_book_list_html() {
    $files = glob('metadata/*.json');
    $books = [];
    foreach ($files as $file) {
        $book_id = basename($file, '.json');
        $metadata = json_decode(file_get_contents($file), true);
        $metadata['id'] = $book_id;
        $metadata['date_added'] = filectime($file);
        $metadata['author_first_letter'] = strtoupper(substr($metadata['author_last'], 0, 1));
        $books[] = $metadata;
    }

    $html = '<!DOCTYPE html>
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
                    <span class="letter-filter" data-filter="all">All</span> |';
    
    // Generate A-Z filters
    for ($i = 65; $i <= 90; $i++) {
        $letter = chr($i);
        $html .= ' <span class="letter-filter" data-filter="' . $letter . '">' . $letter . '</span>';
    }

    $html .= '
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
                <tbody>';

    foreach ($books as $book) {
        $title = htmlspecialchars($book['title']);
        $author = htmlspecialchars($book['author_first'] . ' ' . $book['author_last']);
        $http_link = '?route=download&id=' . urlencode($book['id']);
        $https_link = 'https://' . $_SERVER['HTTP_HOST'] . '/?route=download&id=' . urlencode($book['id']);
        
        $html .= '
                <tr class="book-row" 
                    data-title="' . $title . '" 
                    data-author="' . $author . '"
                    data-date="' . $book['date_added'] . '"
                    data-genre="' . htmlspecialchars($book['genre']) . '"
                    data-author-letter="' . $book['author_first_letter'] . '">
                    <td>
                        [<a href="' . $http_link . '">http</a>] 
                        <a href="' . $https_link . '">' . $title . '</a>, 
                        <a href="' . $https_link . '">' . $author . '</a>
                    </td>
                </tr>';
    }

    $html .= '
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
</html>';

    file_put_contents('ksiazki.php', $html);
}

function generate_all_static_html() {
    generate_book_list_html();
}

if (basename($_SERVER['SCRIPT_NAME']) === basename(__FILE__)) {
    generate_all_static_html();
}
?>
