<?php
require_once 'generate_static_html.php';

// Generate all static HTML files
generate_all_static_html();

// Set success message
flash_message('Static HTML files generated successfully');

// Redirect back to library
header('Location: /');
exit;
?>
