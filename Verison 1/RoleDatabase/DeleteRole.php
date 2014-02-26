<!DOCTYPE html>
<html>
<head>
<title>Delete Role</title>

<style>
#deleteRole-Dialog{
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

<!-- This page recieves data from the Role Database web page and deletes the role from the database. --!>
<body>

	<div id="deleteRole-Dialog">

<?php

// Connect to mySQL database
$con=mysqli_connect("localhost", "crew", "crew123", "arclight");

// Check connection
if (mysqli_connect_errno())
{
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// We use the role's title to estabilish its ID number...  then we reference this number to delete the appropriate records or tables.
$title = $_POST[selectedName];
$result = mysqli_query($con, 'select * from roleList where roleTitle="' . $title . '"');
$row = mysqli_fetch_array($result);
$roleID = $row[0];

// Now that the ID is stored in roleID, we start deleting things.
mysqli_query($con,'delete from roleList where roleID=' . $roleID);
mysqli_query($con,'delete from roleTimings where roleID=' . $roleID);
mysqli_query($con,'delete from roleStaffingGuide where roleID=' . $roleID);
mysqli_query($con,'delete from pins where roleID=' . $roleID);

// Now we need to update the roleIDs in all tables to reflect the removed role.
// Get the largest roleID.
$result = mysqli_query($con, 'select * from roleList order by roleID desc');
$row = mysqli_fetch_array(result);
$maxRoleID = $row[0];

for ($i = $roleID + 1; $i <= $maxRoleID; $i++)
{
	$newI = $i - 1;
	mysqli_query($con, 'update roleList set roleID=' . $newI . ' where roleID=' . $i);
	mysqli_query($con, 'update roleTimings set roleID=' . $newI . ' where roleID=' . $i);
	mysqli_query($con, 'update roleStaffingGuide set roleID=' . $newI . ' where roleID=' . $i);
	mysqli_query($con, 'update pins set roleID=' . $newI . ' where roleID=' . $i);
}


echo "<p> Role successfully deleted." . '</p><br><br>';

mysqli_close($con);

?>

<a href="RoleDatabaseHome.php"><button type="button">Continue</button></a>
</div>

</body>

</html>