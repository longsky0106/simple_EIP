var bShow_debug_msg = 0;
$(document).ready(function(){

	if(bShow_debug_msg==1){
		$('#debug_content').css("display","block");
	}
	//$('#load_content').load('include/index_load_content.php');
	load_content();
	index_menu_width = $('#index_menu').outerWidth();
	index_menu_height = $('#index_menu').outerHeight();
	$('#index_menu').css("width",index_menu_width);
	$('#index_menu').css("min-width",index_menu_width);
	// Get_Width_Height();
	renewDraggable();
	
	$('#menu_toggle_button_L').click(function(){
		if($('#index_menu').css("left").replace("px","")!=0&&detectMob()){
			$('#index_menu').css(
				{
					"left":0,
					"right":"auto"
				}
			);
		}else{
			$('#select_item').toggle();
		}
	});
	
	$('#menu_toggle_button_R').click(function(){
		if($('#index_menu').css("right").replace("px","")!=0&&detectMob()){
			$('#index_menu').css(
				{
					"left":"auto",
					"right":0
				}
			);
		}else{
			$('#select_item').toggle();
		}
	});

});



