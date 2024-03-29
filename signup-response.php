<!DOCTYPE html>
<html>
    <!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php' ?>
    </head>
    <body>
        <?php include 'headerbar-unauth.php' ?>
        <div class="container mt-5 text-center">
            <h3>Account creation successful!</h3>
            <p>
                Please wait until your account to be approved before you log in.
            </p>
            <?php
                if (isset($_GET['userId']) && isset($_GET['email'])){
                    $userId = $_GET['userId'];
                    $email = $_GET['email'];
                    echo "<p>Your User ID is <strong>$userId</strong>. Please use this to login next time.</p>";
                }
                else
                {
                    header("Location: signup.php");
                }
                
            ?>
            <p></p>
        </div>
    </body>
    <script>
        const userId = "<?php echo $userId ?>";
        
        // Async version
        // async function fetchIsUserApproved(){
        //     const resp = await fetch('isuserapproved.php?userId=' + userId);
        //     const text = await resp.text();
        //     if (text == 1){
        //         console.log('User is approved. Redirecting...');
        //         window.location = '/index.php';
        //     }
        // }

        function fetchIsUserApproved(){
            console.log('Checking if user is approved...');
            fetch('isuserapproved.php?userId=' + userId)
            .then((result) => result.text())
            .then((text) => {
                if (text == 1){
                    console.log('User is approved. Redirecting...');
                    var now = new Date();
                    var time = now.getTime();
                    var expireTime = time + 1000*36000;
                    now.setTime(expireTime);

                    var now2 = new Date();
                    var time = now.getTime();
                    var expireTime = time + 1000*36000*2;
                    now.setTime(expireTime);

                    document.cookie = "signed-in=true;expires=" + now.toUTCString() + ";path=/"
                    document.cookie = "user-id=" + userId + ";expires=" + now.toUTCString() + ";path=/"
                    window.location = '/index.php';
                }
            })
            .catch((e) => console.error(e))
        }

        fetchIsUserApproved();

        setInterval(fetchIsUserApproved, 5000);
    </script>
</html>