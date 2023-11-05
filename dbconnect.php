<?php
@$db = new mysqli('localhost','root','', 'mzcinema');
// @ to ignore error message display //
if ($db->connect_error){
	echo "Database is not online"; 
	exit;
	// above 2 statments same as die() //
	}
/*	else
	echo "Congratulations...  MySql is working..";
*/
if (!$db->select_db ("mzcinema"))
	exit("<p>Unable to locate the mzcinema database</p>");
?>	