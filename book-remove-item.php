<?php
    include 'connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $isbn = $_GET["isbn"];
        $itemID = $_GET["itemID"];

        $query = "DELETE FROM library.library.Item WHERE library.library.Item.[Item ID] = ?";

        $stmt = sqlsrv_prepare($conn, $query, array($itemID));

        if (!$stmt){
            header("Location: /admin-editbook.php?isbn=$isbn&errormsg=Failed to delete copy of the book. (1)");
            return;
        }

        $res = sqlsrv_execute($stmt);

        if (!$res){
            header("Location: /admin-editbook.php?isbn=$isbn&errormsg=Failed to delete copy of the book. (2)");
            return;
        }

        header("Location: /admin-editbook.php?isbn=$isbn");
        return;
    }
    else
    {
        header("Location: /books.php");
    }

?>