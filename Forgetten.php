<?php
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';
require 'OAuthTokenProvider.php';
require 'POP3.php';

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
        // $Phone =  $_POST['Phone'];
        // $select = "SELECT * FROM `sign_up` WHERE `Email`='$Email' AND `Phone`='$Phone'";
        $select = "SELECT * FROM `sign_up` WHERE `Email`='$Email'";
        $result =  mysqli_query($conn, $select);
        if ($result) {
            if (mysqli_num_rows($result) === 1) {
                // echo "Correct Phone number \n Welcome!";
        $verificationCode = rand(100000, 999999);
                    
            
        // $update = "UPDATE `sign_up` SET `VerifyCode`='$verificationCode' WHERE `Email`='$Email' AND `Phone`='$Phone'";
        $update = "UPDATE `sign_up` SET `VerifyCode`='$verificationCode' WHERE `Email`='$Email'";
        $result = mysqli_query($conn, $update);
        if (!$result) {
            echo "Update failed: " . mysqli_error($conn);
        }
        

                $to = $Email;
                $sender = 'khalilkapo15@gmail.com';
                $subject = "Email Verification Of Air Quality Application";
                $message = "Thank you for using our Air Quality application. To verify your account, please enter the 6-digit code below:\n\nVerification Code: $verificationCode\n\nIf you did not request this verification, please ignore this email.\n\nBest regards,\n Air Quality Application Team";
            
                $mail = new PHPMailer\PHPMailer\PHPMailer();
                $mail->IsSMTP();
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'ssl';
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 465;
                $mail->Username = $sender;
                $mail->Password = 'zwxntcgqnqxuyedv';
                $mail->SetFrom($sender, 'Sender Name');
                $mail->Subject = $subject;
                $mail->Body = $message;
                $mail->AddAddress($to);
            
                if (!$mail->Send()) {
                    // echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                    header("Content-Type: JSON");
                    http_response_code(400);
                    $response = array(
                        'Code' => 400,
                        'message' => 'Message could not be sent.',
                        'data' => "None"
                    );
                    echo json_encode($response,JSON_PRETTY_PRINT);
                } else {
                    // echo "hello";
                    header("Content-Type: JSON");
                    http_response_code(200);
                    $response = array(
                        'Code' => 200,
                        'message' => 'An new OTP was send.',
                        'data' => "None"
                    );
                    echo json_encode($response,JSON_PRETTY_PRINT);
                }
            } else {
            header("Content-Type: JSON");
            http_response_code(400);
                $response = array(
                    "Code" => 400,
                    "message" => "The email not found please \n write your Email correct.",
                    // "message" => "The email is found please wait your OTP that send to your Email.",
                    "data" => "None"
                );
                echo json_encode($response,JSON_PRETTY_PRINT);
            }
        } 
    }else {
            header("Content-Type: JSON");
            http_response_code(500);
        $response = array(
            "Code" => 500,
            "message" => "Query error: " . mysqli_error($conn),
            "data" => "None"
        );
        echo json_encode($response,JSON_PRETTY_PRINT);
    }
    // mysqli_free_result($result);
    mysqli_close($conn);
    // echo json_encode($response);
}
?>
