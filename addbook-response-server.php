<?php
    include 'connect.php';
    include 'require-signin.php';
    
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
            INSERT INTO library.library.[Book Title] (library.library.ISBN, library.library.Title, library.library.Genre, library.library.AuthorFName, library.library.AuthorLName, library.library.AuthorMName, library.library.[Replacement Cost], library.library.DDN, library.library.[Year Published])
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);
        ";

        echo $query;

        $stmt = sqlsrv_prepare($conn, $query, array($isbn, $title, $genre, $authorfname, $authorlname, $authormname, $replacementcost, $ddn, $yearpublished));

        $res = sqlsrv_execute($stmt);

        if ($res == false){
            $e = json_encode(sqlsrv_errors());
            header("Location: admin-addbooks.php?errormsg=Failed to add book. Make sure that you aren't adding a duplicate book. Error: $e");
        }

        for ($i=0; $i < $quantity; $i++) { 
            $query = "INSERT INTO library.library.Item (library.library.Item.[Date Added], library.library.Item.[Book Title ID], library.library.Item.[Created By]) VALUES (CURRENT_TIMESTAMP, ?, ?)";

            $stmt = sqlsrv_prepare($conn, $query, array($isbn, $cookie_userID));
            $res = sqlsrv_execute($stmt);

            if (!$res){
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