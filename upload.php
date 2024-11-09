<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['book'])) {
        flash_message('No file selected');
        header('Location: /');
        exit;
    }
    
    $book = $_FILES['book'];
    if ($book['error'] !== UPLOAD_ERR_OK) {
        flash_message('Error uploading file');
        header('Location: /');
        exit;
    }
    
    if (!str_ends_with(strtolower($book['name']), '.mobi')) {
        flash_message('Only .mobi files are allowed');
        header('Location: /');
        exit;
    }
    
    $book_id = uniqid();
    $filename = $book_id . '.mobi';
    
    if (move_uploaded_file($book['tmp_name'], '_ksiazki/' . $filename)) {
        $metadata = [
            'title' => $_POST['title'] ?? 'Untitled',
            'author_first' => $_POST['author_first'] ?? '',
            'author_last' => $_POST['author_last'] ?? '',
            'series' => $_POST['series'] ?? '',
            'series_position' => $_POST['series_position'] ?? '',
            'genre' => $_POST['genre'] ?? '',
            'filename' => $filename
        ];
        
        file_put_contents(
            'metadata/' . $book_id . '.json',
            json_encode($metadata, JSON_PRETTY_PRINT)
        );
        
        // Generate static HTML for the new book
        require_once 'generate_static_html.php';
        generate_static_html_for_book($book_id, $metadata);
        
        flash_message('Book uploaded successfully');
    } else {
        flash_message('Error saving file');
    }
    
    header('Location: /');
    exit;
}

header('Location: /');
exit;
?>
