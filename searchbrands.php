<?php
include("database.php");

$query = isset($_GET['query']) ? mysqli_real_escape_string($conn, $_GET['query']) : '';

$sql = "SELECT brands.id, brands.name, u.name AS username FROM brands JOIN users u ON brands.userId = u.id WHERE brands.name LIKE '%$query%'";
$result = mysqli_query($conn, $sql);

$brands = array();
while ($row = mysqli_fetch_assoc($result)) {
    $brands[] = $row;
}

echo json_encode($brands);
?>
