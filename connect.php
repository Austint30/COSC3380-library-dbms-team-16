<?php
	// --------------------------------------------------------------------------------
	// Old MySQL connection code
	// --------------------------------------------------------------------------------
	/*
	$host = 'team16librarydb.cytnyqknpuhd.us-east-1.rds.amazonaws.com';
	$user = 'admin';
	$pass = 'team16libraryuh';
	$db_name = 'library'; 
	
	$conn = new mysqli($host, $user, $pass, $db_name);
	if($conn->connect_error){
		die('Connection error: '.$conn->connect_error);
	}
	*/

	// --------------------------------------------------------------------------------
	// New SQL Server conection code
	// --------------------------------------------------------------------------------
	$host = 'tcp:library-dbms-ms.cytnyqknpuhd.us-east-1.rds.amazonaws.com,1433';
	$user = 'admin';
	$pass = 'team16libraryuh';
	$db_name = 'library';

	$connectionOptions = array("Database"=>$db_name,"Uid"=>$user, "PWD"=>$pass, "TrustServerCertificate"=>true);
	$conn = sqlsrv_connect($host, $connectionOptions);
	$e = json_encode(sqlsrv_errors());
	if($conn == false){
		die("Failed to connect to SQL Server $e");
	}

	// --------------------------------------------------------------------------------
	// Example user accounts query using SQL Server
	// --------------------------------------------------------------------------------
	/*
	$sql = "SELECT [User ID], [Last Name], [First Name] FROM library.Account";
	$stmt = sqlsrv_query( $conn, $sql );
	if( $stmt === false) {
		die( print_r( sqlsrv_errors(), true) );
	}
	
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
		echo $row['User ID'].", ".$row['Last Name'].", ".$row['First Name'].'<br />';
	}
	*/

	// $sql = "SELECT [User ID], [Last Name], [First Name] FROM library.Account";
	// $stmt = sqlsrv_query( $conn, $sql );
	// sqlsrv_field_metadata($stmt);
	// foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) {
	// 	echo $fieldMetadata["Name"];
	// 	foreach( $fieldMetadata as $name => $value) {
	// 	   echo "$name: $value<br />";
	// 	}
	// 	  echo "<br />";
	// }
?>	