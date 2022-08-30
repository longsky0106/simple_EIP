<?php

require_once '../functions/MyPDO.php';
require_once '../functions/MyFunctions.php';
require_once '../system/MyConfig.php';
header('Content-Type:text/html;charset=utf8');
	
	set_time_limit(100);
	$shop_menu2_id = $_POST["shop_menu2_id"];
	$SK_NO = $_POST["SK_NO"];
	// $shop_menu2_id = 1;
	// $SK_NO = 'PPUCSR060A';
	//$prod_class_id="30";
	$pdo = new MyPDO;
	
	// 顯示所有連接埠介面
	$sql_pct = "SELECT [Spec_Port]
				FROM [PCT].[dbo].[Data_Prod_Spec_Port]
				WHERE Spec_Port != :dump";
	$query = $pdo->bindQuery($sql_pct, [
		':dump' => ''
	]);			
	$row_count = count($query);
	if($row_count){		
		foreach($query as $row){
			$Spec_Port .= $row['Spec_Port'].",";
		}
		$Spec_Port = substr($Spec_Port,0,-2);
		$Spec_Port = str_replace("[","",$Spec_Port);
		$Spec_Port = str_replace("]","",$Spec_Port);
		// echo $Spec_Port;
	}
	$query=null;
	
	
	// 取得規格項目名稱
	$sql = "SELECT 
			[spec_item_id]
			,[spec_item_name]
			,[spec_item_name_en]
			,[spec_item_name_form]
			FROM [PCT].[dbo].[Menu_Spec_Item_Universal_1]
			UNION ALL
			SELECT	
			[spec_item_id]
			,[spec_item_name]
			,[spec_item_name_en]
			,[spec_item_name_form]
			FROM [PCT].[dbo].[Menu_Prod_class] as B
			LEFT JOIN [PCT].[dbo].[Menu_Spec_Link] on [prod_class_id] = MPC
			LEFT JOIN [PCT].[dbo].[Menu_Prod_Type] on prod_type_id = MPT
			LEFT JOIN [PCT].[dbo].[Menu_Spec_Item] on [spec_item_id] = MSI
			LEFT JOIN [PCT].[dbo].[Menu_Prod_Class_shop] on [spec_menu_class_index] = prod_class_id
			WHERE shop_menu2_id =:shop_menu2_id
			UNION ALL
			SELECT 
			[spec_item_id]
			,[spec_item_name]
			,[spec_item_name_en]
			,[spec_item_name_form]
			FROM [PCT].[dbo].[Menu_Spec_Item_Universal_2]";
	
	// 取得產品資料 顏色 規格
	$sql2 = "SELECT SK_NO, SK_NAME, SK_NOWQTY, SK_SPEC, SK_UNIT, SK_COLOR, SK_SIZE, SK_SESPES, SK_ESPES, SK_IKIND, SK_KINDNAME, SK_REM, SK_SMNETS, BD_DSKNO, BM_USKNO, SK_USE, SK_LOCATE, fd_name
			FROM (
			SELECT DISTINCT ".$ly_sql_db_table.".SK_NO, ".$ly_sql_db_table.".SK_NAME, SK_NOWQTY, CONVERT(NVARCHAR(MAX),SK_SPEC) AS 'SK_SPEC', SK_UNIT, SK_COLOR, SK_SIZE, SK_SESPES, CONVERT(VARCHAR(MAX),SK_ESPES) AS 'SK_ESPES', SK_IKIND, SK_KINDNAME = case when SK_KINDNAME IS NULL then '' else SK_KINDNAME end, CONVERT(NVARCHAR(MAX),SK_REM) AS 'SK_REM', CONVERT(NVARCHAR(MAX),SK_SMNETS) AS 'SK_SMNETS', BD_DSKNO, BM_USKNO, SK_USE, SK_LOCATE, fd_name
			, ROW_NUMBER ( ) OVER ( PARTITION BY ".$ly_sql_db_table.".SK_NO order by ".$ly_sql_db_table.".SK_NO DESC) as rn
			FROM ".$ly_sql_db_table."
			LEFT JOIN XMLY5000.dbo.SSTOCKKIND on ".$ly_sql_db_table.".SK_IKIND = XMLY5000.dbo.SSTOCKKIND.SK_KINDID
			LEFT JOIN XMLY5000.dbo.BOMDT on ".$ly_sql_db_table.".SK_NO = XMLY5000.dbo.BOMDT.BD_USKNO
			LEFT JOIN ".$ly_sql_db_table_FD." on ".$ly_sql_db_table.".SK_NO = ".$ly_sql_db_table_FD.".fd_skno
			LEFT JOIN (
				SELECT BM_USKNO,SK_NO
				FROM XMLY5000.dbo.BOM
				LEFT JOIN XMLY5000.dbo.BOMDT ON ( BOM.BM_USKNO = BOMDT.BD_USKNO )
				INNER JOIN ".$ly_sql_db_table." on (".$ly_sql_db_table.".sk_no=BOMDT.BD_DSKNO )
			) BOMUSE on ".$ly_sql_db_table.".SK_NO = BOMUSE.SK_NO
			) AS SKM
			WHERE rn = 1 and SK_NO =:SK_NO";
	
	$sql3 = "SELECT [shop_menu2_id]
			,[shop_menu1_index]
			,[shop_menu2_name]
			,[spec_menu_class_index]
			,[pct_menu2_id]
			FROM [PCT].[dbo].[Menu_Prod_Class_shop]
			WHERE shop_menu2_id =:shop_menu2_id";
	$query = $pdo->bindQuery($sql, [
		':shop_menu2_id' => $shop_menu2_id
	]);	

	$query2 = $pdo->bindQuery($sql2, [
		':SK_NO' => $SK_NO
	]);	

	// $query3 = $pdo->bindQuery($sql3, [
		// ':shop_menu2_id' => $shop_menu2_id
	// ]);	
	$row_count = count($query); 
	$row_count2 = count($query2); 

	if($row_count){
	
?>
		<form action="">
<?php
		// 產品子分類: 11 - 擴充座
		if($shop_menu2_id == 11 || $shop_menu2_id == 13){
			echo $Spec_Port;
?>
			擴充座輸出介面:
			<div id="spec_port">
				<div>
					<label>
						<input id="port_HDMI" title="HDMI" class="checkbox_spec_port" type="checkbox" autocomplete="off">HDMI
					</label>
					<input type="text" class="port_count" name="HDMI_port_count" autocomplete="off"  value="" disabled>
				</div>
				<div>
					<label>
						<input id="port_DisplayPort" class="checkbox_spec_port" type="checkbox" autocomplete="off">DisplayPort
					</label>
				</div>
				<div>
					<label>
						<input id="port_DVI" class="checkbox_spec_port" type="checkbox" autocomplete="off">DVI
					</label>
				</div>
				<div>
					<label>
						<input id="port_VGA" class="checkbox_spec_port" type="checkbox" autocomplete="off">VGA
					</label>
				</div>
			</div>
<?php
		}
?>				
			<div id="spec_input_content">
			
				<div id="zh-tw_spec" class="spec_input_aren">
				<div class="spec_input_title">中文</div>
<?php		
				if($row_count2){
					foreach($query2 as $row2){
?>									
						<label for="spec_item_name">銷售品名</label>
						<input type="text" id="" name="name_for_sell_tw" value="<?=$row2['fd_name']?>">
<?php									
						//print_r(nl2br($row2['SK_SPEC']));
					}
				}

				foreach($query as $row){
					if($row['spec_item_id']!=''){
?>				
						<label for="spec_item_name"><?=$row['spec_item_name']?></label><!-- 規格項目標題 -->
						<input type="text" id="" name="<?=$row['spec_item_name_form']?>" value="<?php //規格項目數值

							if($row_count2){
								// 格式調整	
								foreach($query2 as $row2){
									//$spec_item_search_string = str_replace(' (mm)','',$row['spec_item_name']).": ";
									$spec_item_search_string = $row['spec_item_name'].": ";
									$spec_item_array = explode("<br />" ,nl2br(str_replace('    ',': ',str_replace('	',': ',$row2['SK_SPEC']))));
									$spec_item_key = array_search_partial($spec_item_array, $spec_item_search_string);
									$spec_item_get = str_replace($spec_item_search_string,'',$spec_item_array[$spec_item_key]);
									if ($row['spec_item_name']=='顏色'){
										$spec_item_get = $row2['SK_COLOR'];
									}
								}
								echo $spec_item_get;
							}
						?>">
<?php
						if ($row['spec_item_name']!='型號' && $row['spec_item_name']!='尺寸 (mm)'){
?>							
							<div class="example_content ex_tw">
								<input type="button" class="example_btn" value="1" onclick=";">
								<input type="button" class="example_btn" value="2" onclick=";">
								<input type="button" class="example_btn" value="3" onclick=";">
							</div>
<?php
						}
?>
						<br>
<?php				
					}	
				}
?>			
				</div>
				
				<div id="en-us_spec" class="spec_input_aren">
					<div class="spec_input_title"><div id="title_en">英文</div><div id="title_example">帶入範例</div></div>
<?php
					if($row_count2){
						foreach($query2 as $row2){
?>									
							<label for="spec_item_name">Marketable Product Name</label>
							<input type="text" id="" name="name_for_sell_en" value="<?=$row2['SK_SESPES']?>">
<?php									
						}
					}
					foreach($query as $row){
						if($row['spec_item_id']!=''){
?>					
							<label for="spec_item_name_en"><?=$row['spec_item_name_en']?></label>
							<input type="text" id="" name="<?=$row['spec_item_name_form'].'_en'?>" value="<?php
								if($row_count2){
									foreach($query2 as $row2){
										$spec_item_search_string = str_replace(' (mm)','',$row['spec_item_name_en']).": ";
										$spec_item_array = explode("<br />" ,nl2br(str_replace('    ',': ',str_replace('	',': ',$row2['SK_ESPES']))));
										$spec_item_key = array_search_partial($spec_item_array, $spec_item_search_string);
										$spec_item_get = str_replace($spec_item_search_string,'',$spec_item_array[$spec_item_key]);
									}
									echo $spec_item_get;
								}
							?>">
<?php
						if ($row['spec_item_name']!='型號' && $row['spec_item_name']!='尺寸 (mm)'){
?>									
							<!-- 帶入範例值的按鈕 -->
							<div class="example_content ex_en">
								<input type="button" class="example_btn" value="1" onclick=";">
								<input type="button" class="example_btn" value="2" onclick=";">
								<input type="button" class="example_btn" value="3" onclick=";">
							</div>
							<div class="example_content ex_both">
								<input type="button" class="example_btn" value="1" onclick=";">
								<input type="button" class="example_btn" value="2" onclick=";">
								<input type="button" class="example_btn" value="3" onclick=";">
							</div>
<?php
						}
?>							
							<br>
<?php				
						}	
					}
?>			
				</div>
				
			</div>
			<!--<input type="button" value="送出" onclick=";">-->
		</form>
<?php
	}
	$query=null;
	$query2=null;
?>	
