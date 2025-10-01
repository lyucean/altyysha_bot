document.addEventListener('DOMContentLoaded', function() {
    const searchIcon = document.getElementById('mobile-search-icon');
    const searchBar = document.querySelector('.mobile-search-bar');

    if (searchIcon && searchBar) {
        searchIcon.addEventListener('click', function() {
            if (searchBar.style.display === 'none') {
                searchBar.style.display = 'block';
                searchBar.style.animation = 'slideDown 0.3s ease-out';
            } else {
                searchBar.style.display = 'none';
            }
        });
    }
});
