<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Login</title>
    <link rel="stylesheet" href="style.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <script src="sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
</head>

<body>
    <?php
    require('database.php');
    session_start();
    // When form submitted, check and create user session.
    if (isset($_REQUEST['username'])) {
        $username = stripslashes($_REQUEST['username']);    // removes backslashes
        $username = mysqli_real_escape_string($con, $username);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($con, $password);
        // Check user is exist in the database
        $query    = "SELECT * FROM info WHERE username='$username'
                     AND password='" . md5($password) . "'";
        $result = mysqli_query($con, $query) or die();
        $rows = mysqli_num_rows($result);
        if ($rows == 1) {
            $_SESSION['username'] = $username;
            // Redirect to user dashboard page
            header("location: home.php");
        } else {
            echo "
            <div class='login-page'>
            <div class='form'>
                  <h3 style='text-align: center; font-size: 20px;'>Invalid Username or Password.</h3><br/>
                  <button class='back'><a class='link' href='login.php'>Back to Login</a></button>
                  </div>
                  </div>";
        }
    } else {
    ?>
        <div class="login-page">
            <form class="form" method="post" name="login">
                <h1 class="login-title">LOGIN</h1>
                <span id="username" class="form-label">Username</span>
                <input type="text" class="login-input" name="username" autofocus="true" />
                <span id="password" class="form-label">Password</span>
                <input type="password" class="login-input" name="password" />
                <!-- <div class="ml-2">
                    <input type="checkbox" onclick="myFunction()"> Show Password
                </div> -->
                <input type="submit" value="Login" name="submit" class="button" />
                <p class="message">Not registered? <a href="register.php">Create an account</a></p>
            </form>
        </div>
    <?php
    }
    ?>
</body>

</html>

<!-- <script>
    function myFunction() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script> -->