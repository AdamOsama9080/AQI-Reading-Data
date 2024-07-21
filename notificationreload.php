<?php
$servername = "MYSQL5048.site4now.net";
// REPLACE with your Database name
$dbname = "db_a9cda7_airobse";
// REPLACE with Database user
$username = "a9cda7_airobse";
// REPLACE with Database user password
$password = "AirObserver@123";
$conn = mysqli_connect($servername, $username, $password, $dbname);

$repo = array();
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $Email = mysqli_real_escape_string($conn, $_POST['Email']);

        $select = "SELECT `notification_one`, `notification_date` FROM `sign_up` WHERE `Email`='$Email' AND `active` = 1";

        $result = mysqli_query($conn, $select);
        if ($result) {
            if (mysqli_num_rows($result) === 1) {
                $rows = mysqli_fetch_assoc($result);
                $repo["notification"] = $rows["notification_one"];
                $repo["dateNotification"] = $rows["notification_date"];

                header("Content-Type: JSON");
                http_response_code(200);
                echo json_encode(array(
                    "Code" => 200,
                    "message" => "Authorization has been accepted for this request.",
                    "data" => $repo
                ), JSON_PRETTY_PRINT);
            } else {
                http_response_code(401);
                echo json_encode(array(
                    "Code" => 401,
                    "message" => "This email is not active.",
                    "data" => null
                ), JSON_PRETTY_PRINT);
            }
        } else {
            echo "Query error: " . mysqli_error($conn);
        }
        mysqli_free_result($result);
    }
    mysqli_close($conn);
}
?>
