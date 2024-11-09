<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ebook Library - Complete Collection</title>
    <link href="https://cdn.replit.com/agent/bootstrap-agent-dark-theme.min.css" rel="stylesheet">
    <link href="static/css/custom.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Complete Book Collection</h1>
        <div class="row mb-4">
            <div class="col-md-6">
                <input type="text" class="form-control" id="searchInput" placeholder="Search books...">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Books</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("searchInput");
        if (searchInput) {
            searchInput.addEventListener("input", function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const bookRows = document.querySelectorAll(".book-row");

                bookRows.forEach(row => {
                    const title = row.dataset.title.toLowerCase();
                    const author = row.dataset.author.toLowerCase();

                    if (title.includes(searchTerm) || author.includes(searchTerm)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        }
    });
    </script>
</body>
</html>