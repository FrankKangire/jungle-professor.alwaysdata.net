<?php
$servername = "";
$username = "";
$password = "";
$db_name = ""; // Make sure this matches your local DB name

$conn = new mysqli($servername, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
