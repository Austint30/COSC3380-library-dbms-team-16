<?php
    $cookieName = "signed-in";
    if(!isset($_COOKIE[$cookieName]) || !$_COOKIE[$cookieName]){
        header("Location: /signin.php?errormsg=You have to be signed in to do that.");
    }
?>