var bShow_debug_msg = 1;
//index_menu_height = 0;
$(document).ready(function(){

	if(bShow_debug_msg==1){
		$('#debug_content').css("display","block");
	}
	//$('#load_content').load('include/index_load_content.php');
	load_content();
	// window_width = $( window ).width();
	index_menu_width = $('#index_menu').outerWidth();
	index_menu_height = $('#index_menu').outerHeight();
	$('#index_menu').css("width",index_menu_width);
	$('#index_menu').css("min-width",index_menu_width);
	$('body').css("overflow","hidden");
	window_width_check();
	renewDraggable();
	
});



