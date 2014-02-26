<!DOCTYPE html>
<html>
<head>
<title>Scheduler</title>

<style>

#mainDisplay{
		overflow-y: scroll;
		overflow-x: scroll;
		width: 800px;
		border: 7px solid #0000E6;
		padding-left: 5px;
		padding-top: 5px;
		background-color:white;
		font: 100% "Monaco";
		margin-top: -1px;
}

#mainSection{
		clear: left;
		float: left;
}


#dayTabs{
	list-style:none;
	padding: 0px;
	margin: 0px;
}

#dayTabs li{
	float: left;
	width: 109px;
	margin: 0 5px 0 0;
	background-color:black;
	color:white;
	text-align: center;
	font: 120% "Calibri";
	
	padding-bottom: 2px;
	
	-moz-border-top-left-radius: 15px;
	-moz-border-top-right-radius: 15px;
	border-top-left-radius: 15px;
	border-top-right-radius: 15px;
}

#areaTabs{
	list-style:none;
	padding: 0px;
	margin: 0px;
}

#areaTabs li{
	background-color: black;
	color: white;
	width: 200px;
	text-align:center;
	font: 120% "Calibri";
	
	margin-bottom: 4px;
	
	-moz-border-top-left-radius: 15px;
	-moz-border-top-right-radius: 15px;
	-moz-border-bottom-left-radius: 15px;
	-moz-border-bottom-right-radius: 15px;
	border-top-left-radius: 15px;
	border-top-right-radius: 15px;
	border-bottom-left-radius: 15px;
	border-bottom-right-radius: 15px;
}

#viewSelectSection{
	padding-left: 30px;
	float:left;
}

#schedulerToolbar{
}

#templateBuilderOverlay{
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

#addShiftOverlay{
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

#editShiftOverlay{
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


#templateBuilderDialog{
	width: 700px;
	margin:25px auto;
	background-color:#add8e6;
	border: 2px solid black;
	padding: 15px;
	text-align: center;
	z-index: 101;
}

#addShiftDialog{
	width: 400px;
	margin:25px auto;
	background-color:#add8e6;
	border: 2px solid black;
	padding: 15px;
	text-align: center;
	z-index: 101;
}

#editShiftDialog{
	width: 400px;
	margin:25px auto;
	background-color:#add8e6;
	border: 2px solid black;
	padding: 15px;
	text-align: center;
	z-index: 101;
}

#roleList{
	display:none;
}

#crewList{
	display:none;
}

#areaList{
	display:none;
}

#specList
{
	display:none;
}

#weekend{
	clear:both;
}

#weekday{
	clear:both;
}

#submitButtons{
	clear:both;
}

.singleDay{
	float:left;
	text-align: left;
	margin-left: 20px;
}

body{
		font: 100% "Calibri", sans-serif;
		margin:20px;
	}
	
select > option:nth-child(odd) {
	background-color: #FFFFFF; 
	}
	
select > option:nth-child(even) { 
	background-color: #E2E7E7; 
	}
</style>

<script type="text/javascript" src="SchedulerGuts.js"></script>

</head>

<body>
	<h1>Scheduler</h1>

	<div id="navigationMenu">
		<a href="../HomePage.html">Home</a>  
		<a href="../CrewDatabase/CrewDatabaseHome.php">Crew Database</a>
		<a href="../RoleDatabase/RoleDatabaseHome.php">Role Database</a>
		<p1>Scheduler</p1>
	</div>
	
	<br> <br> 
	<p1>This is the main interface for the Scheduler. This app can create a template schedule for all seven days, and then fill it according to the availability and skills of crew.<br> </p1>
