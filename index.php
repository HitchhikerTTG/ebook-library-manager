<?php
session_start();

// Create necessary directories
if (!file_exists('_ksiazki')) {
    mkdir('_ksiazki', 0777, true);
}
if (!file_exists('metadata')) {
    mkdir('metadata', 0777, true);
}

// Utility functions
function flash_message($message) {
    $_SESSION['flash_message'] = $message;
}

function get_flash_messages() {
    $messages = isset($_SESSION['flash_message']) ? [$_SESSION['flash_message']] : [];
    unset($_SESSION['flash_message']);
    return $messages;
}

// Get all books
function get_books() {
    $books = [];
    $files = glob('metadata/*.json');
    foreach ($files as $file) {
        $content = file_get_contents($file);
        $metadata = json_decode($content, true);
        $metadata['id'] = basename($file, '.json');
        $books[] = $metadata;
    }
    return $books;
}

// Include the appropriate page based on the request
$route = $_GET['route'] ?? 'library';

switch ($route) {
    case 'library':
        $books = get_books();
        include 'templates/library.php';
        break;
    case 'upload':
        include 'upload.php';
        break;
    case 'edit':
        include 'edit.php';
        break;
    case 'delete':
        include 'delete.php';
        break;
    case 'download':
        include 'download.php';
        break;
    case 'manage_metadata':
        include 'manage_metadata.php';
        break;
    case 'generate_static':
        include 'generate_static.php';
        break;
    default:
        $books = get_books();
        include 'templates/library.php';
}
?>
