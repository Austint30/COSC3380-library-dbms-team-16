<!DOCTYPE html>
<html>
<!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php'; 
		include 'connect.php';
        include 'require-signin.php';
        ?>	
    </head>
    <?php

        $df_startTime = "";
        $df_endTime = "";
        $df_activityType = "";
        $df_userID = "";
        $df_reportType = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $df_startTime = $_POST["startTime"];
            $df_endTime = $_POST["endTime"];
            $df_activityType = $_POST["activityType"];
            $df_userID = $_POST["userID"];
            $df_reportType = $_POST["report-type"];

        }

        function echoSelected($var, $value, $default=false){
            if ($var == $value || $default){
                echo "selected";
            }
        }

        function echoChecked($var, $value, $default=false){
            if ($var == $value || $default){
                echo "checked";
            }
        }
    ?>
	<body>
		<?php include 'headerbar-auth.php' ?>
		<div class="container mt-5">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb h3">
                    <li class="breadcrumb-item" aria-current="page"><a href="/reports.php">Reports</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Inventory changes</li>
                </ol>
            </nav>
            <?php include 'messages.php' ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Set criteria</h5>
                    <form method="post">
                        <div class="row my-3">
                            <div class="col">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="report-type" id="inlineRadio1" value="summary" <?php echoChecked($df_reportType, "summary", true) ?>>
                                    <label class="form-check-label" for="inlineRadio1">Summary report</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="report-type" id="inlineRadio2" value="detail" <?php echoChecked($df_reportType, "detail") ?>>
                                    <label class="form-check-label" for="inlineRadio2">Detail report</label>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-start">
                            <div class="col-3 mb-3">
                                <label for="report-datetime-start" class="form-label">Start time (UTC)</label>
                                <input type="datetime-local" class="form-control" id="report-datetime-start" name="startTime" required value="<?php echo $df_startTime ?>">
                            </div>
                            <div class="col-3 mb-3">
                                <label for="report-datetime-end" class="form-label">End time (UTC)</label>
                                <input type="datetime-local" class="form-control" id="report-datetime-end" name="endTime" required value="<?php echo $df_endTime ?>">
                            </div>
                            <div class="col-3 mb-3" id="activity-container">
                                <label for="report-activity-type" class="form-label">Activity Type</label>
                                <select id="report-activity-type" class="form-select" name="activityType" required>
                                    <option value="" <?php echoSelected($df_activityType, "", true) ?>>Choose an activity type</option>
                                    <option value="CREATE" <?php echoSelected($df_activityType, "CREATE") ?>>Item created</option>
                                    <option value="MODIFY" <?php echoSelected($df_activityType, "MODIFY") ?>>Item modified</option>
                                    <option value="HOLD" <?php echoSelected($df_activityType, "HOLD") ?>>Item held</option>
                                    <option value="REM_HOLD" <?php echoSelected($df_activityType, "REM_HOLD") ?>>Item hold removed</option>
                                    <option value="DELIST" <?php echoSelected($df_activityType, "DELIST") ?>>Item delisted</option>
                                </select>
                            </div>
                            <div class="col-3 mb-3">
                                <label for="report-user-id" class="form-label">Filter by user ID</label>
                                <input class="form-control" id="report-user-id" name="userID" value="<?php echo $df_userID ?>">
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
                    $uf_startTime = new DateTime($_POST["startTime"]);
                    $uf_endTime = new DateTime($_POST["endTime"]);

                    $startTime = $uf_startTime->format('Y-m-d H:i:s');
                    $endTime = $uf_endTime->format('Y-m-d H:i:s');
                    $activityType = $_POST["activityType"];
                    $userID = $_POST["userID"];
                    $reportType = $_POST["report-type"];

                    $cmd = "";
                    $summTitle = "";

                    switch ($activityType) {
                        case 'CREATE':
                            $cmd = "[Created By]";
                            $summTitle = "[No. Items Created]";
                            break;
                        case 'DELIST':
                            $cmd = "[Delisted By]";
                            $summTitle = "[No. Items Delisted]";
                            break;
                        case 'HOLD':
                            $cmd = "[Held By]";
                            $summTitle = "[No. Items Held]";
                            break;
                        case 'REM_HOLD':
                            $cmd = "[Modified By]";
                            $summTitle = "[No. Items Hold Removed]";
                            break;
                        case 'MODIFY':
                        default:
                            $cmd = "[Modified By]";
                            $summTitle = "[No. Items Modified]";
                            break;
                    }

                    $summary = "SELECT it.$cmd as [User ID], ca.[Last Name] + ', ' + ca.[First Name] as [User Name], ca.Type as [User Type], COUNT(*) as $summTitle
                        FROM [library].[library].[Item Transaction] as it
                        LEFT JOIN library.library.Account as ca ON it.$cmd = ca.[User ID]
                        RIGHT JOIN library.dbo.Items_With_Title_Details_No_Delist as i ON i.[Item ID] = it.[Item ID]
                        WHERE i.[Item ID] = it.[Item ID]";
                    
                    $detail = "SELECT [Trans ID]
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
                        WHERE i.[Item ID] = it.[Item ID]";

                    $params = array();

                    $userSql = "";
                    
                    if ($userID){
                        if ($activityType == "CREATE"){
                            $userSql = " AND it.[Created By]=?";
                            array_push($params, $userID);
                        }
                        else if ($activityType == "DELIST"){
                            $userSql = " AND it.[Delisted By]=?";
                            array_push($params, $userID);
                        }
                        else if ($activityType == "MODIFY"){
                            $userSql = " AND it.[Modified By]=?";
                            array_push($params, $userID);
                        }
                    }
                    
                    if ($reportType == "detail"){
                        $sql = $detail."
                                $userSql
                                AND it.[Trans Time] >= ?
                                AND it.[Trans Time] <= ?
                                AND it.[Trans Type] = ?
                                ORDER BY [Trans Time] DESC
                            ";
                    }
                    else
                    {
                        $sql = $summary." 
                                $userSql
                                AND it.[Trans Time] >= ?
                                AND it.[Trans Time] <= ?
                                AND it.[Trans Type] = ?
                                GROUP BY it.$cmd ,ca.[Last Name] + ', ' + ca.[First Name], ca.Type
                            ";
                    }

                    $options = array("ReturnDatesAsStrings"=>true, "Scrollable" => 'static');
                    
                    array_push($params, $startTime, $endTime, $activityType);
                    
                    $stmt = sqlsrv_prepare($conn, $sql, $params, $options);

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

                    if ($reportType == "summary"){
                        echo "<div class='container'>";
                    }

                    echo "<h5>".sqlsrv_num_rows($stmt)." records found from ".$uf_startTime->format('l M j, y \a\t g:iA')." to ".$uf_endTime->format('l M j, y \a\t g:iA')."</h5>";

                    echo "<div class='table-responsive'><table class='table table-hover table-striped table-sm table-bordered'>";
                    echo "<thead class='table-success'><tr>";

                    foreach($columns as $colData){
                        $colName = $colData["Name"];
                        echo "<th>$colName</th>";
                    }

                    echo "</tr></thead>";
                    echo "<tbody>";

                    $total = 0;

                    while( $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC) ){
                        if ($reportType == "summary"){
                            $total += $row[3];
                        }
                        echo "<tr>";
                        foreach($row as $col){
                            echo "<td>$col</td>";
                        }
                        echo "</tr>";
                    }

                    echo "</tbody>";

                    if ($reportType == "summary"){



                        echo "<tfoot><tr>";
                        echo "<th colspan='3' style='text-align: right;'>Total:</th>";
                        echo "<th>$total</th>";
                        echo "</tr></tfoot>";
                    }

                    echo "</table></div>";

                    if ($reportType == "summary"){
                        echo "</div>";
                    }

                    sqlsrv_free_stmt($stmt);  
                    sqlsrv_close($conn);  

                }
            ?>
        </div>
    </body>
    <!-- <script>
        const activityContainer = document.getElementById('activity-container');
        const acChildren = activityContainer.children;
        const acChildrenClones = [];

        for (let i = 0; i < acChildren.length; i++) {
            const child = acChildren[i];
            acChildrenClones.push(child.cloneNode(true));
        }

        function removeChildNodes(parent){
            while (parent.firstChild){
                parent.removeChild(parent.firstChild);
            }
        }

        function handleReportTypeChange(e){
            removeChildNodes(activityContainer);
            
            let value;
            if (e){
                value = e.target.value;
            }
            else
            {
                value = "summary";
            }


            if (value === "summary"){
                removeChildNodes(activityContainer);
                activityContainer.setAttribute('style', 'display: none;');
            }
            else
            {
                activityContainer.removeAttribute('style');
                for (let i = 0; i < acChildrenClones.length; i++) {
                    activityContainer.appendChild(acChildrenClones[i].cloneNode(true))
                }
            }
        }

        handleReportTypeChange();
    </script> -->
    <?php include 'scripts.php' ?>
<!---------------------------------------------------------------> 
</html>