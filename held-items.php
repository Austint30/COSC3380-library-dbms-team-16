<!DOCTYPE html>
<html>
<!--------------------------------------------------------------->
    <head>
        <?php include 'bootstrap.php'; 
		include 'connect.php';
        include 'require-signin.php';
        ?>	
    </head>
<!----------------------Here we have the popular books----------------------------------------->
	<body>
		<?php include 'headerbar-auth.php' ?>		
		<div class="container mt-5">
            <h1>Held Items</h1>
            <?php
                $result = $conn->query(
                    "SELECT Title, Genre, AuthorLName, AuthorMName, AuthorFName, `Year Published`, ISBN, Item.`Book Title ID`
                    FROM library.Item, library.`Book Title`
                    WHERE library.Item.`Book Title ID` = library.`Book Title`.ISBN
                        AND library.Item.`Held By` = $cookie_userID;"
                );
                $books = $result->fetch_all();
            ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Genre</th>
                        <th>Author</th>
                        <th>Year Published</th>
                        <th>ISBN</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 0;
                        foreach($books as $row){
                            echo "<tr>";
							for ($i = 0; $i < 2; $i++) {
								echo "<td>$row[$i]</td>";
							}
							$fullNameArray = [$row[2], $row[3], $row[4]];
							$fullNameArray = array_filter($fullNameArray, 'strlen');
							$fullNameStr = join(", ", $fullNameArray);
							echo "<td>$fullNameStr</td>";
							for ($i = 5; $i < count($row)-1; $i++) {
								$value = $row[$i];
								echo "<td>$value</td>";
							}
							echo '<td>
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="heldItemsDropdownMenuButton$i" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="heldItemsDropdownMenuButton$i">
                                    <li><a class="dropdown-item" href="#">Remove Hold</a></li>
                                </ul>
                            </td>';
							echo "</tr>";
                            $i++;
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
    <?php include 'scripts.php' ?>
<!---------------------------------------------------------------> 
</html>