<?php
    setcookie("signed-in", true, time() + (86400 * 30), "/"); // 86400 = 1 day
    setcookie("user-id", "22", time() + (86400 * 30*2), "/"); // 86400 = 2 days
    header("Location: /");
?>