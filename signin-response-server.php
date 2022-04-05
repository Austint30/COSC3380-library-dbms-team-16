<?php
    include 'connect.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userId = $_POST['userId'];
        $password = $_POST["password"];

        $result = $conn->query("
            SELECT Account.`User ID`, Account.Password, Account.Type FROM Account WHERE Account.`User ID`='$userId' AND Account.Password='$password'
        ");
        $rows = $result->fetch_all();
        if (count($rows) > 0){
            $row = $rows[0];
            $storedPass = $row[1];
            $type = $row[2];
            if ($storedPass == $password){
                // TODO: Add admin page
                // if ($type == "ADMIN"){

                // }
                setcookie("signed-in", true, time() + (86400 * 30), "/"); // 86400 = 1 day
                setcookie("user-id", $userId, time() + (86400 * 30*2), "/"); // 86400 = 2 days
                header("Location: /");
            }
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
            <h1>Sign in failed</h1>
            <p>
                <?php echo $result ?>
            </p>
        </div>
    </body>
</html>