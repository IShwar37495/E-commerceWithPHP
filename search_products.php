<?php

include("database.php");

if (isset($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);
    $status = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : '';

    $sql = "SELECT 
                p.*, 
                u.name AS username, 
                c.name AS category_name, 
                b.name AS brand_name 
            FROM products p
            LEFT JOIN users u ON p.userId = u.id
            LEFT JOIN category c ON p.category = c.id
            LEFT JOIN brands b ON p.brand = b.id
            WHERE 1";

    if (!empty($query)) {
        $sql .= " AND (p.name LIKE '%$query%' OR p.price LIKE '%$query%' OR b.name LIKE '%$query%')";
    }

    if (!empty($status)) {
        if ($status == 'inStock') {
            $sql .= " AND p.status > 0"; 
        } elseif ($status == 'notInStock') {
            $sql .= " AND p.status = 0";
        }
    }

    // Execute the SQL query
    $result = $conn->query($sql);
    
    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['status'] = $row['status'] > 0 ? 'In Stock' : 'Not in stock'; 
            $products[] = $row;
        }
    }
    echo json_encode($products);
}
?>




