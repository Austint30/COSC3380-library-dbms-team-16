
<?php
    // This file specifies what type of user is currently signed in and sets their max holds depending on their user type
    include 'connect.php';
    include 'require-signin.php';

    $result = $conn->query("SELECT Account.Type FROM Account WHERE Account.`User ID`=$cookie_userID");
    $user = $result->fetch_row();

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