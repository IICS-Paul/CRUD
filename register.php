<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Registration</title>
    <link rel="stylesheet" href="style.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <script src="sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
</head>

<body>
    <div class="login-page">
        <form class="form" action="" method="post" enctype="multipart/form-data">
            <h1 class="login-title">Registration</h1>
            <input type="text" class="login-input" name="name" placeholder="Name" required />
            <input type="text" class="login-input" name="username" placeholder="Username">
            <input type="password" class="login-input" name="password" placeholder="Password">
            <button type="submit" name="submit" class="button">Register</button>
            <p class="message">Not registered? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>

</html>

<?php
date_default_timezone_set('Asia/Manila');
require('database.php');
// When form submitted, insert values into the database.
if (isset($_REQUEST['username'])) {
    // removes backslashes
    $name = stripslashes($_REQUEST['name']);
    //escapes special characters in a string
    $name = mysqli_real_escape_string($con, $name);
    $username    = stripslashes($_REQUEST['username']);
    $username    = mysqli_real_escape_string($con, $username);
    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($con, $password);
    $date = date('Y/m/d h:i:sa');
    $query    = "INSERT INTO info (name, username, password, date_added)
                     VALUES ('$name', '$username', '" . md5($password) . "', '$date')";
    $result   = mysqli_query($con, $query);
    if ($result) {
        echo '<script type="text/javascript">
        $(document).ready(function(){
        Swal.fire({
            icon: "success",
            titleText: "Registered Successfully!",
            text: "New User Registered to the Database!",
            showConfirmButton: false,
            timer: 2000,
            hideClass: {
              popup: "animate__animated animate__fadeOutUp",
            },
          })
        });
        </script>';
    } else {
        echo "<div class='form'>
              <h3>Required fields are missing.</h3><br/>
              <p class='link'>Click here to <a href='register.php'>registration</a> again.</p>
              </div>";
    }
} else {
}
?>