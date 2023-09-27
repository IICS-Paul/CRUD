<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CRUD</title>
    <link rel="icon" href="photos/icon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js" integrity="sha512-5SUkiwmm+0AiJEaCiS5nu/ZKPodeuInbQ7CiSrSnUHe11dJpQ8o4J1DU/rw4gxk/O+WBpGYAZbb8e17CDEoESw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        .wrapper-fluid {
            width: 900px;
            margin: 0 auto;
            margin-top: 5%;
            text-align: center;
            background-color: rgba(236, 240, 241, 1);
            padding: 30px 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #000;
            /* background: linear-gradient(rgba(255, 255, 255, .5), rgba(255, 255, 255, .5)); */
        }

        .table th:last-child {
            width: 220px;
        }

        .bot {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
        }

        .bg {
            background-image: url("./image/back.jpg");
            background-size: 100% 100%;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        .modal-content {
            background-image: url("./image/back.jpg");
            background-size: 100% 100%;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        .modal-dialog-centered {
            max-width: 510px;
        }

        .logout {
            margin: 0 auto;
            display: flex;
            justify-content: flex-end;
            padding: 20px;
        }

        .background-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: fixed;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: -1;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('.view').on('click', function() {

                id_emp = $(this).attr('id')
                $.ajax({
                    url: "view.php",
                    method: 'GET',
                    data: {
                        id: id_emp
                    },
                    success: function(result) {
                        $(".modal-body").html(result);
                    }
                });

                $('#viewrecordmodal').modal('show');

            })

        });
    </script>

</head>

<body class="bg">

    <!-- <video class="background-video" autoplay loop muted>
        <source src="photos/vid.mp4" type="video/mp4">
    </video> -->

    <!-- Modal -->
    <div class="modal fade" id="viewrecordmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><b><i class="fa fa-info-circle"></i> User Record</b></h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div> -->
            </div>
        </div>
    </div>

    <div class="logout"><a href="logout.php" class="btn btn-secondary">Logout</a></div>
    <div class="wrapper-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-2 mb-4 clearfix">
                        <h2 class="pull-left">User Details</h2>
                        <a href="add.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New</a>
                    </div>

                    <?php

                    // Include database file
                    require 'database.php';
                    // Attempt select query execution
                    $db = "SELECT * FROM sample";
                    if ($result = mysqli_query($con, $db)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo '<table class="table-responsive">';
                            echo '<table class=" table table-striped">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>#</th>";
                            echo "<th>Name</th>";
                            echo "<th>Age</th>";
                            echo "<th>Address</th>";
                            // echo "<th>Image</th>";
                            echo "<th>Action</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['age'] . "</td>";
                                echo "<td>" . $row['address'] . "</td>";

                                echo '<td = class="bot">';
                                echo '<button id=' . $row['id'] . ' class="btn btn-dark view" data-bs-toggle="modal" data-target="#viewrecordmodal"><i class="fa fa-eye"></i></button>';

                                echo '<a href="update.php?id=' . $row['id'] . '"class="btn btn-primary"><i class="fa fa-edit"></i></a>';
                                echo '<a href="delete.php?id=' . $row['id'] . '"class="btn btn-danger"><i class="fa fa-trash"></i></a>';


                                echo "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else {
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    }
                    // Close connection
                    mysqli_close($con);
                    ?>
                </div>
                <!-- <iframe
              width="100%"
              height="400px"
              src="https://www.youtube.com/embed/JfVOs4VSpmA?autoplay=1&mute=1"
              title="YouTube video player"
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              allowfullscreen
            ></iframe> -->
            </div>
        </div>
    </div>
</body>

</html>