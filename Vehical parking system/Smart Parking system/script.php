<!DOCTYPE html>
<html>
<head>
    <title>Traffic Counting System</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>


    <?php
    $servername = "localhost";
    $username = "u607713813_porsgrunnpark";
    $password = "Porsgrunn@1064";
    $dbname = "u607713813_parking";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT * FROM parking_data";
    $result = $conn->query($sql);
    
    ?>
    <h2>Parking Data</h2>
    <table>
        <tr>
            <th>Timestamp</th>
            <th>Car Count</th>
            <th>Free Space</th>
            <th>Place</th>
        </tr>
        <?php foreach ($result as $row) { ?>
            <tr>
                <td><?php echo $row['time']; ?></td>
                <td><?php echo $row['car_count']; ?></td>
                <td><?php echo $row['free_space']; ?></td>
                <td><?php echo $row['place']; ?></td>
            </tr>
        <?php } ?>
    </table>
    
</body>
</html>