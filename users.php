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
            <div class="mb-3 d-flex">
                <h1 class="mb-0">User Management</h1>
                <button class="btn btn-primary ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                    Add user
                </button>
            </div>
            <?php include 'messages.php' ?>
            <div class="collapse collapse-vertical" id="collapseWidthExample">
                <form class="card mb-3" action="admin-create-user-response-server.php" method="post">
                    <div class="card-body">
                        <h5 class="card-title">Add new account</h5>
                        <div class="row align-items-start">
                            <div class="col-6 mb-3">
                                <label for="newuser-firstname" class="form-label">First name</label>
                                <input class="form-control" id="newuser-firstname" name="firstName" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="newuser-lastname" class="form-label">Last name</label>
                                <input class="form-control" id="newuser-lastname" name="lastName" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="newuser-password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="newuser-password" name="password">
                        </div>
                        <div class="mb-3">
                            <label for="newuser-type" class="form-label">Account type</label>
                            <select id="newuser-type" class="form-select" name="type" required>
                                <option value="" selected>Choose an account type</option>
                                <option value="ADMIN">Admin</option>
                                <option value="STAFF">Staff</option>
                                <option value="STUDENT">Student</option>
                                <option value="FACULTY">Faculty</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label id="newuser-email-label" for="newuser-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="newuser-email" disabled name="email" required>
                        </div>
                        <div class="mb-3">
                            <label id="newuser-phone-label" for="newuser-phone" class="form-label">Phone</label>
                            <input type="phone" class="form-control" id="newuser-phone" disabled name="phone" required>
                        </div>
                        <div class="d-flex align-items-center">
                            <button id="newuser-button" type="submit" class="btn btn-primary" disabled>Add account</button>
                            <div class="form-text ms-3">Users created using this form are automatically approved.</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card mb-3">
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
    <?php include 'scripts.php' ?>
    <script>
        const typeField = document.getElementById("newuser-type");
        const newuserButton = document.getElementById("newuser-button");

        function onTypeClicked(){
            console.log("Account type changed.");
            const emailField = document.getElementById("newuser-email");
            const phoneField = document.getElementById("newuser-phone");
            const emailLabel = document.getElementById("newuser-email-label");
            const phoneLabel = document.getElementById("newuser-phone-label");

            let selectValue = typeField.options[typeField.selectedIndex];
            if (selectValue.value === "STUDENT"){
                emailLabel.innerHTML = "Parent/Guardian email";
                phoneLabel.innerHTML = "Parent/Guardian phone";
            }
            else
            {
                emailLabel.innerHTML = "Email";
                phoneLabel.innerHTML = "Phone";
            }
            if (selectValue.value){
                emailField.removeAttribute("disabled");
                phoneField.removeAttribute("disabled");
                newuserButton.removeAttribute("disabled");
            }
            else
            {
                emailField.setAttribute("disabled", true);
                phoneField.setAttribute("disabled", true);
                newuserButton.setAttribute("disabled", true);
            }
        }
        typeField.onchange = onTypeClicked;
    </script>
</html>