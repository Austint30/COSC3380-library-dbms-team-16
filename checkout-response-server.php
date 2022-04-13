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
        $sql = "SELECT i.[Item ID] FROM library.library.Item as i WHERE i.[Item ID]=? AND i.[Checked Out By] IS NULL";
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

        $sql = "UPDATE library.library.Item
            SET
                library.library.Item.[Held By] = NULL,
                library.library.Item.[Checked Out By] = ?,
                library.library.Item.State = 'CHECKED OUT',
                library.library.Item.[Checkout Appr By Librarian] = ?
            
            WHERE
                library.library.Item.[Item ID] = ?

            INSERT INTO library.library.Item_Due_Date (library.library.Item_Due_Date.[Item ID], library.library.Item_Due_Date.[Due Date])
            VALUES (?, ?);
            ";

        $result = sqlsrv_query($conn, $sql, array($userID, $cookie_userID, $itemID, $itemID, $dueDate));

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