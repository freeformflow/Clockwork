<!DOCTYPE html>
<html>
<head>
<title>Crew Database</title>


<style>
#crewRoster {
		overflow-y: scroll;
		overflow-x: hidden;
		height: 350px;
		width: 250px;
		list-style-type: none;
		border: 3px solid black;
		padding-left: 5px;
		padding-top: 5px;
}

#AddCrewDialog{
	visibility: hidden;
	position: absolute;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	text-align: center;
	z-index: 100;
	background-color: rgba(0,0,0,0.5);	
}

#DeleteCrewDialog{
	visibility: hidden;
	position: absolute;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	text-align: center;
	z-index: 100;
	background-color: rgba(0,0,0,0.5);	
}

#AvailabilityDialog{
	visibility: hidden;
	position: absolute;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	text-align: center;
	z-index: 100;
	background-color: rgba(0,0,0,0.5);	
}

#CompetencyDialog{
	visibility: hidden;
	position: absolute;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	text-align: center;
	z-index: 100;
	background-color: rgba(0,0,0,0.5);	
}

	
#dialogContent{
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
		margin:20px;
	}
	
#roster{
			float:left;
	   }
	   
#databaseAccess{
					float:left;
					padding-left:30px;
					padding-top:50px;
				}
				


	
</style>

<script>
	function AddCrewMember()
	{
		document.getElementById("AddCrewDialog").style.visibility = "visible";
	}
	
	function DeleteCrewMember()
	{
		document.getElementById("DeleteCrewDialog").style.visibility = "visible";
	}
	
	function SetAvailability()
	{
		document.getElementById("AvailabilityDialog").style.visibility = "visible";
	}
	
	function SetCompetency()
	{
		document.getElementById("CompetencyDialog").style.visibility = "visible";
	}
	
	function AddCrewValidation()
	{
		// This function checks the list of current crew members and rejects attempts to add two crew members with identical first AND last names.
		
		var roster = document.getElementById("crewRoster").getElementsByTagName("li");
		var firstName = document.forms["addCrew"]["firstName"].value;
		var lastName = document.forms["addCrew"]["lastName"].value;
		
		// As we iterate through the names in the roster, we check them against what the User is trying to add.
		
		var testString = lastName + ", " + firstName;
		
		for (var i = 0; i < roster.length; i++)
		{
			if (roster[i].innerHTML == testString)
			{
				alert("Apologies: A crew member with this name already exists.  Please enter a *unique* name for this crew member.");
				return false;
			}
		}		
	}

</script>

</head>

<body>
	<h1>Crew Database</h1>

	<div id="navigationMenu">
		<a href="../HomePage.html">Home</a>  
		<p1>Crew Database</p1>
		<a href="../RoleDatabase/RoleDatabaseHome.php">Role Database</a>
		<a href="../Scheduler/SchedulerHome.php">Scheduler</a>
	</div>


	<br> <br> 
	<p1>This is the main interface for the Crew Database. Crew members can be added or deleted below.  Competencies and availability for existing crew members can also be adjusted  <br> </p1>
	
	<div id="roster">
	<h2> Current Roster </h2>
	<ul id="crewRoster">

	<?php
		// This block of PHP code pulls the current roster from the *crew* table (from database) and displays each member as an item in this list element.
		
		// Connect to mySQL database
		$con=mysqli_connect("localhost", "crew", "crew123", "arclight");

		// Check connection
		if (mysqli_connect_errno())
		{
  			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

		$result = mysqli_query($con,"SELECT * FROM crew order by lastName");	  
	  
		while($row = mysqli_fetch_array($result))
		{
			echo '<li>' . $row['lastName'] . ", " . $row['firstName'] . '</li>';
		}

		mysqli_close($con);
	?>
	
	</ul>
	</div>
	
	<div id="databaseAccess">
		
		<p>Roster Maintenance</p>
		<form>
			<input type="button" value="Add Crew Member" onclick="AddCrewMember()"> 
			<br><br>
			<input type="button" value="Delete Crew Member" onclick="DeleteCrewMember()">
		</form>
		<hr>
		
		<p>Crew Data</p>
		<form>
			<input type="button" value="Set Standard Availability" onclick="SetAvailability()">
			<br><br> 
			<input type="button" value="Set Crew Competency" onclick="SetCompetency()">
		</form>
		</div>


	<div id="AddCrewDialog">
		<div id="dialogContent">
			<h2>Add Crew Member</h2>
			</p> Please enter crew member's name below.<br><br>
			<form name="addCrew" action="AddCrew.php" method="post" onsubmit="return AddCrewValidation()">
				First Name: <input type="text" name="firstName"><br>
				Last Name: <input type="text" name="lastName"><br><br>
				<a href="CrewDatabaseHome.php"> <input type="button" value="Cancel"></a>
				<input type="submit" value="Add Crew Member">
			</form>
		</div>
	</div>
	
	<div id="DeleteCrewDialog">
		<div id="dialogContent">
			<h2>Delete Crew Member</h2>
			</p> Please select the crew member's name.<br><br>
			<form action="DeleteCrew.php" method="post">
				<select id="crewSelector" name="selectedName">
					<script>
						var crew = document.getElementById("crewRoster")
							.getElementsByTagName("li");
						var name;
						
						//Setup the select box properties
						var selector = document.getElementById("crewSelector");

						for(var i = 0; i < crew.length; i++)
						{
							name = crew[i].innerHTML;
							selector.options[selector.length] = new Option(name, name);
						}
					</script>
				</select>
				 <br><br><br>
				<a href="CrewDatabaseHome.php"><input type="button" value="Cancel"></a>
				<input type="submit" value="Delete Crew Member">
			</form>
		</div>
	</div>
	
		<div id="AvailabilityDialog">
		<div id="dialogContent">
			<h2>Adjust Crew Availability </h2>
			</p> Please select the crew member's name below.<br><br>
			<form action="AdjustCrewAvailability.php" method="post">
				<select id="crewSelectorAvail" name="selectedName">
					<script>
						var crew = document.getElementById("crewRoster")
							.getElementsByTagName("li");
						var name;
						
						//Setup the select box properties
						var selectorAvail = document.getElementById("crewSelectorAvail");

						for(var i = 0; i < crew.length; i++)
						{
							name = crew[i].innerHTML;
							selectorAvail.options[selectorAvail.length] = new Option(name, name);
						}
					</script>
				</select>

				
				<div style="clear:both">
				<br><br>
				<a href="CrewDatabaseHome.php"><input type="button" value="Cancel"></a>
				<input type="submit" value="Next">
				</div>
			</form>
		</div>
	</div>
	
			<div id="CompetencyDialog">
		<div id="dialogContent">
			<h2>Adjust Crew Competency </h2>
			</p> Please select the crew member's name below.<br><br>
			<form action="AdjustCrewCentricCompetencies.php" method="post">
				<select id="crewSelectorComp" name="selectedName">
					<script>
						var crew = document.getElementById("crewRoster")
							.getElementsByTagName("li");
						var name;
						
						//Setup the select box properties
						var selector = document.getElementById("crewSelectorComp");

						for(var i = 0; i < crew.length; i++)
						{
							name = crew[i].innerHTML;
							selector.options[selector.length] = new Option(name, name);
						}
					</script>
				</select>

				
				<div style="clear:both">
				<br><br>
				<a href="CrewDatabaseHome.php"><input type="button" value="Cancel"></a>
				<input type="submit" value="Next">
				</div>
			</form>
		</div>
	</div>

</body>

</html>