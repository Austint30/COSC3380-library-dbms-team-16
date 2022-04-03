<!DOCTYPE html>
<html>
<!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php'; 
		include 'connect.php';?>	
    </head>
<!----------------------Here we have the book title table----------------------------------------->
    <body>
	    <?php include 'headerbar-auth.php' ?>		
		<?php
			$result = $conn->query(
				"SELECT Title, Genre, AuthorFName, AuthorMName, AuthorLName, `Year Published`, ISBN, count(library.Item.`Book Title ID`) as Stock
				FROM library.`Book Title`
				LEFT OUTER JOIN library.Item ON library.`Book Title`.ISBN = library.Item.`Book Title ID`
				WHERE Item.`Checked Out By` IS NULL AND Item.`Held By` IS NULL
				GROUP BY library.`Book Title`.Title
				ORDER BY library.`Book Title`.Title"
			);
			$columns = $result->fetch_fields();
			$results = $result->fetch_all();
		?>
		<div class="container mt-5">
            <h1>Books</h1>
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
								if ($i == 7) { // Quantity column
									if ($value == 0){
										$value = "<span class='text-danger'>Out of stock</span>";
									}
									else if ($value < 10){
										$value = "<span class='text-warning'>Limited stock</span>";
									}
									else
									{
										$value = "In stock";
									}
									echo "<td>$value</td>";
								}
								else
								{
									echo "<td>$value</td>";
								}
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