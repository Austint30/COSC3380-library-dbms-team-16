<?php
    include 'customExceptions.php';

    function userHoldsTable($userID, $tableClass, $showCheckInHoldBtn){
        include '../connect.php';
        $sql = "SELECT ISBN, bTitle, [Media ID], mTitle, [Model No.], dName, [Item ID]
            FROM library.dbo.Items_With_Title_Details
            WHERE [Held By] = ?
            ";
        
        $result = sqlsrv_query($conn, $sql, array($userID));

        if (!$result){
            throw new SqlQueryException("Failed to get user holds.");
        }

        $btnColumn = "";
        if ($showCheckInHoldBtn){
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
            if ($showCheckInHoldBtn){
                echo "<td><button data-userid='$userID' data-itemid='$itemID' class='btn btn-primary use-hold-btn' style='float: right;'>Use Hold</button></td>";
            }
            echo "</tr>";
        }

        echo "</tbody></table>";
        
    }
?>