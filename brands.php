<?php
include("database.php");
include("userheader.php");

$sql = "SELECT `id`, `name` FROM `brands` LIMIT 5";
$result = mysqli_query($conn, $sql);

$categories = [];

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $brands[] = $row;
        }
    } else {
        echo "No brands found.";
    }
} else {
    echo "Error executing query: " . mysqli_error($conn);
}

mysqli_free_result($result);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brands</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 0;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .category-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .category-list li {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 18px;
            color: #333;
        }

        .category-list li:last-child {
            border-bottom: none;
        }

        .category-list li:hover {
            background-color: #f0f0f0;
            cursor: pointer;
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .button-container button {
            background-color: #088178;
            color: #fff;
            border: none;
            padding: .5rem 1rem;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 3px;
        }

        .button-container button:hover {
            background-color: #06635b;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>BRANDS</h2>

    <div class="container">
        <ul class="category-list" id="categoryList">
            <?php
            foreach ($brands as $brand) {
                echo "<li data-id='" . htmlspecialchars($brand['id']) . "'>" . htmlspecialchars($brand['name']) . "</li>";
            }
            ?>
        </ul>

        <div class="button-container">
            <button onclick="loadBrands()">Load More</button>
        </div>
    </div>

    <script>
        let offset = 5;

        function loadBrands() {
            const request = new XMLHttpRequest();
            request.open("GET", 'load-brands.php?offset=' + offset, true);

            request.onload = function() {
                if (this.status === 200) {
                    const newbrands = JSON.parse(this.responseText);
                    const brandList = document.getElementById('categoryList');

                    newbrands.forEach(function(brand) {
                        const li = document.createElement('li');
                        li.textContent = brand.name;
                        li.setAttribute('data-id', brand.id);
                        brandList.appendChild(li);
                    });

                    offset += newbrands.length;

                    if (newbrands.length < 5) {
                        document.querySelector('.button-container button').style.display = 'none';
                    }
                } else {
                    console.error('Failed to load more brands.');
                }
            };

            request.send();
        }

        document.getElementById('categoryList').addEventListener('click', function(e) {
            if (e.target.tagName === 'LI') {
                const brandId = e.target.getAttribute('data-id');
                window.location.href = `products2.php?brandId=${brandId}`;
            }
        });
    </script>

    <?php
    
    include("footer.php");
    
    ?>
</body>
</html>