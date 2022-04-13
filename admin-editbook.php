<?php
    include 'connect.php';
    include 'require-signin.php';

    if (!isset($_GET["isbn"])){
        // Redirect to books page if no isbn is specified.
        header("Location: /book-detail.php");
    }
    $isbn = $_GET["isbn"];

    $result = sqlsrv_query($conn,
        "SELECT b.Title, b.Genre, b.AuthorLName, b.AuthorMName, b.AuthorFName, b.[Year Published], b.DDN, b.ISBN, b.[Replacement Cost]
        FROM library.library.[Book Title] as b
        WHERE b.ISBN='$isbn'"
    );
    $book = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC);

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
        <div class="container mt-5">
            <?php include 'messages.php' ?>
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb h3">
                    <li class="breadcrumb-item" aria-current="page"><a href="/books.php">Books</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="/book-detail.php?isbn=<?php echo $isbn ?>"><?php echo $book[0] ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit book</li>
                </ol>
            </nav>
            <form class="card mb-4" action="editbook-response-server.php?isbn=<?php echo $isbn ?>" method="post">
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
            </form>
            <div class="card">
                <?php
                    $bookISBN = $book[7];
                    $result = sqlsrv_query($conn,
                    "SELECT i.[Item ID]
                    FROM library.library.Item as i
                    WHERE i.[Book Title ID]='$bookISBN'
                        AND i.[Checked Out By] IS NULL
                        AND i.[Held By] IS NULL",
                    array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

                    $numRows = sqlsrv_num_rows( $result );
                ?>
                <div class="card-body">
                    <h5 class="card-title">Checked in copies</h5>
                    <?php echo "<div>$numRows copies</div>"; ?>
                </div>
                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Item ID</th>
                            <th>
                                <form method="post" action="/book-add-copy.php" style='float: right;'>
                                    <input style='display: none;' name='isbn' value='<?php echo $isbn ?>' />
                                    <div class='input-group justify-content-end'>
                                        <input value='1' class='form-control' type='number' min='1' max='100' class='me-1' style='max-width: 8rem;' name='numCopies' />
                                        <button type='submit' class='btn btn-primary'>Add (n) copies</a>
                                    </div>
                                </form>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // if (!$hasdata){
                            //     echo "<tr>";
                            //     echo "<td>No data. You can add a copy with the Add copy button.</td>";
                            //     echo "<td></td>";
                            //     echo "</tr>";
                            //     return;
                            // }
                            
                            while ( $row = sqlsrv_fetch_array($result)){
                                echo "<tr>";
                                echo "<td>$row[0]</td>";
                                echo "<td>
                                    <a href='/book-remove-item.php?isbn=$bookISBN&itemID=$row[0]' class='btn btn-outline-danger' style='float: right;'>Remove Item</a>
                                </td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
    <?php include 'scripts.php' ?>
    <!--------------------------------------------------------------->
</html>
