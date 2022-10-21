$(document).ready(function(){
	go_search = 0;
	// 路徑檢查
	root_path = window.location.pathname;
	root_path = root_path.indexOf("php");
	if(root_path < 0){
		root_path = window.location.pathname + "/system/";
	}else{
		root_path = "";
	}

	// 變更每頁顯示數量
	$(document).on('change', '#display_per_page', function(){
		setTimeout(function(){
				$('#pagejump').html("取得分頁中...");
		}, 20);
		let searchParams = new URLSearchParams(window.location.search);
		let param = searchParams.get('page');
		if(!param){
			param = 1;
		}
		load_page(param, search_text);
	});
	
	// 搜尋按鈕按下時的功能
	$("#search_btn").click(function(){
		setTimeout(function(){
				$('#pagejump').html("取得分頁中...");
		}, 20);
		search_text = $("input[name=model]").val();
		go_search = 1;
		let searchParams = new URLSearchParams(window.location.search);
		let param = searchParams.get('page');
		if(!param || typeof(data) !== 'undefined'){
			param = 1;
		}
		load_page(param, search_text);
	});
	
	// 瀏覽器按下上一頁/下一頁按鈕事件
	$(window).on('popstate', function(event) {
		let searchParams = new URLSearchParams(window.location.search);
		let param = searchParams.get('page');
		if(!param){
			param = 1;
		}
		load_page(param, search_text);
	});
	
	
});

// 分頁載入
function load_page(page, search_text){
	
	limit = $('#display_per_page').val();
	if (typeof(search_text) === 'undefined') {
		search_text = "";
	}
	$('#page_load_status').css("display","flex");
	
	let pagejump;
	$.get(root_path + 'show_all_prod_list.php?page=' + page + '&limit=' + limit + '&data=' + search_text, function (pagedata) {
		pagejump = $(pagedata).find('#pagejump');
	});
	
	$('#main_content_L').load(root_path + 'show_all_prod_list.php?page=' + page + '&limit=' + limit + '&data=' + search_text + ' .data_room_L', function(response, status, xhr) {
		if(status!="error"){
			$('#pagejump').html(pagejump);
			// window.history.pushState({current_page: show_all_prod_list.php}, "簡易EIP - 第" + page + "頁", "?page=" + page);
			setTimeout(function(){
				$('#page_load_status').css("display","none");
			}, 10);
		}else{
			$('#page_load_status').html("載入失敗!");
		}
	});
	

}



