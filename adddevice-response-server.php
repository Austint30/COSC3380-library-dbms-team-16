<?php
    include 'connect.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $modelNo = $_POST['modelNo'];
        $deviceName = $_POST['deviceName'];
        $type = $_POST['deviceType'];
        $manufacturer = $_POST['deviceManufacturer'];
        $replacementcost = $_POST['deviceCost'];
		$dateAdded = $_POST['deviceDate'];
		
        echo "POST received. Values are below:";
        echo $modelNo;
        echo $deviceName;
        echo $type;
        echo $manufacturer;
        echo $replacementcost;
		echo $dateAdded;
		
        $result = $conn->query("
            INSERT INTO `Device Title` (`Device Title`.`Model No.`, `Device Title`.Name, `Device Title`.Type, `Device Title`.Manufacturer, `Device Title`.`Replacement Cost`, `Device Title`.`Date Added`)
            VALUES ('$modelNo', '$deviceName', '$type', '$manufacturer', '$replacementcost', '$dateAdded');
        ");
        header("Location: /devices.php?msg=Device Added successfully");
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }
?>

<html>
    <!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php' ?>
    </head>
    <body>
        <?php include 'headerbar-unauth.php' ?>
        <div class="container mt-5 text-center">
            <h1></h1>
            <p>
                
            </p>
        </div>
    </body>
</html>