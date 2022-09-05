<?php
<<<<<<< HEAD:system/input_update.php
	$root_path = $_SERVER['HTTP_REFERER'];
	if(!$root_path){
		$root_path = "";
	}else{
		$root_path = "http://192.168.1.56/PHPtoPDF(dev)/system/";
	}
	
	session_start();
	
	// 如果沒登入就轉到登入頁面
	if(!ISSET($_SESSION['user'])){
		$current_QUERY_STRING = explode("?",$_SERVER['HTTP_REFERER'])[1];
		$_SESSION['current_page'] = "/system/".basename($_SERVER['SCRIPT_FILENAME'])."?".$current_QUERY_STRING;
		header('location:'.$root_path.'../login.php');
		exit();
	}
	$Model = strip_tags($_GET["Model"]);
	$action = strip_tags($_GET["action"]);
	
	
=======
>>>>>>> e738cba (edit php):input_update.php
?>
<!doctype html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
    <title>銘鵬規格小幫手Web-資料更新(BETA)(PHP 8.1)</title>
    <link rel="stylesheet" href="<?=$root_path?>../CSS/shop_helper.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>
    <script src="<?=$root_path?>../JS/main.js"></script>
	
  </head>
  <body style="font-size:16px">
<<<<<<< HEAD:system/input_update.php
   <script>
		$(document).ready(function(){
			$(window).scrollTop(0); // 捲動到頂端
		});
	</script>
<?php  
	if($action == "create"){
?>
		<script>
			submit_data(<?="'','".$action."'"?>);
		</script>
		<div style="margin:0 0 0.8em 0"><span style="color:blue;font-size:26px;"><b>銘鵬規格小幫手Web-資料新增</b></span></div>
		<label style="font-size:1.2em;color:red;font-weight:bold;">型號
			<input type="Text" id="SK_create" name="create_Model" placeholder="請輸入要新增的型號" style="margin:0 1em;height: 1.2em;width: fit-content;" autocomplete="off">
		</label>
<?php	
	} // 有傳入Model參數就直接執行查詢
	else if(isset($Model) && !empty($Model) && (preg_match('/(?=.*[A-Z])(?=.*[0-9]){3}/', $Model)) ){
?>
	  <script>
			submit_data(<?="\"".$Model."\""?>);
		</script>
		<div id="statu_check"><span style="color:blue;">查詢中...請稍後</span></div>
<?php		
	}else{
		// 如果沒有傳Model參數就顯示查詢輸入框(初始版本)
?>
		<div><span style="color:blue;font-size:26px"><b>銘鵬規格小幫手Web-資料更新(BETA)(PHP 8.1)(dev)</b></span></div>
		<div id="search_contain">
		  <div id="search_contain">
			<form name="lys" action="" method="Post">
			  <input type="Text" id="SK_search" name="Model" placeholder="請輸入要修改的型號" autocomplete="off">
			  <input type="button" id="SK_search_btn" value="送出" onClick="submit1();">
			</form>
		  </div>
		</div><br>
		<div id="statu_check"></div>
		</br>
		  <hr>
<?php	
	}
?>      
=======
	<div><span style="color:blue;font-size:26px"><b>銘鵬規格小幫手Web-資料更新(BETA)(PHP 8.1)</b></span></div>
    <div id="search_contain">
       <div id="search_contain">
      <form name="lys" action="" method="Post">
        <input type="Text" id="SK_search" name="Model" placeholder="請輸入要修改的型號" autocomplete="off">
        <input type="button" id="SK_search_btn" value="送出" onClick="submit1();">
		<!--<input class="checkbox_sql" id="check_pct_sql_temp" type="checkbox" autocomplete="off">使用臨時資料庫-->
      </form>
    </div>
    </div>
    <div>
      <div id="statu_check"></div>
	  </br>
	  <hr>  
>>>>>>> dd9337c (修正首頁標題):input_update.php
	  <div id="show_data">
	    <span><b>基本資料</b></span><br>
			料號1(主要)<input type="text" id="" name="SK_NO1" value=""><br>
			料號2(次要)<input type="text" id="" name="SK_NO2" value=""><br>
			料號3(備用)<input type="text" id="" name="SK_NO3" value=""><br>
			料號4(臨時)<input type="text" id="" name="SK_NO4" value=""><br>
			售價&emsp;&emsp;<input type="text" id="" name="Price" value=""><br>
			建議售價<input type="text" id="" name="Suggested_Price" value=""><br>
			成本&emsp;&emsp;<input type="text" id="" name="Cost_Price" value=""><br>
			<br>
<?php
	if($action != "create"){
?>  
      <input type="button" value="更新基本資料" onclick=";" disabled> <span id="statu_base_check"></span>
<?php
	}
