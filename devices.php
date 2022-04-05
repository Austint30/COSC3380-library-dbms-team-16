<!DOCTYPE html>
<html>
<!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php'; 
		include 'connect.php';?>	
    </head>
<!----------------------Here we have the device title table----------------------------------------->
    <body>
	    <?php include 'headerbar-auth.php' ?>		
		<?php
			$result = $conn->query(
				"SELECT `Model No.`, `Name`, `Manufacturer`, `Device Title`.`Date Added`, `Type`, count(library.Item.`Device Title ID`) as Stock
				FROM library.`Device Title`
				LEFT OUTER JOIN library.Item ON library.`Device Title`.`Model No.` = library.Item.`Device Title ID`
				AND library.Item.`Checked Out By` IS NULL AND library.Item.`Held By` IS NULL
                WHERE library.`Device Title`.Delisted = 0
				GROUP BY library.`Device Title`.`Model No.`
				ORDER BY library.`Device Title`.`Name`"
			);
			$columns = $result->fetch_fields();
			$results = $result->fetch_all();
		?>
		<div class="container mt-5">
            <h1>Devices</h1>
			<div class="alert alert-warning mb-3" role="alert">
			This page is still a WIP. It may not work properly yet.
			</div>
			<?php
				if (isset($_GET["search"])){
					// We have a search url parameter. Display a message.
					echo "<p>Searching for: <i>\"";
					echo $_GET["search"];
					echo "</i>\" (doesn't actually work yet)</p>";
				}
			?>
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<?php
							foreach($columns as $colData){
								echo "<th>$colData->name</th>";
							}
						?>
						<th>Learn More</th>
					</tr>
				<thead>
				<tbody>
					<?php
						foreach($results as $row){
							echo "<tr>";
							for ($i = 0; $i < 5; $i++) {
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