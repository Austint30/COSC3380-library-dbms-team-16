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
        $df_userID = "";
        $df_reportType = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $df_startTime = $_POST["startTime"];
            $df_endTime = $_POST["endTime"];
            $df_reportType = $_POST["report-type"];
            $df_userID = $_POST["userID"];
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
                    <li class="breadcrumb-item active" aria-current="page">Fee revenue</li>
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
                        <div class="row align-items-start">
                            <div class="col-4 mb-3">
                                <label for="report-datetime-start" class="form-label">Start time (UTC)</label>
                                <input type="datetime-local" class="form-control" id="report-datetime-start" name="startTime" required value="<?php echo $df_startTime ?>">
                            </div>
                            <div class="col-4 mb-3">
                                <label for="report-datetime-end" class="form-label">End time (UTC)</label>
                                <input type="datetime-local" class="form-control" id="report-datetime-end" name="endTime" required value="<?php echo $df_endTime ?>">
                            </div>
                            <div class="col-4 mb-3">
                                <label for="report-user-id" class="form-label">Filter by fee paying user ID</label>
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
                    $userID = $_POST["userID"];
                    $reportType = $_POST["report-type"];

                    $summary = "SELECT a.[User ID] as [Fee Paying User ID], a.[Last Name] + ', ' + a.[First Name] as [Fee Paying User Name], a.Type as [User Type], SUM(f.[Amount Paid]) as [Total Paid]
                    FROM library.library.Account as a, [library].[library].[Fee Transaction] as f
                    WHERE a.[User ID] = f.[Fee Payer]
                        AND f.[Trans Time] >= ?
                        AND f.[Trans Time] <= ?
                        AND f.[Amount Paid] != 0";

                    $params = array($startTime, $endTime);
                    $userid_pushed = false;

                    if ($userID && $reportType == "summary"){
                        $summary = $summary." AND f.[User ID]=?";
                        array_push($params, $userID);
                        $userid_pushed = true;
                    }

                    $summary = $summary." GROUP BY a.[User ID], a.[Last Name] + ', ' + a.[First Name], a.Type";

                    $detail = "SELECT f.[Trans ID]
                            ,f.[Fee ID]
                            ,f.[Item ID]
                            ,f.[Fee Payer]
                            ,aa.[Last Name] + ', ' + aa.[First Name] as [Fee Payer Name]
                            ,f.[Amount Owed]
                            ,f.[Amount Paid],
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
                        FROM [library].[library].[Fee Transaction] as f, library.dbo.Items_With_Title_Details as i, library.library.Account as aa
                        WHERE i.[Item ID] = f.[Item ID] AND f.[Fee Payer] = aa.[User ID] AND f.[Amount Owed] > f.[Amount Paid]
                            AND f.[Trans Time] >= ?
                            AND f.[Trans Time] <= ?
                        ";
                    
                    if ($userID && !$userid_pushed && $reportType == "detail"){
                        $detail = $detail." AND f.[Fee Payer]=?";
                        array_push($params, $userID);
                        $userid_pushed = true;
                    }
                    
                    if ($reportType == "summary"){
                        $sql = $summary;
                    }
                    else
                    {
                        $sql = $detail;
                        $sql = $sql." ORDER BY [Trans Time] DESC";
                    }

                    $options = array("ReturnDatesAsStrings"=>true, "Scrollable" => 'static');

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
                        else {
                            $total += $row[6];
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
                    else
                    {
                        echo "<tfoot class='table-dark'><tr>";
                        echo "<th colspan='6' style='text-align: right;'>Total:</th>";
                        echo "<th>$total</th>";
                        echo "<th colspan='4' />";
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
    <?php include 'scripts.php' ?>
<!---------------------------------------------------------------> 
</html>