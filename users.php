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
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Users awaiting approval</h5>
                </div>
                <?php
                    $stmt = sqlsrv_query($conn, "SELECT Account.[Last Name], Account.[First Name], Account.Type, Account.[Email], Account.[User ID] FROM Account WHERE Account.Approved='0'");
                    $unApprColumns = sqlsrv_fetch_metadata($result);
                    $unApprUsers = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC);
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
                            for ($j = 0; $j < count($unApprUsers); $j++) {
                                $row = $unApprUsers[$j];
                                $userID = $row[4];
                                $tdStyle = "";
                                if ($j == count($unApprUsers)-1){
                                    $tdStyle = "border-bottom: none;";
                                }
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
                    $stmt = sqlsrv_query($conn, "SELECT Account.[Last Name], Account.[First Name], Account.Type, Account.[Email], Account.[User ID] FROM Account WHERE Account.Approved='1'");
                    $columns = sqlsrv_fetch_metadata($result);
                    $users = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC);
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
                            for ($j = 0; $j < count($users); $j++) {
                                $row = $users[$j];
                                $userID = $row[3];
                                $tdStyle = "";
                                if ($j == count($users)-1){
                                    $tdStyle = "border-bottom: none;";
                                }
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