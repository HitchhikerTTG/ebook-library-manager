<?php
function validate_metadata($metadata) {
    $required_fields = ['title', 'author_first', 'author_last', 'genre'];
    foreach ($required_fields as $field) {
        if (empty($metadata[$field])) {
            return false;
        }
    }
    return true;
}

function scan_and_sync_metadata() {
    $books = glob('_ksiazki/*.mobi');
    $metadata_files = glob('metadata/*.json');
    $metadata_map = [];
    
    // Create map of existing metadata files
    foreach ($metadata_files as $file) {
        $metadata = json_decode(file_get_contents($file), true);
        if (isset($metadata['filename'])) {
            $metadata_map[$metadata['filename']] = basename($file, '.json');
        }
    }
    
    $orphaned_books = [];
    foreach ($books as $book_path) {
        $filename = basename($book_path);
        if (!isset($metadata_map[$filename])) {
            $orphaned_books[] = $filename;
        }
    }
    
    return $orphaned_books;
}

function create_default_metadata($filename) {
    $book_id = uniqid();
    $title = ucwords(str_replace(['.mobi', '_', '-'], ' ', basename($filename, '.mobi')));
    
    $metadata = [
        'title' => $title,
        'author_first' => 'Unknown',
        'author_last' => 'Author',
        'series' => '',
        'series_position' => '',
        'genre' => 'Uncategorized',
        'filename' => $filename
    ];
    
    file_put_contents(
        'metadata/' . $book_id . '.json',
        json_encode($metadata, JSON_PRETTY_PRINT)
    );
    
    return $book_id;
}
?>
