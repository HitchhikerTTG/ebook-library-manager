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

$metadata = json_decode(file_get_contents($metadata_file), true);
$file_path = '_ksiazki/' . $metadata['filename'];

if (!file_exists($file_path)) {
    flash_message('Book file not found');
    header('Location: /');
    exit;
}

header('Content-Type: application/x-mobipocket-ebook');
header('Content-Disposition: attachment; filename="' . basename($metadata['filename']) . '"');
header('Content-Length: ' . filesize($file_path));
readfile($file_path);
exit;
?>
