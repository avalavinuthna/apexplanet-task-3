<?php
include "db.php";

if(isset($_POST['register']))
{
    $fullname=$_POST['fullname'];
    $email=$_POST['email'];
    $password=md5($_POST['password']);

    $image=$_FILES['image']['name'];
    $temp=$_FILES['image']['tmp_name'];

    move_uploaded_file($temp,"uploads/".$image);

    $sql="INSERT INTO users(fullname,email,password,image)
          VALUES('$fullname','$email','$password','$image')";

    mysqli_query($conn,$sql);

    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
<h2>Register</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="fullname" placeholder="Full Name" required>

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<input type="file" name="image" required>

<button type="submit" name="register">Register</button>

</form>

<p><a href="login.php">Login Here</a></p>

</div>

</body>
</html>
