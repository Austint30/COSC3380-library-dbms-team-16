<?php
    include 'connect.php';
    include 'require-signin.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $mediaID = $_POST["mediaID"];
        $numCopies = $_POST["numCopies"];

        for ($i=0; $i < $numCopies; $i++) { 
            $query = "INSERT INTO dbo.Avail_Items (dbo.Avail_Items.[Date Added], dbo.Avail_Items.[Media Title ID]) VALUES (CURRENT_TIMESTAMP, ?)";

            $stmt = sqlsrv_prepare($conn, $query, array($mediaID));

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

        header("Location: /admin-editmedia.php?mediaID=$mediaID");
        return;
    }
    else
    {
        header("Location: /media.php");
    }

?>