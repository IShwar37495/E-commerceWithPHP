<?php
include("database.php");

$error = "";

if(isset($_POST['click'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $avatar = $_FILES['avatar'];
    $type=$_POST['type'];

    if($type=="admin"){

        $type=1;
    }

    else{
        $type=0;
    }
    

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result1 = mysqli_query($conn, $checkEmail);

    if(mysqli_num_rows($result1) > 0){
        $error = "This email already exists, please use another one.";
    } else {
        $filename = $_FILES['avatar']['name'];
        $tmpname = $_FILES['avatar']['tmp_name'];
        $target_dir = "uploads/" . $filename;
        $avatar_name = "";

        if(!empty($filename)) {
            if(move_uploaded_file($tmpname, $target_dir)){
                $avatar_name = $filename;
            } else {
                $error = "Error uploading file.";
            }
        } else {
            
            $avatar_name = "Default-Profile-Picture-PNG.png"; 
        }

        if(empty($error)) {
            $sql = "INSERT INTO `users`(`name`, `email`, `password`, `mobile`, `address`, `avatar`,`type`) VALUES ('$name','$email','$hashed_password','$mobile','$address', '$avatar_name', '$type')";
            $result = mysqli_query($conn, $sql);

            if($result){
                echo "Data inserted successfully.";
                header("Location: login.php");
                exit;
            } else {
                $error = "Error inserting data.";
            }
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container">
        <h2 class="form-title">Registration Form</h2>
        <?php if($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="signup.php" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required>
                <div id="nameError" class="form-text text-danger"></div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                <div id="emailHelp" class="form-text"></div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="mobile" class="form-label">Mobile Number</label>
                <input type="tel" class="form-control" id="mobile" name="mobile" pattern="[0-9]{10}" value="<?php echo isset($mobile) ? $mobile : ''; ?>" required>
                 <div id="number-error" class="form-text text-danger"></div>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3" required><?php echo isset($address) ? $address : ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="avatar" name="avatar">Avatar (optional)</label>
                <input type="file" class="form-control" name="avatar" id="avatar">
                <p id="error-message" style="color: red;"></p>
            </div>
            <div>

            <label for="type" name="type">Type</label>
            <select name="type" id="myDropdown">
             <option value="user">user</option>
            <option value="admin">admin</option>
          </select>
          </div>
            <input type="submit" name="click" class="btn btn-primary" value="Submit"/>
        </form>
    </div>
</div>
<script>
  document.getElementById('avatar').addEventListener('change', function() {
    const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    const fileExtension = this.value.split('.').pop().toLowerCase();
    const errorMessage = document.getElementById('error-message');
    errorMessage.textContent = ''; 

    if (!allowedExtensions.includes(fileExtension)) {
        errorMessage.textContent = 'Only image files (jpg, jpeg, png, gif) are allowed.';
        this.value = '';  
    }
  });

  function validateForm() {
    const name = document.getElementById('name').value;
    const nameError = document.getElementById('nameError');
    const namePattern = /^[a-zA-Z_][a-zA-Z0-9_]*$/;

    if (!name.match(namePattern)) {
        nameError.textContent = "Name must start with a letter or underscore and can contain only letters, numbers, and underscores.";
        return false;
    }

    return true;
  }
  

      function checkNumber(e) {
            const number = e.target.value;
            const error = document.getElementById("number-error");
            const alphabetRegex = /[a-zA-Z]/;

            if (alphabetRegex.test(number)) {
                error.style.display = "block";
                error.textContent="only numbers are allowed";
            } else {
                error.style.display = "none";
            }
        }

  const number=document.getElementById("mobile");
  number.addEventListener("input", checkNumber);
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>