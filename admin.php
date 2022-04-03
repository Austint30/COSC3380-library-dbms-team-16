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
            <h1 class="mb-2 pb-3 border-bottom">This is the admin page.</h1>
            <h4>Unapproved users</h4>
            <table class="table table-striped">
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
                        foreach($unApprUsers as $row){
                            $userID = $row[3];
                            echo "<tr>";
                            for ($i = 0; $i < count($row); $i++) {
                                $value = $row[$i];
                                echo "<td>$value</td>";
                            }
                            echo "<td>
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
    </body>
</html>