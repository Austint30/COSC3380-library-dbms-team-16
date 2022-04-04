<?php
    include 'connect.php';
    if (!isset($_GET["isbn"])){
        // Redirect to books page if no isbn is specified.
        header("Location: /books.php");
    }
    $isbn = $_GET["isbn"];
    $result = $conn->query(
        "SELECT Title, Genre, AuthorLName, AuthorMName, AuthorFName, `Year Published`, DDN, ISBN, count(library.Item.`Book Title ID`) as Stock
FROM library.`Book Title`
LEFT OUTER JOIN library.Item ON library.`Book Title`.ISBN = library.Item.`Book Title ID`
	AND library.Item.`Checked Out By` IS NULL AND library.Item.`Held By` IS NULL
    WHERE library.`Book Title`.ISBN = '$isbn'
	GROUP BY library.`Book Title`.ISBN
	ORDER BY library.`Book Title`.Title"
    );
    $book = $result->fetch_row();
    if (!$book){
        // Book not found
        http_response_code(404);
        include '404.php';
        die();
    }
    $fullNameArray = [$book[2], $book[3], $book[4]];
    $fullNameArray = array_filter($fullNameArray, 'strlen');
    $fullNameStr = join(", ", $fullNameArray);
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
                    <li class="breadcrumb-item" aria-current="page"><a href="/books.php">Books</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $book[0] ?></li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <h5 class="card-title">Book Details</h5>
                        <div class="ms-auto d-flex flex-column justfy-content-end">
                            <a
                                href="book-hold.php?isbn=<?php echo $isbn ?>"
                                class="ms-auto btn btn-success <?php if ($book[8] == 0) { echo "disabled"; } ?>"
                            >Place Hold</a>
                            <?php if ($book[8] == 0) { echo '<div class="text-secondary">Sorry, we\'re out of stock</div>'; } ?>
                            
                        </div>
                    </div>
                    
                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="text-bold">Title</td>
                                <?php echo "<td>$book[0]</td>" ?>
                            </tr>
                            <tr>
                                <td class="text-bold">Genre</td>
                                <?php echo "<td>$book[1]</td>" ?>
                            </tr>
                            <tr>
                                <td class="text-bold">Author</td>
                                <?php echo "<td>$fullNameStr</td>" ?>
                            </tr>
                            <tr>
                                <td class="text-bold">Year Published</td>
                                <?php echo "<td>$book[5]</td>" ?>
                            </tr>
                            <tr>
                                <td class="text-bold">DDN</td>
                                <?php echo "<td>$book[6]</td>" ?>
                            </tr>
                            <tr>
                                <td class="text-bold">ISBN</td>
                                <?php echo "<td>$book[7]</td>" ?>
                            </tr>
                            <tr>
                                <td class="text-bold">Stock</td>
                                <?php
                                    $stock = $book[8];
                                    if ($stock == 0){
										$stock = "<span class='text-danger'>Out of stock</span>";
									}
									else if ($stock < 10){
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