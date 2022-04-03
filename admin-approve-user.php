<?php
    include 'connect.php';
    include 'require-signin.php';

    if (isset($_GET["userID"])){
        $userID = $_GET["userID"];

        $conn->query("UPDATE `library`.`Account` SET `Approved` = '1' WHERE (`User ID` = '$userID');");
    }
    
    header("Location: /admin.php");

?>