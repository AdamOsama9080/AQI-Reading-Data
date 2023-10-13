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
        $select = "SELECT * FROM `sign_up` WHERE `Email`='$Email'";
        $result = mysqli_query($conn, $select);
        if ($result) {
            if (mysqli_num_rows($result) === 1) {
                $verificationCode = rand(100000, 999999);
                $update = "UPDATE `sign_up` SET `VerifyCode`='$verificationCode' WHERE `Email`='$Email'";
                $updateResult = mysqli_query($conn, $update);
                if ($updateResult) {
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
                        header("Content-Type: application/json");
                        http_response_code(400);
                        echo json_encode(array("Code" => 400, "message" => "Sign up failed. Email could not be sent. Mailer Error: " . $mail->ErrorInfo, "data" => null), JSON_PRETTY_PRINT);
                    } else {
                        header("Content-Type: application/json");
                        http_response_code(200);
                        echo json_encode(array("Code" => 200, "message" => "Sign up was successful and verification email was sent.", "data" => null), JSON_PRETTY_PRINT);
                    }
                } else {
                    header("Content-Type: JSON");
                    http_response_code(500);
                    $response = array(
                        "Code" => 500,
                        "message" => "Query error: " . mysqli_error($conn),
                        "data" => null
                    );
                    echo json_encode($response,JSON_PRETTY_PRINT);
                }
            } else {
                header("Content-Type: JSON");
                http_response_code(404);
                $response = array(
                    "Code" => 404,
                    "message" => "User not found!",
                    "data" => null
                );
                echo json_encode($response,JSON_PRETTY_PRINT);
            }
        } else {
            header("Content-Type: JSON");
            http_response_code(500);
            $response = array(
                "Code" => 500,
                "message" => "Query error: " . mysqli_error($conn),
                "data" => null
            );
            echo json_encode($response,JSON_PRETTY_PRINT);
        }
        mysqli_free_result($result);
    }
    mysqli_close($conn);
}
?>
