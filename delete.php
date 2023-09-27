<?php
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    include "database.php";

    $delete = "DELETE FROM sample WHERE id = ?";

    if ($stmt = mysqli_prepare($con, $delete)) {
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        $param_id = trim($_POST["id"]);

        if (mysqli_stmt_execute($stmt)) {
            header("location: home.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <style>
        .wrapper-fluid {
            width: 980px;
            margin: 0 auto;
            margin-top: 3%;
            margin-bottom: 2%;
            background-color: rgba(236, 240, 241, 1);
            padding: 30px 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #000;
        }

        .bg {
            background-image: url("./image/back.jpg");
            background-size: 100% 100%;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body class="bg">
    <div class="wrapper-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-2 mb-4">Delete Record</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>" />
                            <p><em><i>Are you sure you want to delete this record?</i></em></p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="home.php" class="btn btn-secondary">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>