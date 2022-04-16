<?php
    include 'connect.php';
    include 'holds-max-per-user-type.php';
    include 'require-signin.php';

    if (!isset($_COOKIE["user-id"])){
        header("Location: /signin.php");
        die();
    }
    if (!isset($_GET["modelNo"])){
        // Redirect to device page if no modelNo is specified.
        header("Location: /devices.php");
        die();
    }
    $modelNo = $_GET["modelNo"];
    $userID = $_COOKIE["user-id"];

    // Check current number of held items
    $stmt = sqlsrv_query($conn, "SELECT COUNT(*) FROM dbo.Avail_Items WHERE dbo.Avail_Items.[Held By]=$userID");
    $holdCount = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)[0];

    if ($holdCount >= $maxDeviceHolds){
        header("Location: /device-details.php?modelNo=$modelNo&errormsg=You can only have $maxDeviceHolds device holds.");
    }

    $holdsLeft = $maxDeviceHolds - $holdCount;

    // Get account information
    $stmt = sqlsrv_query($conn, "SELECT a.Type, a.[User ID] FROM library.library.Account as a WHERE a.[User ID]='$userID'");
    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

    if (!$user){
        header("Location: /signin.php");
        die();
    }

    $userID = $user[1];

    // Find an available item of this device and mark it as held.
    $stmt = sqlsrv_query($conn, "SELECT i.[Item ID] FROM dbo.Avail_Items as i WHERE i.[Device Title ID]='$modelNo' AND i.[Held By] is NULL");
    $item = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

    if (!$item){
        header("Location: /device-details.php?errormsg=Sorry, this item is no longer in stock.");
        die();
    }
    $itemID = $item[0];

    $stmt = sqlsrv_query($conn, "UPDATE library.library.Item SET library.library.Item.[Held By] = '$userID', library.library.Item.[Modified By]='$cookie_userID' WHERE (library.library.Item.[Item ID] = '$itemID');");
    if ($stmt){
        header("Location: /device-details.php?modelNo=$modelNo&msg=Device is now sucessfully held. Please pick up your device at the front desk. You have $holdsleft holds left for devices.");
    }
    else
    {
        $e = json_encode(sqlsrv_errors());
        header("Location: /device-details.php?modelNo=$modelNo&errormsg=Something went wrong and you device is not held. Error: $e");
    }
    echo "holdCount: $holdCount, holdsLeft: $holdsLeft, maxDeviceHolds: $maxDeviceHolds";
?>