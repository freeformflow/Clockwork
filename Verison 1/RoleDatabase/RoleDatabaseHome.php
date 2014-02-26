<!DOCTYPE html>
<html>
<head>
<title>Role Database</title>


<style>
#roleRoster {
		overflow-y: scroll;
		overflow-x: hidden;
		height: 350px;
		width: 500px;
		list-style-type: none;
		border: 3px solid black;
		padding-left: 5px;
		padding-top: 5px;
}

#areaRoster {
		overflow-y: scroll;
		overflow-x: hidden;
		height: 350px;
		width: 250px;
		list-style-type: none;
		border: 3px solid black;
		padding-left: 5px;
		padding-top: 5px;
}

#addRoleDialog{
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

#deleteRoleDialog{
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

#parameterDialog{
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

#competenciesDialog{
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


#addAreaDialog{
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

#deleteAreaDialog{
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

	   
#areaPanel{
					float:left;

				}
				
#rolePanel{
					float:left;
					padding-left:30px;
				}

#databaseAccess{
					float:left;
					padding-left:30px;
					padding-top:50px;
				}
				


	
</style>

<script>
	function addRole()
	{
		document.getElementById("addRoleDialog").style.visibility = "visible";
	}
	
	function deleteRole()
	{
		document.getElementById("deleteRoleDialog").style.visibility = "visible";
	}
	
	function setRoleStaffingParameters()
	{
		document.getElementById("parameterDialog").style.visibility = "visible";
	}
	
	function setCrewCompetencies()
	{
		document.getElementById("competenciesDialog").style.visibility = "visible";
	}
	
	function showAddAreaDialog()
	{
		document.getElementById("addAreaDialog").style.visibility = "visible";
	}
	
	function hideAddAreaDialog()
	{
		document.getElementById("addAreaDialog").style.visibility = "hidden";
	}
	
	function showDeleteAreaDialog()
	{
		document.getElementById("deleteAreaDialog").style.visibility = "visible";
	}
	
	function hideDeleteAreaDialog()
	{
		document.getElementById("deleteAreaDialog").style.visibility = "hidden";
	}
	
	
	function populateAreaList(element)
	{
		var selector = document.getElementById(element);
		var source = document.getElementById("areaRoster").getElementsByTagName("li");

		if (selector.length == 1)
		{
			for (var i = 0; i < source.length; i++)
			{
				selector.options[selector.length] = new Option(source[i].innerHTML, source[i].innerHTML);	
			}
		}
	}
	
	function AddRoleValidation()
	{
		// This function checks the list of current roles and rejects attempts to add two roles with identical names.
		var roster = document.getElementById("roleRoster").getElementsByTagName("li");
		var title = document.forms["addRoles"]["roleTitle"].value;
		
		// As we iterate through the names in the roster, we check them against what the User is trying to add.
		
		for (var i = 0; i < roster.length; i++)
		{
			if (roster[i].innerHTML == title)
			{
				alert("Apologies: A role with this name already exists.  Please enter a *unique* name for this role.");
				return false;
			}
		}	
			
	}

</script>

</head>

