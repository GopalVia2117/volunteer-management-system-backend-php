<?php
    require_once("../includes/config.php");
    // get a single employees by id
    function getCustomer($employee){
        global $conn;

        if($employee['id'] == null){
            return error422("Enter your customer id");
        }


       $id = mysqli_real_escape_string($conn, $employee['id']);

        $query = "SELECT * FROM employees WHERE id='$id' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if($result){
            if(mysqli_num_rows($result) == 1){      
                $res = mysqli_fetch_assoc($result);
                $data = [
                    "status" => 200,
                    "message" => "Employee fetched successfully",
                    "data" => $res
                ];

                header("HTTP/1.0 200 OK");
                return json_encode($data);
            }
            else{
                $data = [
                "status" => 404,
                "message" => "Employee not found",
            ];

            header("HTTP/1.0 404 Request Not Found");
            return json_encode($data);
            }  
        }
        else{
            $data = [
                "status" => 500,
                "message" => "Internal Server Error",
            ];

            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }

    }

    // get all customers from the database
    function getCustomers($params){
        global $conn;
    
        if(isset($params['offset']) && isset($params['limit'])){
            $offset = $params['offset'];
            $limit = $params['limit'];
            $sql = "SELECT * FROM employees ORDER BY created_at desc LIMIT $offset, $limit";  
        }
        else{
            $sql = "SELECT * FROM employees ORDER BY created_at desc";  
        }

        $result = mysqli_query($conn, $sql);

        $sql1  = "SELECT COUNT(*) as count FROM employees";
        $result1  = mysqli_query($conn, $sql1);
        if($result && $result1){
            $count = mysqli_fetch_assoc($result1)['count'];

            if(mysqli_num_rows($result) > 0){
                $res = mysqli_fetch_all($result, MYSQLI_ASSOC);
                 $data = [
                'status' => 200,
                'message' => "Employees List Fetched Successfully",
                'totalCount' => $count,
                'data' => $res
            ];

            header("HTTP/1.0 200 OK");
            return json_encode($data);

            }
            else{
                 $data = [
                'status' => 404,
                'message' => "No Employee found"
            ];

            header("HTTP/1.0 404 No Employee found");
            return json_encode($data);
            }
        }else{
            $data = [
                'status' => 500,
                'message' => "Internal Server Error"
            ];

            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
    }



    function error422($message){
        $data = [
                'status' => 422,
                'message' => $message
        ];

        header("HTTP/1.0 422 Unprocessable Entity");
        return json_encode($data);
    }

    // insert an employee into the database
    function insertEmployee($employee){
        global $conn;
        $name = mysqli_real_escape_string($conn, $employee['name']);
        $email  = mysqli_real_escape_string($conn, $employee['email']);
        $phone = mysqli_real_escape_string($conn, $employee['phone']);
        $city = mysqli_real_escape_string($conn, $employee['city']);
        $state = mysqli_real_escape_string($conn, $employee['state']);

        if(empty(trim($name))){
            return error422("Enter your name");
        }else if(empty(trim($email))){
            return error422("Enter your email");
        }else if(empty(trim($phone))){
            return error422("Enter your phone");
        }else if(empty(trim($city))){
            return error422("Enter your city");
        }else if(empty(trim($state))){
            return error422("Enter your state");
        }else{
            $query = "INSERT INTO employees (name, email, phone, city, state) VALUES ('$name', '$email', '$phone', '$city', '$state')";
            $result = mysqli_query($conn, $query);
            $id = mysqli_insert_id($conn);

            $query2 = "SELECT * FROM employees where id='$id' LIMIT 1";
            $result2 = mysqli_query($conn, $query2);

            $res = mysqli_fetch_assoc($result2);

            if($result && $result2){    
                $data = [
                'status' => 201,
                'message' => "Employee Created Successfully",
                'data' => $res
            ];  

                header("HTTP/1.0 201 Created");
                return json_encode($data);
            }
            else{
                $data = [
                    'status' => 500,
                    'message' => "Internal Server Error"
                ];

                header("HTTP/1.0 500 Internal Server Error");
                return json_encode($data);
            }
        }


    }


    function updateEmployee($params, $employee){
        if(!isset($params['id'])){
            return error422("Employee id not found in the url");
        }else if($params['id'] == null){
            return error422("Employee id could not be null");
        }

        $employeeId = $params['id'];
     

        global $conn;
        $id = mysqli_real_escape_string($conn, $employee['id']);
        $name = mysqli_real_escape_string($conn, $employee['name']);
        $email  = mysqli_real_escape_string($conn, $employee['email']);
        $phone = mysqli_real_escape_string($conn, $employee['phone']);
        $city = mysqli_real_escape_string($conn, $employee['city']);
        $state = mysqli_real_escape_string($conn, $employee['state']);

        if(empty(trim($id))){
            return error422("Enter employee id");
        }
        else if(empty(trim($name))){
            return error422("Enter employee name");
        }else if(empty(trim($email))){
            return error422("Enter employee email");
        }else if(empty(trim($phone))){
            return error422("Enter employee phone");
        }else if(empty(trim($city))){
            return error422("Enter employee city");
        }else if(empty(trim($state))){
            return error422("Enter employee state");
        }else if(trim($id) != $employeeId){
            return error422("Employee to be updated does not match");
        }else{
            $query = "UPDATE employees SET name='$name', email='$email', phone='$phone', city='$city', state='$state' WHERE id='$id' LIMIT 1";
            $result = mysqli_query($conn, $query);

            if($result){    
                $data = [
                'status' => 200,
                'message' => "Employee Updated Successfully",
                'data' => $result
            ];

                header("HTTP/1.0 200 OK");
                return json_encode($data);
            }
            else{
                $data = [
                    'status' => 500,
                    'message' => "Internal Server Error"
                ];

                header("HTTP/1.0 500 Internal Server Error");
                return json_encode($data);
            }
        }
    }


    function deleteEmployee($params){
        global $conn;

        if(!isset($params['id'])){
            return error422("Employee id not found");
        }
        else if($params['id'] == null){
            return error422("Employee id could not be null");
        }

        $id = $params['id'];
        $query = "DELETE FROM employees WHERE id='$id'";
        $result = mysqli_query($conn, $query);
        if($result) {
             $data = [
                'status' => 200,
                'message' => "Employee Deleted Successfully",
                'data' => $result
            ];

                header("HTTP/1.0 200 OK");
                return json_encode($data);
        }
        else{
             $data = [
                    'status' => 500,
                    'message' => "Internal Server Error"
                ];

                header("HTTP/1.0 500 Internal Server Error");
                return json_encode($data);
        }
    }


?>