<?php
$servername = "MYSQL5048.site4now.net";
// REPLACE with your Database name
$dbname = "db_a9cda7_airobse";
// REPLACE with Database user
$username = "a9cda7_airobse";
// REPLACE with Database user password
$password = "AirObserver@123";
$connection = mysqli_connect($servername,$username,$password,$dbname);
$repo = array();
if($connection == true){
    $database = "select * from test_one";
    $data=mysqli_query($connection , $database);
    if($database == true){
        header("Content-Type: JSON");
            $i = 0;
            while($rows = mysqli_fetch_assoc($data)){
                $repo[$i]['NO2'] = $rows ['NO2'];
                $repo[$i]["CO"] = $rows ["CO"];

                $i ++;
            }

        echo json_encode($repo,JSON_PRETTY_PRINT);
    }
}else{
    echo "U R Not InSide DB";
}




// $servername = "mysql8002.site4now.net";

// // REPLACE with your Database name
// $dbname = "db_a90c57_aqi";
// // REPLACE with Database user
// $username = "a90c57_aqi";
// // REPLACE with Database user password
// $password = "AQI@123456";

// // Keep this API Key value to be compatible with the ESP32 code provided in the project page. 
// // If you change this value, the ESP32 sketch needs to match
// $api_key_value = "tPmAT5Ab3j7F9";

// $api_key= $CO= $NO2= "";

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $api_key = test_input($_POST["api_key"]);
//     if($api_key == $api_key_value) {
//         $CO = test_input($_POST["CO"]);
//         $NO2= test_input($_POST["NO2"]);
        
//         // Create connection
//         $conn = new mysqli($servername, $username, $password, $dbname);
//         // Check connection
//         if ($conn->connect_error) {
//             die("Connection failed: " . $conn->connect_error);
//         } 
        
//         $sql = "INSERT INTO test_one(CO, NO2)
//         VALUES ('" . $CO. "', '" . $NO2. "')";
        
//         if ($conn->query($sql) === TRUE) {
//             echo "New record created successfully";
//         } 
//         else {
//             echo "Error: " . $sql . "<br>" . $conn->error;
//         }
    
//         $conn->close();
//     }
//     else {
//         echo "Wrong API Key provided.";
//     }

// }
// else {
//     echo "No data posted with HTTP POST.";
// }

// function test_input($data) {
//     $data = trim($data);
//     $data = stripslashes($data);
//     $data = htmlspecialchars($data);
//     return $data;
// }

?> 