$(document).ready(function(){

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
	
});

function load_page(page){
	limit = $('#display_per_page').val();
	if (typeof(data) === 'undefined') {
		data = "";
	}
	$('#page_load_status').css("display","flex");
	
	let pagejump;
	$.get('show_all_prod_list.php?page=' + page + '&limit=' + limit + '&data=' + data, function (pagedata) {
		pagejump = $(pagedata).find('#pagejump');
	});
	
	$('#main_content_L').load('show_all_prod_list.php?page=' + page + '&limit=' + limit + '&data=' + data + ' .data_room_L', function(response, status, xhr) {
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



