<?php
// Retrieve the data sent via POST request
$time = $_POST['time'];
$place = $_POST['place'];
$emptyAreas = $_POST['empty_areas'];

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

// Bind parameters and execute the statement
$stmt->bind_param("sss", $emptyAreas, $time, $place);

if ($stmt->execute()) {
    echo "Data inserted successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>