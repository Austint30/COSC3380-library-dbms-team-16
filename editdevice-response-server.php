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
                library.library.[Device Title].[Model No.] = ?,
                library.library.[Device Title].Name = ?,
                library.library.[Device Title].Type = ?,
                library.library.[Device Title].Manufacturer = ?,
                library.library.[Device Title].[Replacement Cost] = ?,
            WHERE library.library.[Device Title].ISBN = ?
        ";

        echo $query;

        $stmt = sqlsrv_prepare($conn, $query, array($modelNo, $name, $type, $manufacturer, $replacementcost, $modelNo));
        $res = sqlsrv_execute($stmt);

        if ($res == false){
            echo print_r( sqlsrv_errors());
            $e = sqlsrv_errors()[0][0];
            header("Location: admin-editdevice.php?modelNo=$modelNo&errormsg=Failed to modify device. Make sure that you aren't adding a duplicate device. Error: $e");
        }

        header("Location: admin-editdevice.php?modelNo=$modelNo&msg=Changes saved successfully.");
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }
?>