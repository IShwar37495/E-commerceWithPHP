<?php

session_start();

if(!isset($_SESSION['user_id'])){

    header("Location:loginFirst.php");
    exit;
}
include("database.php");
include("header.php");






$id = $_SESSION['user_id'];

if(isset($_POST['click'])){

    $name = $_POST['name'];
    $description = $_POST['description'];
    $stockInUnit = $_POST['sku'];
    $price = $_POST['price'];
    $salePrice = $_POST['salePrice'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    // $status = $_POST['status']; 




    if($stockInUnit>0){
        $status = 1;
    } else {
        $status = 0;
    }

    $totalFiles = count($_FILES['files']['name']);

    for($i = 0; $i < $totalFiles; $i++){

        $filename = $_FILES['files']['name'][$i];
        $tmpFilePath = $_FILES['files']['tmp_name'][$i];
        $newFilePath = "./uploads/" . $filename;

        if(move_uploaded_file($tmpFilePath, $newFilePath)){
            $stmt = $conn->prepare("INSERT INTO `products` (`name`, `description`, `image`, `sku`, `price`, `salePrice`, `category`, `brand`, `userId`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssddssii", $name, $description, $filename, $stockInUnit, $price, $salePrice, $category, $brand, $id, $status);

            if($stmt->execute()){
                echo "Product added successfully with file: " . $filename . "<br>";
            } else {
                echo "Error inserting product with file " . $filename . ": " . $stmt->error . "<br>";
            }

            $stmt->close();
        } else {
            echo "Error uploading file: " . $filename . "<br>";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product Form</title>
    <style>


.container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ddd;
    background-color: #C8E6C9; 
    border-radius: 25px;
    box-shadow: 10px 10px 9px #929292, -10px -10px 9px #ffffff;
}

form {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between; 
}

.form-group {
    margin-bottom: 15px; 
    width: 100%; 
}

/* Label styles */
label {
    display: block; 
    margin-bottom: 5px; 
    font-weight: bold; 
}

/* Input styles */
input[type="text"],
input[type="number"],
input[type="url"],
input[type="file"],
textarea,
select {
    width: 100%; 
    padding: 10px; 
    border: 1px solid #ccc; 
    border-radius: 5px; 
    box-sizing: border-box; 
    background: #f9f9f9;
    transition: all 0.3s ease-in-out;
    box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.1), inset -2px -2px 5px rgba(255, 255, 255, 0.7);
}

input[type="text"]:hover,
input[type="number"]:hover,
input[type="url"]:hover,
input[type="file"]:hover,
textarea:hover,
select:hover {
    border-color: #bbb;
}

input[type="text"]:focus,
input[type="number"]:focus,
input[type="url"]:focus,
input[type="file"]:focus,
textarea:focus,
select:focus {
    border-color: #4CAF50;
    box-shadow: 0 0 8px rgba(76, 175, 80, 0.4);
    outline: none;
    background: #ffffff;
}

textarea {
    min-height: 100px;
}

button[type="submit"] {
    background-color: #4CAF50; 
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

button[type="submit"]:hover {
    background-color: #45A049; 
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4);
}
h2{
    text-align: center;
}



    </style>
    
</head>
<body>

<div class="container">
    <h2>Add Product Form</h2>
    <form action="addProduct.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
        </div>

        <div class="form-group">
            <label for="image">Image</label>
           <input type="file" name="files[]" multiple required>
        </div>

        <div class="form-group">
            <label for="sku">SKU:</label>
            <input type="text" id="sku" name="sku" required>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="text" id="price" name="price" required>
        </div>

        <div class="form-group">
            <label for="salePrice">Sale Price:</label>
            <input type="text" id="salePrice" name="salePrice">
        </div>

    <div class="form-group">
    <label for="category">Category:</label>
    <select name="category" id="categoryId" required>
        <option value="">Select Category</option>
        <?php

        $sql = "SELECT `id`, `name` FROM `category`";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
            }
        } else {
            echo "<option disabled>No categories available</option>";
        }

        // mysqli_close($conn);
        ?>
    </select>
</div>

<div class="form-group">
    <label for="brand">Brand:</label>
    <select name="brand" id="brandId" required>
        <option value="">Select Brand</option>
        <?php
        $sql = "SELECT `id`, `name` FROM `brands`";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
            }
        } else {
            echo "<option disabled>No brands available</option>";
        }
        ?>
    </select>
</div>



        

        <!-- <div class="form-group">
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="">select status</option>
                <option value="active">In stock</option>
                <option value="inactive">Not In stock</option>
            </select>
        </div> -->

        <div class="form-group">
            <button type="submit" name="click">Submit</button>
        </div>
    </form>
</div>

</body>
</html>

