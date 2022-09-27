$(document).ready(function(){

	$('#load_content').load('include/index_load_content.php');
	// window_width = $( window ).width();
	index_menu_width = $('#index_menu').outerWidth();
	$('#index_menu').css("width",index_menu_width);
	$('#index_menu').css("min-width",index_menu_width);
	window_width_check();
	renewDraggable();
});



