<?php
    include 'connect.php';
    include 'require-signin.php';

    if (!isset($_COOKIE["user-id"])){
        header("Location: /signin.php");
        die();
    }
    if (!isset($_GET["item-id"])){
        // Redirect to books page if no isbn is specified.
        header("Location: /held-items.php");
        die();
    }
    $itemID = $_GET["item-id"];
    $userID = $_COOKIE["user-id"];

    // Find items held by this user
    $stmt = sqlsrv_query($conn, "SELECT i.[Item ID] FROM dbo.Avail_Items as i WHERE i.[Item ID]='$itemID' AND i.[Held By]=$userID");
    $item = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

    if (!$item){
        header("Location: /held-items.php?errormsg=Item is not held by your account.");
        die();
    }
    $itemID = $item[0];

    $stmt = sqlsrv_query($conn, "UPDATE dbo.Avail_Items SET dbo.Avail_Items.[Held By] = NULL WHERE (dbo.Avail_Items.[Item ID] = '$itemID');");
    if ($stmt){
        header("Location: /held-items.php?msg=Item removed from holds.");
    }
    else
    {
        $e = sqlsrv_errors();
        $eCode = $e[0][0];
        $eMsg = $e[0][2];
        header("Location: /held-items.php?errormsg=Something went wrong and you book is not held. Error code: $eCode: $eMsg");
    }
?>