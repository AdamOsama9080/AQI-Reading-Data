<?php
// Connection details
$servername = "MYSQL5048.site4now.net";
// REPLACE with your Database name
$dbname = "db_a9cda7_airobse";
// REPLACE with Database user
$username = "a9cda7_airobse";
// REPLACE with Database user password
$password = "AirObserver@123";

// Get the email from the POST request
$email = $_POST['email'];

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to check if the email exists in the sign_up table
$query = "SELECT * FROM sign_up WHERE Email = '$email'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Email found, update the notification_one column to null
    $updateQuery = "UPDATE sign_up SET notification_one = NULL,notification_date = NULL WHERE Email = '$email'";
    $conn->query($updateQuery);

    // Prepare the JSON response
    $response = array(
        "code" => 200,
        "message" => "The notification delete sucssefully.",
        "data" => null
    );
} else {
    // Email not found
    $response = array(
        "code" => 404,
        "message" => "There are no notifications in this account.",
        "data" => null
    );
}

// Set the response headers
header('Content-Type: application/json');
echo json_encode($response);

// Close the connection
$conn->close();
?>