?>      
		  <hr>
		  <span><b>銷售/料號資料</b></span><br>
		  <br>
	  </div>
	  <hr>
	  <span><b>產品分類</b></span><br>
	  <div id="prod_data">
		  目前產品分類: <br>
		  修改產品分類: <select id="categories" name="categories">
							<option value="0">選擇產品系列</option>
						 </select>
						 <select id="ProdType" name="ProdType">
							<option value="0">選擇產品類別</option>
						 </select><br />
	  </div>

	  <hr>
	  <div id="spec_content_title">
		<span><b>規格(請先設定產品分類才能顯示完整規格列表)</b></span>
	  </div>
	  
	  <div id="spec_edit">
		<form action="" id="spec_input_form">
			擴充座輸出介面:
			<div id="spec_port">
				<div>
					<input id="port_HDMI" title="HDMI" class="checkbox_spec_port" type="checkbox" autocomplete="off">
					<label for="port_HDMI">
						HDMI
					</label>
				</div>
				<div>
					<input id="port_DisplayPort" class="checkbox_spec_port" type="checkbox" autocomplete="off">
					<label for="port_DisplayPort">
						DisplayPort
					</label>
				</div>
				<div>
					<input id="port_DVI" class="checkbox_spec_port" type="checkbox" autocomplete="off">
					<label for="port_DVI">
						DVI
					</label>
				</div>
				<div>
					<input id="port_VGA" class="checkbox_spec_port" type="checkbox" autocomplete="off">
					<label for="port_VGA">
						VGA
					</label>
				</div>
			</div>
			<div id="spec_input_content">
			
				<div id="zh-tw_spec" class="spec_input_aren">
					<div class="spec_input_title">中文</div>				
					<label for="spec_item_name">型號</label>
					<input type="text" id="" name="spec_item_value" value=""><br>				
					<label for="spec_item_name">功能</label>
					<input type="text" id="" name="spec_item_value" value=""><br>				
					<label for="spec_item_name">輸入埠</label>
					<input type="text" id="" name="spec_item_value" value=""><br>				
					<label for="spec_item_name">輸出埠</label>
					<input type="text" id="" name="spec_item_value" value=""><br>
				</div>
				
				<div id="en-us_spec" class="spec_input_aren">
					<div class="spec_input_title">英文</div>					
					<label for="spec_item_name">Model</label>
					<input type="text" id="" name="spec_item_value_en" value=""><br>										
					<label for="spec_item_name">Founction</label>
					<input type="text" id="" name="spec_item_value_en" value=""><br>										
					<label for="spec_item_name">Input</label>
					<input type="text" id="" name="spec_item_value_en" value=""><br>										
					<label for="spec_item_name">Output</label>
					<input type="text" id="" name="spec_item_value_en" value=""><br>
				</div>
			</div>
		</form>
		<!-- <input type="button" value="新增項目" onclick=";"><br> -->
		<!-- &emsp;<input type="button" value="新增已存在項目到現有產品分類下" onclick=";"><br> -->
		<!-- &emsp;<input type="button" value="新增一筆新項目到現有產品分類下" onclick=";"> -->
	  </div>
	  </br>
	  <hr>
	  <span><b>產品描述&特色</b></span><br>
	  <div id="description_features_edit"><form action="" id="description_features_form">
			<div id="description_input_content">
				<div id="zh-tw_description" class="description_input_aren">
					<div class="description_input_title">描述</div>
					<div id="description_input_right" class="text_input_aren">
							<label for="description">中文</label>
							<textarea rows="8" cols="20" name="zh-tw_description" form="description_features_form" autocomplete="off"></textarea>
							<hr>
							<label for="description_en">英文</label>
							<textarea rows="8" cols="20" name="en-us_description" form="description_features_form" autocomplete="off"></textarea>
					</div>
				</div>		
				<div id="zh-tw_features" class="features_input_aren">
					<div class="features_input_title">特色</div>
					<div id="features_input_right" class="text_input_aren">
							<label for="features">中文</label>
							<textarea rows="8" cols="20" name="zh-tw_features" form="description_features_form" autocomplete="off"></textarea>
							<hr>
							<label for="features_en">英文</label>
							<textarea rows="8" cols="20" name="en-us_features" form="description_features_form" autocomplete="off"></textarea>
					</div>
				</div>
			</div>
			<input type="button" disabled value="更新規格" onclick=";"><input class="checkbox_PCT" id="update_PCT" type="checkbox" autocomplete="off">連同更新官網產品資料
		</form></div>
	  <div id="update_preview">
		&emsp;<br>
		&emsp;
	  </div>
	  </br>
  </body>
</html>