<?php
    include 'connect.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $middleName = $_POST['middleName'] ?? '';
        $password = $_POST["password"];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $type = $_POST['type'];
        
        echo "POST received. Values are below:";
        echo $firstName;
        echo $lastName;
        echo $middleName;
        echo $password;
        echo $email;
        echo $phone;
        echo $type;

        $result = sqlsrv_query($conn,"
            INSERT INTO library.library.Account (library.library.Account.[First Name], library.library..[Last Name], library.library..[Middle Name], library.library..Password, library.library..Email, library.library..Phone, library.library..Type)
            OUTPUT INSERTED.[User ID] AS [New User ID]
            VALUES (?, ?, ?, ?, ?, ?, ?);
        ", array($firstName, $lastName, $middleName, $password, $email, $phone, $type));

        if ($result){
            $e = sqlsrv_errors();
            $eMsg = $e[0][2];

            header("Location: signup.php?errormsg=Failed to sign up due to an error. Error: $eMsg");
        }

        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC);
        $userId = $row[0];
        if ($result){
            header("Location: signup-response.php?userId=$userId&email=$email");
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
            <h1>Account creation failed!</h1>
            <p>
                <?php echo $result ?>
            </p>
        </div>
    </body>
</html>