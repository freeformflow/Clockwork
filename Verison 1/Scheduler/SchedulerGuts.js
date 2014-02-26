// This file contains nearly all the JavaScript code for the scheduler.  The amount of code is a little unwieldy for editing purposes, so it has been relocated to this file.  The main interface for the scheduler part of the scheduling app is on this page.  It is handled through JavaScript.  PHP is used only as the page loads to draw the necessary data from the database, and then the result of the User's efforts is saved.


var shifts = [[], [], [], [], [], [], []]; // This is a global, multi-dimensional array that holds shifts for all days and their specifications.

var visibleIndex = []; // This holds the index of visible shifts in "shifts" so that they can be manipulated.

var dayOfTheWeek = 0; // day of the week.  0 through 6 are days Friday through Thursday, respectively.

var visibleArea = -1; // index for which area is being viewed.  0 is all areas.
var visibleRoles = [];  // This array holds the list of role IDs that are allowed to be displayed.

	// This holds the end times of sets...   early, first matinee, second, twilight, prime, late, and the value
	// for midnight is a dummy value, set at the end of the day.  The loop will exit before we reach it.
var setEndTimes = [24, 36, 40, 48, 59, 70, 96];



function selectDay(inputDay)
{
	// This function carries out the operations necessary to change the day of the week displayed in the Scheduler. 
	
	// Start by changing the colors of the tabs on the page to reflect the newly selected day.
	changeDayTab(inputDay);
	
	// Physically change the variable that's keeping track of the User's selected day of the week.
	updateDayVariables(inputDay);

	// Now place the stored shifts in the main display.
	updateDisplay();

}

function changeDayTab(inputDay)
{
	// This function updates the colors of the Scheduler's tabs to reflect the User's changes.  
	
	var inputElement;
	
	var inactiveTabColor = "#000000";
	var inactiveTextColor = "#FFFFFF";
	
	var activeTabColor = "#0000E6";
	var activeTextColor = "#FFFFFF";
	
	if (inputDay != dayOfTheWeek)
	{
		// First change the color of the old tab back to the unselected color.
		switch (dayOfTheWeek)
		{
			case 0:
				inputElement = "tabFriday";
				break;
			case 1: 
				inputElement = "tabSaturday";
				break;
			case 2: 
				inputElement = "tabSunday";
				break;
			case 3: 
				inputElement = "tabMonday";
				break;
			case 4: 
				inputElement = "tabTuesday";
				break;
			case 5: 
				inputElement = "tabWednesday";
				break;
			case 6: 
				inputElement = "tabThursday";
				break;						
		}	
		selector = document.getElementById(inputElement);
		selector.style.backgroundColor = inactiveTabColor;
		selector.style.color = inactiveTextColor;
		selector.style.border = "none";
		
		// Now, change the color of the new tab to the selected color.
		switch (inputDay)
		{
			case 0: 
				inputElement = "tabFriday";
				break;
			case 1: 
				inputElement = "tabSaturday";
				break;
			case 2: 
				inputElement = "tabSunday";
				break;
			case 3: 
				inputElement = "tabMonday";
				break;
			case 4: 
				inputElement = "tabTuesday";
				break;
			case 5: 
				inputElement = "tabWednesday";
				break;
			case 6: 
				inputElement = "tabThursday";
				break;						
		}
		selector = document.getElementById(inputElement);
		selector.style.backgroundColor = activeTabColor;
		selector.style.border = "2px solid " + activeTabColor;
		selector.style.color = activeTextColor;
	}

}

function updateDayVariables(inputDay)
{
	// This function gathers together any operations needed to update the scheduler's current day.  Besides simply changing the global variable "dayOfTheWeek", the User expects functions to be performed on the selected day once it is clicked.
	
	// First, simply change the global variable that tracks the scheduler's current working day.
	dayOfTheWeek = inputDay;
	
	// The pre-selected day of the week in the addShift dialog should match the current "dayOfTheWeek";
	document.getElementById("addShiftDaySelector").selectedIndex = dayOfTheWeek;
}




function selectArea(inputArea)
{
	// This function carries out the operations necessary to change the area of operations displayed in the Scheduler. 
	
	// Start by changing the colors of the tabs on the page to reflect the newly selected area.
	changeAreaTab(inputArea);
	
	// Physically change the variable that's keeping track of the User's selected day of the week.
	updateAreaVariables(inputArea);

	// Now place the stored shifts in the main display.
	updateDisplay();

}

function updateAreaVariables(inputArea)
{
	// This function gathers together any operations needed to update the scheduler's current visible area.  Besides simply changing the global variable "visibleArea", there are other changes we'll need to make.
	
	// First, simply change the global variable that tracks the scheduler's current visible area.
	visibleArea = inputArea;
	
	// Now, we need to gather together the list of roles that fall within this area, assuming the User has not requested all roles to be visible.  This list of Roles attached to this Area are stored in the element "areaList". 
	if (inputArea != -1)
	{
		var areaArray = document.getElementById("areaList").getElementsByTagName("li");
		var roleArray = document.getElementById("roleList").getElementsByTagName("li");
		var string = areaArray[inputArea].innerHTML;
		var splitString = string.split(";");
		
		visibleRoles = [];
		
		// Now we go through each Role listed on this Area, and assign it a number for comparison later.
		for (var i = 1; i < splitString.length; i++)
		{
			// Determine the role ID based on alphabetical order.
			for (var j = 0; j < roleArray.length; j++)
			{
				
				if (splitString[i] == roleArray[j].innerHTML)
				{
					visibleRoles.push(j);
					break;
				}
			} 
		}
	}
}

