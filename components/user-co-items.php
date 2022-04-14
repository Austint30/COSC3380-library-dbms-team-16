<?php
    include 'user-co-items-functions.php';

    if (isset($_GET["userID"])){
        $userID = $_GET["userID"];
        $tableClass = isset($_GET["tableClass"]) ? $_GET["tableClass"] : "";
        $showCheckInBtn = isset($_GET['showCheckBtn']) ? $_GET['showCheckBtn'] : false;

        try {
            userCoItemsTable($userID, $tableClass, $showCheckInBtn);
        }
        catch(SqlQueryException $e){
            http_response_code(500);
            echo $e->getMessage();
        }
    }
?>