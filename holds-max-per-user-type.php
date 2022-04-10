
<?php
    // This file specifies what type of user is currently signed in and sets their max holds depending on their user type
    include 'connect.php';
    include 'require-signin.php';

    $stmt = sqlsrv_query($conn, "SELECT a.Type FROM library.library.Account as a WHERE a.[User ID]=$cookie_userID");

    if (!$stmt){
        die("Failed to get account information.");
    }

    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);

    if(!$user){
        header("Location: /signin.php?errormsg=User no longer exists.");
    }

    $userType = $user[0];

    $maxBookHolds = 10;
    $maxMediaHolds = 10;
    $maxDeviceHolds = 10;

    if ($userType == "STUDENT"){
        $maxBookHolds = 5;
        $maxMediaHolds = 3;
        $maxDeviceHolds = 1;
    }
    else if ($userType == "FACULTY" || $userType == "STAFF"){
        $maxBookHolds = 10;
        $maxMediaHolds = 6;
        $maxDeviceHolds = 2;
    }
?>