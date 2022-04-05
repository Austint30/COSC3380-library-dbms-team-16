<?php
    include 'connect.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $mediaID = $_POST['mediaID'];
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
        echo $mediaID;
        echo $title;
        echo $yearPublished;
        echo $authorfname;
        echo $authorlname;
        echo $authormname;
        echo $genre;
        echo $dateAdded;
        echo $replacementCost;

        $query = "
            INSERT INTO `Media Title` (`Media Title`.`Media ID`, `Media Title`.Title, `Media Title`.`Year Published`, `Media Title`.AuthorFName, `Media Title`.AuthorLName, `Media Title`.AuthorMName, `Media Title`.Genre, `Media Title`.`Date Added`, `Media Title`.`Replacement Cost`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);
        ";

        echo $query;

        $q = $conn->prepare($query);
        $q->bind_param("sssssssss", $mediaID, $title, $yearPublished, $authorfname, $authorlname, $authormname, $genre, $dateAdded, $replacementCost);

        try {
            $result = $q->execute();
        } catch (\Throwable $th) {
            header("Location: admin-addmedia.php?errormsg=Failed to add media. Make sure that you aren't adding a duplicate media entity.");
        }

        for ($i=0; $i < $quantity; $i++) { 
            $query = "INSERT INTO library.Item (library.Item.`Date Added`, library.Item.`Media Title ID`) VALUES (CURRENT_TIMESTAMP, ?)";

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
?>
