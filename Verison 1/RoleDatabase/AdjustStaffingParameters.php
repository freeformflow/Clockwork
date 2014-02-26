<!DOCTYPE html>
<html>
<head>
<title>Adjust Role Staffing Parameters</title>

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

#staffingGuide {
	float: left;
}

#staffingRoster {
		overflow-y: scroll;
		overflow-x: hidden;
		height: 200px;
		width: 250px;
		list-style-type: none;
		border: 3px solid black;
		padding-left: 5px;
		padding-top: 5px;
		background-color:white;
}

#adjustor{
	float: left;
	text-align: left;
	padding-left: 15px;
}

#submitButtons{
	clear:both;
}

body{
		font: 100% "Calibri", sans-serif;
		margin: 50px;
}


</style>

<script>


function deleteSpecification()
{
	// When the delete button is pressed, any selected values must be removed from the select box.
	var specList = document.getElementById("staffingRoster");
	var deleteList = new Array();
	
	// Collect the list of selected values.
	for (var i = 0; i < specList.length; i++)
	{
		if (specList[i].selected)
		{
			deleteList.push(i);
		}
	}

	// Delete all selected values.
	for (var i = 0; i < deleteList.length; i++)
	{
		index = deleteList[i] - i;
		specList.remove(index);
	}
	
	updateSpecList();
}

function addSpecification()
{
	// When the add button is pressed, the values listed in the spinboxes below the button must be used to add into the current specifications list.  The spinboxes must then be reset.
	
	var specList = document.getElementById("staffingRoster");
	var numOfCrew = document.getElementById("numOfCrew").value;
	var maxAttend = document.getElementById("maxAttend").value;
	var test;
	var textString = numOfCrew + ", " + maxAttend;	

	if (maxAttend != 0)
	{
	if (specList.length == 0)
	{
		// If there is nothing in the Roster, then the loop won't work, so we can use this single line.
		specList.options[0] = new Option(textString, textString);
	}
	else
	{	
		for (var i = 0; i < specList.length; i++)
		{	
			// We need to do some tests to determine what is already loaded in the database.  If the User is loading a specification for a number of crew already present, then this function is more of an edit than an addition.  This loop iterates through all items in the select box.
		
			// Grab the number of crew, the first number displayed.
			test = specList[i].text;
			test = test.split(", ");
		
			
			// Now we conduct our tests.
			if (Number(test[0]) == numOfCrew)
			{
				// This is an edit, so we need to only adjust the second number.
				specList.options[i] = new Option(textString, textString);
				break;
			}
			else if (Number(test[0]) > numOfCrew)
			{
				// Our iterations have found that the input staff level is not currently stored, so we must insert a record.  To do this, we must shift everything down one.
				for (var j = specList.length; j > i; j--)
				{
					// grab the values from the option above.
					var movedText = specList.options[j - 1].text;
					specList.options[j] = new Option(movedText, movedText);
				}
			
				// Now we can place the input numOfCrew into the correct location.
				specList.options[i] = new Option(textString, textString);
				break;
			}
			else if (i == specList.length - 1)
			{
				// This staffing level is greater than any currently specified.  Add this record to the end.
				specList.options[specList.length] = new Option(textString, textString);
			}	
		}
	}
	}
	
	updateSpecList();
}

function updateSpecList()
{
	// This function updates the hidden field that stores the staffing specifications for this role.
	var textString = "";
	var userList = document.getElementById("staffingRoster");
	var output = document.getElementById("specList");
	
	for (var i = 0; i < userList.length; i++)
	{
		textString = textString + ";" + userList[i].text;
	}
	
	output.value = textString;
}

</script>
</head>

<body>
	<!-- This page pulls the staffing guidelines for this role from the database and allows the User to edit them with the form.  We need to use PHP to create the form inputs and set their starting values according to what is already stored. --!> 
	
	<div id="background">
	<div id="dialogContent">
				<h2>Adjust Role Staffing Parameters </h2>
			<form action="SubmitStaffingParameters.php" method="post">
				
				<?php
					// Gather data to fill out this form.
					$inputRole=$_POST[selectedName];
					echo '<input type="hidden" name="inputRole" value="'. $inputRole . '">'; 
	
					// Connect to mySQL database
					$con=mysqli_connect("localhost", "crew", "crew123", "arclight");

					// Check connection
					if (mysqli_connect_errno())
					{
  						echo "Failed to connect to MySQL: " . mysqli_connect_error();
					}
					
					// Get roleID
					$result = mysqli_query($con, 'select roleID from roleList where roleTitle="' . $inputRole . '"');
					$result = mysqli_fetch_array($result);
					$roleID = $result[0];
			
					// Get start and end time parameters
					$result = mysqli_query($con, 'select * from roleTimings where roleID=' . $roleID);
					$result = mysqli_fetch_array($result);
					$startTime = $result[1];
					$endTime = $result[2];
					$setOffset = $result[3];
					
					echo '<b>' . $inputRole . '</b><br><br>';
				?>
				
				 The current staffing parameters are displayed below.  Please make any necessary adjustments and submit the new specifications to the database.
				 
				 <hr>
		
				 <p>Please specify the time (minutes) with respect to operations opening/closing that a particular role should be staffed.  Next, specify the time (minutes) with respect to a new set's start that staffing levels should respond to the set's increase in attendance.</p>   
				 
				 <p>Negative times indicate minutes before, positive indcate afterward.</p>
				 <?php echo 'Opening: <input type="number" name="startTime" min="-9999" max="9999" value="' . $startTime . '">  <br>
				 Closing: <input type="number" name="endTime"  min="-9999" max="9999" value="' . $endTime . '"> <br>
				 New Set: <input type="number" name="setOffset"  min="-9999" max="9999" value="' . $setOffset . '">';
				 ?>
				
				<p>This section allows the template-builder parameters to be edited.  A given staffing level is listed next to the maximum appropriate attendance.</p>
			
	<div id="staffingGuide">
	<h3> Current Specifications </h3>
	<select id="staffingRoster" size="10" multiple>

	<?php
		// This block of PHP code pulls the staffing guidelines from the *roleStaffingGuide* table (from database) and displays each as an item in this select element.
		

		$result = mysqli_query($con,'SELECT * FROM roleStaffingGuide WHERE roleID=' . $roleID . ' order by numOfCrew');	  
	  	
	  	$textString = "";
		while($row = mysqli_fetch_array($result))
		{
			echo '<option value="' . $row[1] . ', ' . $row[2] . '">' . $row[1] . ', ' . $row[2] . '</option>';
			$textString = $textString . ';' . $row[1] . ', ' . $row[2]; 
		}
		
		// Now that the select box is present with all staffing specifications, we need to establish a hidden input field that can pass these values to the submit page.  This field is updated via JavaScript.
		echo '</select>';
		
		echo '<input type="hidden" id="specList" name="specList" value="' . $textString . '">';
		
		mysqli_close($con);
	
	?>
	
		
	
	</div>
	<div id="adjustor">
		<br> <br> <br>
		<input type="button" value="Delete Specification" onclick="deleteSpecification()"> <hr>
		<input type="button" value="Add Specification" onclick="addSpecification()"> <br>
		Number of Crew: <input type="number" id="numOfCrew" min="0" value="0"> <br>
		Maximum Attendance: <input type="number" id="maxAttend" min="0" step="25" value="0">
	</div>
	
			
			<div id="submitButtons">
			<br>
			<hr>	 
			<a href="RoleDatabaseHome.php"><input type="button" value="Cancel"></a>	 
			<input type="submit" value="Submit">
			</div>
			</form>
	</div>
	</div>
</body>
