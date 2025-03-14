

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

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("review-form");
    const reviewList = document.getElementById("review-list");

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        const reviewText = document.getElementById("review-text").value.trim();
        const rating = document.getElementById("rating").value;

        if (reviewText === "") {
            alert("Please enter a review before submitting.");
            return;
        }

        const reviewEntry = document.createElement("div");
        reviewEntry.classList.add("review-item");
        reviewEntry.innerHTML = `<p><strong>Rating:</strong> ${"★".repeat(rating)} (${rating} stars)</p>
                                <p>${reviewText}</p><hr>`;

        reviewList.appendChild(reviewEntry);

        form.reset();
    });
});

