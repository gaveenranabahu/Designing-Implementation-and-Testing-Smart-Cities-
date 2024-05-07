<?php
    $host = 'localhost';
    $dbName2 = 'u607713813_traffic';
    $username2 = 'u607713813_porsgrunn';
    $password2 = 'Porsgrunn@1064';
    
    // Create connection
    $conn = new mysqli($host, $username2, $password2, $dbname2);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT DATE(timestamp) AS date,
    place, 
    SUM(car_count_outgoing) AS total_car_count_outgoing,
    SUM(truck_count_outgoing) AS total_truck_count_outgoing,
    SUM(bus_count_outgoing) AS total_bus_count_outgoing
    FROM vehicle_counts
    WHERE timestamp >= DATE_SUB(CURDATE(), INTERVAL 3 DAY)
    GROUP BY date, place
    ORDER BY date";
    $result = $conn->query($sql);
    
    ?>
    <h2>Parking Data</h2>
    <table>
        <tr>
            <th>Timestamp</th>
            <th>Car Count</th>
            <th>Truck Count</th>
            <th>Bus Count</th>
        </tr>
        <?php foreach ($result as $row) { ?>
            <tr>
                <td><?php echo $row['timestamp ']; ?></td>
                <td><?php echo $row['car_count_outgoing']; ?></td>
                <td><?php echo $row['truck_count_outgoing']; ?></td>
                <td><?php echo $row['bus_count_outgoing']; ?></td>
            </tr>
        <?php } ?>
