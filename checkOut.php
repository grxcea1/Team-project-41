<?php
require_once("ffdbConn.php");
session_start();

$email = $_SESSION['Email'];

if (!isset($_SESSION["uid"]) && !isset($_SESSION["Email"])) {
    $_SESSION["no_account2"] = "You must be logged in or registered to buy a movie.";
    header("Location: ffLoginPage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Form</title>
    <link rel="stylesheet" href="checkOut.css">
    <link rel="shortcut icon" href="fav">
</head>
<body>
    
<div class="pageBox">
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
    <div class="sidebox">
        <nav class="nav-bar">
            <a href="home.php">Home</a>
            <a href="aboutus.php">About Us</a>
            <a href="basket.php">Basket</a>
        </nav>
    </div>

    <div class="checkoutBox">
        <form id="checkoutForm" action="processOrder.php" method="POST">
            <h3>Checkout</h3>
            <h3>Delivery details: </h3>
            <div class="form1">
                <label for="country">Country/Region</label>
                <select id="country" name="country" required>
                    <option value="">Select a Country</option>
                    <option value="USA">United States</option>
                    <option value="CAN">Canada</option>
                    <option value="UK">United Kingdom</option>
                </select>
            </div>
            <div class="form1">
                <label for="address">Address</label>
                <input type="text" id="address" name="addressLine1" required placeholder="Street address, P.O. box, company name, etc.">
            </div>
            <div class="form1 form2">
                <label for="city">City</label>
                <input type="text" id="city" name="city" required>
            </div>
            <div class="form1 form2">
                <label for="zip">ZIP/Postal Code</label>
                <input type="text" id="zip" name="postCode" required>
            </div>
            <div class="form1">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="m_Number" required>
            </div>

            <div class="basketdata">
                <input type="hidden" name="cartData" id="cartData">
                <input type="hidden" name="totalPrice" id="totalPrice">
            </div>

            <h3>Payment</h3>
            <div class="paymentOptions">
                <label>
                    <input type="radio" name="payment" value="credit" checked> Credit Card
                </label>
                <label>
                    <input type="radio" name="payment" value="paypal"> PayPal
                </label>
                <label>
                    <input type="radio" name="payment" value="GooglePay"> Google Pay
                </label>
            </div>

            <div id="cardInfo">
                <div class="form-group">
                    <label for="card-name">Cardholder Name</label>
                    <input type="text" id="card-name" name="card-name">
                </div>
                <div class="form-group">
                    <label for="card-number">Card Number</label>
                    <input type="text" id="card-number" name="card-number" pattern="\d{16}" placeholder="1234 5678 9012 3456">
                </div>
                <div class="form-group half-width">
                    <label for="exp-date">Expiration Date</label>
                    <input type="text" id="exp-date" name="exp-date" placeholder="MM / YY">
                </div>
                <div class="form-group half-width">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" pattern="\d{3}" placeholder="123">
                </div>
            </div>

            <button type="submit">Pay Now</button>
        </form>
    </div>
</div>

<script src="checkOut.js"></script>
<script>
    let cart = JSON.parse(localStorage.getItem("cart")) || {};
    let totalPrice = localStorage.getItem("totalPrice") || "0.00";

    document.getElementById("checkoutForm").addEventListener("submit", function () {
        document.getElementById("cartData").value = JSON.stringify(cart);
        document.getElementById("totalPrice").value = totalPrice;
        localStorage.removeItem("cart");
        localStorage.removeItem("totalPrice");
    });
</script>
</body>
</html>
