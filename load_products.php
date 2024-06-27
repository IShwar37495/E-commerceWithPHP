<?php
include("database.php");

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$products_per_page = 10;

$sql = "SELECT 
            p.*, 
            u.name AS username, 
            c.name AS category_name, 
            b.name AS brand_name 
        FROM products p
        LEFT JOIN users u ON p.userId = u.id
        LEFT JOIN category c ON p.category = c.id
        LEFT JOIN brands b ON p.brand = b.id
        LIMIT $products_per_page OFFSET $offset";
$result = mysqli_query($conn, $sql);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

echo json_encode($products);
?>

