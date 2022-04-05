<?php
    include 'connect.php';
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $isbn = $_GET['isbn'];

        $result = $conn->query("UPDATE `Book Title` SET Delisted=1 WHERE ISBN='$isbn'");

        header("Location: books.php?msg=Book sucessfully delisted. If this was a mistake, please contact database administrator.");
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }
?>