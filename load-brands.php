<?php
include("database.php");

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = 5;

$sql = "SELECT `id`, `name` FROM `brands` LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

$categories = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    echo json_encode($categories);
} else {
    echo json_encode([]);
}

$stmt->close();
$conn->close();
?>