<hr>

	<ul id="dayTabs">
		<li id="tabFriday" onclick="selectDay(0)" style="background-color:#0000E6;border:2px solid #0000E6;">Friday</li>
		<li id="tabSaturday" onclick="selectDay(1)">Saturday</li>
		<li id="tabSunday" onclick="selectDay(2)">Sunday</li>
		<li id="tabMonday" onclick="selectDay(3)">Monday</li>
		<li id="tabTuesday" onclick="selectDay(4)">Tuesday</li>
		<li id="tabWednesday" onclick="selectDay(5)">Wednesday</li>
		<li id="tabThursday" onclick="selectDay(6)">Thursday</li>
	</ul>
	
	<div id=mainSection>
	<select id="mainDisplay" size="20" multiple>
	</select>
	<br>
	<div id="schedulerMenu">
		<input type="button" value="Save Schedule">
		<input type="button" value="Load Schedule"> 
		<p1>  </p1>
		<input type="button" value="Auto-Build Template" onclick="showTemplateAutoBuilder()">
		<input type="button" value="Auto-Allocate Crew">
		<p1>  </p1>
		<input type="button" value="Add Shift" onclick="showAddShiftOverlay()">
		<input type="button" value="Delete Shift" onclick="deleteShifts()">
		<input type="button" value="Edit Shift" onclick="showEditShiftOverlay()">
	</div>
	</div>
	
	<div id="viewSelectSection">
		<p>Area Select</p>
		<ul id="areaTabs">
		
			<li id="areaTabAll" style="background-color:#0000E6;border:2px solid #0000E6;margin-bottom:10px" onclick=selectArea(-1)>All Areas</li>
			
			<?php
				// Connect to mySQL database
				$con=mysqli_connect("localhost", "crew", "crew123", "arclight");

				// Check connection
				if (mysqli_connect_errno())
				{
  					echo "Failed to connect to MySQL: " . mysqli_connect_error();
				}
			
				// Pull all the area names from the database.
				$result = mysqli_query($con, 'select areaTitle from areaList order by areaTitle');
			
				$iter = 0;
				while($row = mysqli_fetch_array($result))
				{
					$text = 'areaTab' . $iter;
					echo '<li id="' . $text . '" onclick=selectArea(' . $iter . ')>' . $row[0] . '</li>';
					$iter++;
				}
			?>
		</ul>
	</div>

<!-- This next section will store values from the database so that only one call to the server is needed to use this part of the app. --!>

	<ul id="roleList">
	<?php
		// grab all roles from table roleList.
		$result = mysqli_query($con, 'select roleTitle from roleList order by roleTitle');
		
		while($row = mysqli_fetch_array($result))
		{
			echo '<li>' . $row[0] . '</li>';
		}
	?>
	</ul>
	
	<ul id="crewList">
	<?php
		// grab all crew names from table crew.
		$result = mysqli_query($con, 'select * from crew order by lastName');
		
		while($row = mysqli_fetch_array($result))
		{
			echo '<li>' . $row[2] . ', ' . $row[1] . '</li>';
		}
	?>
	</ul>
	
	<ul id="areaList">
	<?php	
		// Pull all the area names from the database.
		$result = mysqli_query($con, 'select * from areaList order by areaTitle');
			
		while($row = mysqli_fetch_array($result))
		{
			$result2 = mysqli_query($con, 'select roleTitle from roleList where areaID='. $row[0]);
			
			$textString = $row[1];
			while($row2 = mysqli_fetch_array($result2))
			{
				$textString = $textString . ';' . $row2[0]; 
			}
			echo '<li>' . $textString . '</li>';
		}
	?>
	</ul>
	
	<ul id="specList">
	<?php
		// Pull staffing specifications from the database.
		
		// Start by pulling a list of all Roles and cycling through them.
		$result = mysqli_query($con, 'select roleID from roleList order by roleTitle');
		
		while ($row = mysqli_fetch_array($result))
		{
			// Pull each role (identified by its ID), and then print out its staffing guide parameters.
			$result2 = mysqli_query($con, 'select count(roleID) from roleStaffingGuide where roleID=' . $row[0]);
			
			$specificationCount = mysqli_fetch_array($result2);
			
			if ($specificationCount[0] != 0)
			{
				// First, we'll list the open/close timing offsets.
				$result2 = mysqli_query($con, 'select * from roleTimings where roleID=' . $row[0]);
				$row2 = mysqli_fetch_array($result2);
				
				$textString = $row2[1] . ";" . $row2[2] . ";" . $row2[3];  // timing offsets.
				
				// Now, we'll move on to the staffing guide parameters.
				$result2 = mysqli_query($con, 'select * from roleStaffingGuide where roleID=' . $row[0]);
				
				while ($row2 = mysqli_fetch_array($result2))
				{
					$textString = $textString . ";" . $row2[1] . "," . $row2[2];
				}
				
				echo '<li>' . $textString . '</li>';
			}
			else
			{
				// No specifications have been entered for this role.
				echo '<li>None</li>';
			}
		} 
	?>
	</ul>
	
