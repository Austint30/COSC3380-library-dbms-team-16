<?php
    include 'connect.php';
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
        <form class="container mt-5" action="adddevice-response-server.php" method="post">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb h3">
                    <li class="breadcrumb-item" aria-current="page"><a href="/devices.php">Devices</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Device(s)</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add Device</h5>
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="adddevice-modelNo" class="form-label">Model Number</label>
                            <input class="form-control" id="adddevice-modelNo" name="modelNo" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="adddevice-name" class="form-label">Name</label>
                            <input class="form-control" id="adddevice-name" name="deviceName" required>
                        </div>
                    </div>
					<div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="adddevice-type" class="form-label">Type</label>
							<select id="signup-type" class="form-select" name="deviceType" required>
                            <option value="" selected>Choose a device</option>
							<option value="TABLET">TABLET</option>
                            <option value="LAPTOP">LAPTOP</option>
                            <option value="CALCULATOR">CALCULATOR</option>
                        </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="adddevice-Manufacturer" class="form-label">Manufacturer</label>
                            <input class="form-control" id="adddevice-Manufacturer" name="deviceManufacturer" required>
                        </div>
                    </div>
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="adddevice-replacementcost" class="form-label">Replacement Cost</label>
                            <input class="form-control" id="adddevice-replacementcost" name="deviceCost" required>
                        </div>
                    </div> 
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="adddevice-Date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="adddevice-Date" name="deviceDate" required>
                        </div>
                    </div>					
                    <button id="adddevice-button" type="submit" class="btn btn-primary">Add Device</button>
                </div>
            </div>
        </form>
    </body>
    <!--------------------------------------------------------------->
</html>
