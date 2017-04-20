<?php
$servername = "52.48.14.221";
$username = "pi_select";
$password = "S3l3ct10n";
$dbname = "measurements";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
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
