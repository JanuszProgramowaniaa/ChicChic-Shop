document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('#search-form');
    const searchInput = document.querySelector('input[name="search"]');

    searchForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const searchTerm = searchInput.value.trim();
        const page = 1;

        const searchURL = searchForm.dataset.searchUrl.replace('value', encodeURIComponent(searchTerm));

        window.location.href = `${searchURL}/${page}`;
    });
});