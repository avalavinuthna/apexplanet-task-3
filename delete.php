<?php
session_start();
include "db.php";

$name=$_SESSION['user'];

mysqli_query($conn,
"DELETE FROM users WHERE fullname='$name'");

session_destroy();

header("Location: register.php");
?>
