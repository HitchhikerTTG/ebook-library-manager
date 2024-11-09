<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title mb-4">Edit Book Metadata</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="author_first" class="form-label">Author First Name</label>
                            <input type="text" class="form-control" id="author_first" name="author_first" value="<?php echo htmlspecialchars($book['author_first']); ?>" required>
                        </div>
                        <div class="col">
                            <label for="author_last" class="form-label">Author Last Name</label>
                            <input type="text" class="form-control" id="author_last" name="author_last" value="<?php echo htmlspecialchars($book['author_last']); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="genre" class="form-label">Genre</label>
                        <input type="text" class="form-control" id="genre" name="genre" value="<?php echo htmlspecialchars($book['genre']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="series" class="form-label">Series (optional)</label>
                        <input type="text" class="form-control" id="series" name="series" value="<?php echo htmlspecialchars($book['series']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="series_position" class="form-label">Series Position (optional)</label>
                        <input type="number" class="form-control" id="series_position" name="series_position" value="<?php echo htmlspecialchars($book['series_position']); ?>">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="/" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
