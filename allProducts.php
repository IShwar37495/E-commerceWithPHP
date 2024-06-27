<?php
session_start();

if(!isset($_SESSION['user_id'])){

    header("Location:loginFirst.php");
    exit;
}
include("database.php");
include("header.php");

$id = $_SESSION['user_id'];
$products_per_page = 10;
$sql = "SELECT 
            p.*, 
            u.name AS username, 
            c.name AS category_name, 
            b.name AS brand_name 
        FROM products p
        LEFT JOIN users u ON p.userId = u.id
        LEFT JOIN category c ON p.category = c.id
        LEFT JOIN brands b ON p.brand = b.id
        LIMIT $products_per_page";
$result = $conn->query($sql);

// Fetch total number of products for checking if more products exist
$total_products_sql = "SELECT COUNT(*) AS total FROM products";
$total_products_result = $conn->query($total_products_sql);
$total_products_row = $total_products_result->fetch_assoc();
$total_products = $total_products_row['total'];

if (isset($_POST['delete'])) {
    $delete_id = $_POST['id'];
    $_SESSION['delete_id'] = $delete_id;
    echo "<script>document.getElementById('confirm-delete').style.display = 'block';</script>";
}

if (isset($_POST['confirmed_delete'])) {
    if (isset($_POST['id'])) {
        $delete_id = $_POST['id'];
        $sql = "DELETE FROM products WHERE id = $delete_id";
        if ($conn->query($sql) === TRUE) {
            echo "Product deleted successfully";
            header("Location: allProducts.php");
            exit;
        } else {
            echo "Error deleting product: " . $conn->error;
        }
    } else {
        echo "Error: Missing Product ID for deletion";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <style>
        table {
            width: 80%;
            margin: 2rem auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .load-more-container {
            text-align: center;
            margin: 2rem;
        }
        .load-more-button {
            padding: 0.5rem 1rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .edit-btn, .delete-btn {
            padding: 0.5rem;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 3px;
            text-decoration: none;
        }
/* 
        .edit-btn{
            display: block;
               padding: 0.4rem;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 3px;
            text-decoration: none;
            text-align: center;
        } */
        .edit-btn:hover, .delete-btn:hover {
            background-color: rgb(30, 30, 30);
            cursor: pointer;
        }
        #confirm-delete {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }
        #confirm-delete h2 {
            margin-top: 0;
        }
        #confirm-delete button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #confirm-delete button:hover {
            background-color: #0056b3;
        }  
        .search-container {
            text-align: center;
            margin: 2rem;
        }
        .search-input {
            padding: 0.5rem;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-input, .dropdown {
            padding: 0.5rem;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        .load-more-button{
     background-color: #66D736;
        }

        .edit-btn{
            background-color: #66D736;
        }

        .delete-btn{
            background-color: #66D736;
            /* margin-top: .8rem; */
        }

        .button1{
            background-color: #66D736;
        }
    </style>
</head>
<body>

<h1 style="text-align: center;">Product List</h1>

<div class="search-container">
    <input type="text" class="search-input" placeholder="Enter name to search" onkeyup="searchProducts()">
    <label for="status">Status</label>
    <select name="status" class="dropdown" onchange="searchProducts()">
        <option value="">All</option>
        <option value="inStock">In stock</option>
        <option value="notInStock">Not in Stock</option>
    </select>
</div>

<table id="product-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Image</th>
            <th>Stock in Unit</th>
            <th>Price</th>
            <th>Sale Price</th>
            <th>Category</th>
            <th>Brand</th>
            <th>User</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status = $row['status'] == 1 ? "In Stock" : "Not in stock";
                
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['description']}</td>
                        <td><img src='./uploads/{$row['image']}' alt='{$row['name']}' width='50'></td>
                        <td>{$row['sku']}</td>
                        <td>{$row['price']}</td>
                        <td>{$row['salePrice']}</td>
                        <td>{$row['category_name']}</td>
                        <td>{$row['brand_name']}</td>
                        <td>{$row['username']}</td>
                        <td>{$status}</td>
                        <td>
                            <a href='editProduct.php?id={$row['id']}' class='edit-btn'>edit</a>
                            <button type='button' class='delete-btn' onclick='confirmDelete({$row['id']})'>delete</button>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='12'>No products found</td></tr>";
        }
        ?>
    </tbody>
</table>

<div class="load-more-container">
    <?php if ($total_products > $products_per_page): ?>
        <button class="load-more-button" onclick="loadMoreProducts()">Load More</button>
    <?php endif; ?>
</div>

<!-- Confirmation dialog -->
<div id="confirm-delete">
    <h2>Are you sure you want to delete this product?</h2>
    <form method="POST">
        <input type="hidden" id="delete-id" name="id">
        <button type="submit" name="confirmed_delete" class="button1">Yes</button>
        <button type="button" class="button1" onclick="cancelDelete()">No</button>
    </form>
</div>

<script>
    let offset = <?php echo $products_per_page; ?>;
    const productsPerPage = <?php echo $products_per_page; ?>;
    const totalProducts = <?php echo $total_products; ?>;

    function loadMoreProducts() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `load_products.php?offset=${offset}`, true);
        xhr.onload = function () {
            if (this.status === 200) {
                const products = JSON.parse(this.responseText);
                const productTable = document.querySelector('#product-table tbody');
                products.forEach(product => {
                    // const status = product.status > 0 ? "In Stock" : "Not in stock"; 
                    const row = productTable.insertRow();
                    row.innerHTML = `
                        <td>${product.id}</td>
                        <td>${product.name}</td>
                        <td>${product.description}</td>
                        <td><img src='./uploads/${product.image}' alt='${product.name}' width='50'></td>
                        <td>${product.sku}</td>
                        <td>${product.price}</td>
                        <td>${product.salePrice}</td>
                        <td>${product.category_name}</td>
                        <td>${product.brand_name}</td>
                        <td>${product.username}</td>
                        <td>${product.status}</td>
                        <td>
                            <a href='editProduct.php?id=${product.id}' class='edit-btn'>edit</a>
                            <button type='button' class='delete-btn' onclick='confirmDelete(${product.id})'>delete</button>
                        </td>
                    `;
                });
                offset += productsPerPage;
                if (offset >= totalProducts) {
                    document.querySelector('.load-more-button').style.display = 'none';
                }
            }
        };
        xhr.send();
    }

    function confirmDelete(id) {
        document.getElementById('delete-id').value = id;
        document.getElementById('confirm-delete').style.display = 'block';
    }

    function cancelDelete() {
        document.getElementById('confirm-delete').style.display = 'none';
    }

    function searchProducts() {
        const query = document.querySelector('.search-input').value;
        const status = document.querySelector('select[name="status"]').value;
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `search_products.php?query=${query}&status=${status}`, true);
        xhr.onload = function () {
            if (this.status === 200) {
                const products = JSON.parse(this.responseText);
                const productTable = document.querySelector('#product-table tbody');
                productTable.innerHTML = '';
                products.forEach(product => {
                    // const status = product.status >0 ? "In Stock" : "Not in stock";
                    const row = productTable.insertRow();
                    row.innerHTML = `
                        <td>${product.id}</td>
                        <td>${product.name}</td>
                        <td>${product.description}</td>
                        <td><img src='./uploads/${product.image}' alt='${product.name}' width='50'></td>
                        <td>${product.sku}</td>
                        <td>${product.price}</td>
                        <td>${product.salePrice}</td>
                        <td>${product.category_name}</td>
                        <td>${product.brand_name}</td>
                        <td>${product.username}</td>
                        <td>${product.status}</td>
                        <td>
                            <a href='edit-page.php?id=${product.id}' class='edit-btn'>edit</a>
                            <button type='button' class='delete-btn' onclick='confirmDelete(${product.id})'>delete</button>
                        </td>
                    `;
                });
            }
        };
        xhr.send();
    }
</script>


</body>
</html>

