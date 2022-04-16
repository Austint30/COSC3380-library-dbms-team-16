<?php
    include 'connect.php';
    include 'require-signin.php';

    if (!isset($_GET["item-id"])){
        // Redirect to books page if no isbn is specified.
        header("Location: /held-items.php");
        die();
    }
    $itemID = $_GET["item-id"];
    $userID = $cookie_userID;

    // Find items held by this user
    $stmt = sqlsrv_query($conn, "SELECT i.[Item ID] FROM library.library.Item as i WHERE i.[Item ID]=? AND i.[Held By]=?", array($itemID, $userID));
    $item = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

    if (!$item){
        $e = json_encode(sqlsrv_errors());
        header("Location: /held-items.php?errormsg=Item is not held by your account.");
        die();
    }
    $itemID = $item[0];

    $stmt = sqlsrv_query($conn, "UPDATE library.library.Item SET library.library.Item.[Held By] = NULL WHERE (library.library.Item.[Item ID] = ?);", array($itemID));
    if ($stmt){
        header("Location: /held-items.php?msg=Item removed from holds.");
    }
    else
    {
        $e = json_encode(sqlsrv_errors());
        header("Location: /held-items.php?errormsg=Something went wrong and you book is not held. Error code: $e");
    }
?>