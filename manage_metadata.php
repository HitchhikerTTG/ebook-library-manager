<?php
require_once 'utils.php';

$orphaned_books = scan_and_sync_metadata();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_metadata'])) {
        foreach ($_POST['selected_books'] as $filename) {
            create_default_metadata($filename);
        }
        flash_message('Metadata created for selected books');
        header('Location: /');
        exit;
    }
}

$content = 'templates/_manage_metadata_content.php';
include 'templates/base.php';
?>
