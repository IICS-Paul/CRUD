<?php
// Include config file
date_default_timezone_set('Asia/Manila');
include "database.php";

// Define variables and initialize with empty values
$name = $address = $age = "";
$name_err = $address_err = $age_err = "";

// Processing form data when form is submitted
if (isset($_POST) && $_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate name
    $input_name = trim($_POST["name"]);
    if (empty($input_name)) {
        $name_err = "Please enter a name.";
    } elseif (!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $name_err = "Please enter a valid name.";
    } else {
        $name = $input_name;
    }

    // Validate address
    $input_address = trim($_POST["address"]);
    if (empty($input_address)) {
        $address_err = "Please enter an address.";
    } else {
        $address = $input_address;
    }

    // Validate age
    $input_age = trim($_POST["age"]);
    if (empty($input_age)) {
        $age_err = "Please enter the age";
    } elseif (!ctype_digit($input_age)) {
        $age_err = "Please enter an integer value.";
    } else {
        $age = $input_age;
    }

    $date = date('Y/m/d h:i:sa');
    $uploadDirectory = "image/";
    $validExtensions = array('jpg', 'jpeg', 'png', 'gif');

    $img_ref = rand();
    $sqlValues = "";

    foreach ($_FILES['upload']['tmp_name'] as $imageKey => $imageValue) {

        $image = $_FILES['upload']['name'][$imageKey];
        $imageTmp = $_FILES['upload']['tmp_name'][$imageKey];
        $imageType = pathinfo($uploadDirectory . $image, PATHINFO_EXTENSION);

        if (!empty($image) && !in_array($imageType, $validExtensions)) {
        } else if (!empty($image)) {

            $sqlValues .= "('" . $img_ref . "', '" . $image . "'),";

            $store = move_uploaded_file($imageTmp, $uploadDirectory . $image);
        }
    }

    if (!empty($image)) {
        $sqlIns = "INSERT INTO sample (name, age, address, img_ref, date) VALUES ('" . $name . "', '" . $age . "', '" . $address . "', '" . $img_ref . "', '" . $date . "');";

        $sqlIns .= "INSERT INTO images (img_code, image) VALUES $sqlValues";

        $sqlIns = rtrim($sqlIns, ",");

        $result = mysqli_multi_query($con, $sqlIns);

        if ($result) {

            header("location: home.php");
        }
    }


    // Check input errors before inserting in database
    if (empty($name_err) && empty($address_err) && empty($age_err)) {
        // Prepare an insert statement
        $add = "INSERT INTO sample (name, age, address) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($con, $add)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_age, $param_address);

            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_age = $age;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: home.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close conection
    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <style>
        .wrapper-fluid {
            width: 900px;
            margin: 0 auto;
            margin-top: 3%;
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

        .flex-container {
            display: flex;
            column-gap: 20px;
            float: right;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group {
            padding: 15px;
        }
    </style>

</head>

<body class="bg">
    <div class="wrapper-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-3">Create New Record</h2>
                    <p><em>Please fill this form and submit to add new record to the database.</em></p>
                    <div id="content">
                        <form method="POST" id="form" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" placeholder="" value="<?php echo $name; ?>">
                                <span class="invalid-feedback"><?php echo $name_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Age</label>
                                <input type="text" name="age" class="form-control <?php echo (!empty($age_err)) ? 'is-invalid' : ''; ?>" placeholder="" value="<?php echo $age; ?>">
                                <span class="invalid-feedback"><?php echo $age_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" placeholder="" value="<?php echo $address; ?>">
                                <span class="invalid-feedback"><?php echo $address_err; ?></span>
                            </div>

                            <div class="form-group">
                                <input type="file" class="form-control" name=" upload[]" multiple>
                                <span class="invalid-feedback"><?php echo $file_error; ?></span>
                            </div>
                            <span class="flex-container">
                                <a href="home.php" class="btn btn-secondary">Cancel</a>
                                <input type="submit" class="btn btn-success" value="Submit">
                            </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>