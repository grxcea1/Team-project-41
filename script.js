

const items = [
    
];

function searchFunction() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const searchResults = document.getElementById('search-results');
    
    searchResults.innerHTML = '';

    const filteredItems = items.filter(item => item.toLowerCase().includes(searchInput));
    
    if (filteredItems.length > 0) {
        filteredItems.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item;
            searchResults.appendChild(li);
        });
    } else {
        const li = document.createElement('li');
        li.textContent = 'No results found';
        li.style.color = 'red';  
        searchResults.appendChild(li);
    }
}
