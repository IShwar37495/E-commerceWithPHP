<?php


session_start();



if(!isset($_SESSION['user_id'])){

    header("Location:loginFirst.php");
    exit;
}
include("database.php");
include("header.php");


// Initialize error and success messages
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';

// Clear session messages after displaying them
unset($_SESSION['error']);
unset($_SESSION['success']);

$id = $_SESSION['user_id'];

// Check if product ID is provided in URL
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Fetch product details based on product ID
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "Product ID not provided.";
    exit;
}

// Fetch categories from the database
$sqlCategories = "SELECT `id`, `name` FROM `category`";
$resultCategories = mysqli_query($conn, $sqlCategories);

// Fetch brands from the database
$sqlBrands = "SELECT `id`, `name` FROM `brands`";
$resultBrands = mysqli_query($conn, $sqlBrands);

// Handle form submission
if (isset($_POST['click'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $stockInUnit = $_POST['sku'];
    $price = $_POST['price'];
    $salePrice = $_POST['salePrice'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];

    // Check if new image file is uploaded
    if (!empty($_FILES['files']['name'][0])) {
        // Remove existing image file
        $currentImage = $product['image'];
        $currentImagePath = "./uploads/" . $currentImage;
        if (file_exists($currentImagePath)) {
            unlink($currentImagePath);
        }

        $filename = $_FILES['files']['name'][0];
        $tmpFilePath = $_FILES['files']['tmp_name'][0];
        $newFilePath = "./uploads/" . $filename;

        if (move_uploaded_file($tmpFilePath, $newFilePath)) {
            
            $sql = "UPDATE products SET `name`=?, `description`=?, `image`=?, `sku`=?, `price`=?, `salePrice`=?, `category`=?, `brand`=?, `userId`=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssddssii", $name, $description, $filename, $stockInUnit, $price, $salePrice, $category, $brand, $id, $productId);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Product updated successfully with new image";
                header("Location: editProduct.php?id=$productId");
                exit;
            } else {
                $_SESSION['error'] = "Error updating product with new image: " . $stmt->error;
                header("Location: editProduct.php?id=$productId");
                exit;
            }
        } else {
            $_SESSION['error'] = "Error uploading new file: " . $filename;
            header("Location: editProduct.php?id=$productId");
            exit;
        }
    } else {
        
        $sql = "UPDATE products SET `name`=?, `description`=?, `sku`=?, `price`=?, `salePrice`=?, `category`=?, `brand`=?, `userId`=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssddssi", $name, $description, $stockInUnit, $price, $salePrice, $category, $brand, $id, $productId);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Product updated successfully without changing image";
            header("Location: editProduct.php?id=$productId");
            exit;
        } else {
            $_SESSION['error'] = "Error updating product without changing image: " . $stmt->error;
            header("Location: editProduct.php?id=$productId");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product Form</title>
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

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

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

    h2 {
      text-align: center;
    }

    .current-image {
      margin-bottom: 10px;
    }

.success-message {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60%; 
    max-width: 400px; 
    background-color: white; 
    display: flex;
    flex-direction: column; /* Stack items vertically */
    justify-content: center;
    align-items: center;
    z-index: 99;
    color: black;
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); 
    text-align: center;
    padding: 20px;
}

.success-message .close-btn {
    background-color: #66D736;
    color: white;
    font-size: 16px;
    cursor: pointer;
    padding: 8px 16px;
    border-radius: 5px;
    margin-top: 20px; /* Space between message and button */
    transition: background-color 0.3s, color 0.3s;
    border: none; /* Remove border for cleaner look */
}

.success-message .close-btn:hover {
    background-color: #6FEB3B;
}

.success-message .content {
    margin: 20px 0;
    font-size: 18px; /* Adjusted font size */
}



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

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

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

        h2 {
            text-align: center;
        }

        .current-image {
            margin-bottom: 10px;
        }

        .error-message {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .error-icon {
            font-size: 20px;
            color: #f44336;
            margin-right: 10px;
        }

      

    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Product Form</h2>
        <form action="editProduct.php?id=<?php echo $productId; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="current-image">Current Image:</label><br>
                <?php if (!empty($product['image'])): ?>
                    <img src="./uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Current Image" class="current-image" width="150"><br>
                <?php else: ?>
                    <p>No image uploaded.</p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="new-image">New Image:</label>
                <input type="file" name="files[]" id="new-image">
            </div>

            <div class="form-group">
                <label for="sku">SKU:</label>
                <input type="text" id="sku" name="sku" value="<?php echo htmlspecialchars($product['sku']); ?>" required>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>

            <div class="form-group">
                <label for="salePrice">Sale Price:</label>
                <input type="text" id="salePrice" name="salePrice" value="<?php echo htmlspecialchars($product['salePrice']); ?>">
            </div>

            <div class="form-group">
                <label for="category">Category:</label><br>
                <select name="category" id="category" required>
                    <option value="">Select Category</option>
                    <?php while ($row = mysqli_fetch_assoc($resultCategories)) : ?>
                        <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $product['category']) echo 'selected'; ?>><?php echo htmlspecialchars($row['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="brand">Brand:</label><br>
                <select name="brand" id="brand" required>
                    <option value="">Select Brand</option>
                    <?php mysqli_data_seek($resultCategories, 0);?>
                    <?php while ($row = mysqli_fetch_assoc($resultBrands)) : ?>
                        <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $product['brand']) echo 'selected'; ?>><?php echo htmlspecialchars($row['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <?php if ($error): ?>
                <div class="error-message">
                    <div>
                        <span class="error-icon">&#10060;</span>
                        <?php echo $error; ?>
                    </div>
                    <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success-message">
                    <div>
                        <span class="success-icon">&#10004;</span>
                        <?php echo $success; ?>
                    </div>
                    <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <button type="submit" name="click">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>


