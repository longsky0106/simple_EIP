$(document).ready(function(){
	$.post("F_show_online_user.php", {
		sys_type: "生產製造"
	}, function(result){
		$("#ly_bom").html(result);
	});
	$.post("F_show_online_user.php", {
		sys_type: "出口貿易"
	}, function(result){
		$("#LyTrade").html(result);
	});
	$.post("F_show_online_user.php", {
		sys_type: "會計財務"
	}, function(result){
		$("#LyAct").html(result);
	});

	$(function(){
		setInterval(function(){
		   $.post("F_show_online_user.php", {
				sys_type: "生產製造"
			}, function(result){
				$("#ly_bom").html(result);
			});
			$.post("F_show_online_user.php", {
				sys_type: "出口貿易"
			}, function(result){
				$("#LyTrade").html(result);
			});
			$.post("F_show_online_user.php", {
				sys_type: "會計財務"
			}, function(result){
				$("#LyAct").html(result);
			});
		}, 15 * 1000);
	});

	
});

