<?php
	
	$host = 'team16librarydb.cytnyqknpuhd.us-east-1.rds.amazonaws.com';
	$user = 'admin';
	$pass = 'team16libraryuh';
	$db_name = 'library'; 
	
	$conn = new PDO("mysql:host=$host;dbname=$db_name", $user, $pass);
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0); // Allows multiple queries in one statement it seems
	// if($conn->connect_error){
	// 	die('Connection error: '.$conn->connect_error);
	// }

?>	