document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather icons
    feather.replace();

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const bookCards = document.querySelectorAll('.book-card');

            bookCards.forEach(card => {
                const title = card.dataset.title.toLowerCase();
                const author = card.dataset.author.toLowerCase();
                const genre = card.dataset.genre.toLowerCase();

                if (title.includes(searchTerm) || 
                    author.includes(searchTerm) || 
                    genre.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // Select All functionality for metadata management
    const selectAllCheckbox = document.getElementById('selectAll');
    const checkboxes = document.getElementsByName('selected_books[]');
    
    if (selectAllCheckbox && checkboxes.length > 0) {
        selectAllCheckbox.addEventListener('change', function() {
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    }
});
