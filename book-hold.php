<?php
    include 'connect.php';
    if (!isset($_COOKIE["user-id"])){
        header("Location: /signin.php");
        die();
    }
    if (!isset($_GET["isbn"])){
        // Redirect to books page if no isbn is specified.
        header("Location: /books.php");
        die();
    }
    $isbn = $_GET["isbn"];
    $userID = $_COOKIE["user-id"];
    $result = $conn->query("SELECT Account.Type, Account.`User ID` FROM Account WHERE Account.`User ID`='$userID'");
    $user = $result->fetch_row();

    if (!$user){
        header("Location: /signin.php");
        die();
    }

    $userID = $user[1];

    // Find an available item of this book and mark it as held.
    $result = $conn->query("SELECT Item.`Item ID` FROM Item WHERE Item.`Book Title ID`=$isbn");
    $item = $result->fetch_row();

    if (!$item){
        header("Location: /book-detail.php?errormsg=Sorry, this item is no longer in stock.");
        die();
    }
    $itemID = $item[0];

    $result = $conn->query("UPDATE `library`.`Item` SET `Held By` = '$userID' WHERE (`Item ID` = '$itemID');");
    if ($result){
        header("Location: /book-detail.php?isbn=$isbn&msg=Book is now sucessfully held. Please pick up your book at the front desk.");
    }
    else
    {
        header("Location: /book-detail.php?isbn=$isbn&errmsg=Something went wrong and you book is not held.");
    }
?>