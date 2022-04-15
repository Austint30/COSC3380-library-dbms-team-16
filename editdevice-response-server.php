<?php
    include 'connect.php';
    include 'require-signin.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $modelNo = $_POST['deviceModelNo'];
        $name = $_POST['deviceName'];
        $type = $_POST['deviceType'];
        $manufacturer = $_POST["deviceManufacturer"];
        $replacementcost = $_POST['deviceCost'];
		
        echo "POST received. Values are below:";
        echo $modelNo;
        echo $name;
        echo $type;
        echo $manufacturer;
        echo $replacementcost;


        $query = "
            UPDATE library.library.[Device Title]
            SET
                library.library.[Device Title].Name = ?,
                library.library.[Device Title].Type = ?,
                library.library.[Device Title].Manufacturer = ?,
                library.library.[Device Title].[Replacement Cost] = ?
            WHERE library.library.[Device Title].[Model No.] = ?
        ";

        echo $query;

        $stmt = sqlsrv_prepare($conn, $query, array($name, $type, $manufacturer, $replacementcost, $modelNo));
        $res = sqlsrv_execute($stmt);

        if (!$res){
            $e = json_encode( sqlsrv_errors());
            header("Location: admin-editdevice.php?modelNo=$modelNo&errormsg=Failed to modify device.  Error: $e");
            return;
        }

        header("Location: admin-editdevice.php?modelNo=$modelNo&msg=Changes saved successfully.");
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }
?>