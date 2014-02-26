<!DOCTYPE html>
<html>
<head>
<title>Add Crew</title>

<style>
#AddCrewMember-Dialog{
	width: 300px;
	margin:100px auto;
	background-color:#add8e6;
	border: 2px solid black;
	padding: 15px;
	text-align: center;
	z-index: 101;
}

body{
		font: 100% "Calibri", sans-serif;
		margin: 50px;
}
</style>
</head>

<!-- This page recieves data from the Crew Database web page and adds the new person to the database. --!>
<body>

	<div id="AddCrewMember-Dialog">

<?php

// Connect to mySQL database
$con=mysqli_connect("localhost", "crew", "crew123", "arclight");

// Check connection
if (mysqli_connect_errno())
{
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// We are going to give this crew member an ID number.  This number is given based on the order they are added to the database, so we need to determine how many crew members there currently are.

$result = mysqli_query($con,"SELECT count(firstName) FROM crew");
$row = mysqli_fetch_array($result);
$row[0] += 1;

// Add the crew member's name to the crew table, complete with their ID number.
mysqli_query($con,"insert into crew (employeeID, firstName, lastName) 
				values ($row[0], '$_POST[firstName]', '$_POST[lastName]')");

// Add a record to the crewAvailability table using the ID number.  New crew members are assigned full availability.
mysqli_query($con, "insert into crewAvailability(employeeID, friType, friStart, friEnd, satType, satStart, satEnd, sunType, sunStart, sunEnd, monType, monStart, monEnd, tueType, tueStart, tueEnd, wedType, wedStart, wedEnd, thuType, thuStart, thuEnd) values ($row[0], 0, 0, 23, 0, 0, 23, 0, 0, 23, 0, 0, 23, 0, 0, 23, 0, 0, 23, 0, 0, 23)");
		
echo "</p> Crew member successfully added." . '</p><br><br>';

mysqli_close($con);
?>

<a href="CrewDatabaseHome.php"><button type="button">Continue</button></a>
</div>

</body>

</html>