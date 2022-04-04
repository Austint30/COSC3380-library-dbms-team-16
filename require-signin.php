<?php
    $cookieName = "signed-in";
    if((!isset($_COOKIE["signed-in"]) || !$_COOKIE["signed-in"]) || (!isset($_COOKIE["user-id"]) || !$_COOKIE["user-id"])){
        header("Location: /signin.php?errormsg=You have to be signed in to do that.");
        die();
    }

    $cookie_userID = $_COOKIE["user-id"];
?>