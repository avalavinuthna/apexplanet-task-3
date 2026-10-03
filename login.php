<?php
session_start();
include "db.php";

if(isset($_POST['login']))
{
    $email=$_POST['email'];
    $password=md5($_POST['password']);

    $sql="SELECT * FROM users
          WHERE email='$email'
          AND password='$password'";

    $result=mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)>0)
    {
        $row=mysqli_fetch_assoc($result);

        $_SESSION['user']=$row['fullname'];

        header("Location: dashboard.php");
    }
    else
    {
        echo "Invalid Login";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

<h2>Login</h2>

<form method="POST">

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit" name="login">Login</button>

</form>

<p><a href="register.php">Register Here</a></p>

</div>

</body>
</html>
