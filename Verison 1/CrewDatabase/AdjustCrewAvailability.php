<!DOCTYPE html>
<html>
<head>
<title>Adjust Crew Availability</title>

<style>

#dialogContent{
	width: 600px;
	margin:50px auto;
	background-color:#add8e6;
	border: 2px solid black;
	padding: 15px;
	text-align: center;
	z-index: 101;
}

#background{
	position: absolute;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	text-align: center;
	z-index: 100;
	background-color: rgba(0,0,0,0.5);	
}

#FridayAvail{
				float:left;
				padding-left:30px;
			}
#SaturdayAvail{
				float:left;
				padding-left:30px;
			  }
#SundayAvail{
				float:left;
				padding-left:30px;
			}
#MondayAvail{
				clear:left;
				float:left;
				padding-left:30px;
				padding-top:30px;
			}
#TuesdayAvail{
				float:left;
				padding-left:30px;
				padding-top:30px;
			}
#WednesdayAvail{
				float:left;
				padding-left:30px;
				padding-top:30px;
			}
#ThursdayAvail{
				float:left;
				padding-left:30px;
				padding-top:30px;
			}
			
body{
		font: 100% "Calibri", sans-serif;
		margin: 50px;
}
</style>

<script>
// This block of code controls how the screen changes when different radio buttons are selected.
	function fridayOff()
	{
		document.getElementById("fridayStart").disabled=true;
		document.getElementById("fridayEnd").disabled=true;
	}
	
	function fridayOn()
	{
		document.getElementById("fridayStart").disabled=false;
		document.getElementById("fridayEnd").disabled=false;
	}


	function saturdayOff()
	{
		document.getElementById("saturdayStart").disabled=true;
		document.getElementById("saturdayEnd").disabled=true;
	}
	
	function saturdayOn()
	{
		document.getElementById("saturdayStart").disabled=false;
		document.getElementById("saturdayEnd").disabled=false;
	}
	
	
	function sundayOff()
	{
		document.getElementById("sundayStart").disabled=true;
		document.getElementById("sundayEnd").disabled=true;
	}
	
	function sundayOn()
	{
		document.getElementById("sundayStart").disabled=false;
		document.getElementById("sundayEnd").disabled=false;
	}
	
	
	function mondayOff()
	{
		document.getElementById("mondayStart").disabled=true;
		document.getElementById("mondayEnd").disabled=true;
	}
	
	function mondayOn()
	{
		document.getElementById("mondayStart").disabled=false;
		document.getElementById("mondayEnd").disabled=false;
	}
	
	
	function tuesdayOff()
	{
		document.getElementById("tuesdayStart").disabled=true;
		document.getElementById("tuesdayEnd").disabled=true;
	}
	
	function tuesdayOn()
	{
		document.getElementById("tuesdayStart").disabled=false;
		document.getElementById("tuesdayEnd").disabled=false;
	}
	
	
	function wednesdayOff()
	{
		document.getElementById("wednesdayStart").disabled=true;
		document.getElementById("wednesdayEnd").disabled=true;
	}
	
	function wednesdayOn()
	{
		document.getElementById("wednesdayStart").disabled=false;
		document.getElementById("wednesdayEnd").disabled=false;
	}
	
	
	function thursdayOff()
	{
		document.getElementById("thursdayStart").disabled=true;
		document.getElementById("thursdayEnd").disabled=true;
	}
	
	function thursdayOn()
	{
		document.getElementById("thursdayStart").disabled=false;
		document.getElementById("thursdayEnd").disabled=false;
	}
</script>

</head>



<body>

<!-- This page pulls the crew member's availability from the database and allows the User to edit it with the form.  We need to use PHP to create the form inputs and set their starting values according to what is already stored. --!> 

