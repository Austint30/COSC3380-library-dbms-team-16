<?php
    include 'user-holds-functions.php';

    if (isset($_GET["userID"])){
        $userID = $_GET["userID"];
        $tableClass = isset($_GET["tableClass"]) ? $_GET["tableClass"] : "";
        $showCheckInHoldBtn = isset($_GET['showCheckBtn']) ? $_GET['showCheckBtn'] : false;

        try {
            userHoldsTable($userID, $tableClass, $showCheckInHoldBtn);
        }
        catch(SqlQueryException $e){
            http_response_code(500);
            echo $e->getMessage();
        }
    }
?>