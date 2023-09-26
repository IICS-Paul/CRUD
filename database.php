<?php
    $mysql_hostname = "localhost";
    $mysql_username = "root";
    $mysql_password = "";
    $mysql_db = "test";

    
    $con = mysqli_connect($mysql_hostname,$mysql_username,$mysql_password,$mysql_db);
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
?>