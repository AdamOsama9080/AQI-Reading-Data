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

// Query the database for the last 24-hour records in the air_quality_api table
$sql = "SELECT MAX, DATE_FORMAT(CONCAT(Date, ' ', Time), '%Y-%m-%d %H:%i') AS DateTime 
        FROM air_quality_api 
        WHERE DATE_SUB(NOW(), INTERVAL 24 HOUR) <= CONCAT(Date, ' ', Time)
        ORDER BY DateTime ASC";

$result = mysqli_query($conn, $sql);

// Create an empty array to store the transformed data
$data = array();

// If there are rows in the result, transform them into the desired JSON format
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Extract the date, time, and AQI values from the row
        $date = date("Y-m-d", strtotime($row["DateTime"]));
        $time = date("H:i", strtotime($row["DateTime"]));
        $aqi = $row["MAX"];
        
        // Transform the data for this record into the desired format
        $record = array(
            "Date" => $date,
            "Time" => $time,
            "AQI" => $aqi
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
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);

mysqli_close($conn);
?>
