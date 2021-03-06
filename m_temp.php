<?php

include('access.php');

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$dB",
                               $username, $password);

    /*** The SQL SELECT statement ***/

    $sth1 = $dbh->prepare("
       SELECT ROUND(AVG(`temperature`),1) AS temperature, 
       TIMESTAMP(CONCAT(LEFT(`dtg`,15),'0')) AS date, sensor_id
       FROM `temperature` 
       GROUP BY `sensor_id`,`date`
       ORDER BY `temperature`.`dtg` DESC
       LIMIT 0,900
    ");
    $sth1->execute();

/***
    $sth = $dbh->prepare("
       SELECT ROUND(AVG(`temperature`),1) AS temperature, 
       TIMESTAMP(CONCAT(LEFT(`dtg`,15),'0')) AS date, sensor_id
       FROM `temperature` 
       GROUP BY `sensor_id`,`date`
       ORDER BY `temperature`.`dtg` DESC
       LIMIT 0,100
    ");
    $sth->execute();
***/

    /* Fetch all of the remaining rows in the result set */
    $result1 = $sth1->fetchAll(PDO::FETCH_ASSOC);
/**    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
**/
    /*** close the database connection ***/
    $dbh = null;
    
}
catch(PDOException $e)
    {
        echo $e->getMessage();
    }

$json_data1 = json_encode($result1);     
/**$json_data = json_encode($result);     
**/
?>

<!DOCTYPE html>
<meta charset="utf-8">
<style> /* set the CSS */

body { font: 12px Arial;}

path { 
    stroke: steelblue;
    stroke-width: 2;
    fill: none;
}

.axis path,
.axis line {
    fill: none;
    stroke: grey;
    stroke-width: 1;
    shape-rendering: crispEdges;
}

.legend {
    font-size: 16px;
    font-weight: bold;
    text-anchor: middle;
}

</style>
<body>
<heading>Temperature around the house. v0.3 22Jan17</heading> 
<!-- load the d3.js library -->    
<script src="http://d3js.org/d3.v3.min.js"></script>

<script>

// Set the dimensions of the canvas / graph
var margin = {top: 30, right: 20, bottom: 70, left: 50},
    width = 900 - margin.left - margin.right,
    height = 300 - margin.top - margin.bottom;

// Parse the date / time
var parseDate = d3.time.format("%Y-%m-%d %H:%M:%S").parse;

// Set the ranges
var x = d3.time.scale().range([0, width]);
var y = d3.scale.linear().range([height, 0]);

// Define the axes
var xAxis = d3.svg.axis().scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis().scale(y)
    .orient("left").ticks(5);

var yAxisRight = d3.svg.axis().scale(y)
    .orient("right").ticks(5);

// Define the line
var temperatureline = d3.svg.line()	
    .x(function(d) { return x(d.date); })
    .y(function(d) { return y(d.temperature); });
    
// Adds the svg canvas
var svg = d3.select("body")
    .append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
    .append("g")
        .attr("transform", 
              "translate(" + margin.left + "," + margin.top + ")");

// Functions for adding gridlines

//  gridlines in x axis function
function make_x_gridlines() {		
    return xAxis(x)
        .ticks(5)
}

// gridlines in y axis function
function make_y_gridlines() {		
    return yAxis(y)
        .ticks(5)
}

// Get the data    Start of the Section to duplicate *********
<?php echo "data=".$json_data1.";" ?>

data.forEach(function(d) {
	d.date = parseDate(d.date);
	d.temperature = +d.temperature;
});

// Scale the range of the data
x.domain(d3.extent(data, function(d) { return d.date; }));
y.domain([0, d3.max(data, function(d) { return d.temperature; })]);

// Nest the entries by sensor_id
var dataNest = d3.nest()
	.key(function(d) {return d.sensor_id;})
	.entries(data);

var color = d3.scale.category10();   // set the colour scale

legendSpace = width/dataNest.length; // spacing for the legend


// add the X gridlines
//  svg.append("g")			
//      .attr("class", "grid")
//      .attr("transform", "translate(0," + height + ")")
//      .call(make_x_gridlines()
//          .tickSize(-height)
//          .tickFormat("")
//      )

  // add the Y gridlines
//  svg.append("g")			
//      .attr("class", "grid")
//      .call(make_y_gridlines()
//          .tickSize(-width)
//          .tickFormat("")
//      )

// Loop through each sensor_id / key
dataNest.forEach(function(d,i) { 

	svg.append("path")
		.attr("class", "line")
		.style("stroke", function() { // Add the colours dynamically
			return d.color = color(d.key); })
		.attr("id", 'tag'+d.key.replace(/\s+/g, '')) // assign ID
		.attr("d", temperatureline(d.values));

	// Add the Legend
	svg.append("text")
		.attr("x", (legendSpace/2)+i*legendSpace)  // space legend
		.attr("y", height + (margin.bottom/2)+ 5)
		.attr("class", "legend")    // style the legend
		.style("fill", function() { // Add the colours dynamically
			return d.color = color(d.key); })
		.on("click", function(){
			// Determine if current line is visible 
			var active   = d.active ? false : true,
			newOpacity = active ? 0 : 1; 
			// Hide or show the elements based on the ID
			d3.select("#tag"+d.key.replace(/\s+/g, ''))
				.transition().duration(100) 
				.style("opacity", newOpacity); 
			// Update whether or not the elements are active
			d.active = active;
			})  
		.text(
		    function() {
		        if (d.key == '28-021500397eff') {return "Shed";}
		        if (d.key == '28-04146de659ff') {return "Office";}
		        if (d.key == '28-00043e8defff') {return "Outlet";}
		        else {return d.key;}
		        }); 
});

// Add the X Axis
svg.append("g")
	.attr("class", "x axis")
	.attr("transform", "translate(0," + height + ")")
	.call(xAxis);

// Add the Y Axis
svg.append("g")
	.attr("class", "y axis")
	.call(yAxis);

svg.append("g")
	.attr("class", "y axis")
        .attr("transform", "translate(" + width + " ,0)")
//        .style("fill", "red")		
	.call(yAxisRight);
</script>
</body>