<body>
	<h1>Role Database</h1>

	<div id="navigationMenu">
		<a href="../HomePage.html">Home</a>
		<a href="../CrewDatabase/CrewDatabaseHome.php">Crew Database</a>  
		<p1>Role Database</p1>
		<a href="../Scheduler/SchedulerHome.php">Scheduler</a>
	</div>


	<br> <br> 
	<p1>This is the main interface for the Role Database. Roles can be added or deleted below.    For an individual Role, its parameters can be specified... including title, staffing guidelines, and start/end timings.  Roles are grouped by their Operational Area for ease of use.  These Areas are also maintained through this interface.<br> </p1>
	<hr>
	
	<div id="areaPanel">
	<p style="font: 150% Calibri;"> Currently Specified Areas </p>
	
	<ul id="areaRoster">
	
	<?php
		// This block of PHP code pulls the current roster from the *areaList* table (from database) and displays each member as an item in this list element.
		// Connect to mySQL database
		$con=mysqli_connect("localhost", "crew", "crew123", "arclight");

		// Check connection
		if (mysqli_connect_errno())
		{
  			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		$result = mysqli_query($con,"SELECT areaTitle FROM areaList order by areaTitle");	  
	  
		while($row = mysqli_fetch_array($result))
		{
			echo '<li>' . $row[0] . '</li>';
		}
	?>
	
	</ul>
	
	<input type="button" value="Create Area" onclick="showAddAreaDialog()"><br>
	<input type="button" value="Delete Area" onclick="showDeleteAreaDialog()"><br>
	</div>
	
	<div id="rolePanel">
	<p style="font: 150% Calibri;">	Currently Specified Roles </p> 
	
	<ul id="roleRoster">

	<?php
		// This block of PHP code pulls the current roster from the *roleList* table (from database) and displays each member as an item in this list element.
		
		$result = mysqli_query($con,"SELECT * FROM roleList order by roleTitle");	  
	  
		while($row = mysqli_fetch_array($result))
		{
			// The value stored in areaID is an ID number reference to the table "areaList"
			$result2 = mysqli_query($con, "select areaTitle from areaList where areaID=" . $row['areaID']);
			$area = mysqli_fetch_array($result2);
			
			echo '<li>' . $row['roleTitle'] . ' -> ' . $area[0] . '</li>';
		}

		mysqli_close($con);
	?>
	
	</ul>
	</div>
	
	<div id="databaseAccess">
		
		<p>Create/Delete Role</p>
		<form>
			<input type="button" value="Create Role" onclick="addRole()"> 
			<br><br>
			<input type="button" value="Delete Role" onclick="deleteRole()">
		</form>
		<hr>
		
		<p>Role Specifications</p>
		<form>
			<input type="button" value="Set Staffing Guide Parameters" onclick="setRoleStaffingParameters()">
			<br><br> 
			<input type="button" value="Set Crew Competencies" onclick="setCrewCompetencies()">
		</form>
	</div>


	<div id="addRoleDialog">
		<div id="dialogContent">
			<h2>Create New Role</h2>
			</p> Please enter the title of this role below.  This role can also be optionally grouped into an area of operations.<br><br>
			<form name="addRoles" action="AddRole.php" method="post" onsubmit="return AddRoleValidation()">
				Title: <input type="text" name="roleTitle"><br><br>
				Operational Area: 
				<select id="addRoleSelector" name="addRoleAreaSelector">
				<option> None </option>
				</select>
				<script> populateAreaList("addRoleSelector"); </script>
				<br>
				<br>
				<a href="RoleDatabaseHome.php"> <input type="button" value="Cancel"></a>
				<input type="submit" value="Create Role">
			</form>
		</div>
	</div>
	
	<div id="deleteRoleDialog">
		<div id="dialogContent">
			<h2>Delete Role</h2>
			</p> Please select the role for deletion.<br><br>
			<form action="DeleteRole.php" method="post">
				<select id="roleSelector" name="selectedName">
					<script>
						var role = document.getElementById("roleRoster")
							.getElementsByTagName("li");
						var step1;
						var step2;
						var name;
						
						//Setup the select box properties
						var selector = document.getElementById("roleSelector");

						for(var i = 0; i < role.length; i++)
						{
							// The main Role display also show the User the Area for each Role.  We need to strip this away and only deliver the Role for deletion.
							step1 = role[i].innerHTML;
							step2 = step1.split(" -&gt");
							name = step2[0];
							selector.options[selector.length] = new Option(name, name);
						}
					</script>
				</select>
				 <br><br><br>
				<a href="RoleDatabaseHome.php"><input type="button" value="Cancel"></a>
				<input type="submit" value="Delete Role">
			</form>
		</div>
	</div>
	
		<div id="parameterDialog">
		<div id="dialogContent">
			<h2>Set Staffing Parameters</h2>
			</p> Please select the role and click "Next" to specify its staffing parameters.<br><br>
			<form action="AdjustStaffingParameters.php" method="post">
				<select id="roleSelectorPara" name="selectedName">
					<script>
						var role = document.getElementById("roleRoster")
							.getElementsByTagName("li");
						var name;
						
						//Setup the select box properties
						var selectorPara = document.getElementById("roleSelectorPara");

						for(var i = 0; i < role.length; i++)
						{
							step1 = role[i].innerHTML;
							step2 = step1.split(" -&gt");
							name = step2[0];
							selectorPara.options[selectorPara.length] = new Option(name, name);
						}
					</script>
				</select>
				 <br><br><br>
				<a href="RoleDatabaseHome.php"><input type="button" value="Cancel"></a>
				<input type="submit" value="Next">
			</form>
		</div>
	</div>
	
		<div id="competenciesDialog">
		<div id="dialogContent">
			<h2>Set Crew Competencies</h2>
			</p> Please select the role and click "Next" to specify eligible crew members.<br><br>
			<form action="AdjustRoleCentricCompetencies.php" method="post">
				<select id="roleSelectorComp" name="selectedName">
					<script>
						var role = document.getElementById("roleRoster")
							.getElementsByTagName("li");
						var name;
						
						//Setup the select box properties
						var selector = document.getElementById("roleSelectorComp");

						for(var i = 0; i < role.length; i++)
						{
							step1 = role[i].innerHTML;
							step2 = step1.split(" -&gt");
							name = step2[0];
							selector.options[selector.length] = new Option(name, name);
						}
					</script>
				</select>
				 <br><br><br>
				<a href="RoleDatabaseHome.php"><input type="button" value="Cancel"></a>
				<input type="submit" value="Next">
			</form>
		</div>
	</div>
	
	
	<div id="addAreaDialog">
		<div id="dialogContent">
			<h2>Create New Operational Area</h2>
			</p> Please enter the title of this area below.<br><br>
			<form name="addArea" action="AddArea.php" method="post">
				Title: <input type="text" name="areaTitle"><br><br>
				<input type="button" value="Cancel" onclick="hideAddAreaDialog()">
				<input type="submit" value="Create Area">
			</form>
		</div>
	</div>
	
		<div id="deleteAreaDialog">
		<div id="dialogContent">
			<h2>Delete Operational Area</h2>
			</p> Please select an Area for deletion.<br><br>
			<form action="DeleteArea.php" method="post">
				<select id="deleteAreaSelector" name="selectedName">
					<script>
						var role = document.getElementById("areaRoster")
							.getElementsByTagName("li");
						var name;
						
						//Setup the select box properties
						var selector = document.getElementById("deleteAreaSelector");

						for(var i = 0; i < role.length; i++)
						{
							// The main Role display also show the User the Area for each Role.  We need to strip this away and only deliver the Role for deletion.
							name = role[i].innerHTML;
							selector.options[selector.length] = new Option(name, name);
						}
					</script>
				</select>
				 <br><br><br>
				<input type="button" value="Cancel" onclick="hideDeleteAreaDialog()">
				<input type="submit" value="Delete Area">
			</form>
		</div>
	</div>
	
</body>

</html>