<!DOCTYPE html>
<html>
<!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php'; 
		include 'connect.php';
        include 'require-signin.php';
        ?>	
    </head>
<!----------------------Here we have the popular books----------------------------------------->
	<body>
		<?php include 'headerbar-auth.php' ?>
        <?php include 'messages.php' ?>	
		<div class="container mt-5">
            <h3>Reports</h2>
            <ol class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <a href="/reports/inventory-changes.php" class="block fw-bold">Inventory changes</a>
                        <div>Reports changes in inventory, including adding of items, modification of items, and delisting of items.</div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <a href="/reports/checkinout-activity.php" class="block fw-bold">Check out/Check in activity</a>
                        <div>Reports check ins and check outs of various items as well as when an item becomes late</div>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <a href="/reports/unpaid-fees.php" href="/reports/unpaid-fees.php" class="block fw-bold">Unpaid Fees</a>
                        <div>Reports fees that are currently unpaid</div>
                    </div>
                </li>
            </ol>
        </div>
    </body>
    <?php include 'scripts.php' ?>
<!---------------------------------------------------------------> 
</html>