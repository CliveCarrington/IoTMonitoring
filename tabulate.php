<?php
$servername = "52.48.14.221";
$username = "pi_select";
$password = "S3l3ct10n";
$dbname = "house";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT dtg, houseTotal, solarPower, waterHeating, houseTotal - waterHeating AS netHouse 
	FROM powerReadings
	ORDER BY dtg DESC
	LIMIT 0,200
	";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    echo "dtg , House, Solar, Water, net House usage <br>";
    while($row = $result->fetch_assoc()) {
        echo $row["dtg"]. ",	 " . $row["houseTotal"]. ",	 " . $row["solarPower"]. ",	 " . $row["waterHeating"]. ",      " . $row["netHouse"] . "<br>";
       // echo "dtg: " . $row["dtg"]. " - House: " . $row["houseTotal"]. " " . $row["solarPower"]. " - Water: " . $row["wterHeating"]. "<br>";
    }
} else {
    echo "0 results this time";
}
$conn->close();
?>
