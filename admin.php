<?php
    include 'connect.php';

    $result = $conn->query("SELECT Account.`Last Name`, Account.`First Name`, Account.`Email`, Account.`User ID` FROM Account WHERE Account.Approved='0'");
    $unApprColumns = $result->fetch_fields();
    $unApprUsers = $result->fetch_all();
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
            <h1 class="mb-3">Admin page</h1>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Users awaiting approval</h5>
                </div>
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <?php
                                foreach($unApprColumns as $colData){
                                    echo "<th>$colData->name</th>";
                                }
                            ?>
                        </tr>
                    <thead>
                    <tbody>
                        <?php
                            for ($j = 0; $j < count($unApprUsers); $j++) {
                                $row = $unApprUsers[$j];
                                $userID = $row[3];
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
        </div>
    </body>
</html>