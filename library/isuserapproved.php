<?php
    include 'connect.php';

    $userId = $_GET["userId"];
    $result = $conn->query("SELECT Account.Approved FROM Account WHERE Account.`User ID`='$userId'");
    $rows = $result->fetch_array();
    echo $rows[0];
?>