<?php
    include 'connect.php';
    include 'require-signin.php';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //$mediaID = $_POST['mediaID'];
        $title = $_POST['mediaTitle'];
        $yearPublished = $_POST['yearPublished'];    
        $authorfname = $_POST["mediaFName"];
        $authorlname = $_POST['mediaLName'];
        $authormname = $_POST['mediaMName'];
        $genre = $_POST['mediaGenre'];
        $dateAdded = $_POST['dateAdded'];
        $replacementCost = $_POST['mediaCost'];
        $quantity = $_POST['quantity'];
        

        echo "POST received. Values are below:";
        //echo $mediaID;
        echo $title;
        echo $yearPublished;
        echo $authorfname;
        echo $authorlname;
        echo $authormname;
        echo $genre;
        echo $dateAdded;
        echo $replacementCost;

        /*$query = "
            INSERT INTO [Media Title] ([Media Title].[Media ID], [Media Title].Title, [Media Title].[Year Published], [Media Title].AuthorFName, [Media Title].AuthorLName, [Media Title].AuthorMName, [Media Title].Genre, [Media Title].[Date Added], [Media Title].[Replacement Cost])
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);
        ";*/
		
		$query = "
            INSERT INTO library.library.[Media Title] (library.library.[Title], library.library.[Year Published], library.library.[AuthorFName], library.library.[AuthorLName], library.library.[AuthorMName], library.library.[Genre], library.library.[Date Added], library.library.[Replacement Cost])
            VALUES (?, ?, ?, ? ,? ,? ,CURRENT_TIMESTAMP,?);
        ";

        echo $query;
		
		$stmt = sqlsrv_prepare($conn, $query, array($title, $yearPublished, $authorfname, $authorlname, $authormname, $genre, $replacementCost));

        $res = sqlsrv_execute($stmt);

        if ($res == false){
            $e = json_encode(sqlsrv_errors());
            header("Location: admin-addmedia.php?errormsg=Failed to add media. Make sure that you aren't adding a duplicate media. Error: $e");
        }

        for ($i=0; $i < $quantity; $i++) { 
            $query = "INSERT INTO library.library.Item (library.library.Item.[Date Added], library.library.Item.[Media Title ID], , library.library.Item.[Created By]) VALUES (CURRENT_TIMESTAMP, ?, )";

            $stmt = sqlsrv_prepare($conn, $query, array($mediaID, $cookie_userID));
            $res = sqlsrv_execute($stmt);

            if (!$res){
                header("Location: admin-addmedia.php?errormsg=Failed to add copies of the media. Please contact system admin.");
            }
        }

        header("Location: admin-addmedia.php?msg=Media sucessfully added.");
    }
    else
    {
        $result = "YOU SHOULDN'T BE HERE!";
    }		

        /*$q = $conn->prepare($query);
        $q->bind_param("sssssssss", $mediaID, $title, $yearPublished, $authorfname, $authorlname, $authormname, $genre, $dateAdded, $replacementCost);

        try {
            $result = $q->execute();
        } catch (\Throwable $th) {
            header("Location: admin-addmedia.php?errormsg=Failed to add media. Make sure that you aren't adding a duplicate media entity.");
        }

        for ($i=0; $i < $quantity; $i++) { 
            $query = "INSERT INTO library.library.Item (library.library.Item.[Date Added], library.library.Item.[Media Title ID]) VALUES (CURRENT_TIMESTAMP, ?)";

            $q = $conn->prepare($query);
            $q->bind_param("s", $mediaID);

            try {
                $result = $q->execute();
            } catch (\Throwable $th) {
                header("Location: admin-addmedia.php?errormsg=Failed to add copies of the media. Please contact system admin.");
            }
        }

        header("Location: admin-addmedia.php?msg=Media sucessfully added.");
    } else {
        $result = "Error";
    }
	*/
?>
