<?php
    include 'connect.php';
    include 'require-signin.php';
    if (!isset($_GET["mediaID"])){
        // Redirect to media page if no model id is specified.
        header("Location: /media.php");
        return;
    }
    $mediaID = $_GET["mediaID"];
    $result = sqlsrv_query($conn,
        "SELECT Title, Genre, AuthorLName, AuthorMName, AuthorFName, [Year Published], count(i.[Media Title ID]) as Stock
        FROM library.library.[Media Title] as m
        LEFT OUTER JOIN library.dbo.Avail_Items as i ON m.[Media ID] = i.[Media Title ID]
        AND i.[Checked Out By] IS NULL AND i.[Held By] IS NULL
        WHERE m.[Media ID] = ?
        GROUP BY Title, Genre, AuthorLName, AuthorMName, AuthorFName, [Year Published]
        ORDER BY m.Title",
        array($mediaID)
    );
    $media = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC);
    if (!$media){
        // Book not found
        http_response_code(404);
        include '404.php';
        die();
    }
    $fullNameArray = [$media[2], $media[3], $media[4]];
    $fullNameArray = array_filter($fullNameArray, 'strlen');
    $fullNameStr = join(", ", $fullNameArray);

    $result = sqlsrv_query($conn, "SELECT a.Type from library.library.Account as a WHERE a.[User ID]=$cookie_userID");
    $userType = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC);
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
        <form class="container mt-5">
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb h3">
                    <li class="breadcrumb-item" aria-current="page"><a href="/media.php">Books</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $media[0] ?></li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <h5 class="card-title">Book Details</h5>
                        <div class="d-flex align-items-start" style="gap: 0.5rem;margin-left: auto;">
                            <?php
                                if ($userType == "STAFF" || $userType == "ADMIN"){
                                    echo "<a href='delistmedia-response-server.php?mediaID=$mediaID' class='btn btn-outline-danger ms-auto'>Delist Book</a>";
                                    echo "<a href='admin-editmedia.php?mediaID=$mediaID' class='btn btn-outline-primary'>Edit Book</a>";
                                }
                            ?>
                            <div class="d-flex flex-column justfy-content-end">
                                <a
                                    href="media-hold.php?mediaID=<?php echo $mediaID ?>"
                                    class="ms-auto btn btn-success <?php if ($media[8] == 0) { echo "disabled"; } ?>"
                                >Place Hold</a>
                                <?php if ($media[6] == 0) { echo '<div class="text-secondary">Sorry, we\'re out of stock</div>'; } ?>
                            </div>
                        </div>
                        
                    </div>
                    
                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="text-bold">Title</td>
                                <?php echo "<td>$media[0]</td>" ?>
                            </tr>
                            <tr>
                                <td class="text-bold">Genre</td>
                                <?php echo "<td>$media[1]</td>" ?>
                            </tr>
                            <tr>
                                <td class="text-bold">Author</td>
                                <?php echo "<td>$fullNameStr</td>" ?>
                            </tr>
                            <tr>
                                <td class="text-bold">Year Published</td>
                                <?php echo "<td>$media[5]</td>" ?>
                            </tr>
                            <tr>
                                <td class="text-bold">Stock</td>
                                <?php
                                    $stock = $media[6];
                                    if ($stock == 0){
										$stock = "<span class='text-danger'>Out of stock</span>";
									}
									else if ($stock < 4){
										$stock = "<span class='text-warning'>Limited stock</span>";
									}
									else
									{
										$stock = "In stock";
									}
									echo "<td>$stock</td>";
                                ?>
                            </tr>
                        </tbody>
                    </table>
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
    <?php include 'scripts.php' ?>
    <!--------------------------------------------------------------->
</html>