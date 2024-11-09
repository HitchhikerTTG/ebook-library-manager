<div class="row mb-4">
    <div class="col-md-6">
        <input type="text" class="form-control" id="searchInput" placeholder="Search books...">
    </div>
    <div class="col-md-6 text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
            Upload Book
        </button>
    </div>
</div>

<div class="row" id="bookList">
    <?php foreach ($books as $book): ?>
    <div class="col-md-6 col-lg-4 mb-4 book-card" 
         data-title="<?php echo htmlspecialchars($book['title']); ?>" 
         data-author="<?php echo htmlspecialchars($book['author_first'] . ' ' . $book['author_last']); ?>"
         data-genre="<?php echo htmlspecialchars($book['genre']); ?>">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                <p class="card-text">
                    <strong>Author:</strong> <?php echo htmlspecialchars($book['author_first'] . ' ' . $book['author_last']); ?><br>
                    <strong>Genre:</strong> <?php echo htmlspecialchars($book['genre']); ?><br>
                    <?php if (!empty($book['series'])): ?>
                    <strong>Series:</strong> <?php echo htmlspecialchars($book['series']); ?> #<?php echo htmlspecialchars($book['series_position']); ?><br>
                    <?php endif; ?>
                </p>
                <div class="btn-group">
                    <a href="?route=download&id=<?php echo urlencode($book['id']); ?>" class="btn btn-secondary">Download</a>
                    <a href="?route=edit&id=<?php echo urlencode($book['id']); ?>" class="btn btn-info">Edit</a>
                    <a href="?route=delete&id=<?php echo urlencode($book['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="?route=upload" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="book" class="form-label">Book File (.mobi)</label>
                        <input type="file" class="form-control" id="book" name="book" accept=".mobi" required>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="author_first" class="form-label">Author First Name</label>
                            <input type="text" class="form-control" id="author_first" name="author_first" required>
                        </div>
                        <div class="col">
                            <label for="author_last" class="form-label">Author Last Name</label>
                            <input type="text" class="form-control" id="author_last" name="author_last" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre</label>
                        <input type="text" class="form-control" id="genre" name="genre" required>
                    </div>
                    <div class="mb-3">
                        <label for="series" class="form-label">Series (optional)</label>
                        <input type="text" class="form-control" id="series" name="series">
                    </div>
                    <div class="mb-3">
                        <label for="series_position" class="form-label">Series Position (optional)</label>
                        <input type="number" class="form-control" id="series_position" name="series_position">
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>
