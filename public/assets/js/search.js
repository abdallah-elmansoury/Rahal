// Search functionality enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Add enter key support for search
    const searchInputs = document.querySelectorAll('input[name="query"]');
    searchInputs.forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.closest('form').submit();
            }
        });
    });

    // Search suggestions (optional - for future enhancement)
    function setupSearchSuggestions() {
        const searchInput = document.querySelector('input[name="query"]');
        if (searchInput) {
            // You can implement AJAX search suggestions here
            searchInput.addEventListener('input', function() {
                // Future: Add AJAX call to get search suggestions
            });
        }
    }
    
    setupSearchSuggestions();
});