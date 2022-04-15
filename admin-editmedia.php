<?php
    include 'connect.php';
    include 'require-signin.php';

    if (!isset($_GET["mediaID"])){
        // Redirect to media page if mediaID is specified.
        header("Location: /media-detail.php");
    }
    $mediaID = $_GET["mediaID"];

    $result = sqlsrv_query($conn,
        "SELECT b.Title, b.Genre, b.AuthorLName, b.AuthorMName, b.AuthorFName, b.[Year Published], b.[Replacement Cost], b.[Date Added], b.[Media ID]
        FROM library.library.[Media Title] as b
        WHERE b.[Media ID]='$mediaID'"
    );
    $media = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC);

    if (!$media){
        header("Location: /media.php?errormsg=Media doesn't exist.");
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
                    <li class="breadcrumb-item" aria-current="page"><a href="/media.php">Media</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="/media-detail.php?mediaID=<?php echo $mediaID ?>"><?php echo $media[0] ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit media</li>
                </ol>
            </nav>
            <form class="card mb-4" action="editmedia-response-server.php?mediaID=<?php echo $mediaID ?>" method="post">
                <div class="card-body">
                    <h5 class="card-title">Edit Media</h5>
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addmedia-mediaID" class="form-label">Media ID</label>
                            <input class="form-control" id="addmedia-mediaID" name="mediaID" value="<?php echo $media[8] ?>" disabled>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="addmedia-title" class="form-label">Title</label>
                            <input class="form-control" id="addmedia-title" name="mediaTitle" value="<?php echo $media[0] ?>" required>
                        </div>
                    </div>
					<div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addmedia-genre" class="form-label">Genre</label>
							<select id="signup-type" class="form-select" name="mediaGenre" required>
                                <option value="" <?php echo $media[1] == "" ? "selected" : "" ?>>Choose an genre</option>
                                <option value="FANTASY" <?php echo $media[1] == "ACTION" ? "selected" : "" ?>>ACTION</option>
                                <option value="FANTASY" <?php echo $media[1] == "FANTASY" ? "selected" : "" ?>>FANTASY</option>
                                <option value="SCI_FI" <?php echo $media[1] == "SCI_FI" ? "selected" : "" ?>>SCI_FI</option>
                                <option value="DYSTOPIAN" <?php echo $media[1] == "DYSTOPIAN" ? "selected" : "" ?>>DYSTOPIAN</option>
                                <option value="ACTION_ADVEN" <?php echo $media[1] == "ACTION_ADVEN" ? "selected" : "" ?>>ACTION_ADVEN</option>
                                <option value="MYSTERY" <?php echo $media[1] == "MYSTERY" ? "selected" : "" ?>>MYSTERY</option>
                                <option value="HORROR" <?php echo $media[1] == "HORROR" ? "selected" : "" ?>>HORROR</option>
                                <option value="THRILL" <?php echo $media[1] == "THRILL" ? "selected" : "" ?>>THRILL</option>
                                <option value="HIS_FICTION" <?php echo $media[1] == "HIS_FICTION" ? "selected" : "" ?>>HIS_FICTION</option>
                                <option value="ROMANCE" <?php echo $media[1] == "ROMANCE" ? "selected" : "" ?>>ROMANCE</option>
                                <option value="GRAPH_NOVEL" <?php echo $media[1] == "GRAPH_NOVEL" ? "selected" : "" ?>>GRAPH_NOVEL</option>
                                <option value="AUTO_BIO" <?php echo $media[1] == "AUTO_BIO" ? "selected" : "" ?>>AUTO_BIO</option>
                                <option value="BIO" <?php echo $media[1] == "BIO" ? "selected" : "" ?>>BIO</option>
                                <option value="ART" <?php echo $media[1] == "ART" ? "selected" : "" ?>>ART</option>
                                <option value="HISTORY" <?php echo $media[1] == "HISTORY" ? "selected" : "" ?>>HISTORY</option>
                                <option value="TRAVEL" <?php echo $media[1] == "TRAVEL" ? "selected" : "" ?>>TRAVEL</option>
                                <option value="CRIME" <?php echo $media[1] == "CRIME" ? "selected" : "" ?>>CRIME</option>
                                <option value="HUMOR" <?php echo $media[1] == "HUMOR" ? "selected" : "" ?>>HUMOR</option>
                                <option value="GUIDE" <?php echo $media[1] == "GUIDE" ? "selected" : "" ?>>GUIDE</option>
                                <option value="RELIGION" <?php echo $media[1] == "RELIGION" ? "selected" : "" ?>>RELIGION</option>
                                <option value="SCIENCE" <?php echo $media[1] == "SCIENCE" ? "selected" : "" ?>>SCIENCE</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="addmedia-fname" class="form-label">AuthorFName </label>
                            <input class="form-control" id="addmedia-fname" name="authorFName" value="<?php echo $media[4] ?>" required>
                        </div>
                    </div>
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addmedia-lname" class="form-label">AuthorLName</label>
                            <input class="form-control" id="addmedia-lname" name="authorLName" value="<?php echo $media[2] ?>" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="addmedia-mname" class="form-label">AuthorMName</label>
                            <input class="form-control" id="addmedia-mname" name="authorMName" value="<?php echo $media[3] ?>" required>
                        </div>
                    </div>
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addmedia-replacementcost" class="form-label">Replacement Cost</label>
                            <input class="form-control" id="addmedia-replacementcost" name="replacementCost" value="<?php echo $media[8] ?>" required>
                        </div>
                    </div> 
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addmedia-yearpublished" class="form-label">Year Published</label>
                            <input class="form-control" id="addmedia-yearpublished" name="yearPublished" value="<?php echo $media[5] ?>" required>
                        </div>
                    </div>					
                    <button id="addmedia-button" type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
            <div class="card">
                <?php
                    $mediaID = $media[8];
                    $result = sqlsrv_query($conn,
                    "SELECT i.[Item ID]
                    FROM Items_With_Check_Out as i
                    WHERE i.[Media Title ID]='$mediaID'
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
                                <form method="post" action="/media-add-copy.php" style='float: right;'>
                                    <input style='display: none;' name='mediaID' value='<?php echo $mediaID ?>' />
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
                                    <a href='/media-remove-item.php?mediaID=$mediaID&itemID=$row[0]' class='btn btn-outline-danger' style='float: right;'>Delist Item</a>
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
