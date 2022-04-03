<?php
    include 'connect.php';
?>

<!DOCTYPE html>
<html>
    <!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php' ?>
    </head>
    <!--------------------------------------------------------------->
    <body>
        <?php include 'headerbar-unauth.php' ?>
        <form class="container mt-5" action="signin-response-server.php" method="post">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sign in</h5>
                    <div class="mb-3">
                        <label for="signin-userid" class="form-label">User ID</label>
                        <input class="form-control" id="signin-userid" name="userId" required>
                    </div>
                    <div class="mb-3">
                        <label for="signin-password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="signin-password" name="password" required>
                    </div>
                    <button id="signin-button" type="submit" class="btn btn-primary">Sign in</button>
                    <a style="float: right;" href="signin-fake-response-server.php">I don't care just sign me in</a>
                </div>
            </div>
            <?php
                if (isset($_GET["msg"])){
                    $errormsg = $_GET["msg"];
                    echo '<div class="alert alert-primary mt-3" role="alert">';
                    echo $errormsg;
                    echo '</div>';
                }
                if (isset($_GET["errormsg"])){
                    $errormsg = $_GET["errormsg"];
                    echo '<div class="alert alert-danger mt-3" role="alert">';
                    echo $errormsg;
                    echo '</div>';
                }
            ?>
        </form>
    </body>
    <!--------------------------------------------------------------->
</html>