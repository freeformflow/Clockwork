// Place all the behaviors and hooks related to the matching controller here.
// All this logic will automatically be available in application.js.
// You can use CoffeeScript in this file: http://coffeescript.org/



// This is a global Object container that will encapsulate any global variables we'll need.
var GlobalStore = {}; 


// These two functions control hiding and revealing modal dialogs.
function ShowOverlay(id)
{
	document.getElementById('fade').style.display ="block";
	document.getElementById(id).style.display = "block";

	if (id == 'delete_person')
	{
		PopulateSelectBox('select_box_delete');
	}
}

function HideOverlay(id)
{
  document.getElementById('fade').style.display ="none";
  document.getElementById(id).style.display = "none";

	if (id == "add_person")
	{
		document.getElementById('add_last_name').value = "";
    document.getElementById('add_first_name').value = "";
    document.getElementById('add_email').value = "";	
	}	
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
  else if (verb == 'POST')
  {
    A = 'first_name=' + document.getElementById('add_first_name').value;
    B = '&last_name=' + document.getElementById('add_last_name').value;
    C = '&email=' + document.getElementById('add_email').value;

    form = A + B + C;
  }
  else if (verb == 'DELETE')
  {
    index = document.getElementById('select_box_delete').selectedIndex;
    form = 'id=' + GlobalStore.keyIndex[index];
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
		cell.innerHTML = x[i].last_name;

		cell = row.insertCell(1);
		cell.innerHTML = x[i].first_name;
	
		cell = row.insertCell(2);
		cell.innerHTML = x[i].email;	
	}

	// We also want to store the primary key for each employee in the Database, so we 
	// know how to ask for a specific person when needed.  This key doesn't need to be
	// displayed, but it should be persistent in memory while the page is loaded.  That's	
	// why we will store in as an array in GlobalStore, an object container with global scope.
	GlobalStore.keyIndex = [];

	for (var i = 0; i < x.length; i++)
	{
		GlobalStore.keyIndex.push( x[i].id );
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
	document.getElementById('edit_last_name').value = row.cells[0].innerHTML;
	document.getElementById('edit_first_name').value = row.cells[1].innerHTML;
	document.getElementById('edit_email').value = row.cells[2].innerHTML;

	GlobalStore.selectedEmployee = row.rowIndex;

	ShowOverlay('edit_person');
}


