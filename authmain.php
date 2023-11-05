<?php //authmain.php
include "dbconnect.php";
session_start();

if (isset($_POST['username']) && isset($_POST['password']))
{
 $username = $_POST['username'];
 $password = $_POST['password'];
 //compare with database password
 $query = 'SELECT * FROM users '   // Added spaces after SELECT and FROM
        . "WHERE username='$username' "  // Added space after WHERE
        . "AND password='$password'";

 $result = $db->query($query);
 if($result->num_rows>0)
 {
	 // if they are in the database register the username
	 $_SESSION['valid_user']=$username;
 }
 $db->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>MZ Cinema</title>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div id="wrapper">
  <div class="top">
    <div class="logo">
        <img src="./img/logo.png" width="120" height="40">
    </div>
    <div class="document">
      <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="movies.php">Movies</a></li>
          <li><a href="cinemas.php">Cinemas</a></li>
          <li><a href="promotions.php">Promotions</a></li>
          <li><a href="dining.php">Dining</a></li> 
          <li><a href="mine.php">Mine</a></li>
      </ul>
    </div>
    <div class="top-right">
    <div class="log">
        <div class="logbutton">
            <a href="login.php">Log in</a>
        </div>
    </div>
    <div class="cart">
        <a href="cart.php"><img src="./img/cart.png" width="40" height="40"></a>
    </div>
    </div>
  </div>
  <div class="main">
    <div class="content">
     
    </div>
  </div>
  <div class="footer">
    <footer>
      <small><i><br>Copyright &copy; Movie Zoomer Cinema Pte Ltd</i>
        <br><br>Email us: <a href="mailto:MZCinema@mz.com">MZCinema@mz.com</a>
        <br><br>Follow us: <img src="./img/iglogo.png" width="30" height="30">  <img src="./img/facebooklogo.png" width="30" height="30">  <img src="./img/tiktoklogo.png" width="30" height="30"></small>
    </footer>
  </div>
</div>
</body>
</html>
