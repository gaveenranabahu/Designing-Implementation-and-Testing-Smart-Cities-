<!DOCTYPE html>
<html>
<head>
    <title>Parking Status</title>
    <style>
        .parking-slot {
            display: inline-block;
            width: 100px;
            height: 50px;
            margin: 10px;
            border: 1px solid black;
            text-align: center;
            line-height: 50px;
            font-weight: bold;
        }

        .vacant {
            background-color: red;
            color: black;
        }

        .occupied {
            background-color: green;
            color: black;
        }

        .car-icon {
            width: 50px;
            height: 50px;
            margin-top: 10px;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Parking Status</h1>
    
    <?php
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

    // Fetch data from the database
    $sql = "SELECT t.*
        FROM empty_areas t
        INNER JOIN (
            SELECT MAX(time) AS max_time, place
            FROM empty_areas
            GROUP BY place
        ) AS latest
        ON t.place = latest.place AND t.time = latest.max_time
        WHERE t.place = 'Downtown'";
    $result = $conn->query($sql);

    // Generate HTML table markup
    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr><th>ID</th><th>Free Slots</th><th>Time</th><th>Place</th></tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['area_name'] . '</td>';
            echo '<td>' . $row['time'] . '</td>';
            echo '<td>' . $row['place'] . '</td>';
            echo '</tr>';
            
            // Generate the parking slots HTML for each row
            $area_name = $row['area_name'];
            $parking_slots = explode(',', $area_name);
            $total_slots = 12;

            // Generate the parking slots HTML
            echo '<div>';
            for ($i = 1; $i <= $total_slots; $i++) {
                if (in_array($i, $parking_slots)) {
                    echo '<div class="parking-slot occupied">';
                    echo 'Slot ' . $i;
                    echo 'Slot ' . $i . ': Vacant';
                    echo '</div>';
                } else {
                    echo '<div class="parking-slot vacant" alt="Occupied">';
                    echo 'Slot ' . $i;
                    echo 'Occupied';
                    echo '</div>';
                    
                }
            }
            
            echo '</div>';
        }
        echo '<br>';
        echo '<h2>Parking Details</h2>';
        echo '</table>';
    } else {
        echo 'No data found.';
    }

    $conn->close();
    ?>
</body>
</html>