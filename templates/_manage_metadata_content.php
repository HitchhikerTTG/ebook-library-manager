<div class="container">
    <h2 class="mb-4">Manage Book Metadata</h2>
    
    <?php if (empty($orphaned_books)): ?>
    <div class="alert alert-info">
        All books in the library have associated metadata.
    </div>
    <?php else: ?>
    <div class="alert alert-warning">
        Found <?php echo count($orphaned_books); ?> book(s) without metadata.
    </div>
    
    <form method="POST">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Filename</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orphaned_books as $filename): ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="selected_books[]" value="<?php echo htmlspecialchars($filename); ?>">
                        </td>
                        <td><?php echo htmlspecialchars($filename); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <button type="submit" name="create_metadata" class="btn btn-primary">
            Create Metadata for Selected Books
        </button>
    </form>
    <?php endif; ?>
</div>