function changeAreaTab(inputArea)
{
	// This function updates the colors of the Scheduler's tabs to reflect the User's changes.  
	
	var inputElement;
	
	var inactiveTabColor = "#000000";
	var inactiveTextColor = "#FFFFFF";
	
	var activeTabColor = "#0000E6";
	var activeTextColor = "#FFFFFF";
	
	if (visibleArea != inputArea)
	{
		if (visibleArea == -1)
		{
			// The pattern the tab's IDs follow is "areaTab" followed by an index number, except for when all areas are displayed...  the input is -1.
			inputElement = "areaTabAll";
		}
		else
		{
			inputElement = "areaTab" + visibleArea;
		}

		selector = document.getElementById(inputElement);
		selector.style.backgroundColor = inactiveTabColor;
		selector.style.color = inactiveTextColor;
		selector.style.border = "none";

		
		// Now, change the color of the new tab to the selected color.
		if (inputArea == -1)
		{
			inputElement = "areaTabAll"
		}
		else
		{
			inputElement = "areaTab" + inputArea;
		}
		
		selector = document.getElementById(inputElement);
		selector.style.backgroundColor = activeTabColor;
		selector.style.border = "2px solid " + activeTabColor;
		selector.style.color = activeTextColor;
	}

}

function updateDisplay()
{
	// Whenever this function is called, we need to update the main shift display.  It could be that the User is asking to see another day, another area, or has added/deleted shifts.
	
	// The array "shifts" is split into 7 arrays, one for each day.  Each day contains a set of arrays that describe the shifts for that day.  Each single shift is described by a single array.  We pass them, labeled as "outputArray" to a function that creates a text string for output.
	
	var outputArray = [];
	var visibleShifts = [];
	var textString;
	
	// First clear the display.
	selector = document.getElementById("mainDisplay");
	
	for (var i = selector.length - 1; i >= 0; i--)
	{
		selector.remove(i);
	}

	// Clear the display's index.
	visibleIndex.length = 0;
	
	// We still need to prepare the sets of shifts that will be viewed....   This can vary not only on the day, but also on whether the User is viewing only part of operations for the day.
	

	// Access the global variable "visibleArea" to determine what the User wishes displayed.		
	if (visibleArea == -1)
	{
		// "All Areas" is selected, so all shifts for this day are to be displayed.
		
		for (var i = 0; i < shifts[dayOfTheWeek].length; i++)
		{
			visibleShifts.push(shifts[dayOfTheWeek][i]); // data from "shifts" to be displayed.
			visibleIndex.push(i); // index of data's location in "shifts" for manipulation.
		}
	}
	else
	{
		// The User is restricting which roles are displayed.  The role of each shift if identified by first element in the array describing a given shift.  Cycle through all shifts for this day.
		for (var i = 0; i < shifts[dayOfTheWeek].length; i++)
		{
			// Compare this role ID to the list of acceptable ones in "visibleRoles".
			for (j in visibleRoles)
			{
				if (shifts[dayOfTheWeek][i][0] == visibleRoles[j])
				{
					// This shift can be displayed.
					visibleShifts.push(shifts[dayOfTheWeek][i]); // data from "shifts" to be displayed.
					visibleIndex.push(i); // index of data's location in "shifts" for manipulation.
					break;
				}
			} 
		}
	}

	// Now display the shifts.... cycle through all elements of "visibleShifts"
	for (var i = 0; i < visibleShifts.length; i++)
	{
		outputArray = visibleShifts[i];
		
		// Call the function to create the display text string.
		textString = createText(outputArray);
	
		// Add the text string to the main display.
		selector.options[selector.length] = new Option(textString, textString);
	}

}


function createText(outputArray)
{
	// "outputArray" is an array of integers, adhering to the following rules. 
	
	// outputArray[0] ->       role: This is the index of the role that is filled by the shift.
	// outputArray[1] ->       bluePin? This is bool, stores whether a blue pin is needed for this shift.
	// outputArray[2, 3, 4] -> startTime: Time is stored in three values. ['hours index', 'minutes index',
	//						        'XM index (0 or 1)']
	// outputArray[5, 6, 7] -> endTime: Time is stored in three values. ['hours index', 'minutes index', 
	//								'XM index (0 or 1)']
	// outputArray[8] -> 	  crewName: This is the index of the crew member that will fill this role.
	// 								 -1 indicates an unallocated shift.
	
	// Our job is to turn this set of integers into a text string that can be displayed, and return that string to whatever called this function.
	
	var textString = "";
	var pretext;
	var pretext2;
	
	// First, check for blue pin.  There are four spaces before the role title.  If there is no pin, the spaces are all "_".  If there is a pin, then we place "<>" on the center two spaces.
	if (outputArray[1] == 1)
	{
		textString = textString + String.fromCharCode(160) + "<>" + String.fromCharCode(160);
	}
	else
	{
		textString = textString + String.fromCharCode(160) + String.fromCharCode(160) + String.fromCharCode(160) + String.fromCharCode(160);
	}
	
	
	// Role Title
	var selector = document.getElementById("roleList").getElementsByTagName("li");
	pretext =  selector[outputArray[0]].innerHTML;
	// Make sure this is 20 characters or less.
	if (pretext.length > 20)
	{
		// Shorten to 20 characters.
		pretext2 = "";
		for (var i = 0; i < 20; i++)
		{
			pretext2 = pretext2 + pretext[i];
		}
		
		pretext = pretext2;
	} 
	
	textString = textString + pretext;
	
	// Now add in spacing to pad out this field before the time field.
	for (var i = pretext.length; i < 20; i++)
	{
		textString = textString + String.fromCharCode(160);
	}
	
	// One extra space for visibility.
	textString = textString + " ";
	
	// Start Hour
	switch (outputArray[2])
	{
		case 0:
			textString = textString + "01:";
			break;
		case 1:
			textString = textString + "02:";
			break;
		case 2:
			textString = textString + "03:";
			break;						
		case 3:
			textString = textString + "04:";
			break;			
		case 4:
			textString = textString + "05:";
			break;
		case 5:
			textString = textString + "06:";
			break;
		case 6:
			textString = textString + "07:";
			break;
		case 7:
			textString = textString + "08:";
			break;
		case 8:
			textString = textString + "09:";
			break;
		case 9:
			textString = textString + "10:";
			break;
		case 10:
			textString = textString + "11:";
			break;
		case 11:
			textString = textString + "12:";
			break;			
	}

	
	// Start Minute 
	switch (outputArray[3])
	{
		case 0:
			textString = textString + "00";
			break;
		case 1:
			textString = textString + "05";
			break;
		case 2:
			textString = textString + "10";
			break;						
		case 3:
			textString = textString + "15";
			break;			
		case 4:
			textString = textString + "20";
			break;
		case 5:
			textString = textString + "25";
			break;
		case 6:
			textString = textString + "30";
			break;
		case 7:
			textString = textString + "35";
			break;
		case 8:
			textString = textString + "40";
			break;
		case 9:
			textString = textString + "45";
			break;
		case 10:
			textString = textString + "50";
			break;
		case 11:
			textString = textString + "55";
			break;			
	}
	
	// AM or PM
	if (outputArray[4] == 0)
	{
		textString = textString + " AM to ";
	}
	else
	{
		textString = textString + " PM to ";
	}
	
	
	// End Hour
	switch (outputArray[5])
	{
		case 0:
			textString = textString + "01:";
			break;
		case 1:
			textString = textString + "02:";
			break;
		case 2:
			textString = textString + "03:";
			break;						
		case 3:
			textString = textString + "04:";
			break;			
		case 4:
			textString = textString + "05:";
			break;
		case 5:
			textString = textString + "06:";
			break;
		case 6:
			textString = textString + "07:";
			break;
		case 7:
			textString = textString + "08:";
			break;
		case 8:
			textString = textString + "09:";
			break;
		case 9:
			textString = textString + "10:";
			break;
		case 10:
			textString = textString + "11:";
			break;
		case 11:
			textString = textString + "12:";
			break;			
	}

	
	// Start Minute 
	switch (outputArray[6])
	{
		case 0:
			textString = textString + "00";
			break;
		case 1:
			textString = textString + "05";
			break;
		case 2:
			textString = textString + "10";
			break;						
		case 3:
			textString = textString + "15";
			break;			
		case 4:
			textString = textString + "20";
			break;
		case 5:
			textString = textString + "25";
			break;
		case 6:
			textString = textString + "30";
			break;
		case 7:
			textString = textString + "35";
			break;
		case 8:
			textString = textString + "40";
			break;
		case 9:
			textString = textString + "45";
			break;
		case 10:
			textString = textString + "50";
			break;
		case 11:
			textString = textString + "55";
			break;			
	}
	
	// AM or PM
	if (outputArray[7] == 0)
	{
		textString = textString + " AM ";
	}
	else
	{
		textString = textString + " PM ";
	}	
	

	// Now that start/end times have been input, pad with some whitespace.
	for (var i = 0; i < 7; i++)
	{
		textString = textString + String.fromCharCode(160);
	}
	
	// Now, we add the allocated crew member's name.
	if (outputArray[8] != -1)
	{
		selector = document.getElementById("crewList").getElementsByTagName("li");
		textString = textString + selector[outputArray[8]].innerHTML;
	}
	else
	{
		// No crew member is allocated for this shift.  We tag this with "unallocated"
		textString = textString + "**UNALLOCATED**";
	}

	// Now that we've constructed the text string.  Return it.
	return textString;
}

