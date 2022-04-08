<?php
    if (isset($_GET["msg"])){
        $msg = $_GET["msg"];
        echo "<div class='alert alert-primary mb-3' role='alert'>
            $msg
        </div>";
    }
    if (isset($_GET["errormsg"])){
        $msg = $_GET["errormsg"];
        echo "<div class='alert alert-danger mb-3' role='alert'>
            $msg
        </div>";
    }
?>