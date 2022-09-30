var hack_margin_top_fix = "false";
var bShow_debug_msg = 0;

function draggable(element, draggable_limit_x, draggable_limit_y){
	if(bShow_debug_msg==1){
		$('#debug_content').css("display","block");
	}	
	// 首頁選單
	$(element).draggable(
	{
		containment: [ 0, 0, draggable_limit_x - 40, draggable_limit_y],
		start: function(event,ui) {
			
			// Hack方式margin-top補正draggable下移復位的問題
			hack_margin_top_fix_down(ui);
		},
		drag: function(event,ui) {
			
			// Hack方式margin-top補正draggable上移的問題
			hack_margin_top_fix_up(ui);

			window_width = $( window ).width();
			window_height = $( window ).height();
			load_content_width = $('#load_content').width();
			load_content_height = $('#load_content').height();
			var pos = ui.helper.offset();
			
			// 選單右移
			if((`${pos.left}`> ($(element).width())/2)||window_width<index_menu_width+375){
				// 上下顯示 
				$('#index_main').css("display","inline");
				hack_margin_top_fix = "false";
			}else{
				// 右側內容未超出視窗寬度才變更CSS
				if(
					`${window_width}>${index_menu_width+375}`
				){
					// 並排顯示左選單與右內容
					$('#index_main').css("display","flex");
				}
			}
			
			// 選單下移
			if((`${pos.top}`> ($(element).height())/2 && `${pos.left}`> ($(element).width())/2)){
				$('#load_content').css("margin-top",index_menu_height * -1);
			}else{
				$('#load_content').css("margin-top",0);
			}
			load_content_width_check_to_CSS();
			
			// 顯示除錯用訊息
			if(bShow_debug_msg==1){
				show_debug_msg(ui);
			}
			
		},
		stop: function(event,ui) {
			
		}
	});
}

// 重新設定Draggable
function renewDraggable(){
	// index_menu_width = $('#index_menu').outerWidth();
	index_menu_height = $('#index_menu').outerHeight();
	limit_width = window_width>load_content_width?window_width:load_content_width;
	limit_height = window_height>load_content_height?window_height:load_content_height;
	draggable_limit_x = limit_width - index_menu_width;
	draggable_limit_y = limit_height - index_menu_height;
	draggable('#index_menu',draggable_limit_x, draggable_limit_y);
}

// 視窗大小變更則
$(window).resize(function(){
	window_width_check();
	renewDraggable();
});

// 檢查視窗與右側顯示區塊並依條件修改CSS
function window_width_check(){
	window_width = $( window ).width();
	window_height = $( window ).height();
	load_content_width = $('#load_content').width();
	load_content_height = $('#load_content').height();
	if(window_width<index_menu_width+375){
		/* 上下顯示 */
		$('#index_main').css("display","inline");
	}else{
		/* 右側內容未超出視窗寬度才變更CSS*/
		if(
			`${window_width}>${index_menu_width+375}`
		){
			/* 並排顯示左選單與右內容*/
			$('#index_main').css("display","flex");
		}
	}
	load_content_width_check_to_CSS();
}

// 檢查右側顯示區塊並依條件在CSS加入適應大小的class
function load_content_width_check_to_CSS(){
	if(load_content_width<1148){	
		$('#zh-tw_spec').addClass('zh-tw_spec_fit');
		$('#en-us_spec').addClass('en-us_spec_fit');
		$('#title_en').addClass('title_en_fit');
		$('#title_example').addClass('title_example_fit');
		
		$('.spec_input_aren').addClass('spec_input_aren_fit');
		$('.ex_tw').addClass('ex_tw_fit');
		$('.ex_en').addClass('ex_en_fit');
		$('.ex_both').addClass('ex_both_fit');
		$('.example_btn').addClass('example_btn_fit');
	}else{
		$('#zh-tw_spec').removeClass('zh-tw_spec_fit');
		$('#en-us_spec').removeClass('en-us_spec_fit');
		$('#title_en').removeClass('title_en_fit');
		$('#title_example').removeClass('title_example_fit');
		
		$(".spec_input_aren").removeClass('spec_input_aren_fit');
		$(".ex_tw").removeClass('ex_tw_fit');
		$(".ex_en").removeClass('ex_en_fit');
		$(".ex_both").removeClass('ex_both_fit');
		$(".example_btn").removeClass('example_btn_fit');
	}
}

// 選單連結項目
function load_content(n){
	switch (n){
		case 1:
			$('#load_content').load('http://192.168.1.56/positest/input_new2.php');
			break;
		case 2:
			// $('#load_content').load('input_update.php');
			$('#load_content').load('input_update.php', function() {
				setTimeout(function(){
					window_width_check();
					renewDraggable();
				}, 10);
			});
			break;
		case 3:
			$('#load_content').load('system/show_online_user.php');
			break;
		case 0:
			var url = "/" + window.location.pathname.split('/')[1] + "/logout.php";
			//$('#load_content').load(url);
			var data = "logout", el_to_msg = '#msg';
			ajax_post(url, data);
			break;
		default:
			$('#load_content').load('include/index_load_content.php');
			break;
	}

}

