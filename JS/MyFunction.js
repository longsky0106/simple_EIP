function draggable(element){
	// 首頁選單
	$(element).draggable(
	{
		handle: "#menu_title",
		containment: "#index_main",
		start: function(event,ui) {
			//ui.helper.css("position","fixed");
			ui_helper_css = ui.helper.css("position");
		},
		drag: function(event,ui) {
			Get_Width_Height();
			var pos = ui.helper.offset();
			
			// 選單右移
			if((`${pos.left}`> ($(element).width())/2)||window_width<index_menu_width+375){
				// 上下顯示 
				$('#index_main').css("flex-direction","column");
			}else{
				// 右側內容未超出視窗寬度才變更CSS
				if(
					`${window_width}>${index_menu_width+375}`
				){
					// 並排顯示左選單與右內容
					$('#index_main').css("flex-direction","row");
				}
			}
			
			// 選單下移
			if(
				`${pos.top}`> ($(element).height())/2 
				&& `${pos.left}`> ($(element).width())/2
				&& ui_helper_css!="fixed"
			){
				$('#load_content').css("margin-top",index_menu_height * -1);
			}else{
				$('#load_content').css("margin-top",0);
			}
			load_content_width_check_to_CSS();
			
			// 顯示除錯用訊息
			if(bShow_debug_msg==1){
				show_debug_msg(ui);
			}
			$('#debug10').html("debug10");
		},
		stop: function(event,ui) {

			// 防止選單跑出畫面上方
			if(ui.helper.css("top").replace("px","")<0){
				ui.helper.css("top",0);
			}
			
			// 防止選單跑出畫面下方
			if(ui.helper.css("top").replace("px","")>(window_height-index_menu_height)){
				ui.helper.css("top",(window_height-index_menu_height));
				
			}		
		}
	});
}

// 重新設定Draggable
function renewDraggable(){
	draggable('#index_menu');
}

// 視窗大小變更
$(window).resize(function() {
    clearTimeout(window.resizedFinished);
    window.resizedFinished = setTimeout(function(){
		window_width_check();
    }, 250);
});

// 取得視窗與載入區域寬高
function Get_Width_Height(){
	window_width = $( window ).width();
	window_height = $( window ).height();
	load_content_width = $('#load_content').width();
	load_content_height = $('#load_content').height();
}

// 檢查視窗與右側顯示區塊並依條件修改CSS
function window_width_check(){
	Get_Width_Height();
	if(window_width<index_menu_width+375){
		/* 上下顯示 */
		$('#index_main').css("flex-direction","column");
	}else{
		/* 右側內容未超出視窗寬度才變更CSS*/
		if(
			`${window_width}>${index_menu_width+375}`
		){
			/* 並排顯示左選單與右內容*/
			$('#index_main').css("flex-direction","row");
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
			$('#load_content').load('http://192.168.1.56/positest/input_new2.php', function() {
				setTimeout(function(){
					window_width_check();
				}, 10);
			});
			break;
		case 2:
			// $('#load_content').load('input_update.php');
			$('#load_content').load('system/show_all_prod_list.php', function() {
				setTimeout(function(){
					window_width_check();
				}, 10);
			});
			break;
		case 3:
			$('#load_content').load('system/show_online_user.php', function() {
				setTimeout(function(){
					window_width_check();
				}, 10);
			});
			break;
		case 0:
			var url = "/" + window.location.pathname.split('/')[1] + "/logout.php";
			//$('#load_content').load(url);
			var data = "logout", el_to_msg = '#msg';
			ajax_post(url, data);
			break;
		default:
			$('#load_content').load('include/index_load_content.php', function() {
				setTimeout(function(){
					window_width_check();
				}, 10);
			});
			break;
	}

}

// ajax post方法(網址,資料,回應資料顯示區塊)
function ajax_post(url,data,el_to_msg,select_ele){
	$.post(url, {data: data})
	.done(function(result){
		// $(el_to_msg).html(result);
		$(el_to_msg).html(function() {
			if(select_ele){
				result = $(el_to_msg).html(result).find(select_ele);
			}
			return result;
		});
		if(result.indexOf("登入成功")>=0){
			
			//alert("登入成功");
			var url = "/" + window.location.pathname.split('/')[1] + "/system/redirect_login.php";
			setTimeout(
				function() 
				{
					$('#load_content').load(url);
				}
			, 1500);
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

// 偵測是否為行動裝置
function detectMob(){
    const toMatch = [
        /Android/i,
        /webOS/i,
        /iPhone/i,
        /iPad/i,
        /iPod/i,
        /BlackBerry/i,
        /Windows Phone/i
    ];
    
    return toMatch.some((toMatchItem) => {
        return navigator.userAgent.match(toMatchItem);
    });
}

// 顯示除錯用訊息
function show_debug_msg(ui){
	var pos = ui.helper.offset(); // 初始化座標偏移
	var margin_top = $('#index_menu').css('margin-top').replace("px","");
	var drag_top_offset = window_height-index_menu_height;
	var index_main_CSS_flex_direction = $('#index_main').css("flex-direction");
	
	$('#debug0').html("load_content_height: "+load_content_height);
	$('#debug1').html("window_height: "+window_height);
	$('#debug2').html("index_menu_height: "+index_menu_height);
	$('#debug3').html("w_height-m_height: "+drag_top_offset);
	$('#debug4').html("pos.top: "+pos.top);
	$('#debug5').html("jq margin-top: "+margin_top+"px");
	$('#debug6').html("jq top: "+$('#index_menu').css('top'));
	$('#debug7').html("debug7");
	$('#debug8').html("pos.top - jq margin-top:"+(pos.top-margin_top));
	$('#debug9').html("|"+index_main_CSS_flex_direction+"|");
	$('#debug10').html("debug10: ");
}