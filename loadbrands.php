<?php
include("database.php");

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$brands_per_page = 10;

$sql = "SELECT brands.id, brands.name, u.name AS username FROM brands JOIN users u ON brands.userId = u.id LIMIT $brands_per_page OFFSET $offset";
$result = mysqli_query($conn, $sql);

$brands = [];
while ($row = mysqli_fetch_assoc($result)) {
    $brands[] = $row;
}

echo json_encode($brands);
?>

