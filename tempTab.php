<?php

include('access.php');

// Create connection
$conn = new mysqli($hostname, $username, $password, $dB);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Can use DATA_FORMAT(timestamp, format, i.e. "%Y-%M-%d")
//SELECT dtg, temperature, sensor_id 
//	SELECT ROUND(AVG(`temperature`),1) AS temperature, 

// TIMESTAMP(CONCAT(LEFT(`dtg`,15),'0')) AS dtg, sensor_id
// %H is 24 hour clock hours
// %i is minutes
// %S is seconds

$sql = "
	SELECT dtg, temperature, sensor_id 
	FROM temperature
	ORDER BY dtg DESC
	LIMIT 0,200
	";
$result = $conn->query($sql);


echo "<table border='1'>
<tr>
<th>Date</th>
<th>Sensor</th>
<th>Temperature</th>
</tr>";

while($row = mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td>" . $row['dtg'] . "</td>";
echo "<td>" . $row['sensor_id'] . "</td>";
echo "<td>" . $row['temperature'] . "</td>";
echo "</tr>";
}
echo "</table>";

mysqli_close($con);
?>