// ajax post方法(網址,資料,回應資料顯示區塊)
function ajax_post(url,data,el_to_msg){
	$.post(url, {data: data})
	.done(function(result){
		$(el_to_msg).html(result);
		if(result.indexOf("登入成功")>=0){
			//alert("登入成功");
			var url = "/" + window.location.pathname.split('/')[1] + "/system/redirect_login.php";
			//window.location.replace(url);
			setTimeout(
				function() 
				{
					$('#load_content').load(url);
				}, 1500);
		}else if(result.indexOf("密碼錯誤")>=0 || result.indexOf("帳號不存在")>=0){
			setTimeout(
				function(){
					$(el_to_msg).empty();
				}
			, 1200);
			}else if(result.indexOf("登出")>=0){
				alert(result);
			}
	}).fail(function(){
		alert("錯誤: 連線失敗!");
		$(el_to_msg).html("錯誤: 連線失敗!");
		setTimeout(
			function(){
				$(el_to_msg).empty();
			}
		, 1500);
		
	});
}

// Hack方式margin-top補正draggable下移復位的問題
function hack_margin_top_fix_down(ui){
	var pos = ui.helper.offset(); // 初始化座標偏移
	
	// 移動偏移量 = 視窗高度 - 選單高度
	var drag_top_offset = window_height-index_menu_height;
	var margin_top = $('#index_menu').css('margin-top').replace("px","");
	
	var index_main_CSS_display = $('#index_main').css("display");
	
	if($(window).scrollTop()&& pos.top > drag_top_offset){ // 如果視窗有向下滾動
		// 如果draggable的自身ui.helper CSS數值 top 減去JQuery 查詢到的margin-top大於移動偏移量
		if((pos.top-margin_top)>=drag_top_offset){

			// 如果#index_main CSS數值display為initial就取消Hack方式margin-top補正	
			if(index_main_CSS_display=="inline"){	
				ui.helper.css('margin-top', 0+'px');
				ui.helper.css('top', pos.top+'px');
				hack_margin_top_fix = "false";
			}else{
				// Hack方式margin-top補正 
				ui.helper.css('margin-top', (pos.top-drag_top_offset)+'px');
			}
			hack_margin_top_fix = "true";
			
		}
		
	}else{
		// 如果JQuery 查詢到的margin-top數值為0
		if(margin_top==0){
			// 取消Hack方式margin-top補正 
			ui.helper.css('margin-top', 0+'px');
			hack_margin_top_fix = "false";
		}
	}
}

// Hack方式margin-top補正draggable上移的問題
function hack_margin_top_fix_up(ui){
	var pos = ui.helper.offset(); // 初始化座標偏移
	var margin_top = $('#index_menu').css('margin-top').replace("px","");
	var margin_top_reduce_px = 6;
	
	if((pos.top<=margin_top)){ // 選單往上移
	
		// 如果自身ui.helper CSS數值 top 大於或等於${margin_top_reduce_px}px
		if(pos.top>=margin_top_reduce_px){
			
			var r_margin_top = ui.helper.css('margin-top').replace("px","");
			
			// 在每次向上拖移時將目前margin-top減5px
			ui.helper.css('margin-top', (r_margin_top-margin_top_reduce_px)+'px');					
		}else{
			
			// 如果自身ui.helper CSS數值 top 小於5px則直接歸0
			ui.helper.css('margin-top', 0+'px');
			hack_margin_top_fix = "false";
		}
	}
}

// 顯示除錯用訊息
function show_debug_msg(ui){
	var pos = ui.helper.offset(); // 初始化座標偏移
	var margin_top = $('#index_menu').css('margin-top').replace("px","");
	var drag_top_offset = window_height-index_menu_height;
	var index_main_CSS_display = $('#index_main').css("display");
	
	$('#debug1').html("window_height: "+window_height);
	$('#debug2').html("index_menu_height: "+index_menu_height);
	$('#debug3').html("w_height-m_height: "+drag_top_offset);
	$('#debug4').html("pos.top: "+pos.top);
	$('#debug5').html("jq margin-top: "+margin_top+"px");
	$('#debug6').html("jq top: "+$('#index_menu').css('top'));
	$('#debug7').html("Hack margin-top fix: "+hack_margin_top_fix);
	$('#debug8').html("pos.top - jq margin-top:"+(pos.top-margin_top));
	$('#debug9').html("|"+index_main_CSS_display+"|");
}