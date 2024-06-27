<?php

session_start();



if(!isset($_SESSION['user_id'])){

    header("Location:loginFirst.php");
    exit;
}

include("database.php");

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE `id`='$user_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);

    if($data['type']==1){

        include("header.php");
    }

    else if($data['type']==0){

        include("userheader.php");
    }
} else {
    echo "User not found.";
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
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
            height: 40px;
            float: left;
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

        .container {
            margin-top: 5rem;
        }

        .profile-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-avatar {
            display: block;
            margin: 0 auto;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            object-position: center;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 10px;
        }

        strong {
            font-weight: bold;
        }

        .logout-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="profile-container">
        <h2 class="profile-title">User Profile</h2>
        <?php if ($data['avatar']): ?>
            <img src="uploads/<?php echo $data['avatar']; ?>" alt="User Avatar" class="profile-avatar">
        <?php endif; ?>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($data['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($data['email']); ?></p>
        <p><strong>Mobile:</strong> <?php echo htmlspecialchars($data['mobile']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($data['address']); ?></p>
        <p><strong>Type:</strong> <?php echo htmlspecialchars($data['type']); ?></p>
        <button class="logout-btn" onclick="logoutUser()">Logout</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function logoutUser() {
        if (confirm('Are you sure you want to logout?')) {
            window.location.href = 'logout.php';
        }
    }
</script>

</body>
</html>

