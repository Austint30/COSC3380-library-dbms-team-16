
<?php

    header("Location: /books.php"); // Remove when there is time to add index page.
?>
<!DOCTYPE html>
<html>
<!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php'; 
		include 'connect.php';?>	
    </head>
<!----------------------Here we have the popular books----------------------------------------->
	<body>
		<?php include 'headerbar-auth.php' ?>		
		<div class="container mt-5">
			<h3>Our Most Popular Books</h3>
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th scope="col">ISBN</th>
						<th scope="col">Title</th>
						<th scope="col">Genre</th>
						<th scope="col">AuthorFName</th>
						<th scope="col">AuthorLName</th>
						<th scope="col">AuthorMName</th>
						<th scope="col">Replacement Cost</th>
						<th scope="col">DDN</th>
						<th scope="col">Year Published</th>
						<th scope="col">Learn More</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row">9780048230911</th>
						<td>The Lord of the Rings</td>
						<td>FANTASY</td>
						<td>John</td>
						<td>Tolkien</td>
						<td>Ronald Reuel</td>
						<td>18</td>
						<td></td>
						<td>1955</td>
						<td><button type="button" class="btn btn-primary">Check It Out</button></td>
					</tr>
					<tr>
						<th scope="row">9780048230911</th>
						<td>The Lord of the Rings</td>
						<td>FANTASY</td>
						<td>John</td>
						<td>Tolkien</td>
						<td>Ronald Reuel</td>
						<td>18</td>
						<td></td>
						<td>1955</td>
						<td><button type="button" class="btn btn-primary">Check It Out</button></td>
					</tr>
					<tr>
						<th scope="row">9780048230911</th>
						<td>The Lord of the Rings</td>
						<td>FANTASY</td>
						<td>John</td>
						<td>Tolkien</td>
						<td>Ronald Reuel</td>
						<td>18</td>
						<td></td>
						<td>1955</td>
						<td><button type="button" class="btn btn-primary">Check It Out</button></td>
					</tr>
				</tbody>
			</table>
		</div>
<!-------------------------------Here we have the popular media-------------------------------->
<div class="container mt-5">
			<h3>Our Most Popular Media</h3>
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th scope="col">Media ID</th>
						<th scope="col">Title</th>
						<th scope="col">Year Published</th>
						<th scope="col">Author</th>
						<th scope="col">Genre</th>
						<th scope="col">Data Added</th>
						<th scope="col">Replacement Cost</th>
						<th scope="col">Learn More</th>
					</tr>
				</thead>
				<tbody>
					<tr>
					</tr>
					<tr>
					</tr>
					<tr>
					</tr>
				</tbody>
			</table>
		</div>
<!--------------------------------Here we have the popular devices------------------------------->
<div class="container mt-5">
			<h3>Our Most Popular Devices</h3>
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th scope="col">Model Number</th>
						<th scope="col">Name</th>
						<th scope="col">Manufacturer</th>
						<th scope="col">Date Added</th>
						<th scope="col">Replacement Cost</th>
						<th scope="col">Type</th>
						<th scope="col">Learn More</th>
					</tr>
				</thead>
				<tbody>
					<tr>
					</tr>
				</tbody>
			</table>
		</div>
<!-----------------------Would like it to look more like this---------------------------------------->
		
		<!-- <div>
			<p class="fs-1">Wanna add buttons to side like this:</p>
			<table class="table table-hover">
				<thead>
					<tr>
						<th scope="col">Media Title</th>
						<th scope="col">Author</th>
						<th scope="col">Genre</th>
						<th scope="col">Date Added</th>
						<th scope="col">Select</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row">--------------------</th>
						<td>--------------------</td>
						<td>--------------------</td>
						<td>--------------------</td>
						<td><button type="button" class="btn btn-primary">Select</button></td>
					</tr>
					<tr>
						<th scope="row">--------------------</th>
						<td>--------------------</td>
						<td>--------------------</td>
						<td>--------------------</td>
						<td><button type="button" class="btn btn-primary">Select</button></td>
					</tr>
					<tr>
						<th scope="row">--------------------</th>
						<td>--------------------</td>
						<td>--------------------</td>
						<td>--------------------</td>
						<td><button type="button" class="btn btn-primary">Select</button></td>
					</tr>
				</tbody>
			</table>
		</div> -->
		
		
		
		<div class="container text-center mt-5">
            <a href="/signup.php">Go to Sign Up</a>
        </div>
    </body>
    <?php include 'scripts.php' ?>
<!---------------------------------------------------------------> 
</html>