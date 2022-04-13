<?php
    include 'connect.php';
    include 'require-signin.php';
?>

<!DOCTYPE hmtl>
<html>
    <head>
        <?php include 'bootstrap.php' ?>
    </head>
    <body>
        <?php include 'headerbar-auth.php' ?>
        <form class = "container mt-5" action = "addmedia-response-server.php" method = "post">
            <nav aria-label = "breadcrumb mb-3">
                <ol class = "breadcrumb h3">
                    <li class = "breadcrumb-item" aria-current = "page" ><a href = "/media.php">Media</a></li>
                    <li class = "breadcrumb-item active" aria-current = "page" >Add Media</li>
                </ol>
            </nav>
            <div class = "card">
               <div class = "card-body">
                    <h5 class = "card-title">Add Media</h5>
                    <div class = "row-align-items-start">
<!--                         <div class = "col-6 mb-3">
                            <label for = "addmedia-mediaID" class = "form-label">Media ID</label>
                            <input class = "form-control" id = "addMedia-mediaID" name = "mediaID" required>
                        </div> -->
                    </div>
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addmedia-genre" class="form-label">Genre</label>
							<select id="signup-type" class="form-select" name="mediaGenre" required>
                            <option value="" selected>Choose an account type</option>
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
<!---------------------------------------------------------------------------------------------------------->
                            <label for="addmedia-fname" class="form-label">Author First Name</label>
                            <input class="form-control" id="addmedia-fname" name="mediaFName" required>
                        </div>
                    </div>
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addmedia-lname" class="form-label">Author Last Name</label>
                            <input class="form-control" id="addmedia-lname" name="mediaLName" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="addmedia-mname" class="form-label">Author Middle Name</label>
                            <input class="form-control" id="addmedia-mname" name="mediaMName" required>
                        </div>
                    </div>
<!---------------------------------------------------------------------------------------------------------->                    
                    <div class="row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addmedia-mediaCost" class="form-label">Media Cost</label>
                            <input class="form-control" id="addbook-mediaCost" name="mediaCost" required>
                        </div>
                    <div class = "row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addmedia-dateAdded" class="form-label">Date Added</label>
                            <input class="form-control" id="addmedia-dateAdded" name="dateAdded" required>
                        </div>
                    </div>
                    <div class = "row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addmedia-dateAdded" class="form-label">Media Title</label>
                            <input class="form-control" id="addmedia-mediaTitle" name="mediaTitle" required>
                        </div>
                    </div>
                    <div class = "row align-items-start">
                        <div class="col-6 mb-3">
                            <label for="addmedia-yearPublished" class="form-label">Year Published</label>
                            <input class="form-control" id="addmedia-yearPublished" name="yearPublished" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="addbook-ddn" class="form-label">Quantity</label>
                            <input type="number" max="100" min="1" class="form-control" id="adddevice-quanity" name="quantity" value="1" required>
                        </div>
                    </div>		
                    <button id = "addbook-button" type = "submit" class = "btn btn-primary">Add Media</button>
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







