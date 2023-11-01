<?php
    require_once("../includes/config.php");
    error_reporting(0);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");
    header("Content-Type: application/json");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


    $requestMethod = $_SERVER["REQUEST_METHOD"];

    if($requestMethod == "GET"){
        global $conn;
        $query = "SELECT COUNT(*) FROM employees";
        $result = mysqli_query($conn, $query);

    }
    else{
        $data = [
            "status" => 405,
            "message" => $requestMethod . " Method Not Allowed",
        ];

        header("HTTP/1.1 405 Method Not Allowed");
        echo json_encode($data);
    }



?>