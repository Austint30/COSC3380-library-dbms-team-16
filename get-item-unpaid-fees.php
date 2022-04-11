<?php
    include 'connect.php';
    if (isset($_GET["itemid"])){

        // Find the ID of the user who is checking the item in
        $sql = "SELECT a.[User ID], a.[First Name], a.[Last Name]
        FROM library.library.Item as i, library.library.Account as a
        WHERE i.[Checked Out By] = a.[User ID] AND i.[Item ID] = ?
        ";

        $itemid = $_GET["itemid"];

        $result = sqlsrv_query($conn, $sql, array($itemid));
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC);

        if (!$row){
            http_response_code(404);
            echo "Item not found or not checked out.";
            return;
        }

        $userID = $row[0];
        $userName = "$row[1] $row[2]";

        // This SQL utilizes a view
        $sql = "SELECT [Fee ID], [Item ID], [User ID]
                    ,[Amount Owed]
                    ,[Amount Paid]
                    ,[bISBN]
                    ,[bTitle]
                    ,[mID]
                    ,[mTitle]
                    ,[dModelNo]
                    ,[dName]
                FROM [library].[dbo].[Fees_With_Item_Details]
                WHERE ([Amount Owed] > [Amount Paid]) AND [User ID]=?";

        $result = sqlsrv_query($conn, $sql, array($userID));

        $fees = [];

        while ( $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $uBal = intval($row['Amount Owed']) - intval($row['Amount Paid']);
            if ($row['bISBN']){
                $name = $row['bTitle'];
                if ($row['Item ID'] == $itemid){
                    $name = "$name (This item)";
                }
                $feeObj = (object) [
                    'hidden:feeID' => $row["Fee ID"],
                    'Unpaid Balance' => "$$uBal",
                    'Item Type' => 'Book',
                    'Title/Name' => $name
                ];
            }
            else if ($row['mID']){
                $name = $row['mTitle'];
                if ($row['Item ID'] == $itemid){
                    $name = "$name (This item)";
                }
                $feeObj = (object) [
                    'hidden:feeID' => $row["Fee ID"],
                    'Unpaid Balance' => "$$uBal",
                    'Item Type' => 'Media',
                    'Title/Name' => $name
                ];
            }
            else if ($row['dModelNo']){
                $name =  $row['mTitle'];
                if ($row['Item ID'] == $itemid){
                    $name = "$name (This item)";
                }
                $feeObj = (object) [
                    'hidden:feeID' => $row["Fee ID"],
                    'Unpaid Balance' => "$$uBal",
                    'Item Type' => 'Device',
                    'Title/Name' => $name
                ];
            }
            else
            {
                $feeObj = (object) [
                    'hidden:feeID' => $row["Fee ID"],
                    'Item Type' => 'Unknown',
                ];
            }
            array_push($fees, $feeObj);
        }

        header('Content-Type: application/json; charset=utf-8');
        $responseObj = (object) [
            'userName' => $userName,
            'fees' => $fees
        ];
        echo json_encode($responseObj);
    }
?>