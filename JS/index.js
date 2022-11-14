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
	$('#menu_title').click(function(){
		//如果為行動裝置
		if(detectMob()){
			if(!$(window).scrollTop()){
				// 顯示或隱藏選單
				$('#select_item').toggle(100, function() {
					var margin_top = $('#index_menu').outerHeight() + 6;
					$('#load_content').css("margin-top",margin_top+"px");
				});				
			}else{
				// 顯示或隱藏選單
				$('#select_item').toggle(100);				
			}

		}
	
	});
	
	// 左選單按鈕
	$('#menu_toggle_button').click(function(){
		
		//如果為行動裝置
		if(detectMob()){
			// 如果選單沒靠左
			if($('#index_menu').css("left").replace("px","")!=0){
				$('#index_menu').css(
					{
						"left":0,
						"right":"auto"
					}
				);
			}
			if(!$(window).scrollTop()){
				// 顯示或隱藏選單
				$('#select_item').toggle(100, function() {
					var margin_top = $('#index_menu').outerHeight() + 6;
					$('#load_content').css("margin-top",margin_top+"px");
				});				
			}else{
				// 顯示或隱藏選單
				$('#select_item').toggle(100);				
			}
		}else{
			// 顯示或隱藏選單
			$('#select_item').toggle(100);
		}
		
		
		
		
	});

});



