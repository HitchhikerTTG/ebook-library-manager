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
    <link href="../static/css/custom.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Complete Book Collection</h1>
        <div class="row">
            <div class="col-md-6 mb-4">
                <input type="text" class="form-control" id="searchInput" placeholder="Search books...">
            </div>
        </div>
        <div class="row" id="bookList">';

    foreach ($books as $book) {
        $html .= '
            <div class="col-md-6 col-lg-4 mb-4 book-card" 
                 data-title="' . htmlspecialchars($book['title']) . '"
                 data-author="' . htmlspecialchars($book['author_first'] . ' ' . $book['author_last']) . '"
                 data-genre="' . htmlspecialchars($book['genre']) . '">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">' . htmlspecialchars($book['title']) . '</h5>
                        <p class="card-text">
                            <strong>Author:</strong> ' . htmlspecialchars($book['author_first'] . ' ' . $book['author_last']) . '<br>
                            <strong>Genre:</strong> ' . htmlspecialchars($book['genre']) . '<br>';
        
        if (!empty($book['series'])) {
            $html .= '<strong>Series:</strong> ' . htmlspecialchars($book['series']);
            if (!empty($book['series_position'])) {
                $html .= ' #' . htmlspecialchars($book['series_position']);
            }
            $html .= '<br>';
        }

        $html .= '    </p>
                        <div class="btn-group">
                            <a href="' . htmlspecialchars($book['id']) . '.html" class="btn btn-info">Details</a>
                            <a href="../?route=download&id=' . urlencode($book['id']) . '" class="btn btn-primary">Download</a>
                        </div>
                    </div>
                </div>
            </div>';
    }

    $html .= '
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("searchInput");
        if (searchInput) {
            searchInput.addEventListener("input", function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const bookCards = document.querySelectorAll(".book-card");

                bookCards.forEach(card => {
                    const title = card.dataset.title.toLowerCase();
                    const author = card.dataset.author.toLowerCase();
                    const genre = card.dataset.genre.toLowerCase();

                    if (title.includes(searchTerm) || 
                        author.includes(searchTerm) || 
                        genre.includes(searchTerm)) {
                        card.style.display = "block";
                    } else {
                        card.style.display = "none";
                    }
                });
            });
        }
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
