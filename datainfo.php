<?php
$servername = "MYSQL5048.site4now.net";
// REPLACE with your Database name
$dbname = "db_a9cda7_airobse";
// REPLACE with Database user
$username = "a9cda7_airobse";
// REPLACE with Database user password
$password = "AirObserver@123";;
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $Email = mysqli_real_escape_string($conn, $_POST['Email']);
        $select = "SELECT * FROM `sign_up` WHERE `Email`='$Email' AND `active` = 1";
        $result = mysqli_query($conn, $select);

        if ($result && mysqli_num_rows($result) === 1) {
            $rows = mysqli_fetch_assoc($result);

            $response = array(
                "Code" => 200,
                "message" => "Authorization has been accepted for this request.",
                "data" => array(
                    "firstname" => $rows['FirstName'],
                    "lastname" => $rows['LastName'],
                    "email" => $rows['Email'],
                    "phone" => $rows['Phone'],
                    "gender" => $rows['Gender'],
                    "Birthday" =>$rows['Birthday']
                )
            );

            header("Content-Type: application/json");
            http_response_code(200);
            echo json_encode($response, JSON_PRETTY_PRINT);
        } else {
            $response = array(
                "Code" => 200,
                "message" => "No data found. Please try again.",
                "data" => null
            );

            header("Content-Type: application/json");
            http_response_code(200);
            echo json_encode($response, JSON_PRETTY_PRINT);
        }

        mysqli_free_result($result);
    }

    mysqli_close($conn);
}
?>