function addShift()
{
	// This function adds a shift to the schedule based on the specifications entered in the addShift Dialog.  Data is stored temporarily in "shifts" -- a multi-dimensional, global array -- until the User formally saves the schedule to the database.  This array is accessed to create the on-screen display.
	
	var inputArray = new Array(); // This is the array that will be placed in the 
								  // multi-dimensional "shifts" array.
 	var day; // This is the index of the day this shift will be placed.
	
	// inputArray[0] ->       role: This is the index of the role that is filled by the shift.
	// inputArray[1] ->       bluePin? This is bool, stores whether a blue pin is needed for this shift.
	// inputArray[2, 3, 4] -> startTime: Time is stored in three values. ['hours index', 'minutes index',
	//						        'XM index (0 or 1)']
	// inputArray[5, 6, 7] -> endTime: Time is stored in three values. ['hours index', 'minutes index', 
	//								'XM index (0 or 1)']
	// inputArray[8] -> 	  crewName: This is the index of the crew member that will fill this role.
	// 								 -1 indicates an unallocated shift.
	
	//---------------------------------------------------------
	// Collect the data from each of the dialog's inputs.
	//---------------------------------------------------------
	
	// Day
	day = document.getElementById("addShiftDaySelector").selectedIndex;
	
	// Role
	inputArray.push(document.getElementById("addShiftSelector").selectedIndex);
	
	// Is a Blue Pin in this Role Required?
	if (document.getElementById("addShiftBluePin").checked)
	{
		inputArray.push(1);
	}
	else
	{
		inputArray.push(0);
	}
	
	// Start Time
	inputArray.push(document.getElementById("startHour").selectedIndex);
	inputArray.push(document.getElementById("startMinute").selectedIndex);
	inputArray.push(document.getElementById("startXM").selectedIndex);
	
	// End Time
	inputArray.push(document.getElementById("endHour").selectedIndex);
	inputArray.push(document.getElementById("endMinute").selectedIndex);
	inputArray.push(document.getElementById("endXM").selectedIndex);
	
	// Crew Member's Name
	if (document.getElementById("addShiftCrewBox").checked)
	{
		inputArray.push(document.getElementById("addShiftCrewSelector").selectedIndex);
	}
	else
	{
		// No crew were allocated to this shift.  Store a dummy value to signal this.
		inputArray.push(-1);
	}
	
	insertShift(day, inputArray);
	
	// Now that the shift has been stored, dismiss the addShift Dialog.
	hideAddShiftOverlay();
	
	// And request for the shift display to update.
	updateDisplay();	 
}

