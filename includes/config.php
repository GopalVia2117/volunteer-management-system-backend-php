<?php
    $host        = "localhost";
    $db_user     = "root";
    $db_password = '';
    $db_name     = "vms_rest_api";

    $conn = mysqli_connect($host, $db_user, $db_password, $db_name);
    if (!$conn) {
        die("Connection failed: ". mysqli_connect_error());
    }

?>
