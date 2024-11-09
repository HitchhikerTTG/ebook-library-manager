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

function generate_all_static_html() {
    $files = glob('metadata/*.json');
    foreach ($files as $file) {
        $book_id = basename($file, '.json');
        $metadata = json_decode(file_get_contents($file), true);
        generate_static_html_for_book($book_id, $metadata);
    }
}

// Generate static HTML for all books when this script is run directly
if (basename($_SERVER['SCRIPT_NAME']) === basename(__FILE__)) {
    generate_all_static_html();
}
?>
