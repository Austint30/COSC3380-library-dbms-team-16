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
    $result = $conn->query("SELECT Item.`Item ID` FROM Item WHERE Item.`Item ID`='$itemID' AND Item.`Held By`=$userID");
    $item = $result->fetch_row();

    if (!$item){
        header("Location: /held-items.php?errormsg=Item is not held by your account.");
        die();
    }
    $itemID = $item[0];

    $result = $conn->query("UPDATE `library`.`Item` SET `Held By` = NULL WHERE (`Item ID` = '$itemID');");
    if ($result){
        header("Location: /held-items.php?msg=Item removed from holds.");
    }
    else
    {
        header("Location: /held-items.php?errormsg=Something went wrong and you book is not held.");
    }
?>