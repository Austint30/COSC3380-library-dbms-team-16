<?php
    include 'connect.php';
    include 'require-signin.php';

    if (!isset($_GET["modelNo"])){
        // Redirect to books page if no isbn is specified.
        header("Location: /device-detail.php");
    }
    $modelNo = $_GET["modelNo"];

    $result = sqlsrv_query($conn,
        "SELECT b.Name, b.Type, b.Manufacturer, b.[Date Added], b.[Model No.], b.[Replacement Cost]
        FROM library.library.[Device Title] as b
        WHERE b.[Model No.]='$modelNo'"
    );
    $device = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC);

    if (!$device){
        header("Location: /devices.php?errormsg=Device doesn't exist.");
    }
?>

<!DOCTYPE html>
<html>
    <!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php' ?>
    </head>
    <!--------------------------------------------------------------->
    <body>
        <?php include 'headerbar-auth.php' ?>
        <div class="container mt-5">
            <?php include 'messages.php' ?>
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb h3">
                    <li class="breadcrumb-item" aria-current="page"><a href="/Devices.php">Devices</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="/device-detail.php?modelNo=<?php echo $modelNo ?>"><?php echo $device[0] ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit device</li>
                </ol>
            </nav>
            <form class="card mb-4" action="editdevice-response-server.php?modelNo=<?php echo $modelNo ?>" method="post">
                <div class="card-body">
                    <h5 class="card-title">Edit Device</h5>
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="adddevice-modelNo" class="form-label">Model Number</label>
                            <input class="form-control" id="adddevice-modelNo" name="deviceModelNo" value="<?php echo $device[4] ?>" readonly>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="adddevice-name" class="form-label">Name</label>
                            <input class="form-control" id="adddevice-name" name="deviceName" value="<?php echo $device[0] ?>" required>
                        </div>
                    </div>
					<div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="adddevice-type" class="form-label">Type</label>
							<select id="signup-type" class="form-select" name="deviceType" required>
                                <option value="" <?php echo $device[1] == "" ? "selected" : "" ?>>Choose an type</option>
                                <option value="LAPTOP" <?php echo $device[1] == "LAPTOP" ? "selected" : "" ?>>LAPTOP</option>
                                <option value="CALULATOR" <?php echo $device[1] == "CALCULATOR" ? "selected" : "" ?>>CALCULATOR</option>
                                <option value="TABLET" <?php echo $device[1] == "TABLET" ? "selected" : "" ?>>TABLET</option>
                                <option value="USB" <?php echo $device[1] == "USB" ? "selected" : "" ?>>USB</option>

                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="adddevice-Manufacturer" class="form-label">Manufacturer </label>
                            <input class="form-control" id="adddevice-Manufacturer" name="deviceManufacturer" value="<?php echo $device[2] ?>" required>
                        </div>
                    </div>
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="adddevie-replacementcost" class="form-label">Replacement Cost</label>
                            <input class="form-control" id="adddevice-replacementcost" name="deviceCost" value="<?php echo $device[5] ?>" required>
                        </div>
                    </div> 
                    <!-- <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="adddevice-dateAdded" class="form-label">Date Added</label>
                             <input class="form-control" id="adddevice-dateAdded" name="bookYear" value="<?/*php echo $device[5] */?>"*/ required> 
                        </div>
                    </div>					 -->
                    <button id="adddevice-button" type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
            <div class="card">
                <?php
                    $deviceModelNo = $device[4];
                    $result = sqlsrv_query($conn,
                    "SELECT i.[Item ID]
                    FROM Items_With_Check_Out as i
                    WHERE i.[Device Title ID]='$deviceModelNo'
                        AND i.[Checked Out By] IS NULL
                        AND i.[Held By] IS NULL",
                    array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

                    $numRows = sqlsrv_num_rows( $result );
                ?>
                <div class="card-body">
                    <h5 class="card-title">Checked in copies</h5>
                    <?php echo "<div>$numRows copies</div>"; ?>
                </div>
                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Item ID</th>
                            <th>
                                <form method="post" action="/device-add-copy.php" style='float: right;'>
                                    <input style='display: none;' name='modelNo' value='<?php echo $modelNo ?>' />
                                    <div class='input-group justify-content-end'>
                                        <input value='1' class='form-control' type='number' min='1' max='100' class='me-1' style='max-width: 8rem;' name='numCopies' />
                                        <button type='submit' class='btn btn-primary'>Add (n) copies</a>
                                    </div>
                                </form>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // if (!$hasdata){
                            //     echo "<tr>";
                            //     echo "<td>No data. You can add a copy with the Add copy button.</td>";
                            //     echo "<td></td>";
                            //     echo "</tr>";
                            //     return;
                            // }
                            
                            while ( $row = sqlsrv_fetch_array($result)){
                                echo "<tr>";
                                echo "<td>$row[0]</td>";
                                echo "<td>
                                    <a href='/device-remove-item.php?modelNo=$deviceModelNo&itemID=$row[0]' class='btn btn-outline-danger' style='float: right;'>Delist Item</a>
                                </td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
    <?php include 'scripts.php' ?>
    <!--------------------------------------------------------------->
</html>