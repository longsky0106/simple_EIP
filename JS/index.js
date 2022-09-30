var hack_margin_top_fix = "false";
var bShow_debug_msg = 1;
window.current_page_n = "";
limit_height = 400;
//var limit_height;
$(document).ready(function(){
	/*$('html, body').css({
		overflow: 'hidden',
		height: '100%'
	});*/
		
	
	
	if(bShow_debug_msg==1){
		$('#debug_content').css("display","block");
	}
	//$('#load_content').load('include/index_load_content.php');
	window.current_page_n = load_content();
	// window_width = $( window ).width();
	index_menu_width = $('#index_menu').outerWidth();
	$('#index_menu').css("width",index_menu_width);
	$('#index_menu').css("min-width",index_menu_width);
	$('body').css("overflow","hidden");
	window_width_check();
	renewDraggable(window.current_page_n);
	
	//alert(current_page_n);
});



