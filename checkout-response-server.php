<?php
    include 'connect.php';
    include 'require-signin.php';
    include 'helper.php';

    if(isset($_POST["userID"]) && isset($_POST["itemID"]) && isset($_POST["dueDate"])){
        $userID = $_POST["userID"];
        $itemID = $_POST["itemID"];
        $dueDate = $_POST["dueDate"];

        $dueDateObj = new DateTime($dueDate, new DateTimeZone("America/Chicago"));
        $dueDateObj->setTimezone(new DateTimeZone("UTC"));
        $dueDate = $dueDateObj->format('Y-m-d H:i:s');

        // Check if item is not already checked out
        $sql = "SELECT i.[Item ID] FROM library.dbo.Items_With_Check_Out as i WHERE i.[Item ID]=? AND i.[Checked Out By] IS NULL";
        $result = sqlsrv_query($conn, $sql, array($itemID));

        if (!$result){
            $e = fmtErrJson();
            header("Location: /checkout.php?errormsg=Item failed to check out due to an error: $e");
            return;
        }
        
        $item = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

        if (!$item){
            header("Location: /checkout.php?errormsg=Item does not exist or is checked out.");
            return;
        }

        // Check if the user exists
        $sql = "SELECT a.[User ID] FROM library.library.Account as a WHERE a.[User ID]=?";
        $result = sqlsrv_query($conn, $sql, array($userID));

        if (!$result){
            $e = fmtErrJson();
            header("Location: /checkout.php?errormsg=Item failed to check out due to an error: $e");
            return;
        }

        $user = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

        if (!$user){
            header("Location: /checkout.php?errormsg=User does not exist.");
            return;
        }

        $sql = "
            USE library
            INSERT INTO library.Checked_Out_Items ([Item ID], [Checked Out By], [Check Out Time], [Approving Librarian], [Due Date])
            VALUES (?, ?, CURRENT_TIMESTAMP, ?, ?);
            ";

        $result = sqlsrv_query($conn, $sql, array($itemID, $userID, $cookie_userID, $dueDate));

        if ($result){
            header("Location: /checkout.php?msg=Item checked out successfully.");
            return;
        }
        else
        {
            $e = json_encode(sqlsrv_errors());
            header("Location: /checkout.php?errormsg=Item failed to check out due to an error: $e");
        }
    }
?>