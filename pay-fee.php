<?php
    include 'connect.php';
    $_POST = json_decode(file_get_contents('php://input'), true);
    if (isset($_POST["feeid"]) && isset($_POST["amount"])){

        $amount = $_POST["amount"];
        $feeID = $_POST["feeid"];

        $sql = "UPDATE library.library.Fee SET library.library.Fee.[Amount Paid] = library.library.Fee.[Amount Paid] + ? WHERE (library.library.Fee.[Fee ID] = ?);";

        $result = sqlsrv_query($conn, $sql, array($amount, $feeID));

        if (!$result){
            $e = json_encode(sqlsrv_errors());
            http_response_code(500);
            echo "An error occurred. Error: $e";
            return;
        }

        http_response_code(200);
        echo true;
        return;
    }
    else
    {
        http_response_code(400);
        echo "Must be a POST request with 'amount' and 'feeid' fields!";
        return;
    }
?>