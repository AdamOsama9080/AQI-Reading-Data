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

// Query the database for pollutant data
$sql = "SELECT * FROM pollutant_airquality";
$result = mysqli_query($conn, $sql);

// Create an empty array to store the transformed data
$data = array();

// If there are rows in the result, transform them into the desired JSON format
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Extract the month and day values from the pollutant_date column
        $month = date('n', strtotime($row["Date"]));
        $day = date('j', strtotime($row["Date"]));
        
        // If there is no data for this month yet, add a new entry to the data array
        if (!array_key_exists($month, $data)) {
            $data[$month] = array(
                "month" => $month,
                "days" => array(),
                "daysDetails" => array()
            );
        }
        
      
            // Add the pollutant data to the appropriate day in the data array
            $data[$month]["days"][] = array(
                "day" => $day,
                "AQI" => $row["AQI"]
            );
            
    
            // Add the pollutant details to the daysDetails array
            $data[$month]["daysDetails"][] = array(
                "Date" => $row["Date"],
                "PM10" => $row["PM10"],
                "SO2" => $row["SO2"],
                "CO" => $row["CO"],
                "O3" => $row["O3"],
                "NO2" => $row["NO2"],
                "AQI" => $row["AQI"],
                "Critical" => $row["Critical"],
                "Category" => $row["Category"]
            );
        
    }
}

// Convert the transformed data to JSON
$response = array(
    "Code" => 200,
    "message" => "Data fetched successfully.",
    "data" => array_values($data)
);

// Convert the data to JSON and output it
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);

mysqli_close($conn);
?>