function editShift()
{
	// This function is called after the User is done making changes to a shift in the "editShift" dialog.  We need to convert the input back into an array suitable for "shifts", but we are over-writing an existing array, not creating a new one.
	
	var inputArray = new Array(); // This is the array that will be placed in the 
								  // multi-dimensional "shifts" array.
 	var day; // This is the index of the day this shift will be placed.
	
	// inputArray[0] ->       role: This is the index of the role that is filled by the shift.
	// inputArray[1] ->       bluePin? This is bool, stores whether a blue pin is needed for this shift.
	// inputArray[2, 3, 4] -> startTime: Time is stored in three values. ['hours index', 'minutes index',
	//						        'XM index (0 or 1)']
	// inputArray[5, 6, 7] -> endTime: Time is stored in three values. ['hours index', 'minutes index', 
	//								'XM index (0 or 1)']
	// inputArray[8] -> 	  crewName: This is the index of the crew member that will fill this role.
	// 								 -1 indicates an unallocated shift.
	
	//---------------------------------------------------------
	// Collect the data from each of the dialog's inputs.
	//---------------------------------------------------------
	
	// Role
	inputArray.push(document.getElementById("editShiftSelector").selectedIndex);
	
	// Is a Blue Pin in this Role Required?
	if (document.getElementById("editShiftBluePin").checked)
	{
		inputArray.push(1);
	}
	else
	{
		inputArray.push(0);
	}
	
	// Start Time
	inputArray.push(document.getElementById("editStartHour").selectedIndex);
	inputArray.push(document.getElementById("editStartMinute").selectedIndex);
	inputArray.push(document.getElementById("editStartXM").selectedIndex);
	
	// End Time
	inputArray.push(document.getElementById("editEndHour").selectedIndex);
	inputArray.push(document.getElementById("editEndMinute").selectedIndex);
	inputArray.push(document.getElementById("editEndXM").selectedIndex);
	
	// Crew Member's Name
	if (document.getElementById("editShiftCrewBox").checked)
	{
		inputArray.push(document.getElementById("editShiftCrewSelector").selectedIndex);
	}
	else
	{
		// No crew were allocated to this shift.  Store a dummy value to signal this.
		inputArray.push(-1);
	}
	
	// Store this in "shifts" by over-writing existing data.
	shifts[dayOfTheWeek].splice(document.getElementById("editShiftIndex").value, 1, inputArray);
	
	// Now that the shift has been stored, dismiss the editShift Dialog.
	hideEditShiftOverlay();
	
	// And request for the shift display to update.
	updateDisplay();	
}

function deleteShifts()
{
	// This function is activated when the User clicks the "delete" button.  We need to delete any shift that is currently selected in the display.
	
	var userSelection = [];
	
	// Iterate through all shifts currently displayed and collect the ones that are selected.
	selector = document.getElementById("mainDisplay");
	for (var i = 0; i < selector.length; i++)
	{
		if (selector[i].selected)
		{
			userSelection.push(i);
		}
	}

	// Now iterate through the list of selected shifts and delete them from the main storage array "shifts".  Their location in "shifts" is stored in "visibleIndex".  "visibleIndex" matches the index on screen to the internal index, which is needed if the User is only viewing part of a day's shifts.
	
	for (var i = 0; i < userSelection.length; i++)
	{
		shifts[dayOfTheWeek].splice(visibleIndex[userSelection[i]] - i, 1);
	}
	
	// Now, update the display to reflect these changes.
	updateDisplay();
}


function insertShift(day, inputArray)
{
	// This function inserts the shift into the "shifts" array in a ordered way.  First, alphabetically by visible role titles, and then chronologically for the day.
		
		if (shifts[day].length == 0)
		{
			// Before we even start, our job is very easy if the list of shifts for this day is empty.
			shifts[day].push(inputArray);
		}
		else
		{
			// Otherwise, we have more work to do.
			
			var testArray = [];
			var testStartTime;
			var inputTestTime;
			var shiftAdded = false;
		
			for (var i = 0; i < shifts[day].length; i++)
			{
					// Load the comparison array.
					testArray = shifts[day][i];
					
					// Now we ask a series of questions to make sure the shift is placed properly.
					if (inputArray[0] > testArray[0])
					{
						// Our input comes later than the test alphabetically. Move on.
						continue;
					}
					else if (inputArray[0] == testArray[0])
					{
						// The role of the shift we're inserting matches the comparison shift.
						// We'll need to compare start times now...   convert the index for the time select boxes into single number that can be used for comparison.
					
						testStartTime = getTimeIndex(testArray[2], testArray[3], testArray[4]);
						inputStartTime = getTimeIndex(inputArray[2], inputArray[3], inputArray[4]);	
					
						if (inputStartTime > testStartTime)
						{
							// Our input comes later than the test, chronologically.  Move on.
							continue;
						}
						else
						{
							// We've found the point of insertion.  We're done.
							shifts[day].splice(i, 0, inputArray);
							shiftAdded = true;
							break;
						}
					}
					else
					{
						// The role of the shift we're inserting comes before the test shift, alphabetically.  Because we've reached this conclusion last, we can be sure we've found the point of insertion.  We're done.
						shifts[day].splice(i, 0, inputArray);
						shiftAdded = true;
						break;
					}
			}
			
			// We're done with the loop.  If we still haven't placed the shift into the array, it belongs at the end.  Place it now.
			
			if (shiftAdded == false)
			{
				shifts[day].push(inputArray);
			}
		}
}

function getTimeIndex(hourIndex, minuteIndex, xmIndex)
{
	// This function takes the inputs from combo boxes describing hours, minutes, and (am/pm) of a time, and converts it to a single integer for comparison.
	
	//  hourIndex -> The Hours select box runs from "01" to "12" , which means we'll need to be careful to note that 12 comes before 01 on the clock.
	
	// minuteIndex -> Minutes run from "00" to "55"
	
	// xmIndex -> either am or pm
	
	
	var result;
	
	if (xmIndex == 0)
	{
		// Working with AM times.
		
		if (hourIndex != 11)
		{
			result = ((hourIndex + 1) * 12) + minuteIndex;
		}
		else
		{
			// If the 12 o'clock hour has been selected, then we must note this hour precedes the 1 o'clock hour.
			result = minuteIndex;
		} 
	}
	else
	{
		// Working with PM times.
		
		if (hourIndex != 11)
		{
			result = ((hourIndex + 1) * 12) + minuteIndex + 144;
		}
		else
		{
			// If the 12 o'clock hour has been selected, then we must note this hour precedes the 1 o'clock hour.
			result = minuteIndex + 144;
		} 
	}
	
	return result;
}




