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
        $df_groupBy = "";
        $df_checkOutUserID = "";
        $df_approvingUserID = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $df_startTime = $_POST["startTime"];
            $df_endTime = $_POST["endTime"];
            $df_activityType = $_POST["activityType"];
            $df_reportType = $_POST["report-type"];
            $df_checkOutUserID = $_POST["checkOutUserID"];
            $df_approvingUserID = $_POST["approvingUserID"];

            if (isset($_POST["group-by"])){
                $df_groupBy = $_POST["group-by"];
            }

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
                    <li class="breadcrumb-item active" aria-current="page">Check in and out activity</li>
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
                                    <input class="form-check-input" type="radio" name="report-type" id="summary-radio" value="summary" <?php echoChecked($df_reportType, "summary", true) ?>>
                                    <label class="form-check-label" for="summary-radio">Summary report</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="report-type" id="detail-radio" value="detail" <?php echoChecked($df_reportType, "detail") ?>>
                                    <label class="form-check-label" for="detail-radio">Detail report</label>
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="group-by" id="approv-radio" value="approv" <?php echoChecked($df_groupBy, "approving", true); if ($df_reportType == "detail"){ echo " disabled"; } ?>>
                                    <label class="form-check-label" for="approv-radio">Group by approving user</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="group-by" id="checking-radio" value="checking" <?php echoChecked($df_groupBy, "checking"); if ($df_reportType == "detail"){ echo " disabled"; } ?>>
                                    <label class="form-check-label" for="checking-radio">Group by checking out user</label>
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
                            <div class="col-3 mb-3">
                                <label for="report-activity-type" class="form-label">Activity Type</label>
                                <select id="report-activity-type" class="form-select" name="activityType" required>
                                    <option value="" <?php echoSelected($df_activityType, "", true)?>>Choose an activity type</option>
                                    <option value="CHECK_OUT" <?php echoSelected($df_activityType, "CHECK_OUT")?>>Item checked out</option>
                                    <option value="CHECK_IN" <?php echoSelected($df_activityType, "CHECK_IN")?>>Item checked in</option>
                                    <option value="LATE" <?php echoSelected($df_activityType, "LATE")?>>Item late</option>
                                    <option value="MODIFY" <?php echoSelected($df_activityType, "MODIFY")?>>Item check out modified</option>
                                </select>
                            </div>
                            <div class="col-3 mb-3">
                                <label for="report-check-out-user-id" class="form-label">Filter checked out user ID</label>
                                <input class="form-control" id="report-check-out-user-id" name="checkOutUserID" value="<?php echo $df_checkOutUserID ?>">
                            </div>
                        </div>
                        <div class="row align-items-start">
                            <div class="col-3 mb-3">
                                <label for="report-approving-user-id" class="form-label">Filter approving user ID</label>
                                <input class="form-control" id="report-approving-user-id" name="approvingUserID" value="<?php echo $df_approvingUserID ?>">
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
                    $checkOutUserID = $_POST["checkOutUserID"];
                    $approvingUserID = $_POST["approvingUserID"];
                    $reportType = $_POST["report-type"];
                    $groupBy = null;
                    if (isset($_POST["group-by"])){
                        $groupBy = $_POST["group-by"];
                    }

                    $cmd = "";
                    $summTitle = "";

                    switch ($activityType) {
                        case 'CHECK_OUT':
                            $summTitle = "[No. Items Checked Out]";
                            break;
                        case 'CHECK_IN':
                            $summTitle = "[No. Items Checked In]";
                            break;
                        case 'LATE':
                            $summTitle = "[No. Items Late]";
                            break;
                        case 'MODIFY':
                        default:
                            $summTitle = "[No. Items Modified]";
                            break;
                    }

                    switch ($groupBy) {
                        case 'checking':
                            $cmd = "[Checked Out By]";
                            break;
                        case 'approv':
                        default:
                            $cmd = "[Approving Librarian]";
                            break;
                    }

                    $args = array($startTime, $endTime, $activityType);

                    $summary = "SELECT a.[User ID], a.[Last Name] + ', ' + a.[First Name] as [User Name], a.Type as [User Type], COUNT(*) as $summTitle
                    FROM library.library.Account as a, [library].[library].[Checked_Out_Items_Transactions] as c
                    WHERE a.[User ID] = c.$cmd
                        AND c.[Trans Time] >= ?
                        AND c.[Trans Time] <= ?
                        AND c.[Trans Type] = ?";
                    
                    if ($approvingUserID){
                        $summary = $summary." AND c.[Approving Librarian]=?";
                        array_push($args, $approvingUserID);
                    }

                    if ($checkOutUserID){
                        $summary = $summary." AND c.[Checked Out By]=?";
                        array_push($args, $checkOutUserID);
                    }

                    $summary = $summary." GROUP BY a.[User ID], a.[Last Name] + ', ' + a.[First Name], a.Type";

                    $detail = "SELECT [Trans ID]
                                ,coit.[Item ID]
                                ,[Trans Time]
                                ,coit.[Checked Out By]
                                ,aa.[Last Name] + ', ' + aa.[First Name] as [Checked Out By Name]
                                ,coit.[Approving Librarian]
                                ,ab.[Last Name] + ', ' + ab.[First Name] as [Approving Librarian Name]
                                ,[Trans Type],
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
                            FROM [library].[library].[Checked_Out_Items_Transactions] as coit, library.dbo.Items_With_Title_Details_No_Delist as i, library.library.Account as aa, library.library.Account as ab
                            WHERE i.[Item ID] = coit.[Item ID] AND coit.[Checked Out By] = aa.[User ID] AND coit.[Approving Librarian] = ab.[User ID]
                                AND coit.[Trans Time] >= ?
                                AND coit.[Trans Time] <= ?
                                AND coit.[Trans Type] = ?
                        ";

                    if ($checkOutUserID){
                        array_push($args, $checkOutUserID);
                        $detail = $detail." AND coit.[Checked Out By]=?";
                    }

                    if ($approvingUserID){
                        array_push($args, $approvingUserID);
                        $detail = $detail." AND coit.[Approving Librarian]=?";
                    }
                    
                    $detail = $detail." ORDER BY [Trans Time] DESC";

                    if ($reportType == "summary"){
                        $sql = $summary;
                    }
                    else
                    {
                        $sql = $detail;
                    }

                    $options = array("ReturnDatesAsStrings"=>true, "Scrollable" => 'static');

                    $stmt = sqlsrv_prepare($conn, $sql, $args, $options);

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
                        echo "<tfoot class='table-dark'><tr>";
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
    <script>
        const summaryRadio = document.getElementById('summary-radio');
        const detailRadio = document.getElementById('detail-radio');
        const approvRadio = document.getElementById('approv-radio');
        const checkingRadio = document.getElementById('checking-radio');

        const activityTypeSelect = document.getElementById('report-activity-type');

        let reportType = "<?php echo $df_reportType | "summary" ?>";

        summaryRadio.addEventListener('change', handleReportTypeChange);
        detailRadio.addEventListener('change', handleReportTypeChange);

        function handleReportTypeChange(e){
            const value = e?.target?.value;
            modifyGroupRadio(value);
        }

        function modifyGroupRadio(rt){

            reportType = rt;

            if (rt === "summary"){
                approvRadio.removeAttribute("disabled");
                checkingRadio.removeAttribute("disabled");
            }
            else
            {
                approvRadio.setAttribute("disabled", true);
                checkingRadio.setAttribute("disabled", true);
            }
        }
    </script>
    <?php include 'scripts.php' ?>
<!---------------------------------------------------------------> 
</html>