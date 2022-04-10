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
				<h1 class="mb-0">Check Out/In</h1>
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
					<form method='post' action='/checkout-response-server.php'>
						<div class="row align-items-start mb-3">
							<div class="col-6">
								<label for="checkout-userid" class="form-label">User ID</label>
								<input class="form-control" id="checkout-userid" name="userid" required>
							</div>
							<div class="col-6">
								<label for="checkout-itemid" class="form-label">Item ID</label>
								<input class="form-control" id="checkout-itemid" name="itemid" required>
							</div>
						</div>
						<div class="row align-items-start mb-3">
							<div class="col-6">
								<label for="checkout-duedate" class="form-label">Set Due Date</label>
								<?php
									$next_due_date = date('Y-m-d', strtotime('+30 days'));
									$date = (new DateTime($next_due_date))->format('Y-m-d');
								?>
								<input type="date" class="form-control" id="checkout-duedate" name="duedate" required value="<?php echo $date ?>">
								<div id="due-date-help" class="form-text">Automatically set to next month</div>
							</div>
						</div>
						<button type='submit' class='btn btn-primary'>Check out</button>
						<div id="checkout-item-info"></div>
					</form>
				</div>
			</div>
			<div class='card mb-3'>
				<div class='card-body'>
					<h5 class='card-title'>Check in an item</h5>
					<form method='post' action='/checkout-response-server.php'>
						<div class="row align-items-start mb-3">
							<div class="col-6">
								<label for="checkin-itemid" class="form-label">Item ID</label>
								<input class="form-control" id="checkin-itemid" name="itemid" required>
							</div>
						</div>
						<button type='submit' class='btn btn-primary'>Check in</button>
						<div id="checkin-fee-info"></div>
						<div id="checkin-item-info"></div>
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
		const co_itemInput = document.getElementById("checkout-itemid");
		const co_itemInfo = document.getElementById("checkout-item-info");

		co_itemInput.addEventListener('input', updateCoItemInfo);

		function renderItemHTML(dataObj){
			let html = `
				<h5 class='mt-3'>Item information</h5>
				<table class='table table-striped'>
					<tbody>
			`;
			let entries = Object.entries(dataObj);
			if (entries.length <= 0){
				return "<div class='alert alert-warning mb-0 mt-3'>Item not found</div>";
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

		function updateCoItemInfo(){
			console.log('updateCoItemInfo called')
			const itemid = co_itemInput.value;
			if (!itemid){
				console.log('item input us empty. Hiding info.');
				co_itemInfo.innerHTML = "";
				return;
			}
			console.log('Updating item info');
			co_itemInfo.innerHTML = "<span class='text-secondary'>Loading...</span>";
			fetch('/get-item-info.php?itemid=' + itemid)
			.then((resp) => resp.json())
			.then((data) => {
				const html = renderItemHTML(data);
				co_itemInfo.innerHTML = html;
			})
			.catch((e) => {
				co_itemInfo.innerHTML = "<div class='alert alert-danger mb-0 mt-3'>Failed to get item information.</div>";
				console.error(e);
			})
		}
	</script>
	<script>
		const ci_itemInput = document.getElementById("checkin-itemid");
		const ci_feeInfo = document.getElementById("checkin-fee-info");
		const ci_itemInfo = document.getElementById("checkin-item-info");

		ci_itemInput.addEventListener('input', updateCiItemInfo);

		let hasCheckedOutItem = false;

		function renderFeesHTML(dataObj){
			console.log('dataObj', dataObj);
			let html = `
				<h5 class='mt-3'>Fee info for ${dataObj.userName}</h5>
			`;

			if (dataObj.fees.length <= 0){
				html += "<div class='alert alert-success mb-0 mt-3'>No unpaid fees!</div>";
				return html;
			}

			html += `<table class='table table-striped table-warning'>
					<thead><tr>
			`

			let colNames = new Set();
			// Compute columns
			dataObj.fees.forEach(fee => {
				let keys = Object.keys(fee);
				keys.forEach(key => colNames.add(key));
			})

			// Put columns into html
			colNames.forEach(col => html += `<th>${col}</th>`)
			html += "</tr></thead>";
			html += "<tbody>";
			
			for (let i = 0; i < dataObj.fees.length; i++) {
				html += "<tr>";
				const fee = dataObj.fees[i];
				colNames.forEach(col => {
					html += `<td>${fee[col]}</td>`;
				});
				html += "</tr>";
			}
			html += "</tbody></table";
			console.log('html', html);
			return html;
		}

		function updateCiItemInfo(){
			hasCheckedOutItem = false;
			const itemid = ci_itemInput.value;
			if (!itemid){
				console.log('item input us empty. Hiding info.');
				ci_itemInfo.innerHTML = "";
				ci_feeInfo.innerHTML = "";
				return;
			}
			console.log('Updating item info');
			ci_itemInfo.innerHTML = "<span class='text-secondary'>Loading...</span>";

			// Fee info
			fetch('/get-item-unpaid-fees.php?itemid=' + itemid)
			.then((resp) => {
				if (resp.status === 404){
					resp.text()
						.then((msg) => ci_feeInfo.innerHTML = `<div class='alert alert-warning mb-0 mt-3'>${msg}</div>`)
						.catch(() => {
							throw Error("Failed to parse 404 response");
						})
					fetchItemInfo();
				}
				else if (resp.status >= 200 && resp.status < 300)
				{
					hasCheckedOutItem = true;
					resp.json().then((data) => {
						console.log('data received: ', data);
						const html = renderFeesHTML(data);
						ci_feeInfo.innerHTML = html;
					})
					fetchItemInfo();
				}
				else
				{
					throw Error("Bad status (" + resp.status + ")");
					fetchItemInfo();
				}
			})
			.catch((e) => {
				ci_feeInfo.innerHTML = "<div class='alert alert-danger mb-0 mt-3'>Failed to get item information.</div>";
				console.error(e);
			})

			function fetchItemInfo(){
				// Item info
				fetch('/get-item-info.php?itemid=' + itemid)
				.then((resp) => resp.json())
				.then((data) => {
					if (hasCheckedOutItem){
						const html = renderItemHTML(data);
						ci_itemInfo.innerHTML = html;
					}
					else
					{
						ci_itemInfo.innerHTML = "";
					}
				})
				.catch((e) => {
					ci_itemInfo.innerHTML = "<div class='alert alert-danger mb-0'>Failed to get item information.</div>";
					console.error(e);
				})
			}

			

		}

	</script>
<!---------------------------------------------------------------> 
</html>