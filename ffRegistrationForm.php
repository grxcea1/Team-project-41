
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
        <input class="input" type="first_name" name="first_name" placeholder="First Name" required><br>
        <input class="input" type="last_name" name="last_name" placeholder="Last Name" required><br>
        <input class="input" type="password" name="password" placeholder="Password" required><br>
        <button class="input-button" type="submit" name="register">Register</button>
    </form>
    <p>Already have an account? <a href="ffLoginPage.php">Login here</a></p>
    </div>
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
       echo "Congratulations $username! You are now registered.";  	
       session_start();
                $_SESSION["username"]=$_POST['username'];
                exit();
       
    }
    catch (PDOexception $ex){
       echo "Sorry, a database error occurred! <br>";
       echo "Error details: <em>". $ex->getMessage()."</em>";
    }
}

?>


