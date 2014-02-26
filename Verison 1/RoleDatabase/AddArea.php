<!DOCTYPE html>
<html>
<head>
<title>Add Area</title>

<style>
#AddArea-Dialog{
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

<!-- This page recieves data from the Role Database web page and adds the new Area to the database. --!>
<body>

	<div id="AddArea-Dialog">

<?php

// Connect to mySQL database
$con=mysqli_connect("localhost", "crew", "crew123", "arclight");

// Check connection
if (mysqli_connect_errno())
{
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// We are going to give this Area an ID number.  This number is given based on the order they are added to the database, so we need to determine how many Areas there currently are.

$result = mysqli_query($con,"SELECT count(areaTitle) FROM areaList");
$row = mysqli_fetch_array($result);
$row[0] += 1;

// Add the Area name to the area table, complete with its ID number.
mysqli_query($con,"insert into areaList (areaID, areaTitle) 
				values ($row[0], '$_POST[areaTitle]')");
		
echo "</p> Operational Area successfully added." . '</p><br><br>';

mysqli_close($con);
?>

<a href="RoleDatabaseHome.php"><button type="button">Continue</button></a>
</div>

</body>

</html>