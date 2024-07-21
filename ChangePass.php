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
        $Password = $_POST['Password'];

        $select = "SELECT * FROM `sign_up` WHERE `Email`='$Email'";
        $result = mysqli_query($conn, $select);
        if ($result) {
            if (mysqli_num_rows($result) === 1) {
                // echo "Correct OTP \n Welcome!";
                $hashedPassword = hash('SHA512', $Password);

                $update = "UPDATE `sign_up` SET `Password`='$hashedPassword' WHERE `Email`='$Email'";
                $result = mysqli_query($conn, $update);
                if (!$result) {
                    echo "Update failed: " . mysqli_error($conn);
                }

                $to = $Email;
                $sender = 'khalilkapo15@gmail.com';
                $subject = "Email Verification Of Air Quality Application";
                // $message = "Please enter the 6-digit code " . $verificationCode . " sent to you to verify your account on the Air Quality application.";
                $message = "The password for this account has been successfully changed. Thank you for taking the necessary steps to ensure the security of your information. We highly recommend regularly updating your passwords to maintain the highest level of protection.";
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
                    http_response_code(200);  
                    $response = array(
                        "Code" => 200,
                        "message" => "Message could not be sent.",
                        "data" => "None"
                    );
                    echo json_encode($response,JSON_PRETTY_PRINT);
              
                } else {
                    header("Content-Type: JSON");
                    http_response_code(200);
                    $response = array(
                        "Code" => 200,
                        "message" => "Change password is sucsses and message was send",
                        "data" => "None"
                    );
                    echo json_encode($response,JSON_PRETTY_PRINT);
                }
            } else {
                header("Content-Type: JSON");
                http_response_code(404);
                $response = array(
                    "Code" => 404,
                    "message" => "Incorrect Email Please try again.",
                    "data" => "None"
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
        // mysqli_free_result($result);
    }
    mysqli_close($conn);
    // echo json_encode($response);
}
?>
