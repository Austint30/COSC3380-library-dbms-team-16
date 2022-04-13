<?php
    include 'connect.php';
    include 'require-signin.php';
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
            UPDATE library.library.[Book Title]
            SET
                library.library.[Book Title].ISBN = ?,
                library.library.[Book Title].Title = ?,
                library.library.[Book Title].Genre = ?,
                library.library.[Book Title].AuthorFName = ?,
                library.library.[Book Title].AuthorLName = ?,
                library.library.[Book Title].AuthorMName = ?,
                library.library.[Book Title].[Replacement Cost] = ?,
                library.library.[Book Title].DDN = ?,
                library.library.[Book Title].[Year Published] = ?
            WHERE library.library.[Book Title].ISBN = ?
        ";

        echo $query;

        $stmt = sqlsrv_prepare($conn, $query, array($isbn, $title, $genre, $authorfname, $authorlname, $authormname, $replacementcost, $ddn, $yearpublished, $isbn));
        $res = sqlsrv_execute($stmt);

        if ($res == false){
            echo print_r( sqlsrv_errors());
            $e = sqlsrv_errors()[0][0];
            header("Location: admin-editbook.php?isbn=$isbn&errormsg=Failed to add book. Make sure that you aren't adding a duplicate book. Error: $e");
        }

        header("Location: admin-editbook.php?isbn=$isbn&msg=Changes saved successfully.");
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }
?>