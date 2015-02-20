/* jquery
*/

function change_page (name, post_per_page)
{
	$('.number.selected').removeClass('selected');
	$('.number[name="'+name+'"]').addClass('selected');
	$('.post').addClass('noshow');
	for (i=0;i<post_per_page;i++)
	{
		$('.post[name="'+(parseInt(name)+i)+'"]').removeClass('noshow');
	}
	
	$(location).attr('href', $('.number.selected').attr('href'));
	window.location.hash = '#' + name;
}

function go_to_post (id, post_per_page)
{
	return;
}

var main = function() {

	//Textarea autosize function
	$('textarea').autosize();
	
	//alert(window.location.hash.split('#')[1]);
	if (window.location.hash.split('#')[1] != null)
	{
		change_page(window.location.hash.split('#')[1], post_per_page);
	}
	
	//Pagiation
	$('.number').click(function() {
		var name = $(this).attr('name');
		change_page(name, post_per_page);
	});
	
	$('a.older').click(function() {
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
	
	//
	$('#new_topic_btn').click(function() {
		$('#new_topic').toggle();
	});
	
	
};

$(document).ready(main);
