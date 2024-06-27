<?php 

session_start();


if(!isset($_SESSION['user_id'])){

    header("Location:loginFirst.php");
    exit;
}
include("database.php");
include("header.php");

$id = $_SESSION['user_id'];
$error = "";
$success = "";

if (isset($_POST['btn'])) {
    $brandname = $_POST['brandName'];

    if ($brandname == "") {
        $error = "Brand name can't be empty";
    } else {
        $sql = "INSERT INTO `brands`(`name`, `userId`) VALUES ('$brandname', '$id')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $_SESSION['success'] = "Brand added successfully";
            // Redirect to avoid form resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error = "Error adding brand";
        }
    }
}

if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']); 
}

if (isset($_GET['error'])) {
    $error = $_GET['error'];
}



if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $_SESSION['delete_id'] = $id;
    echo "<script>document.getElementById('confirm-delete').style.display = 'block';</script>";
}

if (isset($_POST['confirmed_delete'])) {
    if (isset($_POST['id'])) { 
        $id = $_POST['id'];
        $sql = "DELETE FROM `brands` WHERE id = '$id'";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = "Brand deleted successfully";
            header("Location: addBrand.php");
            exit();
        } else {
            echo "Error deleting brand: " . $conn->error;
        }
    } else {
        echo "Error: Missing brand ID for deletion";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
body {
    margin: 0;
    padding: 0;
    /* background-color: black; */
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
     background-color: #088178;
    padding: 10px 20px;
    height: 5rem;
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
    background-color: #f5f5f5;
}

.extra-content {
    margin-right: 20px;
    font-size: 14px;
    color: #333;
}

.container {
    max-width: 800px; /* Increased width for better table display */
    margin-top: 5rem;
}

.form-container {
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
}

.add-brand {
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
    text-align: center;
}

.error-message {
    color: red;
}

.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    text-align: center;
}

.success-icon {
    font-size: 40px;
    color: green;
}

.success-message {
    margin-top: 10px;
    font-size: 20px;
}

a:hover {
    text-decoration: none;
}

.btn {
    background-color: #66D736;
    border: 1px solid #66D736;
}

.btn:hover {
    background-color: #6FEB3B;
    border: 1px solid #66D736;
}

.btn:active {
    background-color: #66D736;
    border: 1px solid #66D736;
}

.brands-table {
    width: 90%;
    margin: 20px auto; /* Center the table */
    border-collapse: collapse;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border-radius: 10px;
}

.brands-table th, .brands-table td {
    padding: 12px 15px;
    text-align: center;
}

.brands-table thead {
    color: #333;
    font-weight: bold;
}

.brands-table th {
     background-color: #7ED381;
     color: #333;
    font-weight: bold;
}

.brands-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.brands-table tbody tr:nth-child(odd) {
 
     background-color: #f9f9f9;
}

.brands-table tbody tr:hover {
    background-color: #f1f1f1;
    cursor: pointer;
}

.brands-table tbody td:nth-child(1) {
    background-color: #7ED381; /* Light Yellow */
}

.brands-table tbody td:nth-child(2) {
    background-color: #C8E6C9; /* Light Green */
}

.brands-table tbody td:nth-child(3) {
      background-color: #C8E6C9; 
}

.brands-table tbody td:nth-child(4) {
      background-color: #C8E6C9; 
   
}
.brands-table tbody td:nth-child(5) {
      background-color: #C8E6C9; 
   
}

#all-brands{
    margin-top: 3rem;
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
            background-color: #66D736;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #confirm-delete button:hover {
            background-color: #6FEB3B;
        } 

       .delete-btn {
    background-color: #66D736;
    border: 1px solid transparent;
    border-radius: 5px;
    cursor: pointer;
    padding: 10px 20px;
    color: black;
    font-weight: bold;
    transition: background-color 0.3s, color 0.3s;
}

.delete-btn:hover {
    background-color: red;
    color: white;
}



    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1 class="add-brand">Add Brand</h1>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="brand-name" class="form-label"><b>Brand Name</b></label>
                    <input type="text" class="form-control" id="brand-name" name="brandName" value="<?php echo isset($brandName) ? $brandName : ''; ?>" >
                    <?php if ($error): ?>
                        <div class="error-message"><?php echo $error; ?></div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary" name="btn">Save</button>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <span class="success-icon">&#10004;</span>
                    <div class="success-message"><?php echo isset($success) ? $success : ''; ?></div>
                    <button type="button" class="btn btn-primary mt-3" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="all-brands">
        <h2 style="text-align:center;">All Available Brands</h2>
        <table class="brands-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Brand Name</th>
                    <th>User Name</th>
                    <th>Action</th>
                
                </tr>
            </thead>
            <tbody>
                <?php
            $query=  "SELECT brands.id, brands.name, u.name AS username
FROM brands
JOIN users u ON brands.userId = u.id";

                $result = mysqli_query($conn, $query);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['username']}</td>
                                   <td>
                            <button type='button' class='delete-btn' onclick='confirmDelete(" . $row['id'] . ")'>delete</button>
                        </td>


                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No brands found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="confirm-delete">
    <h2>Are you sure you want to delete this Brand?</h2>
    <form method="POST">
        <input type="hidden" id="delete-id" name="id">
        <button type="submit" name="confirmed_delete">Yes</button>
        <button type="button" onclick="cancelDelete()">No</button>
    </form>
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            <?php if ($success): ?>
            $('#successModal').modal('show');
            <?php endif; ?>
        });


         function confirmDelete(id) {
        document.getElementById('delete-id').value = id;
        document.getElementById('confirm-delete').style.display = 'block';
    }

    function cancelDelete() {
        document.getElementById('confirm-delete').style.display = 'none';
    }
    </script>

    <?php include("footer.php"); ?>
</body>
</html>





