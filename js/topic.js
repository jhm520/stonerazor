/* jquery
*/

function change_page (name, post_per_page) {
	$('.number.selected').removeClass('selected');
	$('.number[name="'+name+'"]').addClass('selected');
	$('.post').addClass('noshow');
	for (i=0;i<post_per_page;i++)
	{
		$('.post[name="'+(parseInt(name)+i)+'"]').removeClass('noshow');
	}
	$(location).attr('href', $('.number.selected').attr('href'));
	location.hash = '#start=' + name;
}

var main = function() {
	/*
	var post_per_page = $('.post').length - $('.noshow').length;
	var page_per_load = $('.post').length/post_per_page;
	var post_per_load = post_per_page*post_per_load;
	*/

	//Textarea autosize function
	$('textarea').autosize();
	
	alert(window.location.hash.split('#')[1]);
	
	//Pagiation
	$('.number').click(function() {
		var name = $(this).attr('name');
		change_page(name, post_per_page);
	});
	
	$('a.older').click(function() {
		var name = $('.number.selected').attr('name');
		var num = parseInt(name)+post_per_page;
		num = num.toString();
		change_page(num, post_per_page);
	});
	
	$('a.newer').click(function() {
		var name = $('.number.selected').attr('name');
		var num = parseInt(name)-post_per_page;
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
