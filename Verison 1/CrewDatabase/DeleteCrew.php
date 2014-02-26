<!DOCTYPE html>
<html>
<head>
<title>Delete Crew</title>

<style>
#DeleteCrewMember-Dialog{
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

<!-- This page recieves data from the Crew Database web page and deletes the person from the database. --!>
<body>

	<div id="DeleteCrewMember-Dialog">

<?php

// Connect to mySQL database
$con=mysqli_connect("localhost", "crew", "crew123", "arclight");

// Check connection
if (mysqli_connect_errno())
{
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// We use the crew member's first and last name to estabilish their ID number...  then we reference this number to delete their records in other tables.
$input = $_POST[selectedName];
$input = explode(', ',$input);
$firstName = $input[1];
$lastName = $input[0];
$result = mysqli_query($con, 'select * from crew where firstName="' . $firstName . '" and lastName="' . $lastName . '"');
$row = mysqli_fetch_array($result);
$crewID = $row[0];



// Now that the ID is stored in crewID, we start deleting things.
mysqli_query($con,'delete from crew where employeeID='. $crewID);
mysqli_query($con,'delete from crewAvailability where employeeID=' . $crewID);
mysqli_query($con,'delete from pins where employeeID=' . $crewID);


// Now we need to update the crewIDs in all tables to reflect the removed crew member.
// Get the largest employeeID
$result = mysqli_query($con, 'select * from crew order by employeeID desc');
$row = mysqli_fetch_array($result);
$maxCrewID = $row[0];

for ($i = $crewID + 1; $i <= $maxCrewID; $i++)
{ 
	$newI = $i - 1;
	mysqli_query($con, 'update crew set employeeID=' . $newI . ' where employeeID=' . $i);
	mysqli_query($con, 'update crewAvailability set employeeID=' . $newI . ' where employeeID=' . $i);
	mysqli_query($con, 'update pins set employeeID=' . $newI . ' where employeeID=' . $i);
}

echo "<p> Crew member successfully deleted." . '</p><br><br>';

mysqli_close($con);

?>

<a href="CrewDatabaseHome.php"><button type="button">Continue</button></a>
</div>

</body>

</html>