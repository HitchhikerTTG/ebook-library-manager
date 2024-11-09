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

try {
    $metadata = json_decode(file_get_contents($metadata_file), true);
    $book_file = '_ksiazki/' . $metadata['filename'];
    
    if (file_exists($book_file)) {
        unlink($book_file);
    }
    unlink($metadata_file);
    
    flash_message('Book deleted successfully');
} catch (Exception $e) {
    flash_message('Error deleting book');
}

header('Location: /');
exit;
?>
