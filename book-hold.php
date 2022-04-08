<?php
    include 'connect.php';
    include 'holds-max-per-user-type.php';

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

    // Check current number of held items
    $stmt = sqlsrv_query($conn, "SELECT COUNT(*) FROM Item WHERE library.Item.[Held By]=$userID"));
    $holdCount = $result->fetch_row()[0];

    if ($holdCount >= $maxBookHolds){
        header("Location: /book-detail.php?isbn=$isbn&errormsg=You can only have $maxBookHolds book holds.");
    }

    $holdsLeft = $maxBookHolds - $holdCount;

    // Get account information
    $stmt = sqlsrv_query($conn, "SELECT Account.Type, Account.[User ID] FROM Account WHERE Account.[User ID]='$userID'"));
    $user = $result->fetch_row();

    if (!$user){
        header("Location: /signin.php");
        die();
    }

    $userID = $user[1];

    // Find an available item of this book and mark it as held.
    $stmt = sqlsrv_query($conn, "SELECT Item.[Item ID] FROM Item WHERE Item.[Book Title ID]='$isbn' AND Item.[Held By] is NULL"));
    $item = $result->fetch_row();

    if (!$item){
        header("Location: /book-detail.php?errormsg=Sorry, this item is no longer in stock.");
        die();
    }
    $itemID = $item[0];

    $stmt = sqlsrv_query($conn, "UPDATE [library].[Item] SET [Held By] = '$userID' WHERE ([Item ID] = '$itemID');"));
    if ($result){
        header("Location: /book-detail.php?isbn=$isbn&msg=Book is now sucessfully held. Please pick up your book at the front desk. You have $holdsleft holds left for books.");
    }
    else
    {
        header("Location: /book-detail.php?isbn=$isbn&errmsg=Something went wrong and you book is not held.");
    }
    echo "holdCount: $holdCount, holdsLeft: $holdsLeft, maxBookHolds: $maxBookHolds";
?>