<?php
// $servername = 'localhost';
// $username = 'root';
// $password = '';
// $dbname = 'college';

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
        $Password = mysqli_real_escape_string($conn, $_POST['Password']);
        $hashedPassword = hash('sha512', $Password);

        // $select = "SELECT * FROM `test_one` WHERE `Email`='$Email' AND `Password`='$Password'";
        // $select = "SELECT * FROM `sign_up` WHERE `Email`='$Email' AND `Password`='$hashedPassword'";
        $select = "SELECT * FROM `sign_up` WHERE `Email`='$Email' AND `Password`='$hashedPassword' AND `active` = 1";

        $result = mysqli_query($conn, $select);
        if ($result) {
            if (mysqli_num_rows($result) === 1) {
                //echo 'hello';
                $database = "SELECT * FROM `sign_up` WHERE `Email`='$Email' AND `Password`='$hashedPassword'";
                $userinfo = mysqli_query($conn, $database);
                if ($userinfo) {
                    header("Content-Type: JSON");
                    $rows = mysqli_fetch_assoc($userinfo);
                    // echo "Hello".$rows ['FirstName'];
                    $repo['firstname'] = $rows['FirstName'];
                    $repo["lastname"] = $rows["LastName"];
                    $repo ["email"] = $rows ["Email"];
                    $repo ["phone"] = $rows ["Phone"];
                    $repo ["gender"] = $rows ["Gender"];
                    $repo ["birthday"] = $rows ["Birthday"];
                    $repo ["notification"] = $rows["notification_one"];
                    $repo ["dateNotification"] = $rows["notification_date"];

                    //echo json_encode($repo, JSON_PRETTY_PRINT);
                    header("Content-Type: JSON");
                    http_response_code(200);
                    echo json_encode(array(
                        "Code" => 200,
                        "message" => "Authorization has been accepted for this request.",
                        "data" => $repo
                    ), JSON_PRETTY_PRINT);
                }
            } else {
                // echo "Incorrect username or password";
                http_response_code(401);
                echo json_encode(array(
                    "Code" => 401,
                    "message" => "this email is not active.",
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
