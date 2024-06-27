<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: loginFirst.php");
    exit;
}

include("database.php");

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
            margin: 0 20px; 
            padding: 8px 12px; 
            border-radius: 5px;
            transition: background-color 0.3s ease; 
            font-weight: bold; 
        }

        .navbar a:hover {
            background-color: #f5f5f5; 
        }

        .extra-content {
            margin-right: 20px; 
            font-size: 14px; 
            color: #333; 
        }

        .logo-link {
            display: inline-block; 
            position: relative; 
        }

        .logo-link:hover .logo-img {
            transform: scale(1.1); 
        }

        .search-bar {
            display: flex;
            align-items: center;
        }

        .search-bar input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-left: 10px; 
            width: 200px; 
            transition: width 0.3s ease; 
        }

        .search-bar input[type="text"]:focus {
            width: 300px; 
        }

        .search-bar button {
            padding: 8px 16px;
            background-color: #088178;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease; 
        }

        .search-bar button:hover {
            background-color: #06635b;
        }
/* CSS for Popup */
.popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    z-index: 1000; /* Ensure it's above other content */
    justify-content: center;
    align-items: center;
    opacity: 0; /* Initially hidden */
    transition: opacity 0.3s ease; /* Smooth fade-in/out */
}

.popup.active {
    display: flex; /* Show the popup when active */
    opacity: 1; /* Fade in */
}

.popup-content {
    background-color: #fff; /* White background for better contrast */
    color: #088178; /* Text color matching site's primary color */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
    max-width: 400px;
    width: 90%; /* Adjust width as needed */
    text-align: center;
    position: relative;
    opacity: 0; /* Initially hidden */
    transform: translateY(-50px); /* Start animation from top */
    transition: opacity 0.3s ease, transform 0.3s ease; /* Smooth fade-in/out */
}

.popup.active .popup-content {
    opacity: 1; /* Fade in */
    transform: translateY(0); /* Slide in from top */
}

.close {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    font-size: 24px;
    color: #088178; /* Close icon color matching site's primary color */
    transition: color 0.3s ease; /* Smooth color change */
}

.close:hover {
    color: #06635b; /* Darken color on hover */
}

/* Adjusting text styling for readability */
.popup-content p {
    font-size: 16px; /* Adjust font size for better readability */
    margin: 0; /* Remove default margins */
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

    <div class="search-bar">
    <form action="searchProducts.php" method="GET">
        <input type="text" placeholder="Search..." name="productName">
        <button type="submit">Search</button>
    </form>
</div>

</nav>
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
        $sql = "SELECT `id`, `name`, `image`, `sku`, `price`, `salePrice` FROM `products` WHERE `salePrice` <= (`price` / 2)";
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
                     echo "<button onclick='addToCart(" . $row['id'] . ", " . $row['sku'] . ")'>Add to Cart</button>";


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

   <div id="popup" class="popup">
    <div class="popup-content" id="popup-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <p id="popup-message"></p>
    </div>
</div>

   


    <script>
        const dynamicText = [
            "More than 50,000 satisfied customers",
            "Over 4,000 products",
            "Great deals always available"
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


function showPopup(message) {
    const popup = document.getElementById('popup');
    const popupContent = document.getElementById('popup-content');
    const popupMessage = document.getElementById('popup-message');

    popupMessage.textContent = message;
    popup.classList.add('active'); // Show the popup
    setTimeout(() => {
        popupContent.classList.add('active'); // Fade in content after short delay
    }, 50); // Adjust delay if necessary
}

// Function to close the popup
function closePopup() {
    const popup = document.getElementById('popup');
    const popupContent = document.getElementById('popup-content');

    popupContent.classList.remove('active'); // Fade out content
    setTimeout(() => {
        popup.classList.remove('active'); // Hide the popup after fade out
    }, 300); // Wait for fade out transition duration
}



    
    function addToCart(productId, productSku) {
        if (productSku <= 0) {
            showPopup("Product out of stock!");
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open('GET', `cart.php?productId=${productId}`, true);
        xhr.onload = function () {
            if (this.status === 200) {
                showPopup("Added to cart successfully!");
                window.location.reload(); 
            } else {
                showPopup(this.responseText);
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




