<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
             background-color: #088178;
            padding: 10px 20px;
            height: 3rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo img {
            height: 50px;
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
            background-color: #e0e0e0; /* Adjusted hover background color */
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

        .logo-img {
            display: block;
            width: 80px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .logo-link:hover .logo-img {
            transform: scale(1.1);
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
        <a href="adminhome.php">Home</a>
        <a href="addBrand.php">Add brands</a>
        <a href="editBrand.php">Edit Brand</a>
        <a href="addCategory.php">Add category</a>
        <a href="allProducts.php">See Available Products</a>
        <a href="addProduct.php">Add Product</a>
    </div>
</nav>

</body>
</html>


