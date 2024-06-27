<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: loginFirst.php");
    exit;
}

include("database.php");
include("header.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Spartan:wght@100;200;300;400;500;600;700;800;900&display=swap");

        html {
            scroll-behavior: smooth;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Spartan", san-serif;
        }

        body {
            background-color: #f4f4f4;
        }

        .navbar {
            width: 100%;
        }

        #hero {
            background-image: url("https://i.postimg.cc/cCwBHzDV/hero4.png");
            height: 90vh;
            width: 100%;
            background-size: cover;
            background-position: top 25% right 0;
            padding: 0 80px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            line-height: 1;
            text-align: center;
            color: rgb(0,0,0);
            position: relative;
        }

        h1 {
            font-size: 48px;
            line-height: 56px;
            margin-bottom: 16px;
        }

        h2 {
            font-size: 36px;
            line-height: 42px;
            margin-bottom: 16px;
        }

        h4 {
            font-size: 20px;
            margin-bottom: 24px;
        }

        p {
            font-size: 16px;
            color: #465b52;
            margin-bottom: 24px;
        }

        #hero button {
            background-color: #088178;
            color: #fff;
            border: none;
            padding: 14px 32px;
            font-size: 18px;
            cursor: pointer;
        }

        #sale-price {
            background-image: url(https://i.postimg.cc/SsC7D5WD/b2.jpg);
            background-size: cover;
            background-position: center;
            padding: 80px 0;
            text-align: center;
            color: #fff;
            margin-top: 1rem;
        }

        #sale-price h2 {
            font-size: 36px;
            margin-bottom: 24px;
        }

        #dicount-price {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 40px;
        }

        .product {
            width: calc(33.33% - 20px);
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
            padding: 20px;
            text-align: center;
        }

        .product img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .product h4 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .product p {
            font-size: 18px;
            color: #888;
            margin-bottom: 10px;
        }

        .product button {
            background-color: #088178;
            color: #fff;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .product button:hover {
            background-color: #06635b;
        }

        #dynamic-text {
            font-size: 24px;
            margin-top: 16px;
            color: #ff4081; /* Catchy color */
            position: relative;
            display: inline-block;
        }

        @keyframes textColorChange {
            0% { color: #ff4081; }
            33% { color: #ffeb3b; }
            66% { color: #00e676; }
            100% { color: #ff4081; }
        }

        #dynamic-text {
            animation: textColorChange 5s linear infinite;
        }
        #see-more{
            display: block;
            text-decoration: none;
            margin-top: 0;
            color: #088178;
        }

        #see-more:hover{
            color:#06635b;
        }

        #price{
            text-decoration:line-through;
        }
    </style>
</head>

<body>
    <section id="hero">
        <h4>Trade-in-fair</h4>
        <h2>Super value deals</h2>
        <h1>On all Products</h1>
        <p>Save more with coupons and up to 70% off!</p>
        <div id="dynamic-text"></div>
        <button>Shop Now</button>
    </section>

    <section id="sale-price">
        <h2 id="sale"> Sale is live at least 50% or more off on selected products</h2>
    </section>

    <section id="dicount-price">
        <?php
        $sql = "SELECT `id`, `name`, `image`, `price`, `salePrice` FROM `products` WHERE `salePrice` <= (`price` / 2)";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='product'>";
                    echo "<img src='./uploads/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
                    echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
                    echo "<p id='price'>Price: $" . htmlspecialchars($row['price']) . "</p>";
                    echo "<p>Sale Price: $" . htmlspecialchars($row['salePrice']) . "</p>";
                    echo '<a href="productDetail.php?product_id=' . $row['id'] . '" id="see-more" >See more...</a>';
                    echo "<button onclick='addToCart(" . $row['id'] . ")'>Add to Cart</button>";


                    // $_SESSION['product_id']=$row['id'];
                    

                    echo "</div>";
                }
            } else {
                echo "No products found.";
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
        ?>
    </section>

    <script>
        const dynamicText = [
            "More than 50,000 satisfied customers",
            "Over 4,000 products",
            "Great deals always available",
            "apna kaam kr na lawde"
        ];

        let index = 0;
        let charIndex = 0;
        const textElement = document.getElementById("dynamic-text");

        function typeText() {
            if (charIndex < dynamicText[index].length) {
                textElement.textContent += dynamicText[index].charAt(charIndex);
                charIndex++;
                setTimeout(typeText, 50); // Adjust typing speed here
            } else {
                setTimeout(deleteText, 2000); // Wait time before starting to delete
            }
        }

        function deleteText() {
            if (charIndex > 0) {
                textElement.textContent = textElement.textContent.slice(0, -1);
                charIndex--;
                setTimeout(deleteText, 30); // Adjust deleting speed here
            } else {
                index = (index + 1) % dynamicText.length;
                setTimeout(typeText, 500); // Wait time before starting to type new text
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            typeText();
        });

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