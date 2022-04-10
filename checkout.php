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
			<div class='card mb-3'>
				<div class='card-body'>
					<h5 class='card-title'>Check out an item</h5>
					<form>
						<div class="row align-items-start mb-3">
							<div class="col-6">
								<label for="checkout-userid" class="form-label">User ID</label>
								<input class="form-control" id="checkout-userid" name="userid" required>
							</div>
							<div class="col-6">
								<label for="checkout-itemid" class="form-label">Item ID</label>
								<input class="form-control" id="checkout-itemid" name="itemid" required oninput="updateItemInfo()">
							</div>
						</div>
						<div id="checkout-item-info"></div>
					</form>
				</div>
			</div>
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
	<script>
		const itemInput = document.getElementById("checkout-itemid");
		const itemInfo = document.getElementById("checkout-item-info");

		function renderHTML(dataObj){
			let html = `
				<h5>Item information</h5>
				<table class='table table-striped'>
					<tbody>
			`;
			let entries = Object.entries(dataObj);
			if (entries.length <= 0){
				return "<div class='alert alert-warning mb-0'>Item not found</div>";
			}
			for (let i = 0; i < entries.length; i++) {
				const el = entries[i];
				let key = el[0];
				let val = el[1];
				html += `<tr><th>${key}</th><td>${val}</td></tr>`;
			}
			html += "</tbody></table";
			return html;
		}

		function updateItemInfo(){
			const itemid = itemInput.value;
			if (!itemid){
				console.log('item input us empty. Hiding info.');
				itemInfo.innerHTML = "";
				return;
			}
			console.log('Updating item info');
			itemInfo.innerHTML = "<span class='text-secondary'>Loading...</span>";
			fetch('/get-item-info.php?itemid=' + itemid)
			.then((resp) => resp.json())
			.then((data) => {
				const html = renderHTML(data);
				itemInfo.innerHTML = html;
			})
			.catch((e) => {
				itemInfo.innerHTML = "<div class='alert alert-danger mb-0'>Failed to get item information.</div>";
				console.error(e);
			})
		}
	</script>
<!---------------------------------------------------------------> 
</html>