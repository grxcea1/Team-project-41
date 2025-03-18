<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="home.css">
    <link rel="shortcut icon" href="fav">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    
      <script src="sscript.js"></script>

      
      <button id="mode-toggle" onclick="toggleMode()">Switch Mode</button>
  

        <div id="image-container">
          <img id="light-image" src="images/light.jpg" alt="Light Mode Image" class="mode-image" style="display: none;">
          <img id="dark-image" src="images/dark.jpg"  alt="Dark Mode Image" class="mode-image">
      </div>
  
   
    
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
            <a href="home.html">Home</a>
            <a href="ffLoginPage.php">Login</a>
            <a href="aboutus.html">About Us</a>
            <a href="basket.html">Basket</a>
            <a href="account.html">Accounts</a>
            <a href="contact.html">Contact us</a>
        </nav>
    </div>
    
    <div class="contact">
        <h1>Contact Me</h1>
        <p>Feel free to get in touch</p>
        <div class="contact-box">
            <div class="contact-left">
                <h3>Send your request</h3>
                <form action="https://formsubmit.co/grace.adio12@gmail.com" method="POST">
                    <div class="input-row">
                        <div class="input-group">
                            <label>Name</label>
                            <input type="text" name="name" placeholder="Enter your name" required>
                        </div>
                        <div class="input-group">
                            <label>Phone</label>
                            <input type="text" name="phone" placeholder="Enter your phone number" required>
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="input-group">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="input-group">
                            <label>Subject</label>
                            <input type="text" name="subject" placeholder="Enter subject" required>
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="input-group">
                            <label>Confirm Email</label>
                            <input type="email" name="confirm_email" placeholder="Confirm your email" required>
                        </div>
                    </div>
                    <label>Description</label>
                    <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                    <button type="submit">SEND</button>
                </form>
            </div>
            <div class="contact-right">
                <h3>Reach Me</h3>
                <table>
                    <tr>
                        <td>Email</td>
                        <td>FILMFUSE10@aston.ac.uk</td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td>+44 7863451624</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <footer class="footer">
        <hr>
        <p>Join the Film Fuse community today, and let us bring the world of cinema to you.
        Whether you're planning a cozy night in or looking for your next movie obsession,
        we’re here to make it memorable.</p>
    </footer>
</body>
</html>