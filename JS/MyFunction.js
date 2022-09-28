function draggable(element, draggable_limit_x, draggable_limit_y){
	//$('#debug1').html("上下移動限制: "+draggable_limit_y);
	$(element).draggable(
	{
		containment: [ 0, 0, draggable_limit_x - 40, draggable_limit_y],
		start: function(event,ui) {
			//var pos = ui.helper.offset();
			//alert(pos.top);
			var pos = ui.helper.offset();
			//$('#debug1').html("top: "+top);
			
			
		},
		drag: function(event,ui) {
			//window_width_check();
			window_width = $( window ).width();
			window_height = $( window ).height();
			load_content_width = $('#load_content').width();
			load_content_height = $('#load_content').height();
			var pos = ui.helper.offset();
			$('#debug2').html("pos.top: "+pos.top);
			// 選單右移
			if((`${pos.left}`> ($(element).width())/2)||window_width<index_menu_width+375){
				// 上下顯示 
				$('#index_main').css("display","initial");
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
			
		},
		stop: function(event,ui) {
			var top = getTop(ui.helper);
			//ui.helper.css('position', 'fixed');
			//ui.helper.css('top', top+"px");
			$('#debug3').html(top);
		}
	});
}

function getTop(ele)
{
    var eTop = ele.offset().top;
    var wTop = $(window).scrollTop();
    // var top = eTop - wTop;
    //var top = eTop + wTop;
	$('#debug3').html("eTop, wTop: "+eTop+", "+wTop);
	//alert(eTop+", "+wTop);
    var top = eTop;
	if(top<0){
		top = 0;
	}
    return top; 
}

function renewDraggable(){
	//alert("renewDraggable");
	// index_menu_width = $('#index_menu').outerWidth();
	index_menu_height = $('#index_menu').outerHeight();
	limit_width = window_width>load_content_width?window_width:load_content_width;
	limit_height = window_height>load_content_height?window_height:load_content_height;
	draggable_limit_x = limit_width - index_menu_width;
	draggable_limit_y = limit_height - index_menu_height;
	draggable('#index_menu',draggable_limit_x, draggable_limit_y);
}


$(window).resize(function() {
	window_width_check();
	renewDraggable();
	
});

function window_width_check(){
	window_width = $( window ).width();
	window_height = $( window ).height();
	load_content_width = $('#load_content').width();
	load_content_height = $('#load_content').height();
	if(window_width<index_menu_width+375){
		/* 上下顯示 */
		$('#index_main').css("display","initial");
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