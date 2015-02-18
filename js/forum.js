/* jquery
*/

/*Function to change topic pages.*/
function change_page (old_num, new_num) {
	if (new_num < 1){
		new_num = 1;
	}
	else if (new_num >= $('.number').length){
		new_num = $('.number').length;
	}
	if (old_num != 0){
		$('#number'.concat(old_num.toString())).removeClass('selected');
		$('#page'.concat(old_num.toString())).hide();
		$('#page'.concat(old_num.toString())).removeClass('selected');
	}
	
	$('#number'.concat(new_num.toString())).addClass('selected');
	$('#page'.concat(new_num.toString())).show();
	$('#page'.concat(new_num.toString())).addClass('selected');
	
	$('.newer').addClass('active');
	$('.older').addClass('active');
	
	if (new_num == 1){
		$('.newer').removeClass('active');
	}
	
	if (new_num == $('.number').length){
		$('.older').removeClass('active');
	}
	
	window.location = "#page=".concat(new_num.toString());
	
}

function current_page() {
	return $('.page.selected');
}

function current_page_id() {
	return $('.page.selected').attr('id');
}

function get_num(str) {
	return parseInt(str.substring(str.length-1,str.length));
}

var main = function() {
  
  /* Leaving the page number jquery disabled for now. The code may be useful later.
  
  $('.number').click(function() {
	change_page(get_num($('.number.selected').attr('id')), get_num($(this).attr('id')));
  });
  
  $('#older_btn').click(function() {
	change_page(get_num($('.number.selected').attr('id')), get_num($('.number.selected').attr('id'))+1);
  });
  
  $('#newer_btn').click(function() {
	change_page(get_num($('.number.selected').attr('id')), get_num($('.number.selected').attr('id'))-1);
  });
  
	
  var pagenum = get_num(window.location.hash);
  
  $('.page').hide();
  
  if (pagenum){
	change_page(0, pagenum);
  }else{
	change_page(0, 1);
  }

  $('.page.selected').show();
  */
  $('textarea').autosize();
  
  $('#new_topic_btn').click(function() {
	$('#new_topic').toggle();
  });
  
  //$('#new_topic').hide();
  
}

$(document).ready(main);
