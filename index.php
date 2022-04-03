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
			$result = $conn->query("SELECT * FROM library.`Book Title`");
			$columns = $result->fetch_fields();
			$results = $result->fetch_all();
		?>
		<p class="fs-1 text-center">Books:</p>
		<div class="container">
			<table class="table table-hover">
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
<!-------------------------------Here we have the media title table-------------------------------->

		<?php
			$result = $conn->query("SELECT * FROM library.`Media Title`");
			$columns = $result->fetch_fields();
			$results = $result->fetch_all();
		?>
		<p class="fs-1 text-center" >Media:</p>
		<div class="container">
			<table class="table table-striped table-hover">
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
<!--------------------------------Here we have the device title table------------------------------->

		<?php
			$result = $conn->query("SELECT * FROM library.`Device Title`");
			$columns = $result->fetch_fields();
			$results = $result->fetch_all();
			$conn->close();
		?>
		<p class="fs-1 text-center">Devices:</p>
		<div class="container">
			<table class="table table-striped table-hover">
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
            <a href="/library/signup.php">Go to Sign Up</a>
        </div>
    </body>
<!---------------------------------------------------------------> 
</html>