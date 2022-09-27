function draggable(element, draggable_limit_x, draggable_limit_y){
$('#test3').html(draggable_limit_y);
	$(element).draggable(
	{
		containment: [ 0, 0, draggable_limit_x - 40, draggable_limit_y],
		start: function(event,ui) {
			
		},
		drag: function(event,ui) {
			window_width_check();
			$('#test').html(`右側寬度:${load_content_width}
			<br>視窗寬度:${window_width}
			<br>選單+375:${index_menu_width+375}`);
			
			var pos = ui.helper.offset();
			/* 如果可拖動元素右移 */
			if(`${pos.left}`> ($(element).width())/2){
				/* 上下顯示 */
				$('#index_main').css("display","initial");
				$('#test2').html("true");
			}

			if((`${pos.top}`> ($(element).height())/2 && `${pos.left}`> ($(element).width())/2)){
				$('#load_content').css("margin-top",index_menu_height * -1);
			}else{
				$('#load_content').css("margin-top",0);
			}
		},
		stop: function(event,ui) {
			
		}
	});
}

function renewDraggable(){
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
		$('#test2').html("true");
	}else{
		/* 右側內容未超出視窗寬度才變更CSS*/
		if(
			`${window_width}>${index_menu_width+375}`
		){
			/* 並排顯示左選單與右內容*/
			$('#index_main').css("display","flex");
		}
		$('#test2').empty();
	}
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
	
	
	
	
	$('#test').html(`右側寬度:${load_content_width}
				<br>視窗寬度:${window_width}
				<br>選單+375:${index_menu_width+375}`);

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