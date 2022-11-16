<?php

require_once '../functions/MyPDO.php';
require_once '../functions/MyFunctions.php';
require_once '../system/MyConfig.php';
header('Content-Type:text/html;charset=utf8');

	set_time_limit(100);
	$SK_NO = strip_tags($_POST["SK_NO"]);
	$check_pct_sql_temp = strip_tags($_POST["check_pct_sql_temp"]);
	$Model = strip_tags($_POST['Model']);
	$action = strip_tags($_POST["action"]);
	// echo "Model = \"".$Model."\"";
	//$check_pct_sql_temp = true;
	// echo 'check_pct_sql_temp = '.$check_pct_sql_temp;
	//$SK_NO="PPCONR052";
	
	$pct_web_site_local_url = "http://192.168.10.111";
	$RemoteFile1 = "http://assets.pct-max.com.tw/PK1640-C/PK1640-C_F_R.png";
	
	$pdo = new MyPDO;
	

	
	$sql = "SELECT SK_NO, SK_NAME, SK_SMNETS FROM ".$ly_sql_db_table."
			WHERE SK_NO =:SK_NO";
	$query = $pdo->bindQuery($sql, [
		':SK_NO' => $SK_NO
	]);

	

	$row_count = count($query);
	if($row_count){
		foreach($query as $row){
			$description_all = explode("---DESCRIPTION---", $row['SK_SMNETS'])[0];  					
			$description = explode("---Features---", $description_all)[0];								// 產品敘述(中文)
			$arr_features = explode(PHP_EOL, explode("---Features---", $description_all)[1]);			// 
			$arr_features_index = count($arr_features)-1;
			$description_all_en = explode("---DESCRIPTION---", $row['SK_SMNETS'])[1];					
			$description_en = explode("---Features---", $description_all_en)[0];						// 產品敘述(英文)
			$arr_features_en = explode(PHP_EOL, explode("---Features---", $description_all_en)[1]);		// 
			$arr_features_index_en = count($arr_features_en);
			$prod_name = $row['SK_NAME'];
			//$prod_Model = explode("/", $prod_name, 2)[0];

			// 產品特色
			$i=0;
			while($i<$arr_features_index) {
				$features .= $arr_features[$i+1]."\r\n";
				$i ++ ;
			}
			
			// 產品特色(英文)
			$i=0;
			while($i<$arr_features_index_en) {
				$features_en .= $arr_features_en[$i]."\r\n";
				$i ++ ;
			}	
		}
	}
	$query=null;
?>
		<form action="" id="description_features_form">
			<div id="description_input_content">
			
				<div id="zh-tw_description" class="description_input_aren">
					<div class="description_input_title">描述</div>
<?php
					// if($row['SK_NO']!=''){
?>				
						<div id="description_input_right" class="text_input_aren">
							<label for="description">中文</label>
							<textarea rows="8" cols="20" name="zh-tw_description" form="description_features_form"><?=rtrim($description)?></textarea>
							<hr>
<?php				
					// }	
					// if($row['SK_NO']!=''){
?>					
							<label for="description_en">英文</label>
							<textarea rows="8" cols="20" name="en-us_description" form="description_features_form"><?=rtrim($description_en)?></textarea>
						</div>		
<?php				
					// }	
?>			
				</div>
				<div id="zh-tw_features" class="features_input_aren">
					<div class="features_input_title">特色</div>
<?php
					// if($row['SK_NO']!=''){
?>				
						<div id="features_input_right" class="text_input_aren">
							<label for="features">中文</label>
							<textarea rows="8" cols="20" name="zh-tw_features" form="description_features_form" autocomplete="off"><?=rtrim($features)?></textarea>
							<hr>
<?php				
					// }
					// if($row['SK_NO']!=''){
?>					
							<label for="features_en">英文</label>
							<textarea rows="8" cols="20" name="en-us_features" form="description_features_form" autocomplete="off"><?=rtrim($features_en)?></textarea>
						</div>
<?php				
					// }	
?>			
				</div>
			</div>
			<input type="button" id="button_update_data" value="更新/儲存 <?=$Model?> 規格描述資料" onClick="update_submit('<?=$action?>');">
<?php
			if($action=="create"){
?>
				<br><span style="color:blue;" id="statu_insert_check"></span>
				<br><span id="statu_base_temp"></span>
				<br><span id="statu_base_check"></span>
<?php				
			}
?>
<?php
			if($Model){
				// 檢查連結是否存在
				if(checkRemoteFile($pct_web_site_local_url)){
					// 連結官網MySql資料庫，用型號查詢是否存在索引值
					$sql2 = "select data_id from openquery(MYSQL, 'SELECT * FROM `www.pct-max.com`.product_list') where data_no=:data_no";
					$query2 = $pdo->bindQuery($sql2, [
						':data_no' => $Model
					]);	
					$row_count = count($query2);
					if($row_count){
						foreach($query2 as $row){
							$data_id = $row['data_id'];
						}
?>								
					
						&emsp;<input class="checkbox_PCT" id="update_PCT" type="checkbox">
					<span id="update_PCT_description">
<?php								
						echo "連同更新官網 ".$Model." 產品資料";
					}else{
						if(str_starts_with($row['SK_NO'],'PP') || str_starts_with($row['SK_NO'],'PN')){
?>									
							&emsp;<input class="checkbox_PCT" id="update_PCT" type="checkbox" disabled>
<?php									
							echo "連同新增官網 ".$Model." 產品資料(製作中...)";
						}else if(str_ends_with($row['SK_NO'],'_temp')){
							echo "&emsp;※新開發產品";
						}else{
							echo "<span style=\"color:red;\"><b>&emsp;「".$Model."」 非官網產品類型</b></span>";
						}
					}
					$query2=null;
				}else{
					echo "無法取得連線";
				}
			}
?>
				</span>
		</form>
<?php
	
?>	
