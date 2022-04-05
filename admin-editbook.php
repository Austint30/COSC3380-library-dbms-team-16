<?php
    include 'connect.php';

    if (!isset($_GET["isbn"])){
        // Redirect to books page if no isbn is specified.
        header("Location: /book-detail.php");
    }
    $isbn = $_GET["isbn"];

    $result = $conn->query(
        "SELECT Title, Genre, AuthorLName, AuthorMName, AuthorFName, `Year Published`, DDN, ISBN, `Replacement Cost`
        FROM library.`Book Title`
        WHERE ISBN='$isbn'"
    );
    $book = $result->fetch_row();

    if (!$book){
        header("Location: /books.php?errormsg=Book doesn't exist.");
    }
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
        <form class="container mt-5" action="editbook-response-server.php?isbn=<?php echo $isbn ?>" method="post">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb h3">
                    <li class="breadcrumb-item" aria-current="page"><a href="/books.php">Books</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="/book-detail.php?isbn=<?php echo $isbn ?>"><?php echo $book[0] ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit book</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Book</h5>
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addbook-isbn" class="form-label">ISBN</label>
                            <input class="form-control" id="addbook-isbn" name="bookISBN" value="<?php echo $book[7] ?>" disabled>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="addbook-title" class="form-label">Title</label>
                            <input class="form-control" id="addbook-title" name="bookTitle" value="<?php echo $book[0] ?>" required>
                        </div>
                    </div>
					<div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addbook-genre" class="form-label">Genre</label>
							<select id="signup-type" class="form-select" name="bookGenre" required>
                                <option value="" <?php echo $book[1] == "" ? "selected" : "" ?>>Choose an genre</option>
                                <option value="FANTASY" <?php echo $book[1] == "FANTASY" ? "selected" : "" ?>>FANTASY</option>
                                <option value="SCI_FI" <?php echo $book[1] == "SCI_FI" ? "selected" : "" ?>>SCI_FI</option>
                                <option value="DYSTOPIAN" <?php echo $book[1] == "DYSTOPIAN" ? "selected" : "" ?>>DYSTOPIAN</option>
                                <option value="ACTION_ADVEN" <?php echo $book[1] == "ACTION_ADVEN" ? "selected" : "" ?>>ACTION_ADVEN</option>
                                <option value="MYSTERY" <?php echo $book[1] == "MYSTERY" ? "selected" : "" ?>>MYSTERY</option>
                                <option value="HORROR" <?php echo $book[1] == "HORROR" ? "selected" : "" ?>>HORROR</option>
                                <option value="THRILL" <?php echo $book[1] == "THRILL" ? "selected" : "" ?>>THRILL</option>
                                <option value="HIS_FICTION" <?php echo $book[1] == "HIS_FICTION" ? "selected" : "" ?>>HIS_FICTION</option>
                                <option value="ROMANCE" <?php echo $book[1] == "ROMANCE" ? "selected" : "" ?>>ROMANCE</option>
                                <option value="GRAPH_NOVEL" <?php echo $book[1] == "GRAPH_NOVEL" ? "selected" : "" ?>>GRAPH_NOVEL</option>
                                <option value="AUTO_BIO" <?php echo $book[1] == "AUTO_BIO" ? "selected" : "" ?>>AUTO_BIO</option>
                                <option value="BIO" <?php echo $book[1] == "BIO" ? "selected" : "" ?>>BIO</option>
                                <option value="ART" <?php echo $book[1] == "ART" ? "selected" : "" ?>>ART</option>
                                <option value="HISTORY" <?php echo $book[1] == "HISTORY" ? "selected" : "" ?>>HISTORY</option>
                                <option value="TRAVEL" <?php echo $book[1] == "TRAVEL" ? "selected" : "" ?>>TRAVEL</option>
                                <option value="CRIME" <?php echo $book[1] == "CRIME" ? "selected" : "" ?>>CRIME</option>
                                <option value="HUMOR" <?php echo $book[1] == "HUMOR" ? "selected" : "" ?>>HUMOR</option>
                                <option value="GUIDE" <?php echo $book[1] == "GUIDE" ? "selected" : "" ?>>GUIDE</option>
                                <option value="RELIGION" <?php echo $book[1] == "RELIGION" ? "selected" : "" ?>>RELIGION</option>
                                <option value="SCIENCE" <?php echo $book[1] == "SCIENCE" ? "selected" : "" ?>>SCIENCE</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="addbook-fname" class="form-label">AuthorFName </label>
                            <input class="form-control" id="addbook-fname" name="bookFName" value="<?php echo $book[4] ?>" required>
                        </div>
                    </div>
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addbook-lname" class="form-label">AuthorLName</label>
                            <input class="form-control" id="addbook-lname" name="bookLName" value="<?php echo $book[2] ?>" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="addbook-mname" class="form-label">AuthorMName</label>
                            <input class="form-control" id="addbook-mname" name="bookMName" value="<?php echo $book[3] ?>" required>
                        </div>
                    </div>
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addbook-replacementcost" class="form-label">Replacement Cost</label>
                            <input class="form-control" id="addbook-replacementcost" name="bookCost" value="<?php echo $book[8] ?>" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="addbook-ddn" class="form-label">DDN</label>
                            <input class="form-control" id="addbook-ddn" name="bookDDN" value="<?php echo $book[6] ?>" required>
                        </div>
                    </div> 
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addbook-yearpublished" class="form-label">Year Published</label>
                            <input class="form-control" id="addbook-yearpublished" name="bookYear" value="<?php echo $book[5] ?>" required>
                        </div>
                    </div>					
                    <button id="addbook-button" type="submit" class="btn btn-primary">Save</button>
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
