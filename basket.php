<?php
require_once("ffdbConn.php");

// Getting all movies from the database
$stmt = $pdo->query("SELECT pid, p_Name, p_Image, p_Price FROM product");
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Basket - Film Fuse</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="basket.css">
</head>
<body>

    <button id="mode-toggle" onclick="toggleMode()">Switch Mode</button>

    <div id="image-container">
        <img id="light-image" src="images/light.jpg" alt="Light Mode Image" class="mode-image" style="display: none;">
        <img id="dark-image" src="images/dark.jpg" alt="Dark Mode Image" class="mode-image">
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
            <a href="aboutus.php">About Us</a>
            <a href="account.php">Accounts</a>
            <a href="contact.php">Contact us</a>
        </nav>
    </div>

    <main>
        <!-- Movie Basket Section -->
        <div class="movie-basket">
            <h2>Your Basket</h2>
            <ul class="basket-items" id="basketItems"></ul>
            <p class="total">Total: £<span id="totalPrice">0.00</span></p>

            <div class="discount-input">
                <input type="text" id="discountCode" placeholder="Enter Discount Code">
                <button onclick="applyDiscount()">Apply</button>
            </div>
            <p class="discount" id="discountMessage"></p>

            <div class="action-buttons">
                <a href="home.php"><button>Add More Movies</button></a>
                <a href="checkOut.php"><button onclick="proceedToPayment()">Check Out</button></a>
            </div>
        </div>
    </main>

    <script>
        let discount = 0;
        let totalPrice = 0;

        function loadBasket() {
            let cart = JSON.parse(localStorage.getItem("cart")) || {};
            let basketItems = document.getElementById("basketItems");
            totalPrice = 0;
            basketItems.innerHTML = "";

            if (Object.keys(cart).length === 0) {
                basketItems.innerHTML = "<p>Your basket is empty.</p>";
                document.getElementById("totalPrice").textContent = "0.00";
                return;
            }

            Object.keys(cart).forEach(productId => {
                let item = cart[productId];

                if (!item || !item.price || !item.quantity) return;

                let itemTotal = item.price * item.quantity;
                totalPrice += itemTotal;

                let imgSrc =  item.imageUrl;  

                let listItem = document.createElement("li");
                listItem.innerHTML = `
                    <img src="${imgSrc}" alt="${item.name}" width="50" height="70" onerror="this.onerror=null; this.src='images/default.png';">
                    <span>${item.name} - £${item.price.toFixed(2)} x </span>
                    <input type="number" min="1" value="${item.quantity}" onchange="updateQuantity('${productId}', this.value)">
                    <button onclick="removeFromBasket('${productId}')">Remove</button>
                `;
                basketItems.appendChild(listItem);
            });

            updateTotalPrice();
        }

        function updateQuantity(productId, quantity) {
            let cart = JSON.parse(localStorage.getItem("cart")) || {};
            if (cart[productId]) {
                cart[productId].quantity = parseInt(quantity);
                localStorage.setItem("cart", JSON.stringify(cart));
                loadBasket();
            }
        }

        function removeFromBasket(productId) {
            let cart = JSON.parse(localStorage.getItem("cart")) || {};
            delete cart[productId];
            localStorage.setItem("cart", JSON.stringify(cart));
            loadBasket();
        }

        function updateTotalPrice() {
            document.getElementById("totalPrice").textContent = (totalPrice - discount).toFixed(2);
            localStorage.setItem("totalPrice", totalPrice.toFixed(2));
        }

        function applyDiscount() {
            const code = document.getElementById("discountCode").value;
            if (code === 'FILMFUSE10') {
                discount = totalPrice * 0.10;
                document.getElementById("discountMessage").textContent = '10% discount applied!';
            } else {
                discount = 0;
                document.getElementById("discountMessage").textContent = 'Invalid discount code.';
            }
            updateTotalPrice();
        }

        function proceedToPayment() {
            if (Object.keys(JSON.parse(localStorage.getItem("cart")) || {}).length === 0) {
                alert("Your basket is empty.");
                return;
            }
            alert(`Proceeding to payment. Total: £${document.getElementById("totalPrice").textContent}`);
        }

        window.onload = loadBasket;
    </script>

</body>
</html>
