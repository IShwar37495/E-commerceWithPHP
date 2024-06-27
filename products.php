<?php
include("database.php");
include("userheader.php");

$categoryId = isset($_GET['categoryId']) ? intval($_GET['categoryId']) : 0;

$sql = "SELECT `id`, `name`, `price`, `image`, `sku` FROM `products` WHERE `category` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $categoryId);
$stmt->execute();
$result = $stmt->get_result();

$products = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    echo "Error executing query: " . mysqli_error($conn);
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 0;
            margin:0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .product-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
        }

        .product-list li {
            width: 48%;
            margin: 1%;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 8px;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .product-list img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .product-list h3 {
            margin: 10px 0;
            font-size: 18px;
            text-align: center;
        }

        .product-list p {
            font-size: 16px;
            color: #666;
            text-align: center;
        }

        .product-list a {
            color: #088178;
            text-decoration: none;
            margin-top: 10px;
        }

        .product-list a:hover {
            text-decoration: underline;
        }

        .product-list button {
            background-color: #088178;
            color: #fff;
            border: none;
            padding: .5rem 1rem;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 3px;
            margin-top: 10px;
        }

        .product-list button:hover {
            background-color: #06635b;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Products</h2>

    <div class="container">
        <ul class="product-list">
            <?php
            if (!empty($products)) {
                foreach ($products as $product) {
                    echo "<li>";
                    echo "<img src='uploads/" . htmlspecialchars($product['image']) . "' alt='" . htmlspecialchars($product['name']) . "'>";
                    echo "<h3>" . htmlspecialchars($product['name']) . "</h3>";
                    echo "<p>Price: $" . htmlspecialchars($product['price']) . "</p>";
                    echo '<a href="productDetail.php?product_id=' . $product['id'] . '">See more...</a>';
                    echo "<button onclick='addToCart(" . $product['id'] . ", " . $product['sku'] . ")'>Add to Cart</button>";
                    echo "</li>";
                }
            } else {
                echo "<p>No products found in this category.</p>";
            }
            ?>
        </ul>
    </div>

    <script>
 function addToCart(productId, productSku) {
        if (productSku <= 0) {
            alert("Product out of stock!");
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open('GET', `cart.php?productId=${productId}`, true);
        xhr.onload = function () {
            if (this.status === 200) {
                alert("Added to cart successfully!");
                window.location.reload(); 
            } else {
                alert(this.responseText);
            }
        };
        xhr.send();
    }
    </script>
    <?php
    include("footer.php"); ?>
</body>
</html>

