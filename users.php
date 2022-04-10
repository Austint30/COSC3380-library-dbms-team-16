<?php
    include 'connect.php';
?>

<!DOCTYPE html>
<html>
    <!--------------------------------------------------------------->
    <head>
        <?php
            include 'bootstrap.php';
            include 'require-signin.php';
        ?>
        <title>Admin page</title>
    </head>
    <!--------------------------------------------------------------->
    <body>
        <?php include 'headerbar-auth.php' ?>
        <div class="container mt-5">
            <h1 class="mb-3">User Management</h1>
            <?php include 'messages.php' ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Users awaiting approval</h5>
                </div>
                <?php
                    $result = sqlsrv_query($conn, "SELECT a.[Last Name], a.[First Name], a.Type, a.[Email], a.[User ID] FROM library.library.Account as a WHERE a.Approved='0'");
                    $unApprColumns = sqlsrv_field_metadata($result);
                ?>
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <?php
                                foreach($unApprColumns as $colData){
                                    $colName = $colData["Name"];
								echo "<th>$colName</th>";
                                }
                            ?>
                        </tr>
                    <thead>
                    <tbody>
                        <?php
                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC)) {
                                $userID = $row[4];
                                $tdStyle = "";
                                echo "<tr>";
                                for ($i = 0; $i < count($row); $i++) {
                                    $value = $row[$i];
                                    echo "<td style='$tdStyle'>$value</td>";
                                }
                                echo "<td style='$tdStyle'>
                                    <a href='admin-approve-user.php?userID=$userID' class='btn btn-primary btn-small' style='float: right;'>
                                        Approve
                                    </a>
                                </td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Active users</h5>
                </div>
                <?php
                    $result = sqlsrv_query($conn, "SELECT a.[Last Name], a.[First Name], a.Type, a.[Email], a.[User ID] FROM library.library.Account as a WHERE a.Approved='1'");
                    $columns = sqlsrv_field_metadata($result);
                ?>
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <?php
                                foreach($columns as $colData){
                                    $colName = $colData["Name"];
								echo "<th>$colName</th>";
                                }
                            ?>
                        </tr>
                    <thead>
                    <tbody>
                        <?php
                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC)) {
                                $userID = $row[3];
                                $tdStyle = "";
                                echo "<tr>";
                                for ($i = 0; $i < count($row); $i++) {
                                    $value = $row[$i];
                                    echo "<td style='$tdStyle'>$value</td>";
                                }
                                // echo "<td style='$tdStyle'>
                                //     <a href='admin-approve-user.php?userID=$userID' class='btn btn-primary btn-small' style='float: right;'>
                                //         Approve
                                //     </a>
                                // </td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>