<?php
  // create short variable names
  $username=$_POST['username'];
  $password=$_POST['password'];
  $email=$_POST['email'];
  $phonenumber=$_POST['phonenumber'];
  if ((!$username || !$email || !$password || !$phonenumber) {
     echo "You have not entered all the required details.<br />"
          ."Please go back and try again.";
     exit;
  }

  @ $db = new mysqli('localhost','cinema', '', 'user');

  if (mysqli_connect_errno()) {
     echo "Error: Could not connect to database.  Please try again later.";
     exit;
  }

  $query = "insert into user values
            ('".$username."', '".$email."', '".$password."', '".$phonenumber."')";
  $result = $db->query($query);

  if ($result) {
      echo  $db->affected_rows." user inserted into database.";
  } else {
  	  echo "An error has occurred.  The item was not added.";
  }

  $db->close();
?>