<!DOCTYPE html>
<html>
<head>
<title>Create Role</title>

<style>
#addRole-Dialog{
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

<!-- This page recieves data from the Role Database web page and adds the new role to the database. --!>
<body>

	<div id="addRole-Dialog">

<?php

// Connect to mySQL database
$con=mysqli_connect("localhost", "crew", "crew123", "arclight");

// Check connection
if (mysqli_connect_errno())
{
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// We are going to give this role an ID number.  This number is given based on the order they are added to the database, so we need to determine how many Roles are currently specified.

$result = mysqli_query($con,"SELECT count(roleTitle) FROM roleList");
$row = mysqli_fetch_array($result);
$row[0] += 1;


// We also need to find the ID of the Area the User has selected to group this Role.  If "None" is selected, then we can just store the value 0.
$area = $_POST["addRoleAreaSelector"];
if ($area == "None")
{
	$areaID = 0;
}
else
{
	$result = mysqli_query($con, 'select areaID from areaList where areaTitle="' . $area . '"');
	$areaID = mysqli_fetch_array($result);
	$areaID = $areaID[0];
}


// Add the role title to the roleList table, complete with the ID number.
mysqli_query($con,"insert into roleList (roleID, roleTitle, areaID) 
				values ($row[0], '$_POST[roleTitle]', $areaID)");

// Add a record to the roleTimings table using the ID number.  New roles are assigned times of 0, indicating they match up with the doors opening and closing.
mysqli_query($con, "insert into roleTimings (roleID, startTime, endTime) values ($row[0], 0, 0)");
	

echo "</p> New role successfully created." . '</p><br><br>';

mysqli_close($con);
?>

<a href="RoleDatabaseHome.php"><button type="button">Continue</button></a>
</div>

</body>

</html>