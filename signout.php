<?php
    $cookieName = "signed-in";
    if(isset($_COOKIE[$cookieName])){
        setcookie($cookieName, "", time() - 3600);
    }

    $cookieName = "user-id";
    if(isset($_COOKIE[$cookieName])){
        setcookie($cookieName, "", time() - 3600);
    }

    header("Location: /signin.php?msg=You have been logged out.");
?>