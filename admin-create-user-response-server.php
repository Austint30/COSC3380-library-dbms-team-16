<?php
    include 'connect.php';
    include 'require-signup.php';
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
            INSERT INTO library.library.Account (library.library.Account.[First Name], library.library.Account.[Last Name], library.library.Account.[Middle Name], library.library.Account.Password, library.library.Account.Email, library.library.Account.Phone, library.library.Account.Type, library.library.Account.Approved)
            VALUES (?, ?, ?, ?, ?, ?, ?, 1);
        ", array($firstName, $lastName, $middleName, $password, $email, $phone, $type));

        if (!$result){
            $e = sqlsrv_errors();
            $eMsg = $e[0][2];

            header("Location: users.php?errormsg=Failed to sign up due to an error. Error: $eMsg");
        }
        if ($result){
            header("Location: users.php?msg=User successfully created.");
        }
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }
?>
