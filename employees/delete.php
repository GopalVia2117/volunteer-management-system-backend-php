<?php

   error_reporting(0);
   header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Max-Age: 1000");
    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
   include('function.php');

   $requestMethod = $_SERVER['REQUEST_METHOD'];
    if($requestMethod == "OPTIONS"){
      $data = [
                'status' => 200,
                'message' => "OK"
        ];

        header("HTTP/1.0 200 OK");  
        echo json_encode($data);
    }
    else if ($requestMethod == "DELETE") {
        $params = $_GET;
        $deletedEmployee = deleteEmployee($params);
        echo $deletedEmployee;
    }
    else{
        $data = [
                'status' => 405,
                'message' => $requestMethod. " Method Not Allowed"
        ];

        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode($data);
    }

?>