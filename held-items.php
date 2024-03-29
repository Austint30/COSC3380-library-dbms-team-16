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
        <?php
            if (isset($_GET["msg"])){
                $msg = $_GET["msg"];
                echo "<div class='alert alert-primary mt-3 ms-3 me-3' role='alert'>
                    $msg
                </div>";
            }
            if (isset($_GET["errormsg"])){
                $msg = $_GET["errormsg"];
                echo "<div class='alert alert-danger mt-3 ms-3 me-3' role='alert'>
                    $msg
                </div>";
            }
        ?>		
		<div class="container mt-5">
            <ul class="nav mb-5 text-center">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#holds-books">Books</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#holds-media">Media</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#holds-devices">Devices</a>
                </li>
            </ul>
            <h2 id="holds-books">Held Books</h2>
            <?php
                $result = sqlsrv_query($conn,
                    "SELECT Title, Genre, AuthorLName, AuthorMName, AuthorFName, [Year Published], ISBN, i.[Book Title ID], i.[Item ID]
                    FROM dbo.Avail_Items as i, library.[Book Title]
                    WHERE i.[Book Title ID] = library.[Book Title].ISBN
                        AND i.[Held By] = '$cookie_userID';"
                );
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
                        while ( $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC)) {
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
							echo "<td>
                                <a href='hold-remove.php?item-id=$row[8]' class='btn btn-outline-danger'>Remove Hold</a>
                            </td>";
							echo "</tr>";
                            $i++;
                        }
                    ?>
                </tbody>
            </table>
            <h2 id="holds-media" class="mt-5">Held Media</h2>
            <?php
                $result = sqlsrv_query($conn,
                    "SELECT Title, Genre, AuthorLName, AuthorMName, AuthorFName, [Year Published], [Item ID]
                    FROM dbo.Avail_Items as i, library.[Media Title]
                    WHERE i.[Media Title ID] = library.[Media Title].[Media ID]
                        AND i.[Held By] = '$cookie_userID';"
                );
            ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Genre</th>
                        <th>Author</th>
                        <th>Year Published</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 0;
                        while ( $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC)) {
                            echo "<tr>";
							echo "<td>$row[0]</td>";
                            echo "<td>$row[1]</td>";
							$fullNameArray = [$row[2], $row[3], $row[4]];
							$fullNameArray = array_filter($fullNameArray, 'strlen');
							$fullNameStr = join(", ", $fullNameArray);
							echo "<td>$fullNameStr</td>";
							echo "<td>$row[5]</td>";
							echo "<td>
                                <a href='hold-remove.php?item-id=$row[6]' class='btn btn-outline-danger'>Remove Hold</a>
                            </td>";
							echo "</tr>";
                            $i++;
                        }
                    ?>
                </tbody>
            </table>
            <h2 id="holds-devices" class="mt-5">Held Devices</h2>
            <?php
                $result = sqlsrv_query($conn,
                    "SELECT library.[Device Title].[Name], library.[Device Title].[Type], library.[Device Title].Manufacturer, library.[Device Title].[Model No.], library.[Device Title].[Date Added], i.[Item ID]
                    FROM dbo.Avail_Items as i, library.[Device Title]
                    WHERE i.[Device Title ID] = library.[Device Title].[Model No.]
                        AND i.[Held By] = '$cookie_userID';"
                );
            ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Device Name</th>
                        <th>Type</th>
                        <th>Manufacturer</th>
                        <th>Model No.</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 0;
                        while ( $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC)) {
                            echo "<tr>";
							for ($i = 0; $i < 4; $i++) {
								echo "<td>$row[$i]</td>";
							}
							echo "<td>
                                <a href='hold-remove.php?item-id=$row[5]' class='btn btn-outline-danger'>Remove Hold</a>
                            </td>";
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