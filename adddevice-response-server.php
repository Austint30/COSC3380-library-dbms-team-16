<?php
    include 'connect.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $modelNo = $_POST['modelNo'];
        $deviceName = $_POST['deviceName'];
        $type = $_POST['deviceType'];
        $manufacturer = $_POST['deviceManufacturer'];
        $replacementcost = $_POST['deviceCost'];
		$dateAdded = $_POST['deviceDate'];
        $quantity = $_POST['quantity'];
		
        echo "POST received. Values are below:";
        echo $modelNo;
        echo $deviceName;
        echo $type;
        echo $manufacturer;
        echo $replacementcost;
		echo $dateAdded;
        
		/*$query = "
            INSERT INTO [Device Title] ([Device Title].[Model No.], [Device Title].Name, [Device Title].Type, [Device Title].Manufacturer, [Device Title].[Replacement Cost], [Device Title].[Date Added])
            VALUES (?, ?, ?, ?, ?, ?);
        ";*/
		
		$query = "
            INSERT INTO library.library.[Device Title] (library.library.[Model No.], library.library.Name, library.library.Type, library.library.Manufacturer, library.library.[Replacement Cost], library.library.AuthorMName, library.library.[Replacement Cost], library.library.[Date Added])
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);
        ";

        echo $query;
		
		$stmt = sqlsrv_prepare($conn, $query, array($modelNo, $deviceName, $type, $manufacturer, $replacementcost, $dateAdded));

        $res = sqlsrv_execute($stmt);
		
        if ($res == false){
            $e = json_encode(sqlsrv_errors());
            header("Location: admin-adddevices.php?errormsg=Failed to add device. Make sure that you aren't adding a duplicate device. Error: $e");
        }		
        
        for ($i=0; $i < $quantity; $i++) { 
            $query = "INSERT INTO library.library.[Device Title] (library.library.[Model No.], library.library.Name, library.library.Type, library.library.Manufacturer, library.library.[Replacement Cost], library.library.AuthorMName, library.library.[Replacement Cost], library.library.[Date Added]) VALUES (CURRENT_TIMESTAMP, ?)";

            $stmt = sqlsrv_prepare($conn, $query, array($modelNo));
            $res = sqlsrv_execute($stmt);

            if (!$res){
                header("Location: admin-adddevices.php?errormsg=Failed to add copies of the device. Please contact system admin.");
            }
        }

        header("Location: admin-adddevices.php?msg=Device sucessfully added.");
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }

		/*$q = $conn->prepare($query);
        $q->bind_param("ssssss", $modelNo, $deviceName, $type, $manufacturer, $replacementcost, $dateAdded);

        try {
            $result = $q->execute();
        } catch (\Throwable $th) {
            header("Location: admin-adddevices.php?errormsg=Failed to add book. Make sure that you aren't adding a duplicate book.");
        }

        for ($i=0; $i < $quantity; $i++) { 
            $query = "INSERT INTO library.Item (library.Item.[Date Added], library.Item.[Device Title ID]) VALUES (CURRENT_TIMESTAMP, ?)";

            $q = $conn->prepare($query);
            $q->bind_param("s", $modelNo);

            try {
                $result = $q->execute();
            } catch (\Throwable $th) {
                header("Location: admin-adddevices.php?errormsg=Failed to add copies of the book. Please contact system admin.");
            }
        }
        header("Location: /devices.php?msg=Device Added successfully");
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }*/
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