<?php
// Retrieve the vehicle counts and other data
$carCountOutgoing = $_POST['car_count_outgoing'];
$truckCountOutgoing = $_POST['truck_count_outgoing'];
$busCountOutgoing = $_POST['bus_count_outgoing'];
$freeSpaceOutgoing = $_POST['free_space_outgoing'];

$carCountIncoming = $_POST['car_count_incoming'];
$truckCountIncoming = $_POST['truck_count_incoming'];
$busCountIncoming = $_POST['bus_count_incoming'];
$freeSpaceIncoming = $_POST['free_space_incoming'];
$place = $_POST['place'];

// Get the current timestamp
$timestamp = date("Y-m-d H:i:s");

// Database connection parameters
$host = 'localhost';
$dbName = 'u607713813_traffic';
$username = 'u607713813_porsgrunn';
$password = 'Porsgrunn@1064';

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Prepare the SQL statement to insert the data into the database
$sql = "INSERT INTO vehicle_counts (timestamp, car_count_outgoing, truck_count_outgoing, bus_count_outgoing, free_space_outgoing, car_count_incoming, truck_count_incoming, bus_count_incoming, free_space_incoming,place) VALUES (:timestamp, :carCountOutgoing, :truckCountOutgoing, :busCountOutgoing, :freeSpaceOutgoing, :carCountIncoming, :truckCountIncoming, :busCountIncoming, :freeSpaceIncoming,:place)";

// Bind the values to the parameters in the SQL statement
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':timestamp', $timestamp);
$stmt->bindParam(':carCountOutgoing', $carCountOutgoing);
$stmt->bindParam(':truckCountOutgoing', $truckCountOutgoing);
$stmt->bindParam(':busCountOutgoing', $busCountOutgoing);
$stmt->bindParam(':freeSpaceOutgoing', $freeSpaceOutgoing);
$stmt->bindParam(':carCountIncoming', $carCountIncoming);
$stmt->bindParam(':truckCountIncoming', $truckCountIncoming);
$stmt->bindParam(':busCountIncoming', $busCountIncoming);
$stmt->bindParam(':freeSpaceIncoming', $freeSpaceIncoming);
$stmt->bindParam(':place', $place);

// Execute the SQL statement
try {
    $stmt->execute();
    echo "Data inserted successfully.";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>