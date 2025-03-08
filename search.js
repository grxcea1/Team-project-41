document.getElementById('searchInput').addEventListener('input', function() {
    var input = this.value;
    if (input.length > 2) { // Only search if more than 2 characters are entered
        fetch('http://localhost/search.php?search_query=' + encodeURIComponent(input))
        .then(response => response.json())
        .then(data => {
            var suggestionBox = document.getElementById('suggestionBox');
            suggestionBox.innerHTML = ''; // Clear previous suggestions
            data.forEach(function(item) {
                var div = document.createElement('div');
                div.textContent = item;
                suggestionBox.appendChild(div);
            });
        })
        .catch(error => console.error('Error:', error));
    } else {
        document.getElementById('suggestionBox').innerHTML = '';
    }
});
