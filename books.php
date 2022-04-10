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
		<?php
			$result = sqlsrv_query($conn,
				"SELECT Title, Genre, AuthorLName, AuthorMName, AuthorFName, [Year Published], ISBN, count(i.[Book Title ID]) as Stock
				FROM library.library.[Book Title] as b
				LEFT OUTER JOIN library.library.Item as i ON b.ISBN = i.[Book Title ID]
				AND i.[Checked Out By] IS NULL AND i.[Held By] IS NULL
                WHERE b.Delisted = 0
				GROUP BY Title, Genre, AuthorLName, AuthorMName, AuthorFName, [Year Published], ISBN
				ORDER BY b.Title"
			);
			if (!$result){
				die("Failed to get books.");
			}
			$columns = sqlsrv_field_metadata($result);
		?>
		<div class="container mt-5">
			<div class="mb-3 d-flex">
				<h1 class="mb-0">Books</h1>
				<?php
					// Add books button only appears for ADMIN or STAFF users
					$stmt = sqlsrv_query($conn, "SELECT a.Type FROM library.library.Account as a WHERE a.[User ID]=$cookie_userID");
					sqlsrv_fetch($stmt);
					if ($user){
						$userType = sqlsrv_get_field($stmt, 0);
						if ($userType == 'ADMIN' || $userType == "STAFF"){
							echo '<a href="/admin-addbooks.php" class="btn btn-success ms-auto" style="height: fit-content; align-self: end;">Add Book(s)<a>';
						}
					}
				?>
			</div>

			<?php include 'messages.php' ?>
            
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
						<th>Title</th>
						<th>Genre</th>
						<th>Author</th>
						<th>Year Published</th>
						<th>ISBN</th>
						<th>Stock</th>
						<th></th>
					</tr>
				<thead>
				<tbody>
					<?php
						while( $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC) ){
							echo "<tr>";
							for ($i = 0; $i < 2; $i++) {
								echo "<td>$row[$i]</td>";
							}
							$fullNameArray = [$row[2], $row[3], $row[4]];
							$fullNameArray = array_filter($fullNameArray, 'strlen');
							$fullNameStr = join(", ", $fullNameArray);
							echo "<td>$fullNameStr</td>";
							for ($i = 5; $i < count($row); $i++) {
								$value = $row[$i];
								if ($i == 7) { // Quantity column
									if ($value == 0){
										$value = "<span class='text-danger'>Out of stock</span>";
									}
									else if ($value < 4){
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
							$bookISBN = $row[6];
							echo "<td>
                                    <a href='book-detail.php?isbn=$bookISBN' class='btn btn-primary btn-small' style='float: left;'>
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