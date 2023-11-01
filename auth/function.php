<?php

require_once("../includes/config.php");

 function error422($message){
        $data = [
                'status' => 422,
                'message' => $message
        ];

        header("HTTP/1.0 422 Unprocessable Entity");
        return json_encode($data);
    }

    // login user
    function login($user){
        global $conn;
        $name = mysqli_real_escape_string($conn, $user['name']);
        $password  = mysqli_real_escape_string($conn, $user['password']);

        if(empty(trim($name))){
            return error422("Enter username");
        }else if(empty(trim($password))){
            return error422("Enter password");
        }
        else {
            $query = "SELECT * FROM users where name = '$name' and password = '$password' LIMIT 1";
            $result = mysqli_query($conn, $query);

            if($result){
                $res = mysqli_fetch_assoc($result);
                 $data = [
                'status' => 200,
                'message' => "You are logged in",
                'data' => $res['name']
            ];  

                header("HTTP/1.0 200 Logged In");
                return json_encode($data);
            }
            else{
                $data = [
                    'status' => 404,
                    'message' => "User Not Found"
                ];

                header("HTTP/1.0 404 User Not Found");
                return json_encode($data);
            }
        }

    }



?>