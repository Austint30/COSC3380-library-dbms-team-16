<?php
    include 'connect.php';
    include 'require-signin.php';
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $modelNo = $_GET['modelNo'];

        $stmt = sqlsrv_query($conn, "UPDATE library.library.[Device Title] SET library.library.[Device Title].Delisted=1 WHERE library.library.[Device Title].[Model No.]=?", array($deviceID));

        if ($stmt == false){
            $e = json_encode(sqlsrv_errors());
            header("Location: device.php?errormsg=Failed to delist device due to error. Error code: $e");
        }

        header("Location: device.php?msg=Device sucessfully delisted. If this was a mistake, please contact database administrator.");
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }
?>