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

// Query the database for the last two records
$sql = "SELECT id, Date, Time, NO2, CO, O3, PM10, PM25, SO2, Max, CriticalValue, Category, Note
        FROM air_quality_api
        ORDER BY id DESC
        LIMIT 2";

$result = mysqli_query($conn, $sql);

// If there are at least two rows in the result, compare the values
if (mysqli_num_rows($result) >= 2) {
    $currentRow = mysqli_fetch_assoc($result);
    $previousRow = mysqli_fetch_assoc($result);

    // Compare each pollutant value
    $increaseData = array(
        "NO2" => $currentRow["NO2"] > $previousRow["NO2"],
        "CO" => $currentRow["CO"] > $previousRow["CO"],
        "O3" => $currentRow["O3"] > $previousRow["O3"],
        "PM10" => $currentRow["PM10"] > $previousRow["PM10"],
        "PM25" => $currentRow["PM25"] > $previousRow["PM25"],
        "SO2" => $currentRow["SO2"] > $previousRow["SO2"]
    );

    $response = array(
        "Code" => 200,
        "message" => "Data fetched successfully.",
        "data" => array(
            "id" => $currentRow["id"],
            "Date" => $currentRow["Date"],
            "Time" => $currentRow["Time"],
            "NO2" => $currentRow["NO2"],
            "CO" => $currentRow["CO"],
            "O3" => $currentRow["O3"],
            "PM10" => $currentRow["PM10"],
            "PM25" => $currentRow["PM25"],
            "SO2" => $currentRow["SO2"],
            "Max" => $currentRow["Max"],
            "CriticalValue" => $currentRow["CriticalValue"],
            "Category" => $currentRow["Category"],
            "Note" => $currentRow["Note"],
            "IncreaseData" => $increaseData
        )
    );
} else {
    // If no rows were found or there is only one row, return an error message
    $response = array(
        "Code" => 404,
        "message" => "Insufficient data to compare.",
        "data" => "None"
    );
}

// Convert the data to JSON and output it
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);

mysqli_close($conn);
?>
