$(document).ready(function(){

	$(document).on('change', '#display_per_page', function(){
		let searchParams = new URLSearchParams(window.location.search);
		let param = searchParams.get('page');
		if(!param){
			param = 1;
		}
		load_page(param);
	});
	
	
	
	
	
	
});

// 連結$('#pagejump').load('show_all_prod_list.php?page=' + page + '&limit=' + limit + ' #pagejump');
function load_page(page){
	limit = $('#display_per_page').val();
	$('#page_load_status').html("載入中...");
	$('#pagejump').load('show_all_prod_list.php?page=' + page + '&limit=' + limit + ' #pagejump', function(response, status, xhr) {

	});
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



