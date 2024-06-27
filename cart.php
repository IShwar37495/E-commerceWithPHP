<?php 
session_start();
include("database.php");
include("userheader.php");

    

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 0;
            margin:0;
        }

        .cart {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 3rem;
        }

        .product {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
           
        }

        .product img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }

        .product-info {
            flex: 1;
        
        }

        .product h4 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .product p {
            margin: 5px 0;
            font-size: 16px;
            color: #888;
        }

        .product-price {
            font-weight: bold;
        }

        .empty-cart {
            text-align: center;
            font-size: 20px;
            color: #888;
            margin-top: 20px;
        }

        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #088178; /* Match your site's primary color */
            color: #fff;
            padding: 1rem;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            z-index: 1000; /* Ensure it's above other content */
            text-align: center;
        }

        #button {
            background-color: #088178;
            color: #fff;
            border: none;
            padding: .5rem 1rem;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 3px;
        }

        #button:hover {
            background-color: #06635b;
        }
    </style>
</head>
<body>

<div class="cart">
    <h2>Cart Contents:</h2>

    <?php
    
 

  function addToCart($conn, $userId, $productId) {
    // Start a transaction for data integrity
    $conn->begin_transaction();

    try {
        // Fetch product details and lock the row for update
        $sql = "SELECT `sku`, `status` FROM `products` WHERE `id` = ? FOR UPDATE";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $product = $result->fetch_assoc();
            $currentStock = $product['sku'];
            $productStatus = $product['status'];

            if ($currentStock <= 0) {
                throw new Exception("Product out of stock.");
            }

            // Reduce stock by 1
            $newStock = $currentStock - 1;
            $updateSql = "UPDATE `products` SET `sku` = ? WHERE `id` = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("ii", $newStock, $productId);
            if (!$updateStmt->execute()) {
                throw new Exception("Failed to update stock.");
            }

            // Check and update product status if stock reaches zero
            if ($newStock == 0) {
                $updateStatusSql = "UPDATE `products` SET `status` = 0 WHERE `id` = ?";
                $updateStatusStmt = $conn->prepare($updateStatusSql);
                $updateStatusStmt->bind_param("i", $productId);
                if (!$updateStatusStmt->execute()) {
                    throw new Exception("Failed to update product status.");
                }
            }

            // Insert into cart
            $insertSql = "INSERT INTO `cart` (`user`, `product`) VALUES (?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("ii", $userId, $productId);
            if (!$insertStmt->execute()) {
                throw new Exception("Failed to add product to cart.");
            }

            // Commit the transaction
            $conn->commit();
            http_response_code(200);
            echo "Product added to cart successfully!";
            return true;
        } else {
            throw new Exception("Product not found.");
        }
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        http_response_code(500);
        echo $e->getMessage();
        return false;
    }
}


    // Function to get cart items
    function getCartItems($conn, $userId) {
        $sql = "SELECT p.id, p.name, p.image, p.price, p.salePrice 
                FROM `products` p 
                JOIN `cart` c ON p.id = c.product 
                WHERE c.user = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo "Please log in first.";
        exit;
    }

    if (isset($_GET['productId'])) {
        $productId = intval($_GET['productId']);
        $userId = $_SESSION['user_id'];

        addToCart($conn, $userId, $productId);
    }

    $userId = $_SESSION['user_id'];
    $cartItems = getCartItems($conn, $userId);

    if ($cartItems->num_rows > 0) {
        $totalPrice = 0;
        while ($product = $cartItems->fetch_assoc()) {
            echo "<div class='product' id='product-" . $product['id'] . "'>";
            echo "<div class='product-info'>";
            echo "<img src='./uploads/" . htmlspecialchars($product['image']) . "' alt='" . htmlspecialchars($product['name']) . "'>";
            echo "<h4>" . htmlspecialchars($product['name']) . "</h4>";
            echo "<p class='product-price'>Price: $" . htmlspecialchars($product['price']) . "</p>";
            echo "<p>Sale Price: $" . htmlspecialchars($product['salePrice']) . "</p>";
            echo "<button id='button' onclick='removeFromCart(" . $product['id'] . ")'>Remove</button>";
            $totalPrice += $product['salePrice'];
            echo "</div>";
            echo "</div>";
        }
        echo "<p class='total-price'>Total: $" . number_format($totalPrice, 2) . "</p>";
    } else {
        echo "<p class='empty-cart'>Your cart is empty.</p>";
    }
    ?>

</div>

<div id="popupContainer"></div>

<script>
    function removeFromCart(productId) {
        const request = new XMLHttpRequest();
        request.open('GET', `removeFromCart.php?productId=${productId}`, true);
        request.onload = function () {
            if (this.status === 200) {
                const message = this.responseText;
                const productElement = document.getElementById(`product-${productId}`);
                if (productElement) {
                    productElement.remove();
                }
                // Show custom popup message
                showPopup(message);
                updateTotalPrice();
            } else {
                showPopup("Failed to remove product from cart.");
            }
        };
        request.send();
    }

    function showPopup(message) {
        const popupContainer = document.getElementById('popupContainer');
        const popup = document.createElement('div');
        popup.className = 'popup';
        popup.textContent = message;
        popupContainer.appendChild(popup);

        setTimeout(() => {
            popup.remove();
        }, 3000); // Remove popup after 3 seconds (adjust as needed)
    }

    function updateTotalPrice() {
        let totalPrice = 0;
        const salePrices = document.querySelectorAll('.product .product-info p:nth-child(4)');
        salePrices.forEach(priceElement => {
            const priceText = priceElement.innerText.replace('Sale Price: $', '').trim();
            totalPrice += parseFloat(priceText);
        });
        const totalPriceElement = document.querySelector('.total-price');
        if (totalPriceElement) {
            totalPriceElement.innerText = `Total: $${totalPrice.toFixed(2)}`;
        }
    }
</script>


<?php  include("footer.php"); ?>

</body>
</html>







