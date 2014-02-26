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


<!-- This page recieves data from the AdjustRoleCentricCompetencies web page and submits the data to the database. --!>
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
	
	// Determine the roleID
	$inputRole = $_POST[inputRole];
	$result = mysqli_query($con, 'select roleID from roleList where roleTitle="' . $inputRole . '"');
	$row = mysqli_fetch_array($result);
	$roleID = $row[0];
	
	
	// Delete all competencies from *pins* that belong to this role.
	mysqli_query($con, 'delete from pins where roleID=' . $roleID);
	
	// Now, plant the user's input values...  Each competency list made of full names seperated by semicolons.  The first and last name are seperated by a common-space.
	$greenList = $_POST[greenList];
	$greenList = explode(";", $greenList);
	$specCount = count($greenList); 
	
	for ($i = 1; $i < $specCount; $i++)
	{	
		// For each crew member, we need to get their employeeID.
		$spec = explode(", ", $greenList[$i]);
		$result = mysqli_query($con, 'select employeeID from crew where firstName="' . $spec[1] . '" and lastName="' . $spec[0] . '"');
		$crewID = mysqli_fetch_array($result);
		
		// Now, add the competency into the database.
		mysqli_query($con, "insert into pins values ($roleID, $crewID[0], 2)");
	}
	
	// Do the same for the blue pins.
	$blueList = $_POST[blueList];
	$blueList = explode(";", $blueList);
	$specCount = count($blueList); 
	
	for ($i = 1; $i < $specCount; $i++)
	{	
		// For each crew member, we need to get their employeeID.
		$spec = explode(", ", $blueList[$i]);
		$result = mysqli_query($con, 'select employeeID from crew where firstName="' . $spec[1] . '" and lastName="' . $spec[0] . '"');
		$crewID = mysqli_fetch_array($result);
		
		// Now, add the competency into the database.
		mysqli_query($con, "insert into pins values ($roleID, $crewID[0], 3)");
	}
	
	
	echo "<p> Crew Competencies for this role have been successfully updated." . '</p><br>';

	mysqli_close($con);

?>

<a href="RoleDatabaseHome.php"><button type="button">Continue</button></a>
	</div>
</body>
</html>
