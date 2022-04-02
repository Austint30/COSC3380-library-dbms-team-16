<?php
	
	$host = 'team16librarydb.cytnyqknpuhd.us-east-1.rds.amazonaws.com';
	$user = 'admin';
	$pass = 'team16libraryuh';
	$db_name = 'library'; 
	
	$conn = new mysqli($host, $user, $pass, $db_name);
	if($conn->connect_error){
		die('Connection error: '.$conn->connect_error);
	}

?>	