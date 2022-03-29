<?php
    include 'connect.php';
?>

<!DOCTYPE html>
<html>
    <!--------------------------------------------------------------->
    <head>
        <h1>My First Heading</h1>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    </head>
    <!--------------------------------------------------------------->
    <body>
        <p>My first paragraph.</p>
        <?php
            $result = $conn->query("SELECT * FROM library.Account WHERE Type = 'ADMIN'");
            $columns = $result->fetch_fields();
            $results = $result->fetch_all();
            $conn->close();
        ?>

        <table class="table table-primary">
            <thead>
                <tr>
                    <?php
                        foreach($columns as $colData){
                            echo "<th>$colData->name</th>";
                        }
                    ?>
                </tr>
            <thead>
            <tbody>
                <?php
                    foreach($results as $row){
                        echo "<tr>";
                        for ($i = 0; $i < count($row); $i++) {
                            $value = $row[$i];
                            echo "<th>$value</th>";
                        }
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </body>
    <!--------------------------------------------------------------->
</html>