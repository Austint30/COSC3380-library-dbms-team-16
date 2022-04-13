<?php
    include 'connect.php';
    include 'require-signin.php';
?>
<?php
    $stmt = sqlsrv_query($conn, "SELECT * FROM library.Account WHERE Type = 'ADMIN'");
    $columns = sqlsrv_fetch_metadata($result);
    $results = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC);
    $conn->close();
?>
<div class="container">
    <table class="table table-primary">
        <thead>
            <tr>
                <?php
                    foreach($columns as $colData){
                        $colName = $colData["Name"];
								echo "<th>$colName</th>";
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
                        echo "<td>$value</td>";
                    }
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</div>