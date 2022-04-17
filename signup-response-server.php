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

        $result = sqlsrv_query($conn,
            "SET NOCOUNT ON
            DECLARE @temp TABLE(user_id int)

            INSERT INTO library.library.Account (
                library.library.Account.[First Name], 
                library.library.Account.[Last Name], 
                library.library.Account.[Middle Name], 
                library.library.Account.Password, 
                library.library.Account.Email, 
                library.library.Account.Phone, 
                library.library.Account.Type
            )
            OUTPUT INSERTED.[User ID] AS user_id INTO @temp
            VALUES (?, ?, ?, ?, ?, ?, ?)

            SELECT user_id FROM @temp",
            array($firstName, $lastName, $middleName, $password, $email, $phone, $type));

        if ($result == false){
            $e = json_encode(sqlsrv_errors());

            header("Location: signup.php?errormsg=Failed to sign up due to an error. Error: $e");
            return;
        }

        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC);
        if (!$row){
            $e = json_encode(sqlsrv_errors());
            header("Location: signup.php?errormsg=Failed to sign up due to an error. Your account was still created. Contact system admin because this should not happen! Error: $e");
            return;
        }
        $userId = $row[0];
        $json = json_encode($row);
        if ($result){
            header("Location: signup-response.php?userId=$userId&email=$email&row=$json");
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
            <h3>Account creation failed!</h3>
            <p>
                <?php echo $result ?>
            </p>
        </div>
    </body>
</html>