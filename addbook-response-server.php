<?php
    include 'connect.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $isbn = $_POST['bookISBN'];
        $title = $_POST['bookTitle'];
        $genre = $_POST['bookGenre'];
        $authorfname = $_POST["bookFName"];
        $authorlname = $_POST['bookLName'];
        $authormname = $_POST['bookMName'];
        $replacementcost = $_POST['bookCost'];
        $ddn = $_POST['bookDDN'];
		$yearpublished = $_POST['bookYear'];
		
        echo "POST received. Values are below:";
        echo $isbn;
        echo $title;
        echo $genre;
        echo $authorfname;
        echo $authorlname;
        echo $authormname;
        echo $replacementcost;
		echo $ddn;
		echo $yearpublished;
		
        $result = $conn->query("
            INSERT INTO `Book Title` (`Book Title`.ISBN, `Book Title`.Title, `Book Title`.Genre, `Book Title`.AuthorFName, `Book Title`.AuthorLName, `Book Title`.AuthorMName, `Book Title`.`Replacement Cost`, `Book Title`.DDN, `Book Title`.`Year Published`)
            VALUES ('$isbn', '$title', '$genre', '$authorfname', '$authorlname', '$authormname', '$replacementcost', '$ddn', '$yearpublished');
        ");
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }
?>

<html>
    <!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php' ?>
    </head>
    <body>
        <?php include 'headerbar-unauth.php' ?>
        <div class="container mt-5 text-center">
            <h1></h1>
            <p>
                
            </p>
        </div>
    </body>
</html>