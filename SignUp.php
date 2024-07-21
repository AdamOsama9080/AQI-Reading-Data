<?php
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';
require 'OAuthTokenProvider.php';
require 'POP3.php';

$FirstName = $_POST['FirstName'];
$LastName = $_POST['LastName'];
$Email = $_POST['Email'];
$Phone = $_POST['Phone'];
$Password = $_POST['Password'];
$Birthday = $_POST['Birthday'];
$Gender = $_POST["Gender"];
$newEmail = $_POST['Email'];
$newPhone = $_POST['Phone'];

$servername = "MYSQL5048.site4now.net";
// REPLACE with your Database name
$dbname = "db_a9cda7_airobse";
// REPLACE with Database user
$username = "a9cda7_airobse";
// REPLACE with Database user password
$passwordd = "AirObserver@123";

$connection = mysqli_connect($servername, $username, $passwordd, $dbname);

if ($connection->connect_error) {
    die("Connection Failed : " . $connection->connect_error);
} else {
    $hashedPassword = hash('SHA512', $Password);
    $stmt = $connection->prepare("SELECT * FROM `sign_up` WHERE (`Email` = ? OR `Phone` = ?) And `active` = 1");
    $stmt->bind_param("ss", $Email, $Phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Content-Type: application/json");
        http_response_code(400);
        echo json_encode(array("Code" => 400, "message" => "Sign up failed. Email or phone number is already in use.", "data" => "None"), JSON_PRETTY_PRINT);
    } else {
        $verificationCode = rand(100000, 999999);
    
        $stmt = $connection->prepare("SELECT * FROM `sign_up` WHERE (`Email` = ? OR `Phone` = ?) AND `active` = 0");
        $stmt->bind_param("ss", $Email, $Phone);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $stmt = $connection->prepare("UPDATE `sign_up` SET `FirstName`=?, `LastName`=?, `Email`=?, `Phone`=?, `Password`=?, `Birthday`=?, `VerifyCode`=?, `Gender`=? WHERE (`Email` = ? OR `Phone` = ?) AND `active` = 0");
            $stmt->bind_param("sssissssss", $FirstName, $LastName, $Email, $Phone, $hashedPassword, $Birthday, $verificationCode, $Gender, $Email, $Phone);
            $execval = $stmt->execute();
        } else {
            $stmt = $connection->prepare("INSERT INTO `sign_up`(`FirstName`, `LastName`, `Email`, `Phone`, `Password`, `Birthday`, `VerifyCode`, `Gender`) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssissss", $FirstName, $LastName, $Email, $Phone, $hashedPassword, $Birthday, $verificationCode, $Gender);
            $execval = $stmt->execute();
        }
    
            $to = $Email;
            $sender = 'khalilkapo15@gmail.com';
            $subject = "Email Verification Of Air Quality Application";
            $message = "Hello $FirstName $LastName,\n\nThank you for signing up for the Air Quality application. To verify your account, please enter the 6-digit code: $verificationCode.\n\nIf you did not sign up for this application, please ignore this email.\n\nBest regards,\nThe Air Quality Team";
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
                echo json_encode(array("Code" => 400, "message" => "Sign up failed. Email could not be sent. Mailer Error: " . $mail->ErrorInfo, "data" => "None"), JSON_PRETTY_PRINT);
            } else {
            header("Content-Type: application/json");
            http_response_code(200);
            echo json_encode(array("Code" => 200, "message" => "Sign up was successful and verification email was sent.", "data" => "None"), JSON_PRETTY_PRINT);
        }

    $stmt->close();
    }
    $connection->close();

}
?>
