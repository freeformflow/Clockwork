<!DOCTYPE html>
<html>
<head>
<title>Delete Operational Area</title>

<style>
#deleteArea-Dialog{
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

<!-- This page recieves data from the Role Database web page and deletes the area from the database. --!>
<body>

	<div id="deleteArea-Dialog">

<?php

// Connect to mySQL database
$con=mysqli_connect("localhost", "crew", "crew123", "arclight");

// Check connection
if (mysqli_connect_errno())
{
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// We use the Area's title to estabilish its ID number...  then we reference this number to delete the appropriate records or tables.
$title = $_POST[selectedName];
$result = mysqli_query($con, 'select * from areaList where areaTitle="' . $title . '"');
$row = mysqli_fetch_array($result);
$areaID = $row[0];

// Now that the ID is stored in roleID, we start deleting things.
mysqli_query($con,'delete from areaList where areaID=' . $areaID);

// update a role if it's attached to an Operational Area that no longer exists.
mysqli_query($con, 'update roleList set areaID=0 where areaID=' . $areaID);

// Now we need to update the areaIDs in all tables to reflect the removed Operational Area.
// Get the largest areaID.
$result = mysqli_query($con, 'select * from areaList order by areaID desc');
$row = mysqli_fetch_array(result);
$maxAreaID = $row[0];

for ($i = $roleID + 1; $i <= $maxRoleID; $i++)
{
	$newI = $i - 1;
	mysqli_query($con, 'update roleList set areaID=' . $newI . ' where areaID=' . $i);
	mysqli_query($con, 'update areaList set areaID=' . $newI . ' where areaID=' . $i);
}

echo "<p> Operational Area successfully deleted." . '</p><br><br>';

mysqli_close($con);

?>

<a href="RoleDatabaseHome.php"><button type="button">Continue</button></a>
</div>

</body>

</html>