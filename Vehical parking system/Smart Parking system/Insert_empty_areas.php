<?php
// Retrieve the data sent via POST request
$emptyAreas = $_POST['empty_areas'];
$time = $_POST['time'];
$place = $_POST['place'];

// Database connection parameters
$servername = 'localhost';
$username = 'u607713813_porsgrunnpark';
$password = 'Porsgrunn@1064';
$dbname = 'u607713813_parking';

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL statement
$sql = "INSERT INTO empty_areas (area_name, time, place) VALUES (?, ?, ?)";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters and execute the statement for each empty area
foreach ($emptyAreas as $area) {
    $stmt->bind_param("sss", $area, $time, $place);
    $stmt->execute();
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>