function populateTimes(elementID)
{
	// This function populates the available times in the template auto-builder.
	
	selector = document.getElementById(elementID);
	
	if (selector.length == 0)
	{
	
		var times = ["6:00 AM", "6:15 AM", "6:30 AM", "6:45 AM", "7:00 AM", "7:15 AM", "7:30 AM", "7:45 AM", "8:00 AM", "8:15 AM", "8:30 AM", "8:45 AM", "9:00 AM", "9:15 AM", "9:30 AM", "9:45 AM", "10:00 AM", "10:15 AM", "10:30 AM", "10:45 AM", "11:00 AM", "11:15 AM", "11:30 AM", "11:45 AM", "12:00 PM", "12:15 PM", "12:30 PM", "12:45 PM", "1:00 PM", "1:15 PM", "1:30 PM", "1:45 PM", "2:00 PM", "2:15 PM", "2:30 PM", "2:45 PM", "3:00 PM", "3:15 PM", "3:30 PM", "3:45 PM", "4:00 PM", "4:15 PM", "4:30 PM", "4:45 PM", "5:00 PM", "5:15 PM", "5:30 PM", "5:45 PM", "6:00 PM", "6:15 PM", "6:30 PM", "6:45 PM", "7:00 PM", "7:15 PM", "7:30 PM", "7:45 PM", "8:00 PM", "8:15 PM", "8:30 PM", "8:45 PM", "9:00 PM", "9:15 PM", "9:30 PM", "9:45 PM", "10:00 PM", "10:15 PM", "10:30 PM", "10:45 PM", "11:00 PM", "11:15 PM", "11:30 PM", "11:45 PM", "12:00 AM", "12:15 AM", "12:30 AM", "12:45 AM", "1:00 AM", "1:15 AM", "1:30 AM", "1:45 AM", "2:00 AM", "2:15 AM", "2:30 AM", "2:45 AM", "3:00 AM", "3:15 AM", "3:30 AM", "3:45 AM", "4:00 AM", "4:15 AM", "4:30 AM", "4:45 AM", "5:00 AM", "5:15 AM", "5:30 AM", "5:45 AM"];
	
	
		for (var i = 0; i < times.length; i++)
		{
			selector.options[selector.length] = new Option(times[i], times[i]);
		}
	}
}

function populateDaysOfTheWeek(elementID)
{
	// This function populates the days of the week into the select box.
	
	selector = document.getElementById(elementID);
	
	if (selector.length == 0)
	{
		var days = ["Friday", "Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday"];
		
		for (var i = 0; i < days.length; i++)
		{
			selector.options[selector.length] = new Option(days[i], days[i]);
		}
	}
}

function populateHoursAndMinutes(element1, element2)
{
	// This function populates times in a time selector.
	
	selector1 = document.getElementById(element1);
	selector2 = document.getElementById(element2);
	
	if (selector1.length == 0)
	{
		var hours = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
		var mins = ["00", "05", "10", "15", "20", "25", "30", "35", "40", "45", "50", "55"];
		
		for (var i = 0; i < hours.length; i++)
		{
			selector1.options[selector1.length] = new Option(hours[i], hours[i]);
		}
		
		for (var i = 0; i < mins.length; i++)
		{
			selector2.options[selector2.length] = new Option(mins[i], mins[i]);
		}
	}
}

function populateRoles(element)
{
	// This function populates roles in a role selector.  The values are pulled from a hidden list on the page that was pulled from the database.
	
	selector = document.getElementById(element);
	source = document.getElementById("roleList").getElementsByTagName("li");

	if (selector.length == 0)
	{
		for (var i = 0; i < source.length; i++)
		{
			selector.options[selector.length] = new Option(source[i].innerHTML, source[i].innerHTML);
		}
	}
}

function populateCrew(element)
{
	// This function populates crew names into a select box.  The values are pulled from a hidden list on the page that was pulled from the database.
	
	selector = document.getElementById(element);
	source = document.getElementById("crewList").getElementsByTagName("li");

	if (selector.length == 0)
	{
		for (var i = 0; i < source.length; i++)
		{
			selector.options[selector.length] = new Option(source[i].innerHTML, source[i].innerHTML);
		}
	}
}



function buildTemplate()
{
	// This function gathers together the necessary operations to automate the template building process.  It does this for the entire week with a simple button push.  The User needs to input the open and close times for the day, and the forecast for each set.
	
	// First clear any shifts currently stored for this week.
	shifts = [[], [], [], [], [], [], []]; 
	
	// Now, build in the shifts.
	autoCreateShifts();
	
	// And finally, dismiss the template builder dialog and update the display.
	hideTemplateAutoBuilder();
	updateDisplay();
}


