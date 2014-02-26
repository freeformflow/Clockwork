<!DOCTYPE html>
<html>
<head>
<title>Role Specifications Submitted</title>


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


<!-- This page recieves data from the AdjustStaffingParameters web page and submits the data to the database. --!>
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
	
	// Pull the timings for this role.
	$startTime = $_POST[startTime];
	$endTime = $_POST[endTime];
	$setOffset = $_POST[setOffset];
	
	// Now, save the user's input values in the roleTimings table.
	mysqli_query($con, "update roleTimings set startTime=$startTime, endTime=$endTime, setOffset=$setOffset where roleID=$roleID"); 
	
	
	// Delete all staffing parameters from roleStaffingGuide that belong to this role.
	mysqli_query($con, 'delete from roleStaffingGuide where roleID=' . $roleID);
	
	// Now, plant the user's input values.  The specifications are packaged in semicolons, while the individual components of each are packaged with a ", ".
	$specList = $_POST[specList];
	$specList = explode(";", $specList);
	$specCount = count($specList); 
	
	
	for ($i = 1; $i < $specCount; $i++)
	{
		$spec = explode(", ", $specList[$i]);
		mysqli_query($con, "insert into roleStaffingGuide (roleID, numOfCrew, maxAttendance) values ($roleID, $spec[0], $spec[1])");
	}
	
	
	echo "<p> Staffing Specifications for this role have been successfully updated." . '</p><br>';

	mysqli_close($con);

?>

<a href="RoleDatabaseHome.php"><button type="button">Continue</button></a>
	</div>
</body>
</html>
