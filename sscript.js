document.addEventListener("DOMContentLoaded", function() {
    let slideIndex = 1;
    showSlides(slideIndex);
    
    window.plusSlides = function (n) {
      showSlides(slideIndex += n);
    }
    
   
    window.currentSlide = function (n) {
      showSlides(slideIndex = n);
    }
    
    //function to show correct slide 
    function showSlides(n) {
      let i;
      let slides = document.getElementsByClassName("mySlides");
      let dots = document.getElementsByClassName("dot");
      if (n > slides.length) {slideIndex = 1}
      if (n < 1) {slideIndex = slides.length}
      for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
      }
      for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
      }
      slides[slideIndex-1].style.display = "block";
      dots[slideIndex-1].className += " active";
    }
    });

    
    function toggleMode() {
      const body = document.body;
      const lightImage = document.getElementById('light-image');
      const darkImage = document.getElementById('dark-image');
  
      body.classList.toggle('dark-mode');
      lightImage.style.display = lightImage.style.display === 'none' ? 'block' : 'none';
      darkImage.style.display = darkImage.style.display === 'none' ? 'block' : 'none';
  }

  
  
    