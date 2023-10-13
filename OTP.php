<?php
$servername = "MYSQL5048.site4now.net";
// REPLACE with your Database name
$dbname = "db_a9cda7_airobse";
// REPLACE with Database user
$username = "a9cda7_airobse";
// REPLACE with Database user password
$password = "AirObserver@123";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $Email = $_POST['Email'];
        $VerifyCode =  $_POST['VerifyCode'];
        $select = "SELECT * FROM `sign_up` WHERE `Email`='$Email' AND `VerifyCode`='$VerifyCode'";
        $result = mysqli_query($conn, $select);
        if ($result) {
            if (mysqli_num_rows($result) === 1) {
                $update = "UPDATE `sign_up` SET `active` = true WHERE `Email`='$Email' AND `VerifyCode`='$VerifyCode'";
                mysqli_query($conn, $update);
                header("Content-Type: JSON");
                http_response_code(200);
                $response = array(
                    'Code' => 200,
                    'message' => 'Your email is active.',
                    'data' => "None"
                );
                echo json_encode($response,JSON_PRETTY_PRINT);
            } else {
                header("Content-Type: JSON");
                http_response_code(400);
                $response = array(
                    'Code' => 400,
                    'message' => 'The OTP is wrong please Check Again.',
                    'data' => "None"
                );
                echo json_encode($response,JSON_PRETTY_PRINT);
            }
        } else {
            header("Content-Type: JSON");
            http_response_code(500);
            $response = array(
                "Code" => 500,
                "message" => "Query error: " . mysqli_error($conn),
                "data" => "None"
            );
            echo json_encode($response,JSON_PRETTY_PRINT);
        }
        mysqli_free_result($result);
    }
    mysqli_close($conn);
    // echo json_encode($response);
}
?> 
