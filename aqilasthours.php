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
}

// Query the database for the last six records in the pollutant_airquality table
$sql = "SELECT MAX, STR_TO_DATE(pollutant_date, '%m/%d/%Y') AS pollutant_date, hour  FROM pollutant_airquality ORDER BY pollutant_date DESC LIMIT 6";
$result = mysqli_query($conn, $sql);

// Create an empty array to store the transformed data
$data = array();

// If there are rows in the result, transform them into the desired JSON format
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Extract the hour value from the pollutant_hour column
        $hour = $row["hour"];
        
        // Transform the data for this record into the desired format
        $record = array(
            "hour" => $hour,
            "MAX" => $row["MAX"]
        );
        
        // Add the transformed data to the data array
        $data[] = $record;
    }
    
    // Create the response array
    $response = array(
        "Code" => 200,
        "message" => "Data fetched successfully.",
        "data" => $data
    );

} else {
    // If there are no rows in the result, create an error response
    $response = array(
        "Code" => 400,
        "message" => "No data found.",
        "data" => array()
    );
}

// Convert the response to JSON and output it
// Convert the data to JSON and output it
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);

mysqli_close($conn);
?>
