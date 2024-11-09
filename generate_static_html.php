<?php
require_once 'utils.php';

function generate_static_html_for_book($book_id, $metadata) {
    if (!file_exists('static_html')) {
        mkdir('static_html', 0777, true);
    }

    $html = '<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($metadata['title']) . ' - Ebook Library</title>
    <link href="https://cdn.replit.com/agent/bootstrap-agent-dark-theme.min.css" rel="stylesheet">
    <link href="../static/css/custom.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title">' . htmlspecialchars($metadata['title']) . '</h1>
                        <div class="mb-4">
                            <p><strong>Author:</strong> ' . htmlspecialchars($metadata['author_first'] . ' ' . $metadata['author_last']) . '</p>
                            <p><strong>Genre:</strong> ' . htmlspecialchars($metadata['genre']) . '</p>';
    
    if (!empty($metadata['series'])) {
        $html .= '<p><strong>Series:</strong> ' . htmlspecialchars($metadata['series']);
        if (!empty($metadata['series_position'])) {
            $html .= ' #' . htmlspecialchars($metadata['series_position']);
        }
        $html .= '</p>';
    }

    $html .= '        </div>
                        <div class="d-grid">
                            <a href="../?route=download&id=' . urlencode($book_id) . '" class="btn btn-primary btn-lg">
                                Download Book
                            </a>
                            <a href="../" class="btn btn-secondary mt-2">
                                Return to Library
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>';

    file_put_contents('static_html/' . $book_id . '.html', $html);
}

function generate_book_list_html() {
    if (!file_exists('static_html')) {
        mkdir('static_html', 0777, true);
    }

    $files = glob('metadata/*.json');
    $books = [];
    foreach ($files as $file) {
        $book_id = basename($file, '.json');
        $metadata = json_decode(file_get_contents($file), true);
        $metadata['id'] = $book_id;
        $books[] = $metadata;
    }

    // Sort books by title
    usort($books, function($a, $b) {
        return strcasecmp($a['title'], $b['title']);
    });

    $html = '<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ebook Library - Complete Collection</title>
    <link href="https://cdn.replit.com/agent/bootstrap-agent-dark-theme.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css" rel="stylesheet">
    <link href="../static/css/custom.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Complete Book Collection</h1>
        <div class="row mb-4">
            <div class="col-md-6">
                <input type="text" class="form-control" id="searchInput" placeholder="Search books...">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="title">Title <i data-feather="chevron-down"></i></th>
                        <th class="sortable" data-sort="author">Author <i data-feather="chevron-down"></i></th>
                        <th class="sortable" data-sort="genre">Genre <i data-feather="chevron-down"></i></th>
                        <th class="sortable" data-sort="series">Series <i data-feather="chevron-down"></i></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';

    foreach ($books as $book) {
        $series_info = !empty($book['series']) ? htmlspecialchars($book['series']) . 
            (!empty($book['series_position']) ? ' #' . htmlspecialchars($book['series_position']) : '') : '';
        
        $html .= '
                <tr class="book-row" 
                    data-title="' . htmlspecialchars($book['title']) . '"
                    data-author="' . htmlspecialchars($book['author_first'] . ' ' . $book['author_last']) . '"
                    data-genre="' . htmlspecialchars($book['genre']) . '"
                    data-series="' . $series_info . '">
                    <td>' . htmlspecialchars($book['title']) . '</td>
                    <td>' . htmlspecialchars($book['author_first'] . ' ' . $book['author_last']) . '</td>
                    <td>' . htmlspecialchars($book['genre']) . '</td>
                    <td>' . $series_info . '</td>
                    <td>
                        <div class="btn-group">
                            <a href="' . htmlspecialchars($book['id']) . '.html" class="btn btn-sm btn-info">Details</a>
                            <a href="../?route=download&id=' . urlencode($book['id']) . '" class="btn btn-sm btn-primary">Download</a>
                        </div>
                    </td>
                </tr>';
    }

    $html .= '
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Feather icons
        feather.replace();

        // Search functionality
        const searchInput = document.getElementById("searchInput");
        if (searchInput) {
            searchInput.addEventListener("input", function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const bookRows = document.querySelectorAll(".book-row");

                bookRows.forEach(row => {
                    const title = row.dataset.title.toLowerCase();
                    const author = row.dataset.author.toLowerCase();
                    const genre = row.dataset.genre.toLowerCase();
                    const series = row.dataset.series.toLowerCase();

                    if (title.includes(searchTerm) || 
                        author.includes(searchTerm) || 
                        genre.includes(searchTerm) ||
                        series.includes(searchTerm)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        }

        // Sorting functionality
        const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;
        
        const comparer = (idx, asc) => (a, b) => ((v1, v2) => 
            v1 !== "" && v2 !== "" && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
        )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

        document.querySelectorAll("th.sortable").forEach(th => {
            const icon = th.querySelector("i");
            th.addEventListener("click", () => {
                const table = th.closest("table");
                const tbody = table.querySelector("tbody");
                Array.from(tbody.querySelectorAll("tr"))
                    .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
                    .forEach(tr => tbody.appendChild(tr));
                
                // Update sort icons
                table.querySelectorAll("th i").forEach(i => i.setAttribute("data-feather", "chevron-down"));
                icon.setAttribute("data-feather", this.asc ? "chevron-up" : "chevron-down");
                feather.replace();
            });
        });
    });
    </script>
</body>
</html>';

    file_put_contents('static_html/index.html', $html);
}

function generate_all_static_html() {
    $files = glob('metadata/*.json');
    foreach ($files as $file) {
        $book_id = basename($file, '.json');
        $metadata = json_decode(file_get_contents($file), true);
        generate_static_html_for_book($book_id, $metadata);
    }
    generate_book_list_html();
}

// Generate static HTML for all books when this script is run directly
if (basename($_SERVER['SCRIPT_NAME']) === basename(__FILE__)) {
    generate_all_static_html();
}
?>
