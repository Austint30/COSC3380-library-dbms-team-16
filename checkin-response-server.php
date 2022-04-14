<?php
    include 'connect.php';
    include 'helper.php';
    include 'require-signin.php';

    if(isset($_POST["itemid"])){
        $itemid = $_POST["itemid"];

        // Make sure item isn't already checked in
        $sql = "SELECT i.[Item ID] FROM library.dbo.Items_With_Check_Out as i WHERE i.[Item ID]=? AND i.[Checked Out By] IS NOT NULL";

        $result = sqlsrv_query($conn, $sql, array($itemid));

        if (!$result){
            $e = fmtErrJson();
            header('Location: /checkout.php?errormsg=Failed to check in item. Error: '.$e);
            return;
        }

        if (!sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            header('Location: /checkout.php?errormsg=Item is checked in already.');
            return;
        }

        $sql = "DELETE FROM library.library.Checked_Out_Items WHERE library.library.Checked_Out_Items.[Item ID]=?;";

        $result = sqlsrv_query($conn, $sql, array($itemid, $itemid));

        if (!$result){
            $e = fmtErrJson();
            header('Location: /checkout.php?errormsg=Failed to check in item. Error: '.$e);
            return;
        }
        else
        {
            header('Location: /checkout.php?msg=Item checked in successfully.');
            return;
        }
    }
?>