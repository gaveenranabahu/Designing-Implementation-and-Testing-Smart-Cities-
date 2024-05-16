<?php
// Retrieve the data sent from the Python script
$car_count = $_POST['car_count'];
$free_space = $_POST['free_space'];
$time = $_POST['time'];
$place = $_POST['place'];

// Perform any necessary processing or validation with the received data
// For example, you can store it in a database or perform calculations

// Example: Storing the data in a MySQL database
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
$sql = "INSERT INTO parking_data (car_count, free_space, time, place) VALUES ('$car_count', '$free_space', '$time', '$place')";

// Execute the SQL statement
if ($conn->query($sql) === TRUE) {
    echo "Data inserted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
?>