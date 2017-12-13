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
	SELECT * 
	FROM centralHeating
	ORDER BY dtg DESC
	LIMIT 0,200
	";
$result = $conn->query($sql);

echo "<table border='1'>
<tr>
<th>Date</th>
<th>topTank</th>
<th>bottomTank</th>
<th>askForHeat</th>
<th>askForHW</th>
<th>roomStatOn</th>
<th>tankStatOn</th>
<th>boilerOn</th>
</tr>";

while($row = mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td>" . $row['dtg'] . "</td>";
echo "<td>" . $row['topTankTemp'] . "</td>";
echo "<td>" . $row['bottomTankTemp'] . "</td>";
echo "<td>" . $row['askForHeating'] . "</td>";
echo "<td>" . $row['askForHotWater'] . "</td>";
echo "<td>" . $row['roomStatOn'] . "</td>";
echo "<td>" . $row['tankStatOn'] . "</td>";
echo "<td>" . $row['boilerOn'] . "</td>";
echo "</tr>";
}
echo "</table>";

mysqli_close($con);
?>


if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "dtg: " . $row["dtg"]. " - House: " . $row["sensor_id"]. " " . $row["temperature"]. "<br>";
    }
} else {
    echo "0 results this time";
}
$conn->close();
?>
