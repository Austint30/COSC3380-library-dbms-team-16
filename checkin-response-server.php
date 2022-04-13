<?php
    include 'connect.php';
    include 'helper.php';
    include 'require-signin.php';

    if(isset($_POST["itemid"])){
        $itemid = $_POST["itemid"];

        // Make sure item isn't already checked in
        $sql = "SELECT i.[Item ID] FROM library.library.Item as i WHERE i.[Item ID]=? AND i.[Checked Out By] IS NOT NULL";

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

        $sql = "UPDATE library.library.Item
        SET
            library.library.Item.[Checked Out By]=NULL,
            library.library.Item.[Checkout Appr By Librarian]=NULL,
            library.library.Item.State = 'CHECKED IN'
        WHERE library.library.Item.[Item ID]=?;

        DELETE FROM library.library.Item_Due_Date WHERE library.library.Item_Due_Date.[Item ID]=?;
        ";

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