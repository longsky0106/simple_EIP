$(document).ready(function(){
	// alert(window.location.pathname);
	root_path = window.location.pathname;
	root_path = root_path.indexOf("php");
	if(root_path < 0){
		root_path = window.location.pathname + "/system/";
	}else{
		root_path = "";
	}

	$.post(root_path + "F_show_online_user.php", {
		sys_type: "生產製造"
	}, function(result){
		$("#ly_bom").html(result);
	});
	$.post(root_path + "F_show_online_user.php", {
		sys_type: "出口貿易"
	}, function(result){
		$("#LyTrade").html(result);
	});
	$.post(root_path + "F_show_online_user.php", {
		sys_type: "會計財務"
	}, function(result){
		$("#LyAct").html(result);
	});

	$(function(){
		setInterval(function(){
			root_path1 = window.location.pathname;
			root_path1 = root_path1.indexOf("php");
			let searchParams = new URLSearchParams(window.location.search);
			let url = searchParams.get('url');
			if (typeof(url) === 'undefined') {
				url = "url";
			}
			if(url=="show_online_user.php" || root_path1 > 0){
				//alert("u");
				$.post(root_path + "F_show_online_user.php", {
					sys_type: "生產製造"
				}, function(result){
					$("#ly_bom").html(result);
				});
				$.post(root_path + "F_show_online_user.php", {
					sys_type: "出口貿易"
				}, function(result){
					$("#LyTrade").html(result);
				});
				$.post(root_path + "F_show_online_user.php", {
					sys_type: "會計財務"
				}, function(result){
					$("#LyAct").html(result);
				});
				
			}

			
		   
		}, 5 * 1000);
	});

	
});