<div id="background">
		<div id="dialogContent">
			<h2>Adjust Crew Availability </h2>
			<form action="SubmitAvailabilityAdjustment.php" method="post">
				<?php echo "<b> $_POST[selectedName] </b>"; ?>
				 <p>The current standard availability for this crew member is displayed below.  Please make any necessary adjustments and submit the new availability to the database.</p>
				 
				 <p><b>Note:</b> Times marked with "**" occur during the following day.</p>
				 <hr>
	
				<?php
					// Connect to mySQL database
					$con=mysqli_connect("localhost", "crew", "crew123", "arclight");

					// Check connection
					if (mysqli_connect_errno())
					{
  						echo "Failed to connect to MySQL: " . mysqli_connect_error();
					}
				
					$times = array("6:00 AM", "7:00 AM", "8:00 AM", "9:00 AM", "10:00 AM", "11:00 AM", "12:00 PM", "1:00 PM", "2:00 PM", "3:00 PM", "4:00 PM", "5:00 PM", "6:00 PM", "7:00 PM", "8:00 PM", "9:00 PM", "10:00 PM", "11:00 PM", "**12:00 AM", "**1:00 AM", "**2:00 AM", "**3:00 AM", "**4:00 AM", "**5:00 AM");
				
				// We use the crew member's first and last name to estabilish their ID number...  then we reference this number to adjust their records in other tables.
				$input = $_POST[selectedName];
				$input = explode(', ',$input);
				$firstName = $input[1];
				$lastName = $input[0];
				$result = mysqli_query($con, 'select * from crew where firstName="'. $firstName . '" and lastName="' . $lastName . '"');
				$row = mysqli_fetch_array($result);
				$crewID = $row[0];

				echo '<input type="text" name="crewID" value="' . $crewID . '" style="visibility:hidden"></input>  ' ;

				$result = mysqli_query($con, 'select * from crewAvailability where employeeID=' . $crewID);
				$row = mysqli_fetch_array($result);

				
				?>
	
				<div id="FridayAvail">
					<p><b>Friday</b></p>
					Full <input type="radio" name="fridayType" value=0 onclick="fridayOff()" 
						<?php if ($row[1] == 0){echo 'checked="checked"';} ?>
						 > </input><br>
					None <input type="radio" name="fridayType" value=1 onclick="fridayOff()"
						<?php if ($row[1] == 1){echo 'checked="checked"';} ?>
						></input><br>
					Limited <input type="radio" name="fridayType" value=2 onclick="fridayOn()"
							<?php if ($row[1] == 2){echo 'checked="checked"';} ?>
							></input><br>
					
					<br>
					Start Time<br>
					<?php
						if ($row[1] != 2)
						{
							echo '<select id="fridayStart" name="fridayStart" disabled="disabled">';
						}
						else
						{
							echo '<select id="fridayStart" name="fridayStart">';
						}
						for ($i = 0; $i < 24; $i++)
						{
							if($i == $row[2])
							{
								echo '<option value=' . $i . ' selected="selected">' . $times[$i] . '</option>';
							}
							else
							{
								echo '<option value=' . $i . '>' . $times[$i] . '</option>';
							}
						}
						echo '</select><br>';
					?>
					End Time<br>
					<?php
						if ($row[1] != 2)
						{
							echo '<select id="fridayEnd" name="fridayEnd" disabled="disabled">';
						}
						else
						{
							echo '<select id="fridayEnd" name="fridayEnd">';
						}
						for ($i = 0; $i < 24; $i++)
						{
							if($i == $row[3])
							{
								echo '<option value=' . $i . ' selected="selected">' . $times[$i] . '</option>';
							}
							else
							{
								echo '<option value=' . $i . '>' . $times[$i] . '</option>';
							}
						}
						echo '</select><br>';
					?>
	
					
				</div>
	
				<div id="SaturdayAvail">
					<p><b>Saturday</b></p>
					Full <input type="radio" name="saturdayType" value=0 onclick="saturdayOff()"
						<?php if ($row[4] == 0){echo 'checked="checked"';} ?>
						></input><br>
					None <input type="radio" name="saturdayType" value=1 onclick="saturdayOff()"
						<?php if ($row[4] == 1){echo 'checked="checked"';} ?>
						></input><br>
					Limited <input type="radio" name="saturdayType" value=2 onclick="saturdayOn()"
						<?php if ($row[4] == 2){echo 'checked="checked"';} ?>
						></input><br>
					
					<br>
					Start Time<br>
					
					<?php
						if ($row[4] != 2)
						{
							echo '<select id="saturdayStart" name="saturdayStart" disabled="disabled">';
						}
						else
						{
							echo '<select id="saturdayStart" name="saturdayStart">';
						}
						for ($i = 0; $i < 24; $i++)
						{
							if($i == $row[5])
							{
								echo '<option value=' . $i . ' selected="selected">' . $times[$i] . '</option>';
							}
							else
							{
								echo '<option value=' . $i . '>' . $times[$i] . '</option>';
							}
						}
						echo '</select><br>';
					?>
					End Time<br>
					<?php
						if ($row[4] != 2)
						{
							echo '<select id="saturdayEnd" name="saturdayEnd" disabled="disabled">';
						}
						else
						{
							echo '<select id="saturdayEnd" name="saturdayEnd">';
						}
						for ($i = 0; $i < 24; $i++)
						{
							if($i == $row[6])
							{
								echo '<option value=' . $i . ' selected="selected">' . $times[$i] . '</option>';
							}
							else
							{
								echo '<option value=' . $i . '>' . $times[$i] . '</option>';
							}
						}
						echo '</select><br>';
					?>
					
				</div>
												
				<div id="SundayAvail">
					<p><b>Sunday</b></p>
					Full <input type="radio" name="sundayType" value=0 onclick="sundayOff()"
						<?php if ($row[7] == 0){echo 'checked="checked"';} ?>
						></input><br>
					None <input type="radio" name="sundayType" value=1 onclick="sundayOff()"
						<?php if ($row[7] == 1){echo 'checked="checked"';} ?>
						></input><br>
					Limited <input type="radio" name="sundayType" value=2 onclick="sundayOn()"
						<?php if ($row[7] == 2){echo 'checked="checked"';} ?>
						></input><br>
					
					<br>
					Start Time<br>
					
					<?php
						if ($row[7] != 2)
						{
							echo '<select id="sundayStart" name="sundayStart" disabled="disabled">';
						}
						else
						{
							echo '<select id="sundayStart" name="sundayStart">';
						}
						for ($i = 0; $i < 24; $i++)
						{
							if($i == $row[8])
							{
								echo '<option value=' . $i . ' selected="selected">' . $times[$i] . '</option>';
							}
							else
							{
								echo '<option value=' . $i . '>' . $times[$i] . '</option>';
							}
						}
						echo '</select><br>';
					?>
					End Time<br>
					<?php
						if ($row[7] != 2)
						{
							echo '<select id="sundayEnd" name="sundayEnd" disabled="disabled">';
						}
						else
						{
							echo '<select id="sundayEnd" name="sundayEnd">';
						}
						for ($i = 0; $i < 24; $i++)
						{
							if($i == $row[9])
							{
								echo '<option value=' . $i . ' selected="selected">' . $times[$i] . '</option>';
							}
							else
							{
								echo '<option value=' . $i . '>' . $times[$i] . '</option>';
							}
						}
						echo '</select><br>';
					?>
					
				</div>
				
				<div id="MondayAvail">
					<p><b>Monday</b></p>
					Full <input type="radio" name="mondayType" value=0 onclick="mondayOff()"
						<?php if ($row[10] == 0){echo 'checked="checked"';} ?>
						></input><br>
					None <input type="radio" name="mondayType" value=1 onclick="mondayOff()"
						<?php if ($row[10] == 1){echo 'checked="checked"';} ?>
						></input><br>
					Limited <input type="radio" name="mondayType" value=2 onclick="mondayOn()"
						<?php if ($row[10] == 2){echo 'checked="checked"';} ?>
						></input><br>
					
					<br>
					Start Time<br>
					
					<?php
						if ($row[10] != 2)
						{
							echo '<select id="mondayStart" name="mondayStart" disabled="disabled">';
						}
						else
						{
							echo '<select id="mondayStart" name="mondayStart">';
						}
						for ($i = 0; $i < 24; $i++)
						{
							if($i == $row[11])
							{
								echo '<option value=' . $i . ' selected="selected">' . $times[$i] . '</option>';
							}
							else
							{
								echo '<option value=' . $i . '>' . $times[$i] . '</option>';
							}
						}
						echo '</select><br>';
					?>
					End Time<br>
					<?php
						if ($row[10] != 2)
						{
							echo '<select id="mondayEnd" name="mondayEnd" disabled="disabled">';
						}
						else
						{
							echo '<select id="mondayEnd" name="mondayEnd">';
						}
						for ($i = 0; $i < 24; $i++)
						{
							if($i == $row[12])
							{
								echo '<option value=' . $i . ' selected="selected">' . $times[$i] . '</option>';
							}
							else
							{
								echo '<option value=' . $i . '>' . $times[$i] . '</option>';
							}
						}
						echo '</select><br>';
					?>
				</div>
				
				<div id="TuesdayAvail">
					<p><b>Tuesday</b></p>
					Full <input type="radio"  name="tuesdayType" value=0 onclick="tuesdayOff()"
						<?php if ($row[13] == 0){echo 'checked="checked"';} ?>
						></input><br>
					None <input type="radio" name="tuesdayType" value=1 onclick="tuesdayOff()"
						<?php if ($row[13] == 1){echo 'checked="checked"';} ?>
						></input><br>
					Limited <input type="radio" name="tuesdayType" value=2 onclick="tuesdayOn()"
						<?php if ($row[13] == 2){echo 'checked="checked"';} ?>
						></input><br>
					
					<br>
					Start Time<br>
					
					<?php
						if ($row[13] != 2)
						{
							echo '<select id="tuesdayStart" name="tuesdayStart" disabled="disabled">';
						}
						else
						{
							echo '<select id="tuesdayStart" name="tuesdayStart">';
						}
						for ($i = 0; $i < 24; $i++)
						{
							if($i == $row[14])
							{
								echo '<option value=' . $i . ' selected="selected">' . $times[$i] . '</option>';
							}
							else
							{
								echo '<option value=' . $i . '>' . $times[$i] . '</option>';
							}
						}
						echo '</select><br>';
					?>
					End Time<br>
					<?php
						if ($row[13] != 2)
						{
							echo '<select id="tuesdayEnd" name="tuesdayEnd" disabled="disabled">';
						}
						else
						{
							echo '<select id="tuesdayEnd" name="tuesdayEnd">';
						}
						for ($i = 0; $i < 24; $i++)
						{
							if($i == $row[15])
							{
								echo '<option value=' . $i . ' selected="selected">' . $times[$i] . '</option>';
							}
							else
							{
								echo '<option value=' . $i . '>' . $times[$i] . '</option>';
							}
						}
						echo '</select><br>';
					?>
				</div>
				
				<div id="WednesdayAvail">
					<p><b>Wednesday</b></p>
					Full <input type="radio" name="wednesdayType" value=0 onclick="wednesdayOff()"
						<?php if ($row[16] == 0){echo 'checked="checked"';} ?>
						></input><br>
					None <input type="radio" name="wednesdayType" value=1 onclick="wednesdayOff()"
						<?php if ($row[16] == 1){echo 'checked="checked"';} ?>
						></input><br>
					Limited <input type="radio" name="wednesdayType" value=2 onclick="wednesdayOn()"
						<?php if ($row[16] == 2){echo 'checked="checked"';} ?>
						></input><br>
					
					<br>
					Start Time<br>
					
					<?php
						if ($row[16] != 2)
						{
							echo '<select id="wednesdayStart" name="wednesdayStart" disabled="disabled">';
						}
						else
						{
							echo '<select id="wednesdayStart" name="wednesdayStart">';
						}
						for ($i = 0; $i < 24; $i++)
						{
							if($i == $row[17])
							{
								echo '<option value=' . $i . ' selected="selected">' . $times[$i] . '</option>';
							}
							else
							{
								echo '<option value=' . $i . '>' . $times[$i] . '</option>';
							}
						}
						echo '</select><br>';
					?>
					End Time<br>
					<?php
						if ($row[16] != 2)
						{
							echo '<select id="wednesdayEnd" name="wednesdayEnd" disabled="disabled">';
						}
						else
						{
							echo '<select id="wednesdayEnd" name="wednesdayEnd">';
						}
						for ($i = 0; $i < 24; $i++)
						{
							if($i == $row[18])
							{
								echo '<option value=' . $i . ' selected="selected">' . $times[$i] . '</option>';
							}
							else
							{
								echo '<option value=' . $i . '>' . $times[$i] . '</option>';
							}
						}
						echo '</select><br>';
					?>
				</div>
				
				<div id="ThursdayAvail">
					<p><b>Thursday</b></p>
					Full <input type="radio" name="thursdayType" value=0 onclick="thursdayOff()"
						<?php if ($row[19] == 0){echo 'checked="checked"';} ?>
						></input><br>
					None <input type="radio" name="thursdayType" value=1 onclick="thursdayOff()"
						<?php if ($row[19] == 1){echo 'checked="checked"';} ?>
						></input><br>
					Limited <input type="radio" name="thursdayType" value=2 onclick="thursdayOn()"
						<?php if ($row[19] == 2){echo 'checked="checked"';} ?>
						></input><br>
					
					<br>
					Start Time<br>
					
					<?php
						if ($row[19] != 2)
						{
							echo '<select id="thursdayStart" name="thursdayStart" disabled="disabled">';
						}
						else
						{
							echo '<select id="thursdayStart" name="thursdayStart">';
						}
						for ($i = 0; $i < 24; $i++)
						{
							if($i == $row[20])
							{
								echo '<option value=' . $i . ' selected="selected">' . $times[$i] . '</option>';
							}
							else
							{
								echo '<option value=' . $i . '>' . $times[$i] . '</option>';
							}
						}
						echo '</select><br>';
					?>
					End Time<br>
					<?php
						if ($row[19] != 2)
						{
							echo '<select id="thursdayEnd" name="thursdayEnd" disabled="disabled">';
						}
						else
						{
							echo '<select id="thursdayEnd" name="thursdayEnd">';
						}
						for ($i = 0; $i < 24; $i++)
						{
							if($i == $row[21])
							{
								echo '<option value=' . $i . ' selected="selected">' . $times[$i] . '</option>';
							}
							else
							{
								echo '<option value=' . $i . '>' . $times[$i] . '</option>';
							}
						}
						echo '</select><br>';
					?>
				</div>
				
				<div style="clear:both">
					<br><br>
					<a href="CrewDatabaseHome.php"><input type="button" value="Cancel"></a>
					<input type="submit" value="Confirm Availability Adjustment">
				</div>
				
			</form>
		 </div>
		 </div>	
	
</div>
</body>
</html>