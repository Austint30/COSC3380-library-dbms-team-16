<!DOCTYPE html>
<html>
<!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php'; 
		include 'connect.php';
		include 'require-signin.php';
		?>
    </head>
<!----------------------Here we have the book title table----------------------------------------->
    <body>
	    <?php include 'headerbar-auth.php' ?>
		<div class="container mt-5">
			<div class="mb-3 d-flex">
				<h1 class="mb-0">Check Out</h1>
			</div>
			<?php
				$result = sqlsrv_query($conn,
					"SELECT a.[Last Name], a.[First Name], COUNT(i.[Item ID]) as Holds
					FROM library.library.Account as a
					INNER JOIN library.library.Item as i ON i.[Held By] = a.[User ID]
					GROUP BY a.[Last Name], a.[First Name]
					ORDER BY a.[Last Name], a.[First Name]"
				);
				if (!$result){
					echo "<div class='alert alert-danger mb-3'>Failed to get holds.</div>";
				}
			?>
			<div class='card'>
				<div class='card-body'>
					<h5 class='card-title'>Users with holds</h5>
				</div>
				<table class='table table-striped mb-0'>
					<thead>
						<tr>
							<td>Last name</td>
							<td>First name</td>
							<td>No. Holds</td>
						</tr>
					</thead>
					<tbody>
						<?php
							if ($result){
								while ( $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
									$lname = $row['Last Name'];
									$fname = $row['First Name'];
									$holds = $row['Holds'];
									echo "<tr>";
									echo "<td>$lname</td>";
									echo "<td>$fname</td>";
									echo "<td class='d-flex justify-content-between'>
										$holds
										<a href='#' class='btn btn-sm btn-outline-primary'>View Holds</a>
									</td>";
									echo "</tr>";
								}
							}
						?>
						<tr></tr>
					</tbody>
				</table>
			</div>
		</div>
    </body>
	<?php include 'scripts.php' ?>
<!---------------------------------------------------------------> 
</html>