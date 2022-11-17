$(document).ready(function(){
	// 路徑檢查
	root_path = window.location.pathname;
	root_path = root_path.indexOf("php");
	
	if(root_path < 0){
		root_path = window.location.pathname;
	}else{
		root_path = "";
	}
	
	$(document)[0].addEventListener("keyup", function(event) {

	if (event.getModifierState("CapsLock")) {
		$('#CapsLock_msg').css("display","block");
	  } else {
		$('#CapsLock_msg').css("display","none");
	  }
	});

	/* 將submit按鈕內容加到<input type='hidden'>以讓serialize()取得數值 */
	var form = $("#login_form");
	$(":submit", form).click(function(){
		if($(this).attr('name')) {
			$(form).append(
				$("<input type='hidden'>").attr( { 
					name: $(this).attr('name'), 
					value: $(this).attr('value') })
			);
		}
	});
	
	
	//msg_submit = "登入功能製作中";
	$( "#login_form" ).submit(function( event ) {
		if (typeof(msg_submit) !== 'undefined') {
		  $('#msg').html(msg_submit);
		}
		event.preventDefault();
		var url = root_path + "/system/do_login.php",
			data = $("#login_form").serialize();
			el_to_msg = '#msg';
		ajax_post(url, data, el_to_msg);
	});
	


});