// 顯示偵錯訊息
var bShow_debug_msg = 0;

$(document).ready(function(){
	
	// 如果開啟顯示偵錯訊息
	if(bShow_debug_msg==1){
		$('#debug_content').css("display","block");
	}
	
	load_content();
	index_menu_width = $('#index_menu').outerWidth();
	index_menu_height = $('#index_menu').outerHeight();
	$('#index_menu').css("width",index_menu_width);
	$('#index_menu').css("min-width",index_menu_width);
	
	// Get_Width_Height();
	renewDraggable();
	
	// 左選單按鈕
	$('#menu_toggle_button_L').click(function(){

		// 如果選單沒靠左且為行動裝置
		if($('#index_menu').css("left").replace("px","")!=0&&detectMob()){
			$('#index_menu').css(
				{
					"left":0,
					"right":"auto"
				}
			);
		}else{
			
			// 顯示或隱藏選單
			$('#select_item').toggle();
		}

	});
	
	// 右選單按鈕
	$('#menu_toggle_button_R').click(function(){
		
		// 如果選單沒靠右且為行動裝置
		if($('#index_menu').css("right").replace("px","")!=0&&detectMob()){
			$('#index_menu').css(
				{
					"left":"auto",
					"right":0
				}
			);
		}else{
			
			// 顯示或隱藏選單
			$('#select_item').toggle();
		}
	});

});



