/****************************

Sam's basic-ass alert box
script.

This should be replaced later
down the road by something
tidier.

*****************************/

function alert_open(alert_id, ref_id)
{
  document.getElementById('alertframe').style.display = 'block';
	document.getElementById(alert_id).style.display = 'block';
	
	if (ref_id)
	{
	  document.getElementById('deletebox_yes').setAttribute("href", ref_id);
	}
}

function alert_close()
	{
	  var alertids = ["alertframe", "loginbox", "registerbox", "newtopicbox", "deletebox"];
	  
	  alertlen = alertids.length;
	  
	  for (var i=0;i<alertlen;i++){
	    element = document.getElementById(alertids[i]);
	    if (element){
	      element.style.display = 'none';
	    }
	  }
	}