function autoCreateShifts()
{
	// This function does the dirty work of generating the schedule template.
	
	var staffingNeeds = [];
	var numberOfIncrements;
	var openOffset;
	var closeOffset;
	var setOffset;
	var string;
	var dataArray;
	var dataArray2;
	var singlSpec = [];
	var specs = [];
	var staffingLevel;
	
	var strandIsEmpty;
	var strandStart;
	var strandStartIsFound;
	var strandEnd;
	var pointA;
	var pointB;
	
	// Gather together all data entered by the User: open and close times, set forecasts.
	var startTimes = getStartTimes();
	var endTimes = getEndTimes();
	var setSpreads = getSetSpreads();
	
	// Cycle through each day to prepare the template.  Starting with Friday, i = 0.
	//for (var i = 0; i < 7; i++)
	//{
		i = 0;
	
		// Now, cycle through each role.
		role = document.getElementById("specList").getElementsByTagName("li");
		
		//for (var j = 0; j < role.length; j++)
		//{
			j = 2;

			// First, we parse the text in this list item.  It contains the parameters we need to automate staffing.
			// The first three values are the the timing offsets, and the rest are staffing guidelines.
			string = role[j].innerHTML;
			dataArray = string.split(";");
		
			openOffset = Math.round(Number(dataArray[0]) / 15);
			closeOffset = Math.round(Number(dataArray[1]) / 15);
			setOffset = Math.round(Number(dataArray[2]) / 15);
		
		
			// Now, we pull staffing level guide for this Role from the page
			specs = [];
			for (var k = 2; k < dataArray.length; k++)
			{
				singleSpec = [];
				
				string = dataArray[k];
				dataArray2 = string.split(",");
				singleSpec.push(Number(dataArray2[0]));
				singleSpec.push(Number(dataArray2[1]));
				
				specs.push(singleSpec);
			}

			// Next, we'll go through the day in 15 minute increments and determine the 
			// number of crew needed at each point. 
	
			staffingNeeds = [];  // This array will hold the number of crew needed to 
								 // staff this role at a given time.

			iter = 0;
			for (var k = 0; k < 96; k++)
			{
				// If we are examining times before doors open, no staff are needed.
				if (k < startTimes[i] + openOffset)
				{
					staffingNeeds.push(0);
					continue;
				}
				
				// If we are examining times after doors close, no staff are needed.
				if (k > endTimes[i] + closeOffset)
				{
					staffingNeeds.push(0);
					continue;
				}
		
		
				// If we've made it past the first two if-statements, we are examining times 
				// during operations.  We will need to keep track of which set we are in, and use 
				// that forecast to determine staffing levels.  
			
				if (k < setEndTimes[iter])
				{
					// We are examining times within this set, so we can use its forecast 
					// to determine appropriate staffing.  

					staffingLevel = getStaffingLevel(setSpreads[i][iter], specs);
					staffingNeeds.push(staffingLevel);
				}
				else
				{
					// We have left the set, and must examine the next.
					
					iter++;
					staffingLevel = getStaffingLevel(setSpreads[i][iter], specs);
					staffingNeeds.push(staffingLevel);
				}
			

			}// Done with looping through the day 15 min at a time.	
			
			
			// Now, we need to make adjustments.  
			// What is complicating matters is the fact that we are modifying the set times with
			// a "new set timing offset".  This  allows us to bring in crew ahead of a 
			// spike in attendance, or clean up after one.  However, if the new
			// set's attendance is less than the current one, we need to override the 
			// modifier and maintain staffing until the bigger set is complete.  
			
			// Cycle through the array "staffingNeeds"
			for (iter = 0; iter < setEndTimes.length - 1; iter++)
			{
				k = setEndTimes[iter] - 1;
				
				if (staffingNeeds[k] < staffingNeeds[k + 1])
				{
					if (setOffset < 0)
					{
						for (m = setOffset; m < 0; m++)
						{
							staffingNeeds[k + 1 + m] = staffingNeeds[k + 1];
						}
					}	
				}
			}

	
			// Now, we go back through the array, "staffingNeeds" and pull out "strands".
			// These are continuous spans of time that must be covered by shifts in an 
			// overlapping manner.  
			
			// For example, there is a strand of concessions that must
			// be covered from 9:30 AM to 1:00 AM the following morning.  That is 15.5 hours,
			// which can be covered by 3 shifts.
			
			// Shifts need to be 5.75 hours long or under.  Overlapping along a strand should
			// be 15 minutes between shifts.
			
			strandIsEmpty = false;
			
			while (strandIsEmpty == false)
			{
				strandStartIsFound = false;
				strandIsEmpty = true;
				
				for (var k = 0; k < 96; k++)
				{
					if (strandStartIsFound == true)
					{
						if (staffingNeeds[k] == 0)
						{
							strandEnd = k - 1;
							break;
						}
						else
						{
							staffingNeeds[k]--;
						}
					}
					
					
					if (strandStartIsFound == false)
					{
						if (staffingNeeds[k] > 0)
						{
							strandIsEmpty = false;
							strandStart = k;
							strandStartIsFound = true;
							staffingNeeds[k]--;
						}
					}
					

				}
				
				if (strandIsEmpty)
				{	
					break;
				}
				
				// Now that we have the strand, divide it into shifts that overlap.
				for (var k = 1; k < 100; k++)
				{
					if (Math.ceil((strandEnd - strandStart + k - 1) / k) < 23)
					{
						// We've found the number of shifts needed to cover this strand
						coverage = k;
						length = Math.ceil((strandEnd - strandStart + k - 1) / k);
						break;
					}
				}
				
				for (var k = 0; k < coverage; k++)
				{
					if (k == 0)
					{
						pointA = strandStart;
						pointB = strandStart + length;
						
						autoAddShift(i, j, pointA, pointB);
					}
					else
					{
						pointA = pointB - 1;
						pointB = pointA + length;
						
						if (pointB > strandEnd)
						{
							pointB = strandEnd;
						}
		
						autoAddShift(i, j, pointA, pointB);
					}
				} 
				
			}
				
		//}	// Done with looping through Roles.
	
	//} Done looping through days.
}


function getStartTimes()
{
	// This function just grabs all the start times for the week and places them into one array for convenience.
	
	var startTimes = [];
	
	startTimes.push(document.getElementById("fridayStart").selectedIndex);
	startTimes.push(document.getElementById("saturdayStart").selectedIndex);
	startTimes.push(document.getElementById("sundayStart").selectedIndex);
	
	startTimes.push(document.getElementById("mondayStart").selectedIndex);
	startTimes.push(document.getElementById("tuesdayStart").selectedIndex);
	startTimes.push(document.getElementById("wednesdayStart").selectedIndex);
	startTimes.push(document.getElementById("thursdayStart").selectedIndex);
	
	return startTimes;
}

function getEndTimes()
{
	// This function just grabs all the end times for the week and places them into one array for convenience.
	var endTimes = [];
	
	endTimes.push(document.getElementById("fridayEnd").selectedIndex);
	endTimes.push(document.getElementById("saturdayEnd").selectedIndex);
	endTimes.push(document.getElementById("sundayEnd").selectedIndex);
	
	endTimes.push(document.getElementById("mondayEnd").selectedIndex);
	endTimes.push(document.getElementById("tuesdayEnd").selectedIndex);
	endTimes.push(document.getElementById("wednesdayEnd").selectedIndex);
	endTimes.push(document.getElementById("thursdayEnd").selectedIndex);
	
	return endTimes;

}

