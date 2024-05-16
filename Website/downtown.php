<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Downtown</title>
<meta name="Generator" content="Serif WebPlus X6">
<meta name="viewport" content="width=580">
<style type="text/css">
body{margin:0;padding:0;}
.Heading-2-P
{
	margin: 0.0px 0.0px 0.0px 0.0px;
	text-align: left;
	font-weight: 400;
	color: #FFF;
	font-size: xx-large;
}
.Body-Text-2-P
{
    margin:0.0px 0.0px 0.0px 0.0px; text-align:left; font-weight:400;
}
.Body-Text-1-P
{
    margin:0.0px 0.0px 0.0px 0.0px; text-align:left; font-weight:400;
}
.Links-P
{
    margin:0.0px 0.0px 0.0px 0.0px; text-align:right; font-weight:400;
}
.Placeholder-C
{
    font-family:"Trebuchet MS", sans-serif; font-weight:700; color:#00a9ec; font-size:18.7px; line-height:1.16em;
}
.Placeholder-C-C0
{
    font-family:"Tahoma", sans-serif; color:#4995c4; font-size:10.7px; line-height:1.60em;
}
.Placeholder-C-C1
{
    font-family:"Tahoma", sans-serif; font-size:10.7px; line-height:1.60em;
}
.Body-Text-1-C
{
    font-family:"Tahoma", sans-serif; font-size:10.7px; line-height:1.60em;
}
.Placeholder-C-C2
{
    font-family:"Tahoma", sans-serif; color:#2c3651; font-size:10.7px; line-height:1.21em;
}
.Heading-1-C
{
    font-family:"Trebuchet MS", sans-serif; font-weight:700; color:#323f67; font-size:19.0px; line-height:1.26em;
}
.Placeholder-C
{
	font-family: "Trebuchet MS", sans-serif;
	font-weight: 700;
	color: #004674;
	font-size: 19.0px;
	line-height: 1.26em;
}
.Placeholder-C-C0
{
    font-family:"Tahoma", sans-serif; color:#1f263e; font-size:11.0px; line-height:1.60em;
}
.Placeholder-C-C1
{
    font-family:"Tahoma", sans-serif; color:#ffffff; font-size:11.0px; line-height:1.60em;
}
.Placeholder-C-C2
{
    font-family:"Tahoma", sans-serif; font-size:11.0px; line-height:1.60em;
}
.Heading-2-C
{
    font-family:"Trebuchet MS", sans-serif; font-weight:700; color:#00a9ec; font-size:19.0px; line-height:1.26em;
}
.Body-Text-1-C
{
    font-family:"Tahoma", sans-serif; font-size:11.0px; line-height:1.60em;
}
.Heading-2-C-C0
{
	font-family: "Trebuchet MS", sans-serif;
	font-weight: 700;
	color: #004674;
	font-size: 32.0px;
	line-height: 1.25em;
}
.parking-slot {
            display: inline-block;
            width: 140px;
            height: 100px;
            margin: 10px;
            border: 1px solid black;
            text-align: center;
            line-height: 100px;
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
<script type="text/javascript" src="wpscripts/jspngfix.js"></script>
<link rel="stylesheet" href="wpscripts/wpstyles.css" type="text/css"><script type="text/javascript">
var blankSrc = "wpscripts/blank.gif";
</script>

</head>

<body text="#000000" style="background:#ffffff url('wpimages/wp110b269a_06.png') repeat-x scroll top center; height:1000px;">
<div style="background-color:transparent;margin-left:auto;margin-right:auto;position:relative;width:1000px;height:1000px;">
  <div id="txt_5" style="position: absolute; left: 23px; top: 92px; width: 400px; height: 108px; overflow: hidden;">
    <h1 class="Heading-2-P">Downtown</h1>
  </div>
  <img src="wpimages/wp0020b4c6_06.png" border="0" width="531" height="1" id="crv_20" alt="" onload="OnLoadPngFix()" style="position:absolute;left:24px;top:312px;">
<div id="txt_66" style="position: absolute; left: 24px; top: 323px; width: 962px; height: 657px; overflow: hidden;">
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
                    echo 'Slot ' . $i . ': Vacant';
                    echo '<br>';
              //        echo 'Vacant';
                    echo '<br>';
                    echo '</div>';


                } else {
                    echo '<div class="parking-slot vacant" alt="Occupied">';
                    echo 'Slot ' . $i . ': Occupied';
                    echo '<br>';
               //       echo 'Occupied';
                    echo '<br>';
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
</div>
</div>
</body>
</html>