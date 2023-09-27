<?php
// Include config file
include "database.php";

// Define variables and initialize with empty values
$name = $address = $age = "";
$name_err = $address_err = $age_err = "";

// Processing form data when form is submitted
if (isset($_POST["submit"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

    // Validate name
    $input_name = trim($_POST["name"]);
    if (empty($input_name)) {
        $name_err = "Please enter a name.";
    } elseif (!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $name_err = "Please enter a valid name.";
    } else {
        $name = $input_name;
    }

    // Validate address address
    $input_address = trim($_POST["address"]);
    if (empty($input_address)) {
        $address_err = "Please enter an address.";
    } else {
        $address = $input_address;
    }

    // Validate age
    $input_age = trim($_POST["age"]);
    if (empty($input_age)) {
        $age_err = "Please enter the age.";
    } elseif (!ctype_digit($input_age)) {
        $age_err = "Please enter an integer value.";
    } else {
        $age = $input_age;
    }


    $uploadDirectory = "image/";
    $validExtensions = array('jpg', 'jpeg', 'png', 'gif');

    // Get the existing img_ref value from the sample table
    $sql_select = "SELECT img_ref FROM sample WHERE id = '$id'";
    $result_select = $con->query($sql_select);
    if ($result_select->num_rows > 0) {
        $row = $result_select->fetch_assoc();
        $img_ref = $row['img_ref'];
    }

    // Retrieve old images associated with the img_code
    $sql_select = "SELECT image FROM images WHERE img_code = '$img_ref'";
    $result_select = $con->query($sql_select);
    $old_images = array();
    if ($result_select->num_rows > 0) {
        while ($row = $result_select->fetch_assoc()) {
            array_push($old_images, $row['image']);
        }
    }

    // Delete old images from the server and the database
    $sql_delete = "DELETE FROM images WHERE img_code = '$img_ref'";
    $result_delete = mysqli_query($con, $sql_delete);

    // Loop through each file input
    $sql_values = array();
    foreach ($_FILES['upload']['tmp_name'] as $key => $value) {
        $image = $_FILES['upload']['name'][$key];
        $imageTmp = $_FILES['upload']['tmp_name'][$key];
        $imageType = pathinfo($uploadDirectory . $image, PATHINFO_EXTENSION);

        if (!empty($image) && in_array($imageType, $validExtensions)) {

            // Insert new image into database
            array_push($sql_values, "('" . $img_ref . "', '" . $image . "')");

            // Move new image to upload directory
            move_uploaded_file($imageTmp, $uploadDirectory . $image);
        }
    }

    if (count($sql_values) > 0) {
        $sql_insert = "INSERT INTO images (img_code, image) VALUES ";
        $sql_insert .= implode(',', $sql_values);
        $result_insert = $con->query($sql_insert);

        // Delete old images from upload directory
        foreach ($old_images as $old_image) {
            unlink($uploadDirectory . $old_image);
        }
    }


    // $image = $_FILES["upload"]["name"];
    // $tempname = $_FILES["upload"]["tmp_name"];

    // $old = "SELECT * FROM images WHERE id='$id'";
    // $old_img = mysqli_query($con, $old);

    // foreach ($old_img as $old_row) {
    //     if ($image == NULL) {
    //         $image_data = $old_row['image'];
    //     } else {
    //         if ($dir = "image/" . $old_row['image']) {
    //             unlink($dir);
    //             $image_data = $image;
    //         }
    //     }
    // }

    // $new = "UPDATE images SET image='$image_data' WHERE id='$id'";
    // $try = mysqli_query($con, $new);

    // if ($try) {
    //     if ($image == NULL) {
    //     } else {
    //         move_uploaded_file($_FILES["upload"]["tmp_name"], "image/" . $image);
    //     }
    // }



    // Check input errors before inserting in database
    if (empty($name_err) && empty($address_err) && empty($age_err)) {
        // Prepare an update statement
        $sql = "UPDATE sample SET name=?, address=?, age=? WHERE id=?";

        if ($stmt = mysqli_prepare($con, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssii", $param_name, $param_address, $param_age, $param_id);

            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_age = $age;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
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
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM sample WHERE id = ?";
        if ($stmt = mysqli_prepare($con, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    $name = $row["name"];
                    $address = $row["address"];
                    $age = $row["age"];
                } else {
                }
            } else {
            }
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);
    } else {
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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

        .flex-container {
            display: flex;
            column-gap: 20px;
            float: right;
        }

        .form-group {
            padding: 10px;
        }

        .form-group label {
            font-weight: bold;
        }

        .img-fluid {
            width: 200px;
            height: 150px;
            margin-right: 15px;
            margin-bottom: 15px;
        }
    </style>

    <script>

    </script>

</head>

<body class='bg'>
    <div class="wrapper-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-3">Update Record</h2>
                    <p><em>Please edit the input values and submit to update the record.</em></p>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input type="text" name="age" class="form-control <?php echo (!empty($age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $age; ?>">
                            <span class="invalid-feedback"><?php echo $age_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $address; ?>">
                            <span class="invalid-feedback"><?php echo $address_err; ?></span>
                        </div>

                        <div class="form-group">
                            <?php
                            include 'database.php';

                            $sql = "SELECT GROUP_CONCAT(images.image) FROM sample INNER JOIN images ON sample.img_ref = images.img_code WHERE sample.id = $id GROUP BY sample.name ORDER BY sample.name ASC";
                            $results = $con->query($sql);

                            if (!$results) {
                                die('Error executing query: ' . $con->error);
                            }

                            while ($row = $results->fetch_assoc()) {
                                $images = explode(',', $row["GROUP_CONCAT(images.image)"]);
                                $count = count($images);
                                for ($i = 0; $i < $count; $i++) {
                                    echo '<img class="img-fluid" src="' . "image/" . $images[$i] . '" alt="">';
                                }
                            }

                            ?>
                        </div>

                        <div class="form-group">
                            <input type="file" name="upload[]" multiple>
                        </div>

                        <span class="flex-container">
                            <input type="hidden" name="id" value="<?php echo $id; ?>" />
                            <a href="home.php" class="btn btn-secondary">Cancel</a>
                            <input type="submit" name="submit" class="btn btn-success" value="Submit">
                        </span>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>