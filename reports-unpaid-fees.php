<!DOCTYPE html>
<html>
<!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php'; 
		include 'connect.php';
        include 'require-signin.php';
        ?>	
    </head>
<!----------------------Here we have the popular books----------------------------------------->
	<body>
		<?php include 'headerbar-auth.php' ?>
		<div class="container mt-5">
            <h3>Unpaid fees report</h2>
            <?php include 'messages.php' ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Set criteria</h5>
                    <form method="post">
                        <div class="row align-items-start">
                            <div class="col-4 mb-3">
                                <label for="report-datetime-start" class="form-label">Start time (UTC)</label>
                                <input type="datetime-local" class="form-control" id="report-datetime-start" name="startTime" required>
                            </div>
                            <div class="col-4 mb-3">
                                <label for="report-datetime-end" class="form-label">End time (UTC)</label>
                                <input type="datetime-local" class="form-control" id="report-datetime-end" name="endTime" required>
                            </div>
                            <div class="col-4 mb-3">
                                <label for="report-user-id" class="form-label">Filter by fee paying user ID</label>
                                <input class="form-control" id="report-user-id" name="userID">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="container-fluid">
        <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST"){
                    $startTime = (new DateTime($_POST["startTime"]))->format('Y-m-d H:i:s');
                    $endTime = (new DateTime($_POST["endTime"]))->format('Y-m-d H:i:s');
                    $userID = $_POST["userID"];

                    $sql = "SELECT f.[Fee ID]
                            ,f.[Item ID]
                            ,f.[Date Added]
                            ,f.[User ID] as [Fee Payer]
                            ,aa.[Last Name] + ', ' + aa.[First Name] as [Fee Payer Name]
                            ,f.[Amount Owed]
                            ,f.[Amount Paid]
                            ,f.[Checked Out Time],
                        CASE
                            WHEN i.ISBN IS NOT NULL THEN i.bTitle
                            WHEN i.[Media ID] IS NOT NULL THEN i.mTitle
                            WHEN i.[Model No.] IS NOT NULL THEN i.dName
                        END AS [Title/Name],
                        CASE
                            WHEN i.ISBN IS NOT NULL THEN 'Book'
                            WHEN i.[Media ID] IS NOT NULL THEN 'Media'
                            WHEN i.[Model No.] IS NOT NULL THEN 'Device'
                        END AS [Item Type],
                        CASE
                            WHEN i.ISBN IS NOT NULL THEN i.ISBN + ' (ISBN)'
                            WHEN i.[Media ID] IS NOT NULL THEN CAST(i.[Media ID] as nvarchar(100)) + ' (Media ID)'
                            WHEN i.[Model No.] IS NOT NULL THEN i.[Model No.] + ' (Model No.)'
                        END AS [ISBN/Media ID/Model No.],
                        CASE
                            WHEN i.ISBN IS NOT NULL THEN i.bAuthLName + ', ' + i.bAuthMName + ', ' + i.bAuthFName + ' (Author)'
                            WHEN i.[Media ID] IS NOT NULL THEN i.mAuthLName + ', ' + i.mAuthMName + ', ' + i.mAuthFName + ' (Author)'
                            WHEN i.[Model No.] IS NOT NULL THEN i.Manufacturer + ' (Manu)'
                        END AS [Author/Manufacturer]
                        FROM [library].[library].Fee as f, library.dbo.Items_With_Title_Details as i, library.library.Account as aa
                        WHERE i.[Item ID] = f.[Item ID] AND f.[User ID] = aa.[User ID] AND f.[Amount Owed] > f.[Amount Paid]
                            AND f.[Date Added] >= ?
                            AND f.[Date Added] <= ?
                        ";
                    
                    if ($userID){
                        $sql = $sql." AND f.[User ID]=?";
                    }
                    
                    $sql = $sql." ORDER BY [Date Added] DESC";

                    $options = array("ReturnDatesAsStrings"=>true, "Scrollable" => 'static');

                    if (!$userID){
                        $stmt = sqlsrv_prepare($conn, $sql, array($startTime, $endTime), $options);
                    }
                    else
                    {
                        $stmt = sqlsrv_prepare($conn, $sql, array($startTime, $endTime, $userID), $options);
                    }

                    if (!$stmt){
                        $e = json_encode(sqlsrv_errors());
                        echo "<div class='alert alert-danger'>Failed to prepare report. Error: $e</div>";
                        die();
                    }

                    if (!sqlsrv_execute($stmt)){
                        $e = json_encode(sqlsrv_errors());
                        echo "<div class='alert alert-danger'>Failed to execute report. Error: $e</div>";
                        die();
                    }

                    $columns = sqlsrv_field_metadata($stmt);

                    echo "<h5>".sqlsrv_num_rows($stmt)." records found</h5>";

                    echo "<div class='table-responsive'><table class='table table-hover table-striped table-sm table-bordered'>";
                    echo "<thead class='table-success'><tr>";

                    foreach($columns as $colData){
                        $colName = $colData["Name"];
                        echo "<th>$colName</th>";
                    }

                    echo "</tr></thead>";
                    echo "<tbody>";

                    while( $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC) ){
                        echo "<tr>";
                        foreach($row as $col){
                            echo "<td>$col</td>";
                        }
                        echo "</tr>";
                    }

                    echo "</tbody></table></div>";

                    sqlsrv_free_stmt($stmt);  
                    sqlsrv_close($conn);  

                }
            ?>
        </div>
    </body>
    <?php include 'scripts.php' ?>
<!---------------------------------------------------------------> 
</html>