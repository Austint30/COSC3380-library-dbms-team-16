<?php
    include 'connect.php';
?>

<!DOCTYPE html>
<html>
    <!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php' ?>
		<?php include 'headerbar-auth.php' ?>
    </head>
    <!--------------------------------------------------------------->
    <body>
	
<!-- 	<div class="d-flex" role="d-flex" aria-label="Toolbar with button groups">
	  <div class="btn-group me-2" role="group" aria-label="First group">
		<button type="button" class="btn btn-primary">1</button>
	  </div>
	  <div class="btn-group me-2" role="group" aria-label="Second group">
		<button type="button" class="btn btn-secondary">5</button>
	  </div>
	  <div class="btn-group" role="group" aria-label="Third group">
		<button type="button" class="btn btn-info">8</button>
	  </div>
	</div> -->
	
	<img src="adminicon.png" class="rounded mx-auto d-block" alt="...">
	
	<form class="container mt-5" action="addbook-response-server.php" method="post">
	<div class="card">
			<div class="row">          
				<div class="btn-group">
				  <button id="addbook-button" type="submit" class="btn btn-primary me-5">Add Book</button>
				  <button id="addbook-button" type="submit" class="btn btn-primary me-5">Add Book</button>
				  <button id="addbook-button" type="submit" class="btn btn-primary ">Add Book</button>
				</div>
			</div>
			<div class="row mt-5">          
				<div class="btn-group">
				  <button id="addbook-button" type="submit" class="btn btn-primary me-5">Add Book</button>
				  <button id="addbook-button" type="submit" class="btn btn-primary me-5">Add Book</button>
				  <button id="addbook-button" type="submit" class="btn btn-primary ">Add Book</button>
				</div>
			</div>
			<div class="row mt-5">          
				<div class="btn-group">
				  <button id="addbook-button" type="submit" class="btn btn-primary me-5">Add Book</button>
				  <button id="addbook-button" type="submit" class="btn btn-primary me-5">Add Book</button>
				  <button id="addbook-button" type="submit" class="btn btn-primary ">Add Book</button>
				</div>
			</div>

	</div>
	</form>

    </body>
    <!--------------------------------------------------------------->
</html>
