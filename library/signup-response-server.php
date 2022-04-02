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

        $result = $conn->query("
            INSERT INTO Account (Account.`First Name`, Account.`Last Name`, Account.`Middle Name`, Account.Password, Account.Email, Account.Phone, Account.Type)
            VALUES ('$firstName', '$lastName', '$middleName', '$password', '$email', '$phone', '$type');
        ");
        $res = $conn->query('SELECT LAST_INSERT_ID()');
        $row = $res->fetch_array();
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
        <?php include '../bootstrap.php' ?>
    </head>
    <body>
        <?php include '../headerbar-unauth.php' ?>
        <div class="container mt-5 text-center">
            <h1>Account creation failed!</h1>
            <p>
                <?php echo $result ?>
            </p>
        </div>
    </body>
</html>