<!-- And now, all sections from here are modal dialogs that allow the User to work the scheduler. --!>	

	<div id="addShiftOverlay">
		<div id="addShiftDialog">
			<h2>Add Shift</h2>
			<p>This section allows the addition of a shift.  Please specify the shift below.</p> <hr>
			Role: <select id="addShiftSelector"></select>
			<br> Blue Pin Required? <input type="checkbox" id="addShiftBluePin">
			<br><br><br>
			Day Of The Week: <select id="addShiftDaySelector"></select>
			<br><br>
			Start: <select id="startHour"></select>
			<select id="startMinute"></select>
			
			<select id="startXM">
			<option>AM</option>
			<option>PM</option>
			</select>
			
			<br>
			End: <select id="endHour"></select>
			<select id="endMinute"></select>
			
			<select id="endXM">
			<option>AM</option>
			<option>PM</option>
			</select>
			
			<br> <br> <br>
			Allocate to Crew? <input type="checkbox" id="addShiftCrewBox" onclick='toggleAllocation("addShiftCrewBox","addShiftCrewSelector")'>
			<br>
			<select id="addShiftCrewSelector" disabled></select>
			
			
			<script> 
				populateRoles("addShiftSelector"); 
				populateDaysOfTheWeek("addShiftDaySelector");
				populateHoursAndMinutes("startHour", "startMinute");
				populateHoursAndMinutes("endHour", "endMinute");
				populateCrew("addShiftCrewSelector");
			</script>
			
			<div id="submitButtons">
			<br><hr><br>
				<input type="button" value="Cancel" onclick="hideAddShiftOverlay()">
				<input type="button" value="Add Shift" onclick="addShift()">
			</div>
		</div>
	</div>
	
		<div id="editShiftOverlay">
		<div id="editShiftDialog">
			<h2>Edit Shift</h2>
			<p>This section allows the alteration of an existing shift.  Please specify changes to the shift below.</p> <hr>
			Role: <select id="editShiftSelector"></select>
			<br> Blue Pin Required? <input type="checkbox" id="editShiftBluePin">
			<br><br><br>
			Day Of The Week: <select id="editShiftDaySelector" disabled></select>
			<br><br>
			Start: <select id="editStartHour"></select>
			<select id="editStartMinute"></select>
			
			<select id="editStartXM">
			<option>AM</option>
			<option>PM</option>
			</select>
			
			<br>
			End: <select id="editEndHour"></select>
			<select id="editEndMinute"></select>
			
			<select id="editEndXM">
			<option>AM</option>
			<option>PM</option>
			</select>
			
			<br> <br> <br>
			Allocate to Crew? <input type="checkbox" id="editShiftCrewBox" onclick='toggleAllocation("editShiftCrewBox","editShiftCrewSelector")'>
			<br>
			<select id="editShiftCrewSelector" disabled></select>
			<input type="hidden" id="editShiftIndex">
			
			<script> 
				populateRoles("editShiftSelector"); 
				populateDaysOfTheWeek("editShiftDaySelector");
				populateHoursAndMinutes("editStartHour", "editStartMinute");
				populateHoursAndMinutes("editEndHour", "editEndMinute");
				populateCrew("editShiftCrewSelector");
			</script>
			
			<div id="submitButtons">
			<br><hr><br>
				<input type="button" value="Cancel" onclick="hideEditShiftOverlay()">
				<input type="button" value="Confirm Changes" onclick="editShift()">
			</div>
		</div>
	</div>

	<div id="templateBuilderOverlay">
		<div id="templateBuilderDialog">
			<h2>Template Auto-Builder</h2>
			<p>This tool automatically constructs a template schedule for all seven days.  All that is needed are the scheduled opening and closing times for receiving guests and the forecasted attendance.</p> <hr>
		
			<div id="weekend">
			<!-- Weekend --!>
				<div class="singleDay">
				<b>Friday</b><br>
				Open: <select id="fridayStart"></select>
				<script> 
					populateTimes("fridayStart");
					document.getElementById("fridayStart").selectedIndex = 20;
				</script> 
				<br>
				Close:<select id="fridayEnd"></select><hr>
				<script> 
					populateTimes("fridayEnd");
					document.getElementById("fridayEnd").selectedIndex = 72;
				</script>
				Attendance:<br>
				Early <input type="number" id="fridayEarly" step="25" min="0" value="0" max="9999"><br>
				First <input type="number" id="fridayFirst" step="25" min="0" value="0" max="9999"><br>
				Second <input type="number" id="fridaySecond" step="25" min="0" value="0" max="9999"><br>
				Twilight <input type="number" id="fridayTwilight" step="25" min="0" value="0" max="9999"><br>
				Prime <input type="number" id="fridayPrime" step="25" min="0" value="0" max="9999"><br>
				Late <input type="number" id="fridayLate" step="25" min="0" value="0" max="9999"><br>
				Midnight <input type="number" id="fridayMidnight" step="25" min="0" value="0" max="9999">
				</div>
				
				<div class="singleDay">
				<b>Saturday</b> <br>
				Open: <select id="saturdayStart"></select>
				<script> 
					populateTimes("saturdayStart");
					document.getElementById("saturdayStart").selectedIndex = 20;
				</script> 
				<br>
				Close:<select id="saturdayEnd"></select><hr>
				<script> 
					populateTimes("saturdayEnd");
					document.getElementById("saturdayEnd").selectedIndex = 72;
				</script>
				Attendance:<br>
				Early <input type="number" id="saturdayEarly" step="25" min="0" value="0" max="9999"><br>
				First <input type="number" id="saturdayFirst" step="25" min="0" value="0" max="9999"><br>
				Second <input type="number" id="saturdaySecond" step="25" min="0" value="0" max="9999"><br>
				Twilight <input type="number" id="saturdayTwilight" step="25" min="0" value="0" max="9999"><br>
				Prime <input type="number" id="saturdayPrime" step="25" min="0" value="0" max="9999"><br>
				Late <input type="number" id="saturdayLate" step="25" min="0" value="0" max="9999"><br>
				Midnight <input type="number" id="saturdayMidnight" step="25" min="0" value="0" max="9999">
				</div>
				
				<div class="singleDay">
				<b>Sunday</b> <br>
				Open: <select id="sundayStart"></select>
				<script> 
					populateTimes("sundayStart");
					document.getElementById("sundayStart").selectedIndex = 20;
				</script> 
				<br>
				Close:<select id="sundayEnd"></select><hr>
				<script> 
					populateTimes("sundayEnd");
					document.getElementById("sundayEnd").selectedIndex = 72;
				</script>
				Attendance:<br>
				Early <input type="number" id="sundayEarly" step="25" min="0" value="0" max="9999"><br>
				First <input type="number" id="sundayFirst" step="25" min="0" value="0" max="9999"><br>
				Second <input type="number" id="sundaySecond" step="25" min="0" value="0" max="9999"><br>
				Twilight <input type="number" id="sundayTwilight" step="25" min="0" value="0" max="9999"><br>
				Prime <input type="number" id="sundayPrime" step="25" min="0" value="0" max="9999"><br>
				Late <input type="number" id="sundayLate" step="25" min="0" value="0" max="9999"><br>
				Midnight <input type="number" id="sundayMidnight" step="25" min="0" value="0" max="9999">
				</div>
			
			</div>

			<div id="weekday">
				<!-- Weekdays --!>
				<br><br>
				<div class="singleDay">
				<b>Monday</b> <br>
				Open: <select id="mondayStart"></select>
				<script> 
					populateTimes("mondayStart");
					document.getElementById("mondayStart").selectedIndex = 20;
				</script> 
				<br>
				Close:<select id="mondayEnd"></select><hr>
				<script> 
					populateTimes("mondayEnd");
					document.getElementById("mondayEnd").selectedIndex = 72;
				</script>
				Attendance:<br>
				Early <input type="number" id="mondayEarly" step="25" min="0" value="0" max="9999"><br>
				First <input type="number" id="mondayFirst" step="25" min="0" value="0" max="9999"><br>
				Second <input type="number" id="mondaySecond" step="25" min="0" value="0" max="9999"><br>
				Twilight <input type="number" id="mondayTwilight" step="25" min="0" value="0" max="9999"><br>
				Prime <input type="number" id="mondayPrime" step="25" min="0" value="0" max="9999"><br>
				Late <input type="number" id="mondayLate" step="25" min="0" value="0" max="9999"><br>
				Midnight <input type="number" id="mondayMidnight" step="25" min="0" value="0" max="9999">
				</div>
				
				<div class="singleDay">
				<b>Tuesday</b> <br>
				Open: <select id="tuesdayStart"></select>
				<script> 
					populateTimes("tuesdayStart");
					document.getElementById("tuesdayStart").selectedIndex = 20;
				</script> 
				<br>
				Close:<select id="tuesdayEnd"></select><hr>
				<script> 
					populateTimes("tuesdayEnd");
					document.getElementById("tuesdayEnd").selectedIndex = 72;
				</script>
				Attendance:<br>
				Early <input type="number" id="tuesdayEarly" step="25" min="0" value="0" max="9999"><br>
				First <input type="number" id="tuesdayFirst" step="25" min="0" value="0" max="9999"><br>
				Second <input type="number" id="tuesdaySecond" step="25" min="0" value="0" max="9999"><br>
				Twilight <input type="number" id="tuesdayTwilight" step="25" min="0" value="0" max="9999"><br>
				Prime <input type="number" id="tuesdayPrime" step="25" min="0" value="0" max="9999"><br>
				Late <input type="number" id="tuesdayLate" step="25" min="0" value="0" max="9999"><br>
				Midnight <input type="number" id="tuesdayMidnight" step="25" min="0" value="0" max="9999">
				</div>
				
				<div class="singleDay">
				<b>Wednesday</b> <br>
				Open: <select id="wednesdayStart"></select>
				<script> 
					populateTimes("wednesdayStart");
					document.getElementById("wednesdayStart").selectedIndex = 20;
				</script> 
				<br>
				Close:<select id="wednesdayEnd"></select><hr>
				<script> 
					populateTimes("wednesdayEnd");
					document.getElementById("wednesdayEnd").selectedIndex = 72;
				</script>
				Attendance:<br>
				Early <input type="number" id="wednesdayEarly" step="25" min="0" value="0" max="9999"><br>
				First <input type="number" id="wednesdayFirst" step="25" min="0" value="0" max="9999"><br>
				Second <input type="number" id="wednesdaySecond" step="25" min="0" value="0" max="9999"><br>
				Twilight <input type="number" id="wednesdayTwilight" step="25" min="0" value="0" max="9999"><br>
				Prime <input type="number" id="wednesdayPrime" step="25" min="0" value="0" max="9999"><br>
				Late <input type="number" id="wednesdayLate" step="25" min="0" value="0" max="9999"><br>
				Midnight <input type="number" id="wednesdayMidnight" step="25" min="0" value="0" max="9999">
				</div>
				
				<div class="singleDay">
				<b>Thursday</b> <br>
				Open: <select id="thursdayStart"></select>
				<script> 
					populateTimes("thursdayStart");
					document.getElementById("thursdayStart").selectedIndex = 20;
				</script> 
				<br>
				Close:<select id="thursdayEnd"></select><hr>
				<script> 
					populateTimes("thursdayEnd");
					document.getElementById("thursdayEnd").selectedIndex = 72;
				</script>
				Attendance:<br>
				Early <input type="number" id="thursdayEarly" step="25" min="0" value="0" max="9999"><br>
				First <input type="number" id="thursdayFirst" step="25" min="0" value="0" max="9999"><br>
				Second <input type="number" id="thursdaySecond" step="25" min="0" value="0" max="9999"><br>
				Twilight <input type="number" id="thursdayTwilight" step="25" min="0" value="0" max="9999"><br>
				Prime <input type="number" id="thursdayPrime" step="25" min="0" value="0" max="9999"><br>
				Late <input type="number" id="thursdayLate" step="25" min="0" value="0" max="9999"><br>
				Midnight <input type="number" id="thursdayMidnight" step="25" min="0" value="0" max="9999">
				</div>
				
		</div>
		

			
			<div id="submitButtons">
			<br><hr><br>
				<input type="button" value="Cancel" onclick="hideTemplateAutoBuilder()">
				<input type="button" value="Build Template" onclick="buildTemplate()">
			</div>
		</div>
	</div>




</body>
</html>