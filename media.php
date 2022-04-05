<!DOCTYPE html>
<html>
<!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php'; 
		include 'connect.php';?>	
    </head>
<!----------------------Here we have the media title table----------------------------------------->
    <body>
	    <?php include 'headerbar-auth.php' ?>		
		<?php
			$result = $conn->query(
				"SELECT Title, AuthorLName, AuthorMName, AuthorFName, `Year Published`, `Media ID`, count(library.Item.`Media Title ID`) as Stock
				FROM library.`Media Title`
				LEFT OUTER JOIN library.Item ON library.`Media Title`.`Media ID` = library.Item.`Media Title ID`
				AND library.Item.`Checked Out By` IS NULL AND library.Item.`Held By` IS NULL
				GROUP BY library.`Media Title`.`Title`
				ORDER BY library.`Media Title`.Title"
			);
			$columns = $result->fetch_fields();
			$results = $result->fetch_all();
		?>
		<div class="container mt-5">
			<div class="mb-3 d-flex">
				<h1 class="mb-0">Media</h1>
				<?php
					// TODO: Make this button only appear for ADMIN/STAFF users.
					// if ($userType == 'ADMIN' || $userType == "STAFF"){
						echo '<a href="/admin-addmedia.php" class="btn btn-success ms-auto" style="height: fit-content; align-self: end;">Add Media<a>'
					// }
				?>
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
							for ($i = 0; $i < count($row); $i++) {
								$value = $row[$i];
								echo "<td>$value</td>";
							}
							echo "<td>
                                    <a href='#' class='btn btn-primary btn-small' style='float: left;'>
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