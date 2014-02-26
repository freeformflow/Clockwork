<!DOCTYPE html>
<html>
<head>
<title>Competencies Submitted</title>


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

<script>
</script>

</head>


<!-- This page recieves data from the AdjustCrewCentricCompetencies web page and submits the data to the database. --!>
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
	
	// Determine the employeeID
	$employeeID = $_POST[employeeID];
		
	// Delete all competencies from *pins* that belong to this crew member.
	mysqli_query($con, 'delete from pins where employeeID="' . $employeeID . '"');
	
	// Now, plant the user's input values...  The roles are stored as text string currently, but we need to identify them based on their roleID.
	$greenList = $_POST[greenList];
	$greenList = explode(";", $greenList);
	$specCount = count($greenList); 
	
	for ($i = 1; $i < $specCount; $i++)
	{	
		// For each role, we need to get its roleID.
		$role = $greenList[$i];
		$result = mysqli_query($con, 'select roleID from roleList where roleTitle="' . $role . '"');
		$roleID = mysqli_fetch_array($result);
		
		// Now, add the competency into the database.
		mysqli_query($con, "insert into pins values ($roleID[0], $employeeID, 2)");
	}
	
	// Do the same for the blue pins.
	$blueList = $_POST[blueList];
	$blueList = explode(";", $blueList);
	$specCount = count($blueList); 
	
	for ($i = 1; $i < $specCount; $i++)
	{	
		// For each role, we need to get its roleID.
		$role = $blueList[$i];
		$result = mysqli_query($con, 'select roleID from roleList where roleTitle="' . $role . '"');
		$roleID = mysqli_fetch_array($result);
		
		// Now, add the competency into the database.
		mysqli_query($con, "insert into pins values ($roleID[0], $employeeID, 3)");
	}
	
	
	echo "<p> Crew Competencies for this crew member have been successfully updated." . '</p><br>';

	mysqli_close($con);

?>

<a href="CrewDatabaseHome.php"><button type="button">Continue</button></a>
	</div>
</body>
</html>
