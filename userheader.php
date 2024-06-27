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

    <!-- <div class="search-bar">
        <input type="text" placeholder="Search...">
        <button type="submit">Search</button>
    </div> -->
</nav>
    
</body>
</html>

