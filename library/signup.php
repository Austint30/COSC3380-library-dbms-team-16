<?php
    include 'connect.php';
?>

<!DOCTYPE html>
<html>
    <!--------------------------------------------------------------->
    <head>
        <?php include '../bootstrap.php' ?>
    </head>
    <!--------------------------------------------------------------->
    <body>
        <?php include '../headerbar.php' ?>
        <!-- <?php include '../usertable.php' ?> -->
        <form class="container mt-5" action="signup-response-server.php" method="post">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sign up</h5>
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="signup-firstname" class="form-label">First name</label>
                            <input class="form-control" id="signup-firstname" name="firstName">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="signup-lastname" class="form-label">Last name</label>
                            <input class="form-control" id="signup-lastname" name="lastName">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="signup-password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="signup-password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="signup-type" class="form-label">Account type</label>
                        <select id="signup-type" class="form-select" name="type">
                            <option value="" selected>Choose an account type</option>
                            <option value="STUDENT">Student</option>
                            <option value="FACULTY">Faculty</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label id="signup-email-label" for="signup-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="signup-email" disabled name="email">
                    </div>
                    <div class="mb-3">
                        <label id="signup-phone-label" for="signup-phone" class="form-label">Phone</label>
                        <input type="phone" class="form-control" id="signup-phone" disabled name="phone">
                    </div>
                    <button id="signup-button" type="submit" class="btn btn-primary" disabled>Sign Up</button>
                </div>
            </div>
        </form>
    </body>
    <script>
        const typeField = document.getElementById("signup-type");
        const signupButton = document.getElementById("signup-button");

        function onTypeClicked(){
            console.log("Account type changed.");
            const emailField = document.getElementById("signup-email");
            const phoneField = document.getElementById("signup-phone");
            const emailLabel = document.getElementById("signup-email-label");
            const phoneLabel = document.getElementById("signup-phone-label");

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
                signupButton.removeAttribute("disabled");
            }
            else
            {
                emailField.setAttribute("disabled", true);
                phoneField.setAttribute("disabled", true);
                signupButton.setAttribute("disabled", true);
            }
        }
        typeField.onchange = onTypeClicked;
    </script>
    <!--------------------------------------------------------------->
</html>