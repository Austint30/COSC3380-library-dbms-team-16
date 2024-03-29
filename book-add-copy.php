<?php
    include 'connect.php';
    include 'require-signin.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $isbn = $_POST["isbn"];
        $numCopies = $_POST["numCopies"];

        for ($i=0; $i < $numCopies; $i++) { 
            $query = "INSERT INTO library.library.Item (library.library.Item.[Date Added], library.library.Item.[Book Title ID], library.library.Item.[Created By], library.library.Item.[Modified By]) VALUES (CURRENT_TIMESTAMP, ?, ?, ?)";

            $stmt = sqlsrv_prepare($conn, $query, array($isbn, $cookie_userID, $cookie_userID));

            if (!$stmt){
                header("Location: /admin-editbook.php?isbn=$isbn&errormsg=Failed to add copies of the book. (1)");
                return;
            }

            $res = sqlsrv_execute($stmt);

            if (!$res){
                header("Location: /admin-editbook.php?isbn=$isbn&errormsg=Failed to add copies of the book. (2)");
                return;
            }
        }

        header("Location: /admin-editbook.php?isbn=$isbn&msg=Successfully added $numCopies copies.");
        return;
    }
    else
    {
        header("Location: /books.php");
    }

?>