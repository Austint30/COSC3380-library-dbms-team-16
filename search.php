<?php
    if (isset($_POST["query"]) && isset($_POST["type"])){
        $searchQuery = $_POST["query"];
        $type = $_POST["type"];

        if ($type === "books"){
            header("Location: /books.php?search=$searchQuery");
        }
        else if ($type === "media")
        {
            header("Location: /media.php?search=$searchQuery");
        }
        else if ($type === "devices")
        {
            header("Location: /devices.php?search=$searchQuery");
        }
        else
        {
            header("Location: /");
        }
    }
?>