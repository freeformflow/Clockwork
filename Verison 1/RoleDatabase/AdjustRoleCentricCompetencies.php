<!DOCTYPE html>
<html>
<head>
<title>Adjust Crew Competencies (Role-Centric)</title>

<style>

#dialogContent{
	width: 700px;
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

#unPinnedSection {
	float: left;
	padding-top: 150px;
}

#pinnedSection {
	float: left;
	padding-left: 15px;
}

#unPinnedList {
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

#bluePinnedList {
		overflow-y: scroll;
		overflow-x: hidden;
		height: 200px;
		width: 250px;
		list-style-type: none;
		border: 3px solid black;
		padding-left: 5px;
		padding-top: 5px;
		background-color:rgb(130, 190, 255);
}

#greenPinnedList {
		overflow-y: scroll;
		overflow-x: hidden;
		height: 200px;
		width: 250px;
		list-style-type: none;
		border: 3px solid black;
		padding-left: 5px;
		padding-top: 5px;
		background-color:rgb(50, 255, 100);
}

#moveButtons{
	float: left;
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

function listTransfer(pointA, pointB)
{
	// This function moves names from one roster to another.  pointA is a roster with names selected.  The User would like to move those selected names...  to pointB. 
	
	pointA = document.getElementById(pointA);
	pointB = document.getElementById(pointB);
	
	var selectedIndex = new Array();
	var selectedText = new Array();
	var index;
	
	// The first step is to collect the names that are highlighted.
	for (var i = 0; i < pointA.length; i++)
	{
		if (pointA[i].selected)
		{
			selectedIndex.push(i);
			selectedText.push(pointA[i].text);
		}
	}
	
	// Since we're done copying, we can delete the highlighted values.	
	for (var i = 0; i < selectedIndex.length; i++)
	{
		index = selectedIndex[i] - i;
		pointA.remove(index);
	}
	
	// Now, we need to add the values we've collected into the destination roster.
	if (pointB.length == 0)
	{
		// If there is nothing in the destination roster, then our job is easy, and we can just dump these names into the roster.
		
		for (var i = 0; i < selectedText.length; i++)
		{
			pointB.options[pointB.length] = new Option(selectedText[i], selectedText[i]);
		}
	}
	else
	{
		// This is a little more difficult.  We'll have to compare each value we've collected to the destination roster.
		
		for (var i = 0; i < selectedText.length; i++)
		{
			for (var j = 0; j < pointB.length; j++)
			{
				if (selectedText[i] < pointB[j].text)
				{
					// The value we are inserting is alphabetically before item in the destination roster.
					// This is where we place the value.
					
					// But wait!  It's not that easy... we have to shift the whole list below this value down one, so we may place the desired value into place.
					for (var k = pointB.length; k > j; k--)
					{
						// grab the values from the option above.
						var movedText = pointB.options[k - 1].text;
						pointB.options[k] = new Option(movedText, movedText);
					}
			
					// Now we can place the transfered value into the correct location.
					pointB.options[j] = new Option(selectedText[i], selectedText[i]);
					break;
				}
				else if (j == pointB.length - 1)
				{
					// We have reached the end of the list and not placed the transfered value, so it needs to be placed at the end.
					pointB.options[pointB.length] = new Option(selectedText[i], selectedText[i]);
					break;
				}
			}
		}
	}
	
	updateCompetencyLists();
}

function updateCompetencyLists()
{
	// This function updates the hidden fields that store the list of crew with green(blue) pins.
	var textString = "";
	var userList = document.getElementById("greenPinnedList");
	var output = document.getElementById("greenList");
	
	for (var i = 0; i < userList.length; i++)
	{
		textString = textString + ";" + userList[i].text;
	}
	
	output.value = textString;
	
	// Now we do the same for the blue pin list.
	textString = "";
	userList = document.getElementById("bluePinnedList");
	output = document.getElementById("blueList");
	
	for (var i = 0; i < userList.length; i++)
	{
		textString = textString + ";" + userList[i].text;
	}
	
	output.value = textString;
}

</script>
</head>

<body>
	<!-- This page pulls the crew competencies for this role from the database and allows the User to edit them with the form.  This interface is "role-centric" meaning it is easy to add many crew to a single role.  We need to use PHP to create the form inputs and set their starting values according to what is already stored. --!> 
	
	<div id="background">
	<div id="dialogContent">
				<h2>Adjust Crew Competencies (Role-Centric)</h2>
			<form action="SubmitRoleCentricComptencies.php" method="post">
				
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
					
					echo '<b>' . $inputRole . '</b><br><br>';
					
			// This next block of PHP code is the meat of this display.  It pulls data from *pins* the table that holds competencies.  It then matches the roleID and employeeIDs to create arrays that will be used to populate the displayed rosters.
					
			// Pull data from *pins*
			$result = mysqli_query($con, "select * from pins where roleID=$roleID");
		
			// Sort the ID numbers into piles of pins.
			while($row = mysqli_fetch_array($result))
			{
				if ($row[2] == 2)
				{
					// Green Pin
					$greenPinList[] = $row[1];
				}
				else
				{
					// Blue Pin
					$bluePinList[] = $row[1];
				}
			}

			
			// Pulling names from *crew*, we can generate alphabetcial rosters of crew in each category.
			$result = mysqli_query($con, "select * from crew order by lastName");
			while($row = mysqli_fetch_array($result))
			{
				$text = $row[2] . ', ' . $row[1];
			
				if (in_array($row[0], $greenPinList))
				{
					// add this crew to the roster of people with green pins.
					$greenPinnedRoster[] = $text;
				}
				else if (in_array($row[0], $bluePinList)) 
				{
					// add this crew to the roster of people with blue pins.
					$bluePinnedRoster[] = $text;
				}
				else
				{
					// add this crew to the roster of people without pins.
					$unPinnedRoster[] = $text;
				}  
			}
		?>
				
				 The current list of pinned and unpinned crew in this role are displayed below.  Please make any necessary adjustments and submit the new competencies to the database.
				 
				 <hr>
		
	<div id="unPinnedSection">
	<h3> Un-Pinned Crew </h3>
	<select id="unPinnedList" size="15" multiple>

	<?php
		// Populate this roster.
		for ($i = 0; $i < count($unPinnedRoster); $i++)
		{
			echo '<option value="' . $unPinnedRoster[$i] . '">'
				. $unPinnedRoster[$i] . '</option>';
		}	
	?>
			
	</select>
	</div>
	
	<div id="moveButtons">
	<br><br><br><br><br><br><br>
	<input type="button" value=">" onclick="listTransfer('unPinnedList', 'bluePinnedList')"><br>
	<input type="button" value="&lt" onclick="listTransfer('bluePinnedList', 'unPinnedList')">
	
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<input type="button" value=">" onclick="listTransfer('unPinnedList', 'greenPinnedList')"> <br>
	<input type="button" value="&lt" onclick="listTransfer('greenPinnedList', 'unPinnedList')">
	</div>
	
	<div id="pinnedSection">
		<h3> Blue-Pinned Crew </h3>
	<select id="bluePinnedList" size="15" multiple>
	<?php
		// Populate this roster and the hidden field we pass to the next page.
		
		$textString = "";
		for ($i = 0; $i < count($bluePinnedRoster); $i++)
		{
			echo '<option value="' . $bluePinnedRoster[$i] . '">'
				. $bluePinnedRoster[$i] . '</option>';
			
			$textString = $textString . ';' . $bluePinnedRoster[$i];
		}

		echo '</select>';
		echo '<input type="hidden" value="'. $textString . '" name=blueList id=blueList>';	
	?>
	
	<br> <br>
	<input type="button" value="^" onclick="listTransfer('greenPinnedList', 'bluePinnedList')">
	<input type="button" value="v" onclick="listTransfer('bluePinnedList', 'greenPinnedList')">
	<br>
	
	
			<h3> Green-Pinned Crew </h3>
	<select id="greenPinnedList" size="15" multiple>
	<?php
		// Populate this roster and the hidden field we pass to the next page.
		
		$textString = "";
		for ($i = 0; $i < count($greenPinnedRoster); $i++)
		{
			echo '<option value="' . $greenPinnedRoster[$i] . '">'
				. $greenPinnedRoster[$i] . '</option>';
				
				$textString = $textString . ';' . $greenPinnedRoster[$i];
		}
		
		echo '</select>';
		
		echo '<input type="hidden" value="'. $textString . '" name=greenList id=greenList>';	
	?>
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
