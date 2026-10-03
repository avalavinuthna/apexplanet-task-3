<?php
session_start();

if(!isset($_SESSION['user']))
{
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

<h2>Welcome <?php echo $_SESSION['user']; ?></h2>

<a href="edit.php">Edit Profile</a>
<br><br>

<a href="delete.php">Delete Account</a>
<br><br>

<a href="logout.php">Logout</a>

</div>

</body>
</html>
