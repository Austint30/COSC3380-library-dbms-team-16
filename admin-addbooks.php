<?php
    include 'connect.php';
    include 'require-signin.php';
?>

<!DOCTYPE html>
<html>
    <!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php' ?>
    </head>
    <!--------------------------------------------------------------->
    <body>
        <?php include 'headerbar-auth.php' ?>
        <form class="container mt-5" action="addbook-response-server.php" method="post">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb h3">
                    <li class="breadcrumb-item" aria-current="page"><a href="/books.php">Books</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add book(s)</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add Book</h5>
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addbook-isbn" class="form-label">ISBN</label>
                            <input class="form-control" id="addbook-isbn" name="bookISBN" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="addbook-title" class="form-label">Title</label>
                            <input class="form-control" id="addbook-title" name="bookTitle" required>
                        </div>
                    </div>
					<div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addbook-genre" class="form-label">Genre</label>
							<select id="signup-type" class="form-select" name="bookGenre" required>
                            <option value="" selected>Choose a genre</option>
							<option value="FANTASY">FANTASY</option>
                            <option value="SCI_FI">SCI_FI</option>
							<option value="DYSTOPIAN">DYSTOPIAN</option>
							<option value="ACTION_ADVEN">ACTION_ADVEN</option>
							<option value="MYSTERY">MYSTERY</option>
							<option value="HORROR">HORROR</option>
							<option value="THRILL">THRILL</option>
							<option value="HIS_FICTION">HIS_FICTION</option>
							<option value="ROMANCE">ROMANCE</option>
							<option value="GRAPH_NOVEL">GRAPH_NOVEL</option>
							<option value="AUTO_BIO">AUTO_BIO</option>
							<option value="BIO">BIO</option>
							<option value="ART">ART</option>
							<option value="HISTORY">HISTORY</option>
							<option value="TRAVEL">TRAVEL</option>
							<option value="CRIME">CRIME</option>
							<option value="HUMOR">HUMOR</option>
							<option value="GUIDE">GUIDE</option>
							<option value="RELIGION">RELIGION</option>
							<option value="SCIENCE">SCIENCE</option>
                        </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="addbook-fname" class="form-label">AuthorFName</label>
                            <input class="form-control" id="addbook-fname" name="bookFName" required>
                        </div>
                    </div>
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addbook-lname" class="form-label">AuthorLName</label>
                            <input class="form-control" id="addbook-lname" name="bookLName" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="addbook-mname" class="form-label">AuthorMName</label>
                            <input class="form-control" id="addbook-mname" name="bookMName" required>
                        </div>
                    </div>
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addbook-replacementcost" class="form-label">Replacement Cost</label>
                            <input class="form-control" id="addbook-replacementcost" name="bookCost" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="addbook-ddn" class="form-label">DDN</label>
                            <input class="form-control" id="addbook-ddn" name="bookDDN" required>
                        </div>
                    </div> 
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addbook-yearpublished" class="form-label">Year Published</label>
                            <input class="form-control" id="addbook-yearpublished" name="bookYear" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="addbook-ddn" class="form-label">Quantity</label>
                            <input type="number" max="100" min="1" class="form-control" id="addbook-quanity" name="bookQuantity" value="1" required>
                        </div>
                    </div>					
                    <button id="addbook-button" type="submit" class="btn btn-primary">Add Book</button>
                </div>
            </div>
            <?php
                if (isset($_GET["msg"])){
                    $msg = $_GET["msg"];
                    echo "<div class='alert alert-primary mt-3' role='alert'>
                        $msg
                    </div>";
                }
                if (isset($_GET["errormsg"])){
                    $msg = $_GET["errormsg"];
                    echo "<div class='alert alert-danger mt-3' role='alert'>
                        $msg
                    </div>";
                }
            ?>
        </form>
    </body>
    <!--------------------------------------------------------------->
</html>
