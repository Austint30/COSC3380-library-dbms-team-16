<?php
    include 'connect.php';
    include 'require-signin.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $mediaID = $_POST["mediaID"];
        $numCopies = $_POST["numCopies"];

        for ($i=0; $i < $numCopies; $i++) { 
            $query = "INSERT INTO library.library.Item (library.library.Item.[Date Added], library.library.Item.[Media Title ID], library.library.Item.[Created By], library.library.Item.[Modified By]) VALUES (CURRENT_TIMESTAMP, ?, ?, ?)";

            $stmt = sqlsrv_prepare($conn, $query, array($mediaID, $cookie_userID, $cookie_userID));

            if (!$stmt){
                header("Location: /admin-editmedia.php?mediaID=$mediaID&errormsg=Failed to add copies of the media. (1)");
                return;
            }

            $res = sqlsrv_execute($stmt);

            if (!$res){
                header("Location: /admin-editmedia.php?mediaID=$mediaID&errormsg=Failed to add copies of the media. (2)");
                return;
            }
        }

        header("Location: /admin-editmedia.php?mediaID=$mediaID&msg=Successfully added $numCopies copies.");
        return;
    }
    else
    {
        header("Location: /media.php");
    }

?>