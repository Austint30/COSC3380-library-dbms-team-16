<?php
    include 'connect.php';
    include 'require-signin.php';

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $modelNo = $_GET["modelNo"];
        $itemID = $_GET["itemID"];

        $query = "UPDATE library.library.Item SET library.library.Item.Delisted=1, library.library.Item.[Delisted By]=?, library.library.Item.[Modified By]=? WHERE library.library.Item.[Item ID] = ?";

        $stmt = sqlsrv_prepare($conn, $query, array($cookie_userID, $cookie_userID, $itemID));

        if (!$stmt){
            header("Location: /admin-editdevice.php?modelNo=$modelNo&errormsg=Failed to delete copy of the device. (1)");
            return;
        }

        $res = sqlsrv_execute($stmt);

        if (!$res){
            header("Location: /admin-editdevice.php?modelNo=$modelNo&errormsg=Failed to delete copy of the device. (2)");
            return;
        }

        header("Location: /admin-editdevice.php?modelNo=$modelNo");
        return;
    }
    else
    {
        header("Location: /device.php");
    }

?>