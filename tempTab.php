<?php

include('access.php');

// Create connection
$conn = new mysqli($hostname, $username, $password, $dB);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT dtg, temperature, sensor_id 
	FROM temperature
	ORDER BY dtg DESC
	LIMIT 0,200
	";
$result = $conn->query($sql);

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
