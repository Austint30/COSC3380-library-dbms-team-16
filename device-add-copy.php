<?php
    include 'connect.php';
    include 'require-signin.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $modelNo = $_POST["modelNo"];
        $numCopies = $_POST["numCopies"];

        for ($i=0; $i < $numCopies; $i++) { 
            $query = "INSERT INTO library.library.Item (library.library.Item.[Date Added], library.library.Item.[Device Title ID], library.library.Item.[Created By]) VALUES (CURRENT_TIMESTAMP, ?, ?)";

            $stmt = sqlsrv_prepare($conn, $query, array($modelNo, $cookie_userID));

            if (!$stmt){
                header("Location: /admin-editdevice.php?modelNo=$modelNo&errormsg=Failed to add copies of the media. (1)");
                return;
            }

            $res = sqlsrv_execute($stmt);

            if (!$res){
                header("Location: /admin-editdevice.php?modelNo=$modelNo&errormsg=Failed to add copies of the media. (2)");
                return;
            }
        }

        header("Location: /admin-editdevice.php?modelNo=$modelNo&msg=Successfully added $numCopies copies.");
        return;
    }
    else
    {
        header("Location: /devices.php");
    }

?>