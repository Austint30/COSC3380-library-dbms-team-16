<?php
    include 'connect.php';
    include 'require-signin.php';

    if (isset($_GET["userID"])){
        $userID = $_GET["userID"];

        $sql = "USE library
        SELECT * FROM library.Account WHERE library.Account.[User ID]=?;";

        $result = sqlsrv_query($conn, $sql, array($userID));

        $user = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

        if (!$user){
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode((object)[]); // Return empty object
            return;
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($user);
    }
?>