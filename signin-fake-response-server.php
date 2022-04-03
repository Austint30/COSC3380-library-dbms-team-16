<?php
    setcookie("signed-in", true, time() + (86400 * 30), "/"); // 86400 = 1 day
    header("Location: /");
?>