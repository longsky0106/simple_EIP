<?php
	session_start();
	$Company_name = "銘鵬科技";
	$user_name = "蟀哥/楣女";
?>

<!doctype html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
    <title><?=$Company_name."簡易EIP-載入區域"?></title>
    <link rel="stylesheet" href="CSS/index_load_content.css">
    <script src=""></script>
  </head>
  <body>
    <div id="empty_load_main">
	<?=$user_name?> 您好<br>
	請選擇要瀏覽的頁面<br>
	部分頁面功能須登入後才可操作<br>
<?php
	if(!ISSET($_SESSION['user'])){
?>		
	<a href="javascript:load_content(0)">登入</a>
<?php	
	}else{
?>		
	<a href="javascript:load_content(99)">登出</a>	
<?php		
	}
?>
	</div>
  </body>
</html>