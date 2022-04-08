<?php
    include 'connect.php';
    if (!isset($_GET["modelNo"])){
        // Redirect to devices page if no model number is specified.
        header("Location: /devices.php");
    }
    $modelNo = $_GET["modelNo"];
    $result = sqlsrv_query($conn,
        "SELECT [Model No.], [Name], [Manufacturer], [Type], count(library.Item.[Device Title ID]) as Stock
        FROM library.[Device Title]
        LEFT OUTER JOIN library.Item ON library.[Device Title].[Model No.] = library.Item.[Device Title ID]
        AND library.Item.[Checked Out By] IS NULL AND library.Item.[Held By] IS NULL
        WHERE library.[Device Title].[Model No.] = '$modelNo'
        GROUP BY library.[Device Title].[Name]"
    );
    $device = $result->fetch_row();
    if (!$device){
        // Device not found die in a hole
        http_response_code(404);
        include '404.php';
        die();
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
        <form class="container mt-5">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb h3">
                    <li class="breadcrumb-item" aria-current="page"><a href="/devices.php">Devices</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $device[1] ?></li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <h5 class="card-title">Device Details</h5>
                        <div class="ms-auto d-flex flex-column justfy-content-end">
                            <a
                                href="device-hold.php?modelNo=<?php echo $modelNo ?>"
                                class="ms-auto btn btn-success <?php if ($device[4] == 0) { echo "disabled"; } ?>"
                            >Place Hold</a>
                            <?php if ($device[4] == 0) { echo '<div class="text-secondary">Sorry, we\'re out of stock</div>'; } ?>
                            
                        </div>
                    </div>
                    
                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="text-bold">ModelNo.</td>
                                <?php echo "<td>$device[0]</td>" ?>
                            </tr>
                            <tr>
                                <td class="text-bold">Name</td>
                                <?php echo "<td>$device[1]</td>" ?>
                            </tr>
                            <tr>
                                <td class="text-bold">Manufacturer</td>
                                <?php echo "<td>$device[2]</td>" ?>
                            </tr>
                            <tr>
                                <td class="text-bold">Type</td>
                                <?php echo "<td>$device[3]</td>" ?>
                            </tr>
                            <tr>
                                <td class="text-bold">Stock</td>
                                <?php
                                    $stock = $device[4];
                                    if ($stock == 0){
										$stock = "<span class='text-danger'>Out of stock</span>";
									}
									else if ($stock < 4){
										$stock = "<span class='text-warning'>Limited stock</span>";
									}
									else
									{
										$stock = "In stock";
									}
									echo "<td>$stock</td>";
                                ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
                if (isset($_GET["msg"])){
                    $msg = $_GET["msg"];
                    echo "<div class='alert alert-primary mt-3' role='alert'>
                        $msg
                    </div>";
                }
                if (isset($_GET["errormsg"])){
                    $msg = $_GET["errormsg"];
                    echo "<div class='alert alert-danger mt-3' role='alert'>
                        $msg
                    </div>";
                }
            ?>
        </form>
    </body>
    <?php include 'scripts.php' ?>
    <!--------------------------------------------------------------->
</html>