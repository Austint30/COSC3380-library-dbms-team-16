<?php
    include 'connect.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userId = $_POST['userId'];
        $password = $_POST["password"];

        $result = sqlsrv_query($conn,"
            SELECT a.[User ID], a.Password, a.Type FROM library.library.Account as a WHERE a.[User ID]='$userId' AND a.Password='$password'
        ");
        if ($result){
            header("Location: /signin.php?errormsg=Failed to sign in due to an unknown error.");
        }
        $hasResult = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC);
        if ($hasResult){
            setcookie("signed-in", true, time() + (86400 * 30), "/"); // 86400 = 1 day
            setcookie("user-id", $userId, time() + (86400 * 30*2), "/"); // 86400 = 2 days
            header("Location: /");
        }
        else
        {
            header("Location: /signin.php?errormsg=Password is inccorrect.");
        }
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }
?>
<html>
    <!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php' ?>
    </head>
    <body>
        <?php include 'headerbar-unauth.php' ?>
        <div class="container mt-5 text-center">
            <h3>Sign in failed</h3>
            <p>
                <?php echo $result ?>
            </p>
        </div>
    </body>
</html>