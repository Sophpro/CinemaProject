<?php // register.php
include "dbconnect.php";
if (isset($_POST['submit'])) {
	if (empty($_POST['username']) || empty ($_POST['password'])
		|| empty ($_POST['password2']) || empty ($_POST['email'])|| empty ($_POST['telephone'])) {
	echo "All records to be filled in";
	exit;}
	}
$username = $_POST['username'];
$password = $_POST['password'];
$password2 = $_POST['password2'];
$email=$_POST['email'];
$telephone=$_POST['telephone'];


// echo ("$username" . "<br />". "$password2" . "<br />");
if ($password != $password2) {
	echo "Sorry passwords do not match";
	exit;
	}
// echo $password;
 $query = "INSERT INTO users (username, email, password, telephone) VALUES
            ('".$username."', '".$email."', '".$password."', '".$telephone."')";
$result = $db->query($query);


if (!$result) 
	echo "Your query failed.";
else
	echo "Welcome ". $username . ". You are now registered";
	
?>