const paymentOptions = document.querySelectorAll('input[name="payment"]');
const cardInfoSection = document.getElementById('cardInfo');

// Toggle the function
function toggleCardInfo() {
    // Check the selected payment method
    const selectedPayment = document.querySelector('input[name="payment"]:checked').value;

    // When credit is selected, show this>>
    if (selectedPayment === 'credit') {
        cardInfoSection.style.display = 'block';
    } else {
        cardInfoSection.style.display = 'none';
    }
}

// Toggle for ohters
paymentOptions.forEach(option => {
    option.addEventListener('change', toggleCardInfo);
});

// When page loads>>
toggleCardInfo();
