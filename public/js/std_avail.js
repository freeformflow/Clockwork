// This is a global Object container that will encapsulate any global variables we'll need.
var GlobalStore = {}; 

// These two functions control hiding and revealing modal dialogs.
function ShowOverlay(id)
{
	document.getElementById('fade').style.display ="block";
	document.getElementById(id).style.display = "block";

}

function HideOverlay(id)
{
  document.getElementById('fade').style.display ="none";
  document.getElementById(id).style.display = "none";

}



// The code in this section handles AJAX methods for updating the page.
function MakeAjaxCall(verb, overlay_name)
{
	var request = new XMLHttpRequest();

	// Bindings
	request.onreadystatechange = function()
	{
		if (request.readyState == 4 && request.status == 200)
		{
			UnpackAjaxResponse(request.responseText);
		}
	} 

	// Prepare form data (if any).
	var form = PrepareForm(verb);
	// Perform the update.
	request.open(verb, '/views/people.erb', true);
	request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	request.send(form);

	if (overlay_name != '')
	{
		HideOverlay(overlay_name);
	}
}


// This function prepares the string to be sent to the server.  This string will instruct the 
// server what to do to the database.
function PrepareForm(verb)
{
  if (verb == 'LINK')
  {
    form = null;
  }
  else if (verb == 'PUT')
  {
    A = 'first_name=' + document.getElementById('edit_first_name').value;
    B = '&last_name=' + document.getElementById('edit_last_name').value;
    C = '&email=' + document.getElementById('edit_email').value;
		D = '&id=' + GlobalStore.keyIndex[GlobalStore.selectedEmployee - 1];

    form = A + B + C + D;
  }

	return form;
}

// Data is passed from the server to this page via a JSON object.  Here, we
// unpack the data and make sure the table is displayed properly for the User.
function UnpackAjaxResponse(response)
{
	// Luckily modern browsers will parse this for us.
 	var x = JSON.parse(response);

	// So all we have to do now is go through each record and display the data.
  var table = document.getElementById('main_view');
	var row;

	// First delete any data present in the table.
	while (table.rows.length > 1)
	{
		table.deleteRow(1);
	}

	// Now place the data into new cells.
	for (var i = 0; i < x.length; i++)
	{
		row = table.insertRow(i + 1);
		
		// also, make this row responsive to double clicks.
		//row.ondblclick = ShowOverlay('edit_person');	
		row.setAttribute('ondblclick', "PopulateEditField(this);" );	

		cell = row.insertCell(0);
		cell.innerHTML = x[i].last_name + ", " + x[i].first_name;

		time_bar = document.createElement('canvas');
		ctx = time_bar.getContext("2d");	
		ctx.fillStyle = "rgb(200,0,0)";
		ctx.fillRect (0, 0, time_bar.width, time_bar.height);
		cell = row.insertCell(1);
		cell.appendChild(time_bar);		

    time_bar = document.createElement('canvas');
    ctx = time_bar.getContext("2d");
    ctx.fillStyle = "rgb(200,0,0)";
    ctx.fillRect (0, 0, time_bar.width, time_bar.height);
    cell = row.insertCell(2);
    cell.appendChild(time_bar);

    time_bar = document.createElement('canvas');
    ctx = time_bar.getContext("2d");
    ctx.fillStyle = "rgb(200,0,0)";
    ctx.fillRect (0, 0, time_bar.width, time_bar.height);
    cell = row.insertCell(3);
    cell.appendChild(time_bar);

    time_bar = document.createElement('canvas');
    ctx = time_bar.getContext("2d");
    ctx.fillStyle = "rgb(200,0,0)";
    ctx.fillRect (0, 0, time_bar.width, time_bar.height);
    cell = row.insertCell(4);
    cell.appendChild(time_bar);

    time_bar = document.createElement('canvas');
    ctx = time_bar.getContext("2d");
    ctx.fillStyle = "rgb(200,0,0)";
    ctx.fillRect (0, 0, time_bar.width, time_bar.height);
    cell = row.insertCell(5);
    cell.appendChild(time_bar);

    time_bar = document.createElement('canvas');
    ctx = time_bar.getContext("2d");
    ctx.fillStyle = "rgb(200,0,0)";
    ctx.fillRect (0, 0, time_bar.width, time_bar.height);
    cell = row.insertCell(6);
    cell.appendChild(time_bar);

    time_bar = document.createElement('canvas');
    ctx = time_bar.getContext("2d");
    ctx.fillStyle = "rgb(200,0,0)";
    ctx.fillRect (0, 0, time_bar.width, time_bar.height);
    cell = row.insertCell(7);
    cell.appendChild(time_bar);

	}

	// We also want to store the primary key for each employee in the Database, so we 
	// know how to ask for a specific person when needed.  This key doesn't need to be
	// displayed, but it should be persistent in memory while the page is loaded.  That's	
	// why we will store in as an array in GlobalStore, an object container with global scope.
	GlobalStore.keyIndex = [];
	GlobalStore.std_avail = [];

	for (var i = 0; i < x.length; i++)
	{
		GlobalStore.keyIndex.push( x[i].id );

		std_avail = [];
		std_avail.push(x[i].mon_start);
		std_avail.push(x[i].mon_end);
		
		GlobalStore.std_avail.push( std_avail );
	} 
}


// This function populates the select box with the names of current employees
// and keeps track of updates. 
function PopulateSelectBox(id)
{
	// This is the select box we wish to adjust, identified in the DOM.
	var box = document.getElementById(id).options;

	// First, clear any options currently in this select box.
	while (box.length > 0)
	{
		box.remove(0);
	}

	// Now, pull all names from the main table and place them in this select box.
	var name_list = document.getElementById('main_view').rows;
	var text_input = "";
	for (var i = 1; i < name_list.length; i++)
	{
		text_input = name_list[i].cells[0].innerHTML + ", " + name_list[i].cells[1].innerHTML;
		box[box.length] = new Option(text_input, text_input);
	}
}

// This function populates the text fields with all current info on this employee.
function PopulateEditField(row)
{
	document.getElementById('employee_label').innerHTML = row.cells[0].innerHTML;

	GlobalStore.selectedEmployee = row.rowIndex;

	alert(GlobalStore.std_avail[row.rowIndex - 1]);	
	ShowOverlay('edit_avil');
}