function getSetSpreads()
{
	// This function just grabs all the set forecasts for the week and places them into one array for convenience.
	
	var setSpreads = [[], [], [], [], [], [], [], []];
	
	setSpreads[0].push(document.getElementById("fridayEarly").value);
	setSpreads[0].push(document.getElementById("fridayFirst").value);
	setSpreads[0].push(document.getElementById("fridaySecond").value);
	setSpreads[0].push(document.getElementById("fridayTwilight").value);
	setSpreads[0].push(document.getElementById("fridayPrime").value);
	setSpreads[0].push(document.getElementById("fridayLate").value);
	setSpreads[0].push(document.getElementById("fridayMidnight").value);
	
	setSpreads[1].push(document.getElementById("saturdayEarly").value);
	setSpreads[1].push(document.getElementById("saturdayFirst").value);
	setSpreads[1].push(document.getElementById("saturdaySecond").value);
	setSpreads[1].push(document.getElementById("saturdayTwilight").value);
	setSpreads[1].push(document.getElementById("saturdayPrime").value);
	setSpreads[1].push(document.getElementById("saturdayLate").value);
	setSpreads[1].push(document.getElementById("saturdayMidnight").value);
	
	setSpreads[2].push(document.getElementById("sundayEarly").value);
	setSpreads[2].push(document.getElementById("sundayFirst").value);
	setSpreads[2].push(document.getElementById("sundaySecond").value);
	setSpreads[2].push(document.getElementById("sundayTwilight").value);
	setSpreads[2].push(document.getElementById("sundayPrime").value);
	setSpreads[2].push(document.getElementById("sundayLate").value);
	setSpreads[2].push(document.getElementById("sundayMidnight").value);
	
	setSpreads[3].push(document.getElementById("mondayEarly").value);
	setSpreads[3].push(document.getElementById("mondayFirst").value);
	setSpreads[3].push(document.getElementById("mondaySecond").value);
	setSpreads[3].push(document.getElementById("mondayTwilight").value);
	setSpreads[3].push(document.getElementById("mondayPrime").value);
	setSpreads[3].push(document.getElementById("mondayLate").value);
	setSpreads[3].push(document.getElementById("mondayMidnight").value);
	
	setSpreads[4].push(document.getElementById("tuesdayEarly").value);
	setSpreads[4].push(document.getElementById("tuesdayFirst").value);
	setSpreads[4].push(document.getElementById("tuesdaySecond").value);
	setSpreads[4].push(document.getElementById("tuesdayTwilight").value);
	setSpreads[4].push(document.getElementById("tuesdayPrime").value);
	setSpreads[4].push(document.getElementById("tuesdayLate").value);
	setSpreads[4].push(document.getElementById("tuesdayMidnight").value);
	
	setSpreads[5].push(document.getElementById("wednesdayEarly").value);
	setSpreads[5].push(document.getElementById("wednesdayFirst").value);
	setSpreads[5].push(document.getElementById("wednesdaySecond").value);
	setSpreads[5].push(document.getElementById("wednesdayTwilight").value);
	setSpreads[5].push(document.getElementById("wednesdayPrime").value);
	setSpreads[5].push(document.getElementById("wednesdayLate").value);
	setSpreads[5].push(document.getElementById("wednesdayMidnight").value);
	
	setSpreads[6].push(document.getElementById("thursdayEarly").value);
	setSpreads[6].push(document.getElementById("thursdayFirst").value);
	setSpreads[6].push(document.getElementById("thursdaySecond").value);
	setSpreads[6].push(document.getElementById("thursdayTwilight").value);
	setSpreads[6].push(document.getElementById("thursdayPrime").value);
	setSpreads[6].push(document.getElementById("thursdayLate").value);
	setSpreads[6].push(document.getElementById("thursdayMidnight").value);
	
	
	return setSpreads;
}

function getStaffingLevel(forecast, spec)
{
	// This function takes an input forecast and determines the appropriate number of crew based on the staffing guide, spec (specifications).  
	
	// The values in spec are arranged in increasing order.  We need to find where the maximum allowed attendance is greater than the forecast for this set.
	
	for (x in spec)
	{
		if (spec[x][1] > forecast)
		{
			// We've found our staffing level.
			return spec[x][0];
		}	
	}
	
	
}


function autoAddShift (day, roleID, startTime, endTime)
{
	// This function adds shifts to the array "shifts" based on the results of the template
	// builder.  Pretty much everything is already known...  the hardest part will be
	// converting the times into a form that is stored in that array.
	
	// inputArray[0] ->       role: This is the index of the role that is filled by the shift.
	// inputArray[1] ->       bluePin? This is bool, stores whether a blue pin is needed for this shift.
	// inputArray[2, 3, 4] -> startTime: Time is stored in three values. ['hours index', 'minutes index',
	//						        'XM index (0 or 1)']
	// inputArray[5, 6, 7] -> endTime: Time is stored in three values. ['hours index', 'minutes index', 
	//								'XM index (0 or 1)']
	// inputArray[8] -> 	  crewName: This is the index of the crew member that will fill this role.
	// 							-1 indicates that this shift is unallocated.
	
	var inputArray = [];
	var timeArray = [];

	inputArray.push(roleID);
	inputArray.push(0);
	
	timeArray = getShiftTime(startTime);
	for (var l = 0; l < 3; l++)
	{
		inputArray.push(timeArray[l]);
	}
	
	timeArray = getShiftTime(endTime);
	for (var l = 0; l < 3; l++)
	{
		inputArray.push(timeArray[l]);
	}
	
	inputArray.push(-1);
	
	insertShift(day, inputArray);
	
}



