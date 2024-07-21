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
} else {
    $from_date = $_GET['from'];
    $to_date = $_GET['to'];

    $sql = "SELECT pollutant_date, hour,PM10, PM25, SO2, CO, O3, NO2, MAX, Critical, Category 
            FROM pollutant_airquality
            WHERE pollutant_date >='$from_date' AND pollutant_date <= '$to_date'";

    $result = mysqli_query($conn, $sql);

    $data = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        $status_code = 200;
        $message = "This is data between two dates";
    } else {
        $status_code = 500;
        $message = "Something went wrong, please try again.";
    }

    $response = array(
        'code' => $status_code,
        'message' => $message,
        'data' => $data
    );

    $json_data = json_encode($response);

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');

    echo $json_data;

    mysqli_close($conn);
}
?>
