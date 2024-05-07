<?php

$car_count = $_POST['car_count'];
$free_space = $_POST['free_space'];
$time = $_POST['time'];
$place = $_POST['place'];


$servername = 'localhost';
$username = 'u607713813_porsgrunnpark';
$password = 'Porsgrunn@1064';
$dbname = 'u607713813_parking';


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "INSERT INTO parking_data (car_count, free_space, time, place) VALUES ('$car_count', '$free_space', '$time', '$place')";


if ($conn->query($sql) === TRUE) {
    echo "Data inserted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();
?>