<?php

session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

/* Get current user */
$stmt = mysqli_prepare(
    $conn,
    "SELECT fullname, email, image FROM users WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);


/* Update profile */
if (isset($_POST['update'])) {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);

    if ($fullname == "" || $email == "") {

        $message = "Please fill all fields.";

    } else {

        $image = $user['image'];

        /* Check new image */
        if (!empty($_FILES['image']['name'])) {

            $allowed_types = [
                "image/jpeg",
                "image/png",
                "image/jpg"
            ];

            $file_type = $_FILES['image']['type'];
            $file_size = $_FILES['image']['size'];

            /* Maximum 2 MB */
            if ($file_size > 2 * 1024 * 1024) {

                $message = "Image must be less than 2 MB.";

            } elseif (!in_array($file_type, $allowed_types)) {

                $message = "Only JPG and PNG images are allowed.";

            } else {

                $image = time() . "_" . basename($_FILES['image']['name']);

                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    "uploads/" . $image
                );
            }
        }

        /* Update database */
        if ($message == "") {

            $update = mysqli_prepare(
                $conn,
                "UPDATE users
                 SET fullname = ?, email = ?, image = ?
                 WHERE id = ?"
            );

            mysqli_stmt_bind_param(
                $update,
                "sssi",
                $fullname,
                $email,
                $image,
                $user_id
            );

            if (mysqli_stmt_execute($update)) {

                $_SESSION['user'] = $fullname;

                $message = "Profile updated successfully.";

                /* Refresh user data */
                $user['fullname'] = $fullname;
                $user['email'] = $email;
                $user['image'] = $image;

            } else {

                $message = "Profile update failed.";
            }
        }
    }
}

?>

<!DOCTYPE html>

<html>

<head>

<title>Edit Profile</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

<h2>Edit Profile</h2>

<?php

if ($message != "") {
    echo "<p><strong>$message</strong></p>";
}

?>

<form method="POST" enctype="multipart/form-data">

<label>Full Name</label>

<input
type="text"
name="fullname"
value="<?php echo htmlspecialchars($user['fullname']); ?>"
required
>

<label>Email</label>

<input
type="email"
name="email"
value="<?php echo htmlspecialchars($user['email']); ?>"
required
>

<label>Profile Picture</label>

<input
type="file"
name="image"
accept=".jpg,.jpeg,.png"
>

<?php

if (!empty($user['image'])) {

?>

<p>Current Image:</p>

<img
src="uploads/<?php echo htmlspecialchars($user['image']); ?>"
width="100"
height="100"
style="object-fit:cover;border-radius:50%;"
>

<?php

}

?>

<br><br>

<button type="submit" name="update">
Update Profile
</button>

</form>

<br>

<a href="dashboard.php">
Back to Dashboard
</a>

</div>

</body>

</html>
