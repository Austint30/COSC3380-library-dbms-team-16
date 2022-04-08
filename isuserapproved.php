<?php
    include 'connect.php';

    $userId = $_GET["userId"];
    $stmt = sqlsrv_query($conn, "SELECT Account.Approved FROM Account WHERE Account.[User ID]='$userId'"));
    $rows = $result->fetch_array();
    echo $rows[0];
?>