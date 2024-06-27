<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Please log in first.";
    exit;
}

include("database.php");

function removeFromCart($conn, $userId, $productId) {
    // Start a transaction for data integrity
    $conn->begin_transaction();

    try {
        // Fetch product details and lock the row for update
        $sql = "SELECT `sku` FROM `products` WHERE `id` = ? FOR UPDATE";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $product = $result->fetch_assoc();
            $currentStock = $product['sku'];

            $newStock = $currentStock + 1;
            $updateSql = "UPDATE `products` SET `sku` = ? WHERE `id` = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("ii", $newStock, $productId);
            if (!$updateStmt->execute()) {
                throw new Exception("Failed to update stock.");
            }

            if ($newStock >0) {
                $updateStatusSql = "UPDATE `products` SET `status` = 1 WHERE `id` = ?";
                $updateStatusStmt = $conn->prepare($updateStatusSql);
                $updateStatusStmt->bind_param("i", $productId);
                if (!$updateStatusStmt->execute()) {
                    throw new Exception("Failed to update product status.");
                }
            }

            $deleteSql = "DELETE FROM `cart` WHERE `user` = ? AND `product` = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("ii", $userId, $productId);
            if (!$deleteStmt->execute()) {
                throw new Exception("Failed to remove product from cart.");
            }

            $conn->commit();
            http_response_code(200);
            echo "Product removed from cart successfully!";
            return true;
        } else {
            throw new Exception("Product not found.");
        }
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo $e->getMessage();
        return false;
    }
}

if (isset($_GET['productId'])) {
    $productId = intval($_GET['productId']);
    $userId = $_SESSION['user_id'];

    removeFromCart($conn, $userId, $productId);
}
?>









