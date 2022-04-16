<?php
    include 'connect.php';
    include 'holds-max-per-user-type.php';
    include 'require-signin.php';

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
    $stmt = sqlsrv_query($conn, "SELECT COUNT(*) FROM dbo.Avail_Items WHERE dbo.Avail_Items.[Held By]=$userID");
    $holdCount = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)[0];

    if ($holdCount >= $maxBookHolds){
        header("Location: /book-detail.php?isbn=$isbn&errormsg=You can only have $maxBookHolds book holds.");
    }

    $holdsLeft = $maxBookHolds - $holdCount;

    // Get account information
    $stmt = sqlsrv_query($conn, "SELECT a.Type, a.[User ID] FROM library.library.Account as a WHERE a.[User ID]='$userID'");
    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

    if (!$user){
        header("Location: /signin.php");
        die();
    }

    $userID = $user[1];

    // Find an available item of this book and mark it as held.
    $stmt = sqlsrv_query($conn, "SELECT i.[Item ID] FROM dbo.Avail_Items as i WHERE i.[Book Title ID]='$isbn' AND i.[Held By] is NULL");
    $item = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

    if (!$item){
        header("Location: /book-detail.php?errormsg=Sorry, this item is no longer in stock.");
        die();
    }
    $itemID = $item[0];

    $stmt = sqlsrv_query($conn, "UPDATE library.library.Item SET library.library.Item.[Held By] = '$userID', library.library.Item.[Modified By]='$cookie_userID' WHERE (library.library.Item.[Item ID] = '$itemID');");
    if ($stmt){
        header("Location: /book-detail.php?isbn=$isbn&msg=Book is now sucessfully held. Please pick up your book at the front desk. You have $holdsleft holds left for books.");
    }
    else
    {
        $e = sqlsrv_errors();
        $eCode = $e[0][0];
        $eMsg = $e[0][2];
        header("Location: /book-detail.php?isbn=$isbn&errormsg=Something went wrong and your book is not held. Error code: $eCode: $eMsg");
    }
    echo "holdCount: $holdCount, holdsLeft: $holdsLeft, maxBookHolds: $maxBookHolds";
?>