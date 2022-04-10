<?php
    include 'connect.php';
    include 'require-signin.php';

    if (isset($_GET["userID"])){
        $userID = $_GET["userID"];

        $result = sqlsrv_query($conn, "UPDATE library.library.Account SET library.library.Account.Approved = '1' WHERE (library.library.Account.[User ID] = '$userID');");
        if (!$result){
            $e = sqlsrv_errors();
            $eCode = $e[0][0];
            $eMsg = $e[0][2];
            header("Location: /users.php?errormsg=Failed to approve user. Error code: $eCode: $eMsg");
        }
    }
    
    header("Location: /users.php");

?>