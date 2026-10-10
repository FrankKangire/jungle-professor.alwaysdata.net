<?php
include "connection.php";
$table = isset($_GET['table']) ? $_GET['table'] : 'users';

// Safety check for table names
$allowed = ['users', 'documents', 'permanent_results'];
if (!in_array($table, $allowed)) die("Invalid table requested.");

$result = pg_query($conn, "SELECT * FROM $table");
$rows = pg_fetch_all($result);

echo "<html><head><link rel='stylesheet' href='bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css'></head><body class='p-4 bg-light'>";
echo "<h3>Viewing Table: $table</h3>";
echo "<div class='mb-3'>
    <a href='?table=users' class='btn btn-sm btn-primary'>Users</a>
    <a href='?table=documents' class='btn btn-sm btn-primary'>Documents</a>
    <a href='?table=permanent_results' class='btn btn-sm btn-primary'>Results</a>
</div>";

if (!$rows) {
    echo "<div class='alert alert-warning'>Table is empty.</div>";
} else {
    echo "<table class='table table-bordered table-striped bg-white'><thead><tr>";
    foreach (array_keys($rows[0]) as $col) echo "<th>$col</th>";
    echo "</tr></thead><tbody>";
    foreach ($rows as $row) {
        echo "<tr>";
        foreach ($row as $val) echo "<td>" . htmlspecialchars($val) . "</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
}
echo "</body></html>";
?>
