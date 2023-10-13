<?php
$servername = "MYSQL5048.site4now.net";
// REPLACE with your Database name
$dbname = "db_a9cda7_airobse";
// REPLACE with Database user
$username = "a9cda7_airobse";
// REPLACE with Database user password
$password = "AirObserver@123";
$conn = mysqli_connect($servername, $username, $password, $dbname);

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';
require 'OAuthTokenProvider.php';
require 'POP3.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $Email = mysqli_real_escape_string($conn, $_POST['Email']);
        $select = "SELECT * FROM `sign_up` WHERE `Email`='$Email' AND `active` = 1";
        $result = mysqli_query($conn, $select);

        if (mysqli_num_rows($result) === 1) {

            $FirstName = mysqli_real_escape_string($conn, $_POST['FirstName']);
            $LastName = mysqli_real_escape_string($conn, $_POST['LastName']);
            $Phone = mysqli_real_escape_string($conn, $_POST['Phone']);
            $Gender = mysqli_real_escape_string($conn, $_POST['Gender']);
            $Birthday = mysqli_real_escape_string($conn, $_POST['Birthday']);

            $updates = array();
            if (!empty($FirstName)) {
                $updates[] = "FirstName='$FirstName'";
            }
            if (!empty($LastName)) {
                $updates[] = "LastName='$LastName'";
            }
            if (!empty($Phone)) {
                $updates[] = "Phone='$Phone'";
            }
            if (!empty($Gender)) {
                $updates[] = "Gender='$Gender'";
            }
            if (!empty($Birthday)) {
                $updates[] = "Birthday='$Birthday'";
            }
            
            if (!empty($updates)) {
                $updateStr = implode(",", $updates);
                $updateQuery = "UPDATE `sign_up` SET $updateStr WHERE `Email`='$Email'";
                mysqli_query($conn, $updateQuery);
            }

            $to = $Email;
            $sender = 'khalilkapo15@gmail.com';
            $subject = "Email Verification Of Air Quality Application";
            $message = "Hello $FirstName $LastName,\n\nYour data has been successfully updated. Thank you for using our application!\n\nBest regards,\nAir Quality Application Team";

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

            if ($mail->Send()) {
                header("Content-Type: JSON");
                http_response_code(200);
                $response = array(
                    "Code" => 200,
                    "message" => "User information updated successfully and an email has been sent.",
                    "data" => null
                );
                echo json_encode($response, JSON_PRETTY_PRINT);
            } else{
                header("Content-Type: JSON");
                http_response_code(400);
                echo json_encode(array("Code" => 400
