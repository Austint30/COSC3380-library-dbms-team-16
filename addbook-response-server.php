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
        $quantity = $_POST["bookQuantity"];
		
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
        
        // Ok so this may look a little weird. But apparently if you just pass the variables into the SQL statement, things like commas can break it. So you have to do it the "prepared" way.
        // https://www.w3schools.com/php/php_mysql_prepared_statements.asp
        // Also requires that a PDO connection is used (as seen in the import 'connect-pdo.php' above)

        $query = "
            INSERT INTO `Book Title` (`Book Title`.ISBN, `Book Title`.Title, `Book Title`.Genre, `Book Title`.AuthorFName, `Book Title`.AuthorLName, `Book Title`.AuthorMName, `Book Title`.`Replacement Cost`, `Book Title`.DDN, `Book Title`.`Year Published`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);
        ";

        echo $query;

        $q = $conn->prepare($query);
        $q->bind_param("sssssssss", $isbn, $title, $genre, $authorfname, $authorlname, $authormname, $replacementcost, $ddn, $yearpublished);

        try {
            $result = $q->execute();
        } catch (\Throwable $th) {
            header("Location: admin-addbooks.php?errormsg=Failed to add book. Make sure that you aren't adding a duplicate book.");
        }

        for ($i=0; $i < $quantity; $i++) { 
            $query = "INSERT INTO library.Item (library.Item.`Date Added`, library.Item.`Book Title ID`) VALUES (CURRENT_TIMESTAMP, ?)";

            $q = $conn->prepare($query);
            $q->bind_param("s", $isbn);

            try {
                $result = $q->execute();
            } catch (\Throwable $th) {
                header("Location: admin-addbooks.php?errormsg=Failed to add copies of the book. Please contact system admin.");
            }
        }

        header("Location: admin-addbooks.php?msg=Book sucessfully added.");
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }
?>