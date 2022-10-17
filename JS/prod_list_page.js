$(document).ready(function(){

});

// 連結
function load_page(page){
	limit = $(':selected').val();
	$('#page_load_status').html("載入中...");
	$('#main_content_L').load('show_all_prod_list.php?page=' + page + '&limit=' + limit + ' .data_room_L', function(response, status, xhr) {
		if(status!="error"){
			window.history.pushState({page: page}, "簡易EIP - 第" + page + "頁", "?page=" + page);
			$('#page_load_status').empty();
			setTimeout(function(){
				window_width_check();
			}, 10);
		}else{
			$('#page_load_status').html("載入失敗!");
		}
		
		
	});

}



