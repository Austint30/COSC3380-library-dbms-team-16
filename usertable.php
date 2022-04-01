<?php
    include 'connect.php';
?>
<?php
    $result = $conn->query("SELECT * FROM library.Account WHERE Type = 'ADMIN'");
    $columns = $result->fetch_fields();
    $results = $result->fetch_all();
    $conn->close();
?>
<div class="container">
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
                        echo "<td>$value</td>";
                    }
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</div>