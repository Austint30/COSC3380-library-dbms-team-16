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
            <h3>Inventory changes report</h2>
            <?php include 'messages.php' ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Set criteria</h5>
                    <form method="post">
                        <div class="row align-items-start">
                            <div class="col-3 mb-3">
                                <label for="report-datetime-start" class="form-label">Start time (UTC)</label>
                                <input type="datetime-local" class="form-control" id="report-datetime-start" name="startTime" required>
                            </div>
                            <div class="col-3 mb-3">
                                <label for="report-datetime-end" class="form-label">End time (UTC)</label>
                                <input type="datetime-local" class="form-control" id="report-datetime-end" name="endTime" required>
                            </div>
                            <div class="col-3 mb-3">
                                <label for="report-activity-type" class="form-label">Activity Type</label>
                                <select id="report-activity-type" class="form-select" name="activityType" required>
                                    <option value="" selected>Choose an activity type</option>
                                    <option value="CREATE">Item created</option>
                                    <option value="MODIFY">Item modified</option>
                                    <option value="HOLD">Item held</option>
                                    <option value="REM_HOLD">Item hold removed</option>
                                    <option value="DELIST">Item delisted</option>
                                </select>
                            </div>
                            <div class="col-3 mb-3">
                                <label for="report-user-id" class="form-label">Filter by user ID</label>
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
                    $activityType = $_POST["activityType"];
                    $userID = $_POST["userID"];

                    $sql = "SELECT [Trans ID]
                            ,it.[Item ID]
                            ,[Trans Time]
                            ,it.[Created By]
                            ,ca.[Last Name] + ', ' + ca.[First Name] as [Created By Name]
                            ,it.[Modified By]
                            ,ma.[Last Name] + ', ' + ma.[First Name] as [Modified By Name]
                            ,it.[Delisted By]
                            ,da.[Last Name] + ', ' + da.[First Name] as [Delisted By Name]
                            ,[Trans Type]
                            ,it.[Held By],
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
                        FROM [library].[library].[Item Transaction] as it
                        LEFT OUTER JOIN library.library.Account as ca ON it.[Created By] = ca.[User ID]
                        LEFT OUTER JOIN library.library.Account as ma ON it.[Modified By] = ma.[User ID]
                        LEFT OUTER JOIN library.library.Account as da ON it.[Delisted By] = da.[User ID]
                        INNER JOIN library.dbo.Items_With_Title_Details_No_Delist as i ON i.[Item ID] = it.[Item ID]
                        WHERE i.[Item ID] = it.[Item ID]
                            AND it.[Trans Time] >= ?
                            AND it.[Trans Time] <= ?
                            AND it.[Trans Type] = ?
                        ";
                    
                    if ($userID){
                        if ($activityType == "CREATE"){
                            $sql = $sql." AND it.[Created By]=?";
                        }
                        else if ($activityType == "DELIST"){
                            $sql = $sql." AND it.[Delisted By]=?";
                        }
                        else if ($activityType == "MODIFY"){
                            $sql = $sql." AND it.[Modified By]=?";
                        }
                    }
                    
                    $sql = $sql." ORDER BY [Trans Time] DESC";

                    $options = array("ReturnDatesAsStrings"=>true, "Scrollable" => 'static');

                    if (!$userID){
                        $stmt = sqlsrv_prepare($conn, $sql, array($startTime, $endTime, $activityType), $options);
                    }
                    else
                    {
                        $stmt = sqlsrv_prepare($conn, $sql, array($startTime, $endTime, $activityType, $userID), $options);
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