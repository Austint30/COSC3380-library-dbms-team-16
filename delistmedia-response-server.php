<?php
    include 'connect.php';
    include 'require-signin.php';
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $mediaID = $_GET['mediaID'];

        $stmt = sqlsrv_query($conn, "UPDATE library.library.[Media Title] SET library.library.[Media Title].Delisted=1 WHERE library.library.[Media Title].[Media ID]=?", array($mediaID));

        if (!$stmt){
            $e = json_encode(sqlsrv_errors());
            header("Location: media.php?errormsg=Failed to delist media due to error. Error code: $e");
            return;
        }

        header("Location: media.php?msg=Media sucessfully delisted. If this was a mistake, please contact database administrator.");
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }
?>