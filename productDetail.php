<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: loginFirst.php");
    exit;
}

include("database.php");

if (!isset($_GET['product_id'])) {
    echo "Product ID not specified.";
    exit;
}

// Sanitize the input to prevent SQL injection
$product_id = mysqli_real_escape_string($conn, $_GET['product_id']);

$sql = "SELECT `id`, `name`, `description`, `image`,  `sku`, `price`, `salePrice` FROM `products` WHERE `id` = '$product_id'";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <style>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        
        body {
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
            padding: 0;
            margin: 0;
        }

        
        .product-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .product-image {
            max-width: 100%;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .product-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .product-price {
            font-size: 20px;
            color: #888;
            margin-bottom: 10px;
        }


        .product-sale-price {
            font-size: 24px;
            color: #e91e63; 
            margin-bottom: 10px;
        }


        .product-description {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .add-to-cart-button {
            background-color: #088178;
            color: #fff;
            border: none;
            padding: 12px 24px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .add-to-cart-button:hover {
            background-color: #06635b;
        }

        #see-more {
            display: block;
            text-decoration: none;
            margin-top: 10px;
            color: #088178;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        #see-more:hover {
            color: #06635b;
        }

        body {
            margin: 0;
            padding: 0;
        }

    .logo {
        display: flex;
        align-items: center;
    }

    .logo-link {
        display: inline-block; /* Ensures the link takes up only as much space as the image */
        position: relative; /* Positioning context for pseudo-element */
        margin-right: 20px; /* Adjust margin as needed */
    }

    .logo-img {
        display: block;
        object-fit: cover;
        width: 49px; /* Allow the logo to adjust its width automatically */
        height: 49px; 
        transition: transform 0.3s ease; /* Smooth transition for scale effect */
    }

    .logo-link:hover .logo-img {
        transform: scale(1.1); /* Scale up the image on hover */
    }

    .search-bar input[type="text"]:focus {
        width: 300px; /* Expand width on focus */
    }

    .search-bar button {
        padding: 8px 16px;
        background-color: #088178;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease; /* Smooth transition for background color */
    }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #088178;
            padding: 10px 20px; /* Adjust padding as needed */
            height: 4rem;
            position: sticky;
            top: 0; /* Stick to the top of the viewport */
            z-index: 1000; /* Ensure the navbar stays above other content */
        }

        .navbar a {
            text-decoration: none;
            color: black;
            margin: 0 20px; /* Increase space between links */
            padding: 8px 12px; /* Add padding to links */
            border-radius: 5px; /* Add rounded corners */
            transition: background-color 0.3s ease; /* Smooth transition for background color */
            font-weight: bold; /* Add thickness to the text */
        }

        .navbar a:hover {
            background-color: #f5f5f5; /* Change background color on hover */
        }

        .extra-content {
            margin-right: 20px; /* Adjust margin as needed */
            font-size: 14px; /* Adjust font size as needed */
            color: #333; /* Adjust text color as needed */
        }

        .logo-link {
            display: inline-block; /* Ensures the link takes up only as much space as the image */
            position: relative; /* Positioning context for pseudo-element */
        }

        .logo-link:hover .logo-img {
            transform: scale(1.1); /* Scale up the image on hover */
        }

        .search-bar {
            display: flex;
            align-items: center;
        }

        .search-bar input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-left: 10px; /* Adjust spacing between logo and search bar */
            width: 200px; /* Adjust width of search input */
            transition: width 0.3s ease; /* Smooth transition for width change */
        }

        .search-bar input[type="text"]:focus {
            width: 300px; /* Expand width on focus */
        }

        .search-bar button {
            padding: 8px 16px;
            background-color: #088178;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease; /* Smooth transition for background color */
        }

        .search-bar button:hover {
            background-color: #06635b; /* Darker background on hover */
        }

        .available-in-stock{


            margin-bottom: .5rem;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="logo">
        <a href="profile.php" class="logo-link">
            <img src="https://seeklogo.com/images/F/For_Dummies-logo-270963AFD1-seeklogo.com.png" alt="Logo" class="logo-img">
        </a> <!-- Dummy logo -->
    </div>

    <div id="links">
        <a href="userhome.php">Home</a>
        <a href="categories.php">Categories</a>
        <a href="brands.php">Brand</a>
        <a href="sale.php">Sale</a>
        <a href="wishlist.php">Wishlist</a>
        <a href="cart.php">Cart</a>
    </div>
</nav>

<?php
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='product-container'>";
            echo "<img class='product-image' src='./uploads/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
            echo "<h2 class='product-name'>" . htmlspecialchars($row['name']) . "</h2>";
            echo "<p class='product-price'>Price: <span id='price'>$" . htmlspecialchars($row['price']) . "</span></p>";
            echo "<p class='product-sale-price'>Sale Price: $" . htmlspecialchars($row['salePrice']) . "</p>";
            echo "<p class='product-description'>" . htmlspecialchars($row['description']) . "</p>";
               echo "<p class='available-in-stock'> available in stock:" . htmlspecialchars($row['sku']) . "</p>";
            echo "<button class='add-to-cart-button' onclick='addToCart(" . $row['id'] . ")'>Add to Cart</button>";
            echo "</div>";
        }
    } else {
        echo "<p>No product found.</p>";
    }
} else {
    echo "<p>Error fetching product details.</p>";
}
?>

<!-- <a href="#" id="see-more">See more products</a> -->

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
