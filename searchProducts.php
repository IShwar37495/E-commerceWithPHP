<?php
session_start();
include("database.php");
include("header.php");

if (isset($_GET['productName'])) {
    $productName = $_GET['productName'];

    $sql = "SELECT `id`, `name`, `image`, `price`, `salePrice` FROM `products` WHERE `name` LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $productName . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="styles.css">
    <style>

.product {
    width: calc(33.33% - 20px);
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 40px;
    padding: 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-left: 30rem;
}

/* Styling for product image */
.product img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Styling for product information */
.product-info {
    flex: 1;
}

/* Styling for product name */
.product h4 {
    font-size: 20px;
    margin-bottom: 10px;
}

/* Styling for product price */
.product p {
    font-size: 18px;
    color: #888;
    margin-bottom: 10px;
}

/* Styling for 'See more...' link */
#see-more {
    display: block;
    text-decoration: none;
    margin-top: 10px;
    color: #088178;
    transition: color 0.3s ease;
}

#see-more:hover {
    color: #06635b;
}

/* Styling for 'Add to Cart' button */
.product button {
    background-color: #088178;
    color: #fff;
    border: none;
    padding: 12px 24px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 10px;
}

.product button:hover {
    background-color: #06635b;
}

    </style>
</head>
<body>
    <section id="search-results">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<img src='./uploads/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
            echo "<div class='product-info'>";
            echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
            echo "<p id='price'>Price: $" . htmlspecialchars($row['price']) . "</p>";
            echo "<p>Sale Price: $" . htmlspecialchars($row['salePrice']) . "</p>";
            echo '<a href="productDetail.php?product_id=' . $row['id'] . '" id="see-more" >See more...</a>';
            echo "<button onclick='addToCart(" . $row['id'] . ")'>Add to Cart</button>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
    </section>
    <script>
        function addToCart(productId) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `cart.php?productId=${productId}`, true);
            xhr.onload = function () {
                if (this.status === 200) {
                    alert("Added to cart successfully!");
                } else {
                    alert("Error adding to cart.");
                }
            };
            xhr.send();
        }
    </script>

    <?php
    include("footer.php");
    
    ?>
</body>
</html>
<?php
} else {
    echo "Error: Search term not provided.";
}
?>


