<?php
require_once 'generate_static_html.php';

// Generate static HTML file
generate_all_static_html();

// Set success message
flash_message('Static book list generated successfully as ksiazki.php');

// Redirect back to library
header('Location: /');
exit;
?>
