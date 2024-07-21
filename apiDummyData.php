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

// Get the month number from the query parameter
$month = $_GET['month'];

// Query the database for pollutant data for the specified month
$sql = "SELECT * FROM pollutant_airquality WHERE MONTH(Date) = $month";
$result = mysqli_query($conn, $sql);

// Create an empty array to store the transformed data
$data = array();

// If there are rows in the result, transform them into the desired JSON format
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Extract the required data from the row
        $date = $row["Date"];
        $pm10 = $row["PM10"];
        $pm25 = $row["PM25"];
        $so2 = $row["SO2"];
        $co = $row["CO"];
        $o3 = $row["O3"];
        $no2 = $row["NO2"];
        $aqi = $row["AQI"];
        $critical = $row["Critical"];
        $category = $row["Category"];

        // Add the pollutant details to the data array
        $data[] = array(
            "Date" => $date,
            "PM10" => $pm10,
            "PM25" => $pm25,
            "SO2" => $so2,
            "CO" => $co,
            "O3" => $o3,
            "NO2" => $no2,
            "AQI" => $aqi,
            "Critical" => $critical,
            "Category" => $category
        );
    }
}

// Convert the transformed data to JSON
$response = array(
    "Code" => 200,
    "message" => "Data fetched successfully.",
    "data" => array(
        "daysDetails" => $data
    )
);

// Convert the data to JSON and output it
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);

mysqli_close($conn);

?>
