<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
  <title>凌越線上使用者一覽</title>
<link rel="stylesheet" href="../CSS/ly_online_user.css">
</head>
<body>

<?php

require_once('../functions/MyPDO.php');
require_once '../functions/MyFunctions.php';
require_once '../system/MyConfig.php';

require_once '../include/Ly_SQL.php';	
	
header('Content-Type:text/html;charset=utf8');

// 顯示程式錯誤訊息
ini_set('display_errors', 0);
	set_time_limit(30);
	$sys_type = $_POST["sys_type"];
	
	if (!isset($sys_type) || empty($sys_type)){
		$sys_type = "生產製造";
	}else{
		$sys_type = $_POST["sys_type"];
	}
	
	
	$pdo = new MyPDO;
	
	// 生產製造/出口貿易/會計財務
	$query = $pdo->bindQuery($sql_ly_bom, [
		':sys_type' => $sys_type
	]);
	$row_count = count($query);
	if($row_count){
?>
		  <!-- 標題列 -->
		  <div class="data_room_con"><!-- pro_con: 欄，dr: 列 -->
				<div class="pro_con0 dr0" >帳號</div>
				<div class="pro_con1 dr0" >使用者</div>
				<div class="pro_con2 dr0" >單據/功能操作</div>
				<div class="pro_con3 dr0" >電腦名稱</div>
				<div class="pro_con4 dr0" >IP</div>
				<div class="pro_con5 dr0" >登入時間</div>
				<div class="pro_con6 dr0" >已連線時間</div>
				<div class="pro_con7 dr0" >閒置時間</div>
			</div>
	
<?php	
		foreach($query as $row){
?>				
				<!-- 內容 -->
				<div class="data_room_con1">
					<div class="pro_con0 pn">
						 <div class="sk_data0 dr" >帳號</div>
						 <div class="sk_data0 dr1" ><?=$row['帳號']?></div>
					</div>
					<div class="pro_con1 pn">
						<div class="sk_data1 dr" >使用者</div>
						<div class="sk_data1 dr1" ><?=$row['使用者']?></div>
					</div>
					<div class="pro_con2 pn">
						<div class="sk_data2 dr" >單據/功能操作</div>
						<div class="sk_data2 dr1" ><?=str_replace(' ,  ','<br>',$row['單據/功能操作'])?></div>
					</div>
					<div class="pro_con3 pn">
						<div class="sk_data3 dr" >電腦名稱</div>
						<div class="sk_data3 dr1" ><?=$row['電腦名稱']?></div>
					</div>
					<div class="pro_con4 pn">
						<div class="sk_data4 dr" >IP</div>
						<div class="sk_data4 dr1" ><?=$row['IP']?></div>	
					</div>
					<div class="pro_con5 pn">
						<div class="sk_data5 dr" >登入時間</div>
						<div class="sk_data5 dr1" ><?=$row['登入時間']?></div>
					</div>
					<div class="pro_con6 pn">
						<div class="sk_data6 dr" >已連線時間</div>
						<div class="sk_data6 dr1" ><?=$row['已連線時間']?></div>
					</div>
					<div class="pro_con7 pn">
						<div class="sk_data7 dr" >閒置時間</div>
						<div class="sk_data7 dr1" ><?=$row['閒置時間']?></div>
					</div>
				</div>
				
<?php	
		}
	}else{
?>		
	
		沒有使用者在線上
<?php			
	}
	$query=null;
?>
	</body>
</html>