<?php
    include 'connect.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $isbn = $_GET['isbn'];
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

        $query = "
            UPDATE `Book Title`
            SET
                `Book Title`.ISBN = ?,
                `Book Title`.Title = ?,
                `Book Title`.Genre = ?,
                `Book Title`.AuthorFName = ?,
                `Book Title`.AuthorLName = ?,
                `Book Title`.AuthorMName = ?,
                `Book Title`.`Replacement Cost` = ?,
                `Book Title`.DDN = ?,
                `Book Title`.`Year Published` = ?
            WHERE `Book Title`.ISBN = ?
        ";

        echo $query;

        $q = $conn->prepare($query);
        $q->bind_param("ssssssssss", $isbn, $title, $genre, $authorfname, $authorlname, $authormname, $replacementcost, $ddn, $yearpublished, $isbn);
        $q->execute();

        header("Location: admin-editbook.php?isbn=$isbn&msg=Changes saved successfully.");
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }
?>