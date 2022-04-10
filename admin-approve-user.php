<?php
    include 'connect.php';
    include 'require-signin.php';

    if (isset($_GET["userID"])){
        $userID = $_GET["userID"];

        sqlsrv_query($conn, "UPDATE [library].[Account] SET [Approved] = '1' WHERE ([User ID] = '$userID');");
    }
    
    header("Location: /users.php");

?>