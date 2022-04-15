<?php
    include 'customExceptions.php';

    function userCoItemsTable($userID, $tableClass, $showCheckInBtn){
        include '../connect.php';
        $sql = "SELECT ISBN, bTitle, [Media ID], mTitle, [Model No.], dName, d.[Item ID]
        FROM library.dbo.Items_With_Title_Details as d, library.library.Checked_Out_Items as co
        WHERE d.[Item ID] = co.[Item ID] AND co.[Checked Out By]=?";
        
        $result = sqlsrv_query($conn, $sql, array($userID));

        if (!$result){
            throw new SqlQueryException("Failed to get user check outs.");
        }

        $btnColumn = "";
        if ($showCheckInBtn){
            $btnColumn = "<th/>";
        }

        echo "
        <table class='table table-striped m-0 $tableClass'>
        <thead>
            <tr>
                <th>Item Type</th>
                <th>Title/Device Name</th>
                <th>ISBN/Media ID/Model No.</th>
                <th>Item ID</th>
                $btnColumn
            </tr>
        </thead>
        <tbody>
        ";

        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $itemID = $row['Item ID'];
            $col1 = "Unknown";
            $col2 = "";
            $col3 = "";
            if ($row['ISBN']){
                $col1 = "Book";
                $col2 = $row['bTitle'];
                $col3 = $row['ISBN']." (ISBN)";
            }
            else if ($row['Media ID']){
                $col1 = "Media";
                $col2 = $row['mTitle'];
                $col3 = $row['Media ID']." (Media ID)";
            }
            else if ($row['Model No.']){
                $col1 = "Device";
                $col2 = $row['dName'];
                $col3 = $row['Model No.']." (Model No.)";
            }
            echo "<tr>";
            echo "<td>".$col1."</td>";
            echo "<td>".$col2."</td>";
            echo "<td>".$col3."</td>";
            echo "<td>".$row['Item ID']."</td>";
            if ($showCheckInBtn){
                echo "<td><button data-userid='$userID' data-itemid='$itemID' class='btn btn-primary use-co-item-btn' style='float: right;'>Check in item</button></td>";
            }
            echo "</tr>";
        }

        echo "</tbody></table>";
        
    }
?>