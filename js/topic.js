/* jquery
*/

function parse_anchor (anchor) {
	
	var cmds = anchor.split("&");
	
	var cmdsLen = cmds.length;
	
	var cmdTuples = [];
	
	for (var i = 0;i<cmdsLen;i++)
	{
		var cmd = cmds[i];
		
		var cmdTuple = cmd.split("=");
		
		cmdTuples.push(cmdTuple);
	}
	
	return cmdTuples;
}

function change_anchor (name, value)
{
	var newAnchor = "#";
	var cmdFound = false;
	
	for (var i = 0; i < cmdTuplesLen; i++)
	{
		var cmdName = cmdTuples[i][0];
		
		if (cmdName == name)
		{
			cmdFound = true;
			cmdTuples[i][1] = value;
		}
		
		if (i != 0)
		{
			newAnchor = newAnchor + "&";
		}

		newAnchor = newAnchor + cmdTuples[i][0] + "=" + cmdTuples[i][1];
	}
	

	if (cmdFound == false)
	{
		if (cmdTuplesLen >= 1)
		{
			newAnchor = newAnchor + "&";
		}
		cmdTuples.push([name, value]);
		newAnchor = newAnchor + name + "=" + value;
	}

	window.location.hash = newAnchor;
}

function change_page (name, post_per_page)
{
	$('.number.selected').removeClass('selected');
	$('.number[name="'+name+'"]').addClass('selected');
	
	if ($('.number.first').hasClass('selected'))
	{
		$('a.newer').addClass('grey');
		//$('a.newer').attr('href', '');
		$('a.newer').removeClass('active');
	}
	else
	{
		$('a.newer').removeClass('grey');
		$('a.newer').addClass('active');
		//$('a.newer').attr('href', 'javascript: void();');
	}
	
	if ($('.number.last').hasClass('selected'))
	{
		$('a.older').addClass('grey');
		//$('a.older').attr('href', '');
		$('a.older').removeClass('active');
	}
	else
	{
		$('a.older').removeClass('grey');
		$('a.older').addClass('active');
		//$('a.older').attr('href', 'javascript: void();');
	}
	$('.post').addClass('noshow');
	for (i=0;i<post_per_page;i++)
	{
		$('.post[name="'+(parseInt(name)+i)+'"]').removeClass('noshow');
	}
	
	$(location).attr('href', $('.number.selected').attr('href'));
	
	change_anchor ("start", name);
	
	
}

function go_to_post (id, post_per_page)
{
	return;
}

var main = function() {

	//Textarea autosize function
	$('textarea').autosize();
	
	var anchor = window.location.hash.split('#')[1];
	
	cmdTuples = [];
	
	if (anchor){
		cmdTuples = parse_anchor(anchor);
	}
	
	cmdTuplesLen = cmdTuples.length;
	
	for (var i = 0; i < cmdTuplesLen;i++)
	{
		if (cmdTuples[i][0] == "start")
		{
			start = cmdTuples[i][1];
		}
	}
	
	//alert(window.location.hash.split('#')[1]);
	if (start != null)
	{
		change_page(start, post_per_page);
	}
	
	//Pagiation
	$('.number').click(function()
	{
		var name = $(this).attr('name');
		change_page(name, post_per_page);
	});
	
	$('a.older').click(function()
	{
		var name = $('.number.selected').attr('name');
		var oldnum = parseInt(name);
		var num = oldnum+parseInt(post_per_page);
		if (num > num_all_post)
		{
			num = oldnum;
		}
		num = num.toString();
		change_page(num, post_per_page);
	});
	
	$('a.newer').click(function() {
		var name = $('.number.selected').attr('name');
		var num = parseInt(name)-parseInt(post_per_page);
		if (num < 0)
		{
			num = 0;
		}
		num = num.toString();
		change_page(num, post_per_page);
	});
	
	//Edit button for each post
	$('.edit_btn').click(function() {
		var btn_name = $(this).attr('name');
		$('.text_body[name="'+btn_name+'"]').toggle();
		$('.edit_body[name="'+btn_name+'"]').toggle();
		$('.edit_body[name="'+btn_name+'"]').find('textarea').trigger('autosize.resize');
	});
	
	//New topic button
	$('#new_topic_btn').click(function() {
		$('#new_topic').toggle();
	});
	
	
};

$(document).ready(main);
