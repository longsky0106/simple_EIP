$(document).ready(function(){
	
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
		load_page(param);
	});
	
	// 搜尋按鈕按下時的功能
	$("#search_btn").click(function(){
		setTimeout(function(){
				$('#pagejump').html("取得分頁中...");
		}, 20);
		data = $("input[name=model]").val();
		let searchParams = new URLSearchParams(window.location.search);
		let param = searchParams.get('page');
		if(!param || typeof(data) !== 'undefined'){
			param = 1;
		}
		load_page(param);
	});
	
	// 瀏覽器按下上一頁/下一頁按鈕事件
	$(window).on('popstate', function(event) {
		let searchParams = new URLSearchParams(window.location.search);
		let param = searchParams.get('page');
		if(!param){
			param = 1;
		}
		load_page(param);
	});
	
	
});

// 分頁載入
function load_page(page){
	limit = $('#display_per_page').val();
	if (typeof(data) === 'undefined') {
		data = "";
	}
	$('#page_load_status').css("display","flex");
	
	let pagejump;
	$.get(root_path + 'show_all_prod_list.php?page=' + page + '&limit=' + limit + '&data=' + data, function (pagedata) {
		pagejump = $(pagedata).find('#pagejump');
	});
	
	$('#main_content_L').load(root_path + 'show_all_prod_list.php?page=' + page + '&limit=' + limit + '&data=' + data + ' .data_room_L', function(response, status, xhr) {
		if(status!="error"){
			$('#pagejump').html(pagejump);
			window.history.pushState({page: page}, "簡易EIP - 第" + page + "頁", "?page=" + page);
			setTimeout(function(){
				$('#page_load_status').css("display","none");
			}, 10);
		}else{
			$('#page_load_status').html("載入失敗!");
		}
	});
	

}



