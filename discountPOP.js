window.onload = function () {
    const popup = document.getElementById('discount-popup');
    const closeBtn = document.querySelector('.close_btn');
  
    setTimeout(() => {
      popup.style.display = 'block';
    }, 4500);
  
    closeBtn.onclick = () => {
      popup.style.display = 'none';
    };
  
    window.onclick = (event) => {
      if (event.target === popup) {
        popup.style.display = 'none';
      }
    };
  };
  