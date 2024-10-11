
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="registrationForm.css">
    <title>Register</title>
</head>
<body class="page">
    <h2>Register</h2>
    <div class="group-1">
    <form method="post" action="ffRegistrationForm.php">
        <input class="input" type="email" name="email" placeholder="Email" required><br>
        <input class="input" type="text" name="username" placeholder="Username" required><br>
        <input class="input" type="password" name="password" placeholder="Password" required><br>
        <button class="input-button" type="submit" name="register">Register</button>
    </form>
    <p>Already have an account? <a href="loginForm.php">Login here</a></p>
    </div>
<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>

<?php

require_once('ffdbConn.php');

if (isset($_POST['register'])){
     $email=isset($_POST['email'])?$_POST['email']:false;
     $username=isset($_POST['username'])?$_POST['username']:false;
     $first_name=isset($_POST['first_name'])?$_POST['first_name']:false;
     $last_name=isset($_POST['last_name'])?$_POST['last_name']:false;
     $password=isset($_POST['password'])?password_hash($_POST['password'],PASSWORD_DEFAULT):false;
     
     if (!($email && preg_match('/^[^@]+@[^@]+\.[^@]+$/', $email))||!($username)||!($first_name)||!($last_name)||!($password)){
        echo "Invalid Email, must have an @";
        exit;
        }
    try{
       
       $stat = $pdo->prepare("INSERT INTO customer(username, first_name, last_name, password, email) VALUES (:username, :first_name, :last_name, :password, :email)");
       $stat->bindParam(':username', $username);
       $stat->bindParam(':first_name', $first_name);
       $stat->bindParam(':last_name', $last_name);
       $stat->bindParam(':password', $password);
       $stat->bindParam(':email', $email);
       
       $stat->execute();

       $id=$pdo->lastInsertId();
       echo "Congratulations! You are now registered. Your ID is: $id  ";  	
       session_start();
                $_SESSION["username"]=$_POST['username'];
               // header("Location:Home.php");
                exit();
       
    }
    catch (PDOexception $ex){
       echo "Sorry, a database error occurred! <br>";
       echo "Error details: <em>". $ex->getMessage()."</em>";
    }
}

?>


