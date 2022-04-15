<?php
    include 'connect.php';
    include 'require-signin.php';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $mediaID = $_POST['mediaID'];
        $title = $_POST['mediaTitle'];
        $yearPublished = $_POST['yearPublished'];    
        $authorfname = $_POST["authorFName"];
        $authorlname = $_POST['authorLName'];
        $authormname = $_POST['authorMName'];
        $genre = $_POST['mediaGenre'];
        $replacementCost = $_POST['replacementCost'];
        

        echo "POST received. Values are below:";
        echo $mediaID;
        echo $title;
        echo $yearPublished;
        echo $authorfname;
        echo $authorlname;
        echo $authormname;
        echo $genre;
        echo $replacementCost;

        $query = "
            UPDATE library.library.[Media Title]
            SET
                library.library.[Media Title].Title = ?,
                library.library.[Media Title].Genre = ?,
                library.library.[Media Title].AuthorFName = ?,
                library.library.[Media Title].AuthorLName = ?,
                library.library.[Media Title].AuthorMName = ?,
                library.library.[Media Title].[Replacement Cost] = ?,
                library.library.[Media Title].[Year Published] = ?
            WHERE library.library.[Media Title].[Media ID] = ?
        ";

        echo $query;

        $stmt = sqlsrv_prepare($conn, $query, array($title, $genre, $authorfname, $authorlname, $authormname, $replacementCost, $yearPublished, $mediaID));
        $res = sqlsrv_execute($stmt);

        if (!$res){
            echo print_r( sqlsrv_errors());
            $e = sqlsrv_errors()[0][0];
            header("Location: admin-editmedia.php?mediaID=$mediaID&errormsg=Failed to add book. Make sure that you aren't adding a duplicate media entity. Error: $e");
        }

        header("Location: admin-editmedia.php?mediaID=$mediaID&msg=Changes saved successfully.");
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }
?>