function getShiftTime(time)
{
	// This function takes a time index from the template builder, a value from 0 to 95,
	// and converts it to a set of three values that store time within the array "shifts".
	
	// 0 to 95 lays out all times possible for the day, from 6:00 AM to 5:45 AM the following day,
	// in 15 minute increments.
	
	var outputArray = [0, 0, 0];
	
	if (time < 24)
	{
		outputArray[2] = 0;
		
		remainder = time % 4;
		if (remainder == 0)
		{
			// :00
			outputArray[1] = 0;
		}
		else if (remainder == 1)
		{
			// :15
			outputArray[1] = 3;
		}
		else if (remainder == 2)
		{
			// :30
			outputArray[1] = 6;
		}
		else
		{
			// :45
			outputArray[1] = 9;
		}
		
		outputArray[0] = 5 + ((time - remainder) / 4);
	}
	else if (time > 24 && time < 72)
	{
		outputArray[2] = 1;
		
		remainder = time % 4;
		if (remainder == 0)
		{
			// :00
			outputArray[1] = 0;
		}
		else if (remainder == 1)
		{
			// :15
			outputArray[1] = 3;
		}
		else if (remainder == 2)
		{
			// :30
			outputArray[1] = 6;
		}
		else
		{
			// :45
			outputArray[1] = 9;
		}
		
		if (time - remainder == 24)
		{
			// 12 o'clock hour
			outputArray[0] = 11;
		}
		else
		{
			outputArray[0] = (((time - remainder) - 24) / 4) - 1
		}
	}
	else
	{
		outputArray[2] = 0;
		
				remainder = time % 4;
		if (remainder == 0)
		{
			// :00
			outputArray[1] = 0;
		}
		else if (remainder == 1)
		{
			// :15
			outputArray[1] = 3;
		}
		else if (remainder == 2)
		{
			// :30
			outputArray[1] = 6;
		}
		else
		{
			// :45
			outputArray[1] = 9;
		}
		
		if (time - remainder == 72)
		{
			// 12 o'clock hour
			outputArray[0] = 11;
		}
		else
		{
			outputArray[0] = (((time - remainder) - 72) / 4) - 1
		}
	}
	
	
	return outputArray;
	
}



function showTemplateAutoBuilder()
{
	document.getElementById("templateBuilderOverlay").style.visibility = "visible";
}

function hideTemplateAutoBuilder()
{
	document.getElementById("templateBuilderOverlay").style.visibility = "hidden";
}




function showAddShiftOverlay()
{
	document.getElementById("addShiftOverlay").style.visibility = "visible";
}

function hideAddShiftOverlay()
{
	document.getElementById("addShiftOverlay").style.visibility = "hidden";
}




function showEditShiftOverlay()
{
	// This function is called when the User clicks the "edit shift" button.  This function needs to call the "editShift" dialog and pre-set the inputs within to match selected shift.  If multiple shifts are selected, only the first is used and all others are ignored.
	
	var userSelection;
	var shiftRecord = [];
	
	// Iterate through all shifts currently displayed and collect the first selected one.
	selector = document.getElementById("mainDisplay");
	for (var i = 0; i < selector.length; i++)
	{
		if (selector[i].selected)
		{
			// Once we've found the first selected shift, store its index, stop looking, and move to the next step.
			userSelection = i;
			break;
		}
	}

	// The values stored in "shifts" are indices for the select boxes in the dialog we are about to summon... so our job is easy. We need only access each input and pre-set it before we call the dialog.
	shiftRecord = shifts[dayOfTheWeek][visibleIndex[userSelection]];
	
	// shiftRecord[0] ->       role: This is the index of the role that is filled by the shift.
	// shiftRecord[1] ->       bluePin? This is bool, stores whether a blue pin is needed for this shift.
	// shiftRecord[2, 3, 4] -> startTime: Time is stored in three values. ['hours index', 'minutes index',
	//						        'XM index (0 or 1)']
	// shiftRecord[5, 6, 7] -> endTime: Time is stored in three values. ['hours index', 'minutes index', 
	//								'XM index (0 or 1)']
	// shiftRecord[8] -> 	  crewName: This is the index of the crew member that will fill this role.
	// 								 -1 indicates an unallocated shift.
	
	//---------------------------------------------------------
	// Collect the data from each of the dialog's inputs.
	//---------------------------------------------------------
	
	// Day
	document.getElementById("editShiftDaySelector").selectedIndex = dayOfTheWeek;
	
	
	// Role
	document.getElementById("editShiftSelector").selectedIndex = shiftRecord[0];
	
	// Is a Blue Pin in this Role Required?
	if (shiftRecord[1] == 1)
	{
		document.getElementById("editShiftBluePin").checked = true;
	}
	else
	{
		document.getElementById("editShiftBluePin").checked = false;
	}
	
	// Start Time
	document.getElementById("editStartHour").selectedIndex = shiftRecord[2];
	document.getElementById("editStartMinute").selectedIndex = shiftRecord[3];
	document.getElementById("editStartXM").selectedIndex = shiftRecord[4];
	
	// End Time
	document.getElementById("editEndHour").selectedIndex = shiftRecord[5];
	document.getElementById("editEndMinute").selectedIndex= shiftRecord[6];
	document.getElementById("editEndXM").selectedIndex = shiftRecord[7];
	
	// Crew Member's Name
	if (shiftRecord[8] != -1)
	{
		document.getElementById("editShiftCrewBox").checked = true;
		
		document.getElementById("editShiftCrewSelector").disabled = false;
		document.getElementById("editShiftCrewSelector").selectedIndex = shiftRecord[8];
	}
	else
	{
		// No crew were allocated to this shift.  
		document.getElementById("editShiftCrewBox").checked = false;
		
		document.getElementById("editShiftCrewSelector").disabled = true;
		document.getElementById("editShiftCrewSelector").selectedIndex = 0;
	}
	
	// Finally, store index of this shift in the array "shifts".  This is stored in a hidden input that the User cannot change.
	document.getElementById("editShiftIndex").value = visibleIndex[userSelection];
	
	// Now that the shift's specifications have been pre-loaded into the dialog, call the editShift Dialog.
	document.getElementById("editShiftOverlay").style.visibility = "visible";
}

function hideEditShiftOverlay()
{
	document.getElementById("editShiftOverlay").style.visibility = "hidden";
}





function toggleAllocation(element1, element2)
{
	// This function enables/disables the select box containing crew names.  This function watches a checkbox located beside the select box.
	
	selector1 = document.getElementById(element1);
	selector2 = document.getElementById(element2); 
	
	if (selector1.checked)
	{
		selector2.disabled = false;
	}
	else
	{
		selector2.disabled = true;
	}
}







