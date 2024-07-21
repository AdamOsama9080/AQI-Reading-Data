<?php

$servername = "MYSQL5048.site4now.net";
// REPLACE with your Database name
$dbname = "db_a9cda7_airobse";
// REPLACE with Database user
$username = "a9cda7_airobse";
// REPLACE with Database user password
$password = "AirObserver@123";
// Create a connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the current date
date_default_timezone_set('Etc/GMT-3');
$currentDate = date("Y-m-d");

// Select data from the excel_files table
$excelQuery = "SELECT date_column FROM excel_files WHERE date_column = '$currentDate'";
$excelResult = mysqli_query($conn, $excelQuery);

// Select data from the pdf_files table
$pdfQuery = "SELECT file_date FROM pdf_files WHERE file_date = '$currentDate'";
$pdfResult = mysqli_query($conn, $pdfQuery);

// Initialize the response array
$response = array();

// Check if both tables have matching dates with the current date
if (mysqli_num_rows($excelResult) > 0 && mysqli_num_rows($pdfResult) > 0) {
    // Loop through each row of the excel_files table
    while ($excelRow = mysqli_fetch_assoc($excelResult)) {
        $excelDate = $excelRow['date_column'];
        
        // Loop through each row of the pdf_files table
        while ($pdfRow = mysqli_fetch_assoc($pdfResult)) {
            $pdfDate = $pdfRow['file_date'];
            
            // Compare the dates from both tables with the current date
            if ($excelDate == $currentDate && $pdfDate == $currentDate) {
                // Perform the action for matching dates (e.g., send notification)
                $message = "Report for the current date has been uploaded. You can download it by click";
                
                // Set the response code, message, and data
                $response["code"] = 200;
                $response["message"] = $message;
                $response["data"] = null;
                
                // Convert the response array to JSON format
                $jsonResponse = json_encode($response);
                
                // Output the JSON response
                echo $jsonResponse;
                
                // Update the notification_one column in the sign_up table
                $updateQuerySignUp = "UPDATE sign_up SET notification_one = '$message', notification_date = '$currentDate'";
                mysqli_query($conn, $updateQuerySignUp);
                
                // Update the notification_one column in the sign_up_gps table
                $updateQuerySignUpGPS = "UPDATE sign_up_gps SET notification_one = '$message', notification_date = '$currentDate'";
                mysqli_query($conn, $updateQuerySignUpGPS);
                
                // Exit the script after sending the response
                exit();
            }
        }
        
        // Reset the pointer of the pdf_files result set back to the beginning
        mysqli_data_seek($pdfResult, 0);
    }
}

// If the condition is not met, send a different response
$response["code"] = 404;
$response["message"] = "No matching dates found for the current date...";
$response["data"] = null;

// Convert the response array to JSON format
$jsonResponse = json_encode($response);

// Output the JSON response
echo $jsonResponse;

// Update the notification_date column in the sign_up table to NULL
$updateNullQuerySignUp = "UPDATE sign_up SET notification_one = NULL, notification_date = NULL";
mysqli_query($conn, $updateNullQuerySignUp);

// Update the notification_date column in the sign_up_gps table to NULL
$updateNullQuerySignUpGPS = "UPDATE sign_up_gps SET notification_one = NULL, notification_date = NULL";
mysqli_query($conn, $updateNullQuerySignUpGPS);

// Close the connection
mysqli_close($conn);
?>
