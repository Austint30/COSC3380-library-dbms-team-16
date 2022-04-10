<?php
    include 'connect.php';

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
    $stmt = sqlsrv_query($conn, "SELECT i.[Item ID] FROM library.library.Item as i WHERE i.[Item ID]='$itemID' AND i.[Held By]=$userID");
    $item = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

    if (!$item){
        header("Location: /held-items.php?errormsg=Item is not held by your account.");
        die();
    }
    $itemID = $item[0];

    $stmt = sqlsrv_query($conn, "UPDATE library.library.Item SET library.library.Item.[Held By] = NULL WHERE (library.library.Item.[Item ID] = '$itemID');");
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