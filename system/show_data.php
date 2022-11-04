<?php
/**
顯示區塊:

-------------------------
銷售/料號資料

-------------------------	
產品分類

-------------------------

*/
require_once('../functions/MyPDO.php');
require_once '../functions/MyFunctions.php';
require_once '../system/MyConfig.php';
header('Content-Type:text/html;charset=utf8');

	set_time_limit(30);
	// $SK_NO = $_POST["SK_NO"];
	$Model = strip_tags($_POST["Model"]);
	$action = strip_tags($_POST["action"]);
	$data_id = '';
	
	$pct_web_site_local_url = "http://192.168.10.111";
	$RemoteFile1 = "http://assets.pct-max.com.tw/PK1640-C/PK1640-C_F_R.png";
		
	$pdo = new MyPDO;
	
	// 基本資料 - 在[PCT].[dbo].[Data_Prod_Reference]從型號找對應料號
	$sql_pct = "SELECT [SK_NO1]
			  ,[SK_NO2]
			  ,[SK_NO3]
			  ,[SK_NO4]
			  ,[Price]
			  ,[Suggested Price]
			  ,[Cost Price]
			  ,[Main_Product]
			  ,[mark1]
			  ,[mark2]
		  FROM [PCT].[dbo].[Data_Prod_Reference]
		  WHERE Model = :Model";
	
	
	  
	if($action=="create"){
?>		
			<span><b>基本資料</b></span>
			  <br>
				料號1(主要)<input type="text" id="" name="SK_NO1" value=""><br>
				料號2(次要)<input type="text" id="" name="SK_NO2" value=""><br>
				料號3(備用)<input type="text" id="" name="SK_NO3" value=""><br>
				料號4(臨時)<input type="text" id="" name="SK_NO4" value="">
				<input type="button" id="SK_NO_TEMP_add_btn" value="新增" onClick="insert_temp_no_data()"><span id="statu_base_temp"></span>
				<br>
				售價&emsp;&emsp;<input type="text" id="" name="Price" value="<?=$Price?>"><br>
				建議售價<input type="text" id="" name="Suggested_Price" value="<?=$Suggested_Price?>"><br>
				成本&emsp;&emsp;<input type="text" id="" name="Cost_Price" value="<?=$Cost_Price?>"><br>
<?php		
	}else{
		// 基本資料 - 在[PCT].[dbo].[Data_Prod_Reference]從型號找對應料號
		$query = $pdo->bindQuery($sql_pct, [
			':Model' => $Model
		]);
		$row_count = count($query);
		if($row_count){
			foreach($query as $row){
				$SK_NO1 = $row['SK_NO1'];
				$SK_NO2 = $row['SK_NO2'];
				$SK_NO3 = $row['SK_NO3'];
				$SK_NO4 = $row['SK_NO4'];
				$Price = round($row['Price']);
				$Suggested_Price = round($row['Suggested Price']);
				$Cost_Price = round($row['Cost Price']);
			}
		
		
			switch(true){
				case $SK_NO1:
					$SK_NO_query = $SK_NO1;
					$ly_sql_db_table = $ly_sql_db_table_std;
					$ly_sql_db_table_FD = $ly_sql_db_table_FD_std;
					break;
				case $SK_NO2:
					$SK_NO_query = $SK_NO2;
					$ly_sql_db_table = $ly_sql_db_table_std;
					$ly_sql_db_table_FD = $ly_sql_db_table_FD_std;
					break;
				case $SK_NO3:
					$SK_NO_query = $SK_NO3;
					$ly_sql_db_table = $ly_sql_db_table_std;
					$ly_sql_db_table_FD = $ly_sql_db_table_FD_std;
					break;
				case $SK_NO4:
					$SK_NO_query = $SK_NO4;
					$ly_sql_db_table = $ly_sql_db_table_temp;
					$ly_sql_db_table_FD = $ly_sql_db_table_FD_temp;
					break;
				default:
					$SK_NO_query = "無資料";
			}
			
			$sql_ly = "SELECT SK_NO, SK_NAME, SK_NOWQTY, SK_SPEC, SK_UNIT, SK_COLOR, SK_SIZE, SK_SESPES, SK_ESPES, SK_REM, SK_SMNETS, BD_DSKNO, BM_USKNO, SK_USE, SK_LOCATE, fd_name
				FROM (
				SELECT DISTINCT ".$ly_sql_db_table.".SK_NO, ".$ly_sql_db_table.".SK_NAME, SK_NOWQTY, CONVERT(NVARCHAR(MAX),SK_SPEC) AS 'SK_SPEC', SK_UNIT, SK_COLOR, SK_SIZE, SK_SESPES, CONVERT(VARCHAR(MAX),SK_ESPES) AS 'SK_ESPES', CONVERT(NVARCHAR(MAX),SK_REM) AS 'SK_REM', CONVERT(NVARCHAR(MAX),SK_SMNETS) AS 'SK_SMNETS', BD_DSKNO, BD_DSKNM, BM_USKNO, SK_USE, SK_LOCATE, fd_name
				, ROW_NUMBER ( ) OVER ( PARTITION BY ".$ly_sql_db_table.".SK_NO order by ".$ly_sql_db_table.".SK_NO DESC) as rn
				FROM ".$ly_sql_db_table."
				LEFT JOIN ".$dbname1.".dbo.BOMDT on ".$ly_sql_db_table.".SK_NO = ".$dbname1.".dbo.BOMDT.BD_USKNO
				LEFT JOIN ".$ly_sql_db_table_FD." on ".$ly_sql_db_table.".SK_NO = ".$ly_sql_db_table_FD.".fd_skno
				LEFT JOIN (
					SELECT BM_USKNO,SK_NO
					FROM ".$dbname1.".dbo.BOM
					LEFT JOIN ".$dbname1.".dbo.BOMDT ON ( BOM.BM_USKNO = BOMDT.BD_USKNO )
					INNER JOIN ".$ly_sql_db_table." on (".$ly_sql_db_table.".sk_no=BOMDT.BD_DSKNO )
				) BOMUSE on ".$ly_sql_db_table.".SK_NO = BOMUSE.SK_NO
				) AS SKM
				WHERE rn = 1 and SK_NO =:SK_NO";
			
	?>	
			  <span><b>基本資料</b></span>
			  
			  <span id="info_base_model" style="color:blue"><b><?=" (".$Model.")"?></b></span>

			  <br>
				料號1(主要)<input type="text" id="" name="SK_NO1" value="<?=$SK_NO1?>"><br>
				料號2(次要)<input type="text" id="" name="SK_NO2" value="<?=$SK_NO2?>"><br>
				料號3(備用)<input type="text" id="" name="SK_NO3" value="<?=$SK_NO3?>"><br>
				料號4(臨時)<input type="text" id="" name="SK_NO4" value="<?=$SK_NO4?>">
					<?php
						if($SK_NO_query == "無資料"){
							echo "是否新增臨時料號? ";
					?>
							<input type="button" id="SK_NO_TEMP_add_btn" value="新增" onClick="insert_temp_no_data()"><span id="statu_base_temp"></span>
					<?php	
						}else{
							
						}
					
					?><br>
				售價&emsp;&emsp;<input type="text" id="" name="Price" value="<?=$Price?>"><br>
				建議售價<input type="text" id="" name="Suggested_Price" value="<?=$Suggested_Price?>"><br>
				成本&emsp;&emsp;<input type="text" id="" name="Cost_Price" value="<?=$Cost_Price?>"><br>
				<br><input type="button" value="更新基本資料" onclick="update_base_submit();"> <span id="statu_base_check"></span>
			  <hr>
			  <span><b>銷售/料號資料<span style="color:blue"> (<?=$SK_NO_query?>)</span></b></span><br>
			  <br>
		
	<?php	
		}else{
	?>		
			<span><b>基本資料</b></span><span id="info_base_model" style="color:blue"><b><?=" (".$Model.")"?></b></span><br>
			什麼東東都沒有喔！是否新增? <input type="button" id="data_add_btn" value="新增" onClick="insert_data()"><span style="color:blue;" id="statu_insert_check"></span>
			<hr>
			  <span><b>銷售/料號資料</b></span><br>
			  <br>
	<?php
		}
		$query=null;
		if($SK_NO_query!="無資料" && !empty($SK_NO_query)){
			$query = $pdo->bindQuery($sql_ly, [
				':SK_NO' => $SK_NO_query
			]);

			$id_count = 1; 
			$row_count = count($query);			
	?>
		<div id="main_content">
			<div class="data_room">
	<?php
		
			if($row_count){
	?>
				<div id="copy_statu">複製</div>
				<div class="data_room_con"><!-- pro_con: 欄，dr: 列 -->
					<div class="pro_con0 dr0" >產品圖片</div>
					<div class="pro_con1 dr0" >產品編號</div>
					<div class="pro_con2 dr0" >銷售品名</div>
					<div class="pro_con3 dr0" >廠內品名</div>
					<div class="pro_con4 dr0" >規格</div>
					<div class="pro_con5 dr0" >官網資料</div>
					<div class="pro_con6 dr0" >銷售頁面範本</div>
				</div>
			
	<?php
				foreach($query as $row){

					$prod_name = $row['SK_NAME'];
					$prod_model = explode("/", $prod_name, 2)[0];   											// 型號
					
					$prod_model = $Model;
					
					$filename = $prod_model;
					$path = 'http://assets.pct-max.com.tw/' . $prod_model . '/'; // 產品圖片路徑

					// 副檔名
					$array_img_type = [
						".jpg",
						".png",
						".gif"
					];
					
					// 型號後面檔名
					$array_img_name = [
						"(no logo)",
						"_ps",
						"_no logo",
						"_x700"
					];
					if(checkRemoteFile($RemoteFile1)){
						$stoploop = false;
						for($i = 0; $i<count($array_img_name) && !$stoploop; $i++){
							for($j = 0; $j<count($array_img_type); $j++){
								$img_path_filename = $path.$filename.str_replace(' ', '%20', $array_img_name[$i]).$array_img_type[$j];
								// echo $img_path_filename."<br>";
								if (@getimagesize($img_path_filename)) {
									$img_result = '<img src="'.$img_path_filename.'" class="img-responsive" />';
									$stoploop = true;
									break;
								}
								$img_result = "暫無圖片";
							}
						}
					}else{
						$img_result = "無法取得連線";
					}

						
					$description_all = explode("---DESCRIPTION---", $row['SK_SMNETS'])[0];  					// 產品敘述(中文)
					$description = explode("---Features---", $description_all)[0];								// 產品敘述(中文)
					$arr_features = explode(PHP_EOL, explode("---Features---", $description_all)[1]);			// 產品特色(中文)
					$arr_features_index = count($arr_features)-1;
					$description_all_en = explode("---DESCRIPTION---", $row['SK_SMNETS'])[1];					// 產品敘述(英文)
					$description_en = explode("---Features---", $description_all_en)[0];						// 產品敘述(英文)
					$arr_features_en = explode(PHP_EOL, explode("---Features---", $description_all_en)[1]);		// 產品特色(英文)
					$arr_features_index_en = count($arr_features_en);
					
					$arr_SPEC = explode(PHP_EOL, $row['SK_SPEC']);
					$arr_SPEC_index = count($arr_SPEC);															// 規格(中文)(未轉成表格)
					$arr_SPEC_en = explode(PHP_EOL, $row['SK_ESPES']);
					$arr_SPEC_index_en = count($arr_SPEC_en);
				
				
					// 產品特色
					$i=1;
					while($i<$arr_features_index) {
						$features .= $arr_features[$i]."\r\n";
						$i++;
					}

					// 產品特色(英文)
					$i=1;
					while($i<$arr_features_index_en) {
						$features_en .= $arr_features_en[$i]."\r\n";
						$i++;
					}
					
					// 規格-表格開頭(暫定)
					$SK_SPEC ="<table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">
						<tbody>";
					$i=0;
					while($i<$arr_SPEC_index) {
						// 表格內容
						$SK_SPEC .="<tr><td>";
						if (strpos($arr_SPEC[$i], '	')) {
							$str_replace_set = '	';
						}
						else {
							$str_replace_set = '    ';
						}
						$SK_SPEC .=str_replace($str_replace_set, "</td><td>", $arr_SPEC[$i])."<br>";
						$SK_SPEC .="</td></tr>";
						$i ++ ;
					}
					$SK_SPEC .="</tbody></table>";  // 表格結尾(暫定)
					
					if($row['SK_ESPES'])
					{
						// 規格-表格開頭(暫定)
						$SK_SPEC_EN ="<table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">
							<tbody>";
						$i=0;
						while($i<$arr_SPEC_index_en) {
							// 表格內容
							$SK_SPEC_EN .="<tr><td>";
							if (strpos($arr_SPEC_en[$i], '	')) {
								$str_replace_set = '	';
							}
							else {
								$str_replace_set = '    ';
							}
							$SK_SPEC_EN .=str_replace($str_replace_set, "</td><td>", $arr_SPEC_en[$i])."<br>";
							$SK_SPEC_EN .="</td></tr>";
							$i ++ ;
						}
						$SK_SPEC_EN .="</tbody></table>";  // 表格結尾(暫定)
					}
					else
					{
						$SK_SPEC_EN = "無資料";
					}
				
					
				
			

	?>				
					<div class="data_room_con1">
						<div class="pro_con0 pn">
							 <div class="sk_data0 dr" >產品圖片</div>
							 <div class="sk_data0 dr1" ><?=$img_result?></div>
						</div>
						<div class="pro_con1 pn">
							<div class="sk_data1 dr" >產品編號</div>
							<div class="sk_data1 dr1" id="sk_no<?=$id_count?>" ><?=$row['SK_NO']?></div>
							<div><?=$sql_db_note?></div>
						</div>
						<div class="pro_con2 pn">
							<div class="sk_data2 dr" >銷售品名</div>
							<div class="sk_data2 dr1" ><?=$row['fd_name']?$row['fd_name']:'未填寫'?></div>
						</div>
						<div class="pro_con3 pn">
							<div class="sk_data3 dr" >廠內品名</div>
							<div class="sk_data3 dr1" ><?=$row['SK_NAME']?></div>
						</div>
						<div class="pro_con4 pn">
							<div class="sk_data4 dr" >規格</div>
							<div class="sk_data4 dr1" ><!-------------規格(手機板)------------------->
								<?php			
									echo (($row['SK_SPEC']!='')?
										"<div style=\"text-align: center;cursor:pointer;color:white;background-color:rgb(158, 46, 62);\" onclick=\"show_SPEC(this,".$id_count.");\">顯示</div>".
										"<div class=\"SK_SPEC dr1\" id=\"show_SPEC_S".$id_count."\" >"
											.$SK_SPEC.
											"<br>-------------英文規格-------------------<br>"
											.$SK_SPEC_EN.
										"</div>"
									:"無資料");	
								?>
							</div>	
						</div>
						<div class="pro_con5 pn">
							<div class="sk_data5 dr" >官網資料</div>
							<div class="sk_data5 dr1" >
								<?php
									// 檢查官網(主要位置)是否上線
									if(checkRemoteFile($pct_web_site_local_url)){
										// 連結官網MySql資料庫用型號查詢是否存在索引值
										$sql = "select data_id from openquery(MYSQL, 'SELECT * FROM `www.pct-max.com`.product_list') where data_no=:data_no";
										$query = $pdo->bindQuery($sql, [
											':data_no' => $prod_model
										]);	
										$row_count = count($query);
										if($row_count){
											foreach($query as $row2){
												$data_id = $row2['data_id'];
												// 連結開新視窗到官網介紹
												echo "<a href=\"http://www.pct-max.com.tw/products-detail.php?index_id=".$data_id."\" target=\"_blank\">".$prod_model."<br>產品介紹</a>";
											}
										}else{
											echo "無資料";
										}
										$query=null; 
										//$pdo->error();
									
									}else{
										echo "無法取得連線";
									}
								?>
							</div>
						</div>
						<div class="pro_con6 pn">
							<div class="sk_data6 dr" >銷售頁面範本</div>
							<div class="sk_data6 dr1" >
								<div style="text-align: center;cursor:pointer;color:white;background-color:#e41d1d;margin:0 0 4px 0;" onclick="pro_maker(this, <?=$id_count?>, 1);">全球KVM</div>
								<div style="text-align: center;cursor:pointer;color:white;background-color:#e41d1d;margin:0 0 4px 0;" onclick="pro_maker(this, <?=$id_count?>, 2);">PChome24H</div>
								<div style="text-align: center;cursor:pointer;color:white;background-color:#e41d1d;margin:0 0 4px 0;" onclick="pro_maker(this, <?=$id_count?>, 3);">純文字內容</div>
								<div style="text-align: center;cursor:pointer;color:white;background-color:#e41d1d;" onclick="pro_maker(this, <?=$id_count?>, 4);">部落格通用</div>
							</div>
						</div>
					</div>
						
					<div class="pro_maker" id="pro_maker<?=$id_count?>"><!-------------產品介紹頁------------------->
						出貨料號：<span id="sale_sk_no"><?=$SK_NO1."_".$Model?></span>
						<button class="btn" data-clipboard-target="#sale_sk_no">
						  複製出貨料號
						 </button><br>
						 商品名稱：<span id="sale_name"><?=$row['fd_name']?"【PCT】".$row['fd_name']."(".$Model.")":'未填寫'?></span>
						 <button class="btn" data-clipboard-target="#sale_name">
						  複製商品名稱
						 </button><br>
						<div class="pro_c_t">
						產品類別一：<?=(($row['SK_USE']!='')?$row['SK_USE']:"未填寫")?><br>
						產品類別二：<?=(($row['SK_LOCATE']!='')?$row['SK_LOCATE']:"未填寫")?><br>
						範本說明：<span id="pro_maker_des">此網頁範本說明</span>
						</div>
						<hr>
						<button id="btn_pro_maker_content" class="btn" data-clipboard-target="#pro_maker_content<?=$id_count?>">
						  複製頁面範本
						 </button>
						<div class="pro_maker_c" id="pro_maker_content<?=$id_count?>"></div>
					</div>
	<?php				
					$id_count ++ ;
					$features_en = "";
				}
			}
			else {
				echo "無法取得資料，請確認料號 <span style=\"color:blue\"><b>".$SK_NO_query."</b></span>是否位於資料庫！<br>"; 
				echo "若無此料號，請刪除或更換後點擊[更新基本資料]，並再次於上方重新查詢。"; 
			}
	?>	
			</div>
		</div>

	<?php
		}else{
				echo "沒有料號可進行查詢！";
		}
		$query=null;
	}
	



	date_default_timezone_set("Asia/Taipei");
?>