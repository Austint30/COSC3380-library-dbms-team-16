<?php
    include 'connect.php';
    include 'holds-max-per-user-type.php';

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
    $result = $conn->query("SELECT COUNT(*) FROM Item WHERE library.Item.`Held By`=$userID");
    $holdCount = $result->fetch_row()[0];

    if ($holdCount >= $maxDeviceHolds){
        header("Location: /device-details.php?modelNo=$modelNo&errormsg=You can only have $maxDeviceHolds device holds.");
    }

    $holdsLeft = $maxDeviceHolds - $holdCount;

    // Get account information
    $result = $conn->query("SELECT Account.Type, Account.`User ID` FROM Account WHERE Account.`User ID`='$userID'");
    $user = $result->fetch_row();

    if (!$user){
        header("Location: /signin.php");
        die();
    }

    $userID = $user[1];

    // Find an available item of this device and mark it as held.
    $result = $conn->query("SELECT Item.`Item ID` FROM Item WHERE Item.`Device Title ID`='$modelNo' AND Item.`Held By` is NULL");
    $item = $result->fetch_row();

    if (!$item){
        header("Location: /device-details.php?errormsg=Sorry, this item is no longer in stock.");
        die();
    }
    $itemID = $item[0];

    $result = $conn->query("UPDATE `library`.`Item` SET `Held By` = '$userID' WHERE (`Item ID` = '$itemID');");
    if ($result){
        header("Location: /device-details.php?modelNo=$modelNo&msg=Device is now sucessfully held. Please pick up your device at the front desk. You have $holdsleft holds left for devices.");
    }
    else
    {
        header("Location: /device-details.php?modelNo=$modelNo&errmsg=Something went wrong and you device is not held.");
    }
    echo "holdCount: $holdCount, holdsLeft: $holdsLeft, maxDeviceHolds: $maxDeviceHolds";
?>