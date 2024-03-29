<?php
    include 'connect.php';
    include 'require-signin.php';
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $isbn = $_GET['isbn'];

        $stmt = sqlsrv_query($conn, "UPDATE library.library.[Book Title] SET library.library.[Book Title].Delisted=1 WHERE library.library.[Book Title].ISBN=?", array($isbn));

        if (!$stmt){
            $e = json_encode(sqlsrv_errors());
            header("Location: books.php?errormsg=Failed to delist book due to error. Error code: $e");
            return;
        }

        header("Location: books.php?msg=Book sucessfully delisted. If this was a mistake, please contact database administrator.");
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }
?>