<?php
    include 'connect.php';

    $userId = $_GET["userId"];
    $result = sqlsrv_query($conn, "SELECT a.Approved FROM library.library.Account as a WHERE a.[User ID]='$userId'");
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC);
    echo $row[0];
?>