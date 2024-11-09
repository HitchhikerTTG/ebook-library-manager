<?php
if (!isset($_GET['id'])) {
    flash_message('Book not found');
    header('Location: /');
    exit;
}

$book_id = $_GET['id'];
$metadata_file = 'metadata/' . $book_id . '.json';

if (!file_exists($metadata_file)) {
    flash_message('Book not found');
    header('Location: /');
    exit;
}

$book = json_decode(file_get_contents($metadata_file), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book = array_merge($book, [
        'title' => $_POST['title'],
        'author_first' => $_POST['author_first'],
        'author_last' => $_POST['author_last'],
        'series' => $_POST['series'],
        'series_position' => $_POST['series_position'],
        'genre' => $_POST['genre']
    ]);
    
    file_put_contents($metadata_file, json_encode($book, JSON_PRETTY_PRINT));
    
    // Generate static HTML for the updated book
    require_once 'generate_static_html.php';
    generate_static_html_for_book($book_id, $book);
    
    flash_message('Metadata updated successfully');
    header('Location: /');
    exit;
}

$content = 'templates/_edit_content.php';
include 'templates/base.php';
?>
