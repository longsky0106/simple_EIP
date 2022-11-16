<?php
	$origin_url = strip_tags($_REQUEST["origin_url"]);

	$root_path = $_SERVER['HTTP_REFERER'];
	if(!$root_path){
		$root_path = "";
	}else{
		$root_path = "/".explode("/",$_SERVER['SCRIPT_NAME'])[1]."/";
	}
?>

<!doctype html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
    <title>登入頁面</title>
    <link rel="stylesheet" href="<?=$root_path?>CSS/login_form.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="<?=$root_path?>JS/MyFunction.js"></script>
    <script src="<?=$root_path?>JS/login_form.js"></script>   
  </head>
<body>
  <div id="login_form_content">
    <?php
	echo $root_path;
		session_start();
		if(isset($_SESSION['current_page'])){
			$message = "此頁面須登入後再進行操作";
		}
		  //$message = "&emsp;";
		if(isset($message)){
			echo $message;
		}
    ?>
    <!-- <form method="post" onsubmit="return login_check();" id="login_form"> -->
    <form method="post" id="login_form">
      <div>
        <label for="username">帳號: </label>
        <input type="text" placeholder="請輸入帳號" name="username">
        <label for="password" >密碼: </label>
        <input type="password" placeholder="請輸入密碼" name="password">
        <button type="submit" name="btn_login" value="login_check">登入</button>
        <label>
          <input type="checkbox" checked="checked" name="remeber">記住我
        </label>
      </div>    
    </form>
    <div id="msg">00</div>
    <p id="CapsLock_msg">大寫已鎖定</p>
  </div>
</body>
</html>