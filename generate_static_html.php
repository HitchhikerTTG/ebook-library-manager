<?php
require_once 'utils.php';

function generate_static_html_for_book($book_id, $metadata) {
    // Individual book generation is no longer needed, as per the manager's requirements
    return;
}

function generate_book_list_html() {
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
    <link href="static/css/custom.css" rel="stylesheet">
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
                <tr class="book-row" data-title="' . $title . '" data-author="' . $author . '">
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
        if (searchInput) {
            searchInput.addEventListener("input", function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const bookRows = document.querySelectorAll(".book-row");

                bookRows.forEach(row => {
                    const title = row.dataset.title.toLowerCase();
                    const author = row.dataset.author.toLowerCase();

                    if (title.includes(searchTerm) || author.includes(searchTerm)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        }
    });
    </script>
</body>
</html>';

    file_put_contents('ksiazki.php', $html);
}

function generate_all_static_html() {
    generate_book_list_html();
}

// Generate static HTML for all books when this script is run directly
if (basename($_SERVER['SCRIPT_NAME']) === basename(__FILE__)) {
    generate_all_static_html();
}
?>