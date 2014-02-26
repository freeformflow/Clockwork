<!DOCTYPE html>
<html>
<head>
<title>Availability Submitted</title>

<style>
#dialog{
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

<!-- This page recieves data from the AdjustCrewAvailability web page and submits the data to the database. --!>
<body>

	<div id="dialog">

<?php

// Connect to mySQL database
$con=mysqli_connect("localhost", "crew", "crew123", "arclight");

// Check connection
if (mysqli_connect_errno())
{
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// The crew member's ID number is passed to this page via POST. We reference this number to update their record in the crewAvailability table.
$crewID = $_POST[crewID];

// Grab all the values set by the User on the AdjustCrewAvailability page. 
$record[0] = $_POST[fridayType]; 
$record[1] = $_POST[fridayStart]; 
$record[2] = $_POST[fridayEnd];

$record[3] = $_POST[saturdayType]; 
$record[4] = $_POST[saturdayStart]; 
$record[5] = $_POST[saturdayEnd];

$record[6] = $_POST[sundayType]; 
$record[7] = $_POST[sundayStart]; 
$record[8] = $_POST[sundayEnd];

$record[9] = $_POST[mondayType]; 
$record[10] = $_POST[mondayStart]; 
$record[11] = $_POST[mondayEnd];

$record[12] = $_POST[tuesdayType]; 
$record[13] = $_POST[tuesdayStart]; 
$record[14] = $_POST[tuesdayEnd];

$record[15] = $_POST[wednesdayType]; 
$record[16] = $_POST[wednesdayStart]; 
$record[17] = $_POST[wednesdayEnd];

$record[18] = $_POST[thursdayType]; 
$record[19] = $_POST[thursdayStart]; 
$record[20] = $_POST[thursdayEnd];

// Now, make a call to mySQL and update the availability record.

mysqli_query($con, 'update crewAvailability set friType=' . $record[0] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set friStart=' . $record[1] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set friEnd=' . $record[2] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set satType=' . $record[3] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set satStart=' . $record[4] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set satEnd=' . $record[5] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set sunType=' . $record[6] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set sunStart=' . $record[7] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set sunEnd=' . $record[8] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set monType=' . $record[9] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set monStart=' . $record[10] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set monEnd=' . $record[11] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set tueType=' . $record[12] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set tueStart=' . $record[13] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set tueEnd=' . $record[14] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set wedType=' . $record[15] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set wedStart=' . $record[16] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set wedEnd=' . $record[17] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set thuType=' . $record[18] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set thuStart=' . $record[19] . ' where employeeID='. $crewID);
mysqli_query($con, 'update crewAvailability set thuEnd=' . $record[20] . ' where employeeID='. $crewID);



echo "<p> Crew availability successfully updated." . '</p><br><br>';

mysqli_close($con);

?>

<a href="CrewDatabaseHome.php"><button type="button">Continue</button></a>
</div>

</body>

</html>