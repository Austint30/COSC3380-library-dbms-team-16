<!DOCTYPE html>
<html>
<!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php';
		include 'connect.php';
		include 'require-signin.php';?>	
    </head>
<!----------------------Here we have the device title table----------------------------------------->
    <body>
	    <?php include 'headerbar-auth.php' ?>		
		<?php

			$sql = "SELECT dt.[Model No.], dt.[Name], dt.[Manufacturer], dt.[Date Added], dt.[Type], COUNT(i.[Device Title ID]) as Stock
			FROM library.library.[Device Title] as dt
			LEFT OUTER JOIN Items_With_Check_Out as i ON i.[Device Title ID] = dt.[Model No.]
			AND i.[Checked Out By] IS NULL AND i.[Held By] IS NULL
			WHERE dt.Delisted = 0";

			$search = "";
			if (isset($_GET["search"])){
				$search = $_GET["search"];
				$sql = $sql." AND dt.Name collate SQL_Latin1_General_CP1_CI_AS LIKE ? ";
				// SQL_Latin1_General_CP1_CI_AS somehow gets it to be case insensitive
			}

			$sql = $sql."
				GROUP BY dt.[Model No.], dt.[Name], dt.[Manufacturer], dt.[Date Added], dt.[Type]
				ORDER BY dt.[Name];";

			if (isset($_GET["search"])){
				$result = sqlsrv_query($conn, $sql, array("%".$search."%"));
			}
			else
			{
				$result = sqlsrv_query($conn, $sql);
			}

			if (!$result){
				die("Failed to fetch devices");
			}
			$columns = sqlsrv_field_metadata($result);
		?>
		<div class="container mt-5">
					<div class="mb-3 d-flex">
				<h3 class="mb-0">Devices</h3>
				<?php
					// Add books button only appears for ADMIN or STAFF users
					$stmt = sqlsrv_query($conn, "SELECT a.Type FROM library.library.Account as a WHERE a.[User ID]=$cookie_userID");
					sqlsrv_fetch($stmt);
					if ($user){
						$userType = sqlsrv_get_field($stmt, 0);
						if ($userType == 'ADMIN' || $userType == "STAFF"){
							echo '<a href="/admin-adddevices.php" class="btn btn-success ms-auto" style="height: fit-content; align-self: end;">Add Devices<a>';
						}
					}
				?>
			</div>
			<?php include 'messages.php' ?>
            <!-- <h3>Devices</h3> -->
<!-- 			<div class="alert alert-warning mb-3" role="alert">
			This page is still a WIP. It may not work properly yet.
			</div> -->
			<?php
				if (isset($_GET["search"])){
					// We have a search url parameter. Display a message.
					echo "<p>Searching for: <i>\"";
					echo $_GET["search"];
					echo "</i>\"</p>";
				}
			?>
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<?php
							foreach($columns as $colData){
								$colName = $colData["Name"];
								echo "<th>$colName</th>";
							}
						?>
						<th>Learn More</th>
					</tr>
				<thead>
				<tbody>
					<?php
						while( $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC) ){
							echo "<tr>";
							for ($i = 0; $i < 3; $i++) {
								echo "<td>$row[$i]</td>";
							}
							$formatted = $row[3]->format('m/d/y');
							echo "<td>$formatted</td>";
							for ($i = 4; $i < 5; $i++) {
								echo "<td>$row[$i]</td>";
							}
							$stock = $row[5];
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
							$modelNo = $row[0];
							echo "<td>
                                    <a href='device-details.php?modelNo=$modelNo' class='btn btn-primary btn-small' style='float: left;'>
                                        Learn More
                                    </a>
                                </td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
		</div>
    </body>
	<?php include 'scripts.php' ?>
<!---------------------------------------------------------------> 
</html>