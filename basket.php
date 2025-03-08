<!DOCTYPE html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Movie Basket - Film Fuse</title>
        <link rel="stylesheet" href="home.css">
        <link rel="stylesheet" href="basket.css">
    </head>
    <body>
        <!-- Toggle Button to Switch Backgrounds -->
        <button id="mode-toggle" onclick="toggleMode()">Switch Mode</button>

        <!-- Light Mode and Dark Mode Images -->
        <div id="image-container">
            <img id="light-image" src="images/light.jpg" alt="Light Mode Image" class="mode-image" style="display: none;">
            <img id="dark-image" src="images/dark.jpg"  alt="Dark Mode Image" class="mode-image">
        </div>

        <!-- Header with Logo -->
        <header>
            <div class="logo-container">
                <div class="circle">
                    <div class="text">
                        <span class="initials">FF</span>
                    </div>
                </div>
                <div class="logo-name">Film Fuse</div>
            </div>
        </header>

        <!-- Navigation menu -->
        <div class="sidebox">
            <nav class="nav-bar">
                <a href="home.php">Home</a>
                <a href="ffLoginPage.php">Login</a>
                <a href="aboutus.html">About Us</a>
                <a href="basket.html">Basket</a>
                <a href="account.html">Accounts</a>
                <a href="contact.html">Contact us</a>
            </nav>
        </div>

        <main>
            <!-- Movie Basket Section -->
            <div class="movie-basket">
                <h2>Your Basket</h2>
                <ul class="basket-items" id="basketItems"></ul>
                <p class="total">Total: $<span id="totalPrice">0.00</span></p>

                <div class="discount-input">
                    <input type="text" id="discountCode" placeholder="Enter Discount Code">
                    <button onclick="applyDiscount()">Apply</button>
                </div>
                <p class="discount" id="discountMessage"></p>

                <div class="action-buttons">
                    <a href="home.html">Add More Movies</a>
                    <button onclick="proceedToPayment()">Check Out</button>
                </div>

                <!-- Saved for Later Section -->
                <div id="savedContainer" style="display: none;">
                    <h3>Saved for Later</h3>
                    <ul class="saved-items" id="savedItems"></ul>
                </div>
            </div>

        </main>
        
        <script>
            let basket = [];
            let savedForLater = [];
            let totalPrice = 0;
            let discount = 0;

            function addToBasket(movie) {
                basket.push(movie);
                updateBasketDisplay();
            }

            function updateBasketDisplay() {
                const basketItems = document.getElementById('basketItems');
                basketItems.innerHTML = '';
                totalPrice = 0;

                basket.forEach((movie, index) => {
                    totalPrice += movie.price * movie.quantity;
                    basketItems.innerHTML += `
                        <li>
                            ${movie.name} - $${movie.price.toFixed(2)} x 
                            <input type="number" min="1" value="${movie.quantity}" onchange="updateQuantity(${index}, this.value)">
                            <button class="remove-btn" onclick="removeFromBasket(${index})">X</button>
                            <button class="save-btn" onclick="saveForLater(${index})">Save</button>
                        </li>
                    `;
                });

                document.getElementById('totalPrice').textContent = (totalPrice - discount).toFixed(2);
            }

            function updateQuantity(index, quantity) {
                basket[index].quantity = parseInt(quantity);
                updateBasketDisplay();
            }

            function removeFromBasket(index) {
                basket.splice(index, 1);
                updateBasketDisplay();
            }

            function saveForLater(index) {
                const item = basket.splice(index, 1)[0];
                savedForLater.push(item);
                updateBasketDisplay();
                updateSavedItemsDisplay();
            }

            function updateSavedItemsDisplay() {
                const savedItems = document.getElementById('savedItems');
                const savedContainer = document.getElementById('savedContainer');
                savedItems.innerHTML = '';

                if (savedForLater.length > 0) {
                    savedContainer.style.display = 'block';
                    savedForLater.forEach((movie, index) => {
                        savedItems.innerHTML += `
                            <li>
                                ${movie.name} - $${movie.price.toFixed(2)} x ${movie.quantity}
                                <button onclick="moveBackToBasket(${index})">Move Back</button>
                                <button class="remove-btn" onclick="removeFromSaved(${index})">Remove</button>
                            </li>
                        `;
                    });
                } else {
                    savedContainer.style.display = 'none';
                }
            }

            function moveBackToBasket(index) {
                const item = savedForLater.splice(index, 1)[0];
                basket.push(item);
                updateBasketDisplay();
                updateSavedItemsDisplay();
            }

            function removeFromSaved(index) {
                savedForLater.splice(index, 1);
                updateSavedItemsDisplay();
            }

            function applyDiscount() {
                const code = document.getElementById('discountCode').value;
                if (code === 'FILMFUSE10') {
                    discount = totalPrice * 0.10;
                    document.getElementById('discountMessage').textContent = '10% discount applied!';
                } else {
                    discount = 0;
                    document.getElementById('discountMessage').textContent = 'Invalid discount code.';
                }
                updateBasketDisplay();
            }

            function proceedToPayment() {
                if (basket.length === 0) {
                    alert('Your basket is empty.');
                    return;
                }
                alert(`Proceeding to payment. Total: $${(totalPrice - discount).toFixed(2)}`);
            }
        </script>
    </body>
</html>