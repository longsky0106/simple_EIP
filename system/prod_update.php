<?php

require_once '../functions/MyPDO.php';
require_once '../functions/MyFunctions.php';
require_once '../system/MyConfig.php';
header('Content-Type:text/html;charset=utf8');

	set_time_limit(100);	
	$data = json_decode($_POST['data'], true);
	//print_r($data);
	$pdo = new MyPDO;
	
	// 查出中文規格的標題
	$sql_spec_item_name = "SELECT DISTINCT SpecItem.[spec_item_name],SpecItem.[spec_item_name_en]
			FROM
			(
				SELECT [spec_item_name],[spec_item_name_en],[spec_item_name_form]
				FROM [PCT].[dbo].[Menu_Spec_Item_Universal_1]
				UNION ALL
				SELECT [spec_item_name],[spec_item_name_en],[spec_item_name_form]
				FROM [PCT].[dbo].[Menu_Spec_Item]
				UNION ALL
				SELECT [spec_item_name],[spec_item_name_en],[spec_item_name_form]
				FROM [PCT].[dbo].[Menu_Spec_Item_Universal_2]
			)AS SpecItem
			WHERE [spec_item_name_form] = :spec_item_name_form";
	
	// 初始化規格項目陣列
	$spec_item_name_array = array();
	
	// 將前端送來的資料存到以$key為變數名稱的變數中
	foreach($data as $key => $value){
		
		//$SK_NO = $data["SK_NO"];	
		$$key = $data[$key];
		$spec_item_name_array[] = $key;
		// echo $key."<br>";
		
		$pct_web_update_check = $data["pct_web_update"];
		// $check_pct_sql_temp = $data["check_pct_sql_temp"];

		//查出中文規格的標題並列出
		if(!strpos($key,'_en'))
		{
			$query_to_exec = $pdo->bindQuery($sql_spec_item_name, [
				':spec_item_name_form' => $key
			]);

			$row_count = count($query_to_exec);
			if($row_count){
				
				// 將中文規格每一行排成: 規格標題 全形空格 規格內容
				foreach($query_to_exec as $row){
					$SK_SPEC_tw .=  $row['spec_item_name']."	".$value."\r\n";
					//echo nl2br(str_replace('	','&emsp;',$SK_SPEC_tw));
				}
			}
			$query_to_exec=null;
		}
		//重組出英文文規格的標題並列出
		else if(strpos($key,'_en') && !str_starts_with($key,'SK_') && !str_starts_with($key,'name_for_sell'))
		{
			$$key_en = $data[$key];
			$key = str_replace('_en','',$key);
			$key = str_replace('_',' ',$key);
			$key = str_replace('Max ','Max. ',$key);
			$SK_SPEC_en .= $key."	".$value."\r\n";
			// echo nl2br(str_replace('	','&emsp;',$SK_SPEC_en));
		}
		else
		{
			// echo $key.": ".$value."<br>";
		}
	}
	
	// 檢查前端送來的資料有沒有勾選"使用臨時資料庫"
/* 	if($check_pct_sql_temp){
		$ly_sql_db_table = $ly_sql_db_table_temp;
		$ly_sql_db_table_FD = $ly_sql_db_table_FD_temp;
	}else{
		$ly_sql_db_table = $ly_sql_db_table_std;
		$ly_sql_db_table_FD = $ly_sql_db_table_FD_std;
	} */
	
	// 更新產品對照表基本資料
	
	
	
	
	
	
	//查'網路平台品名'是否已經存在於$dbname.dbo.SSTOCKFD(SQL)
	$sql_name_for_sell = "SELECT fd_name 
						  FROM ".$ly_sql_db_table_FD." 
						  WHERE fd_skno = :fd_skno";
	
					  
	//查'網路平台品名'是否已經存在於$dbname.dbo.SSTOCKFD(執行)
	$query_to_exec = $pdo->bindQuery($sql_name_for_sell, [
		':fd_skno' => $SK_NO
	]);
	$row_count = count($query_to_exec);
	$bHave_fd_skno = false;
	if($row_count){
		
		$bHave_fd_skno = true;
		// SQL語句 - 有紀錄的話就修改
		$sql_name_for_sell = "UPDATE ".$ly_sql_db_table_FD." 
							  SET fd_name = :fd_name
							  WHERE fd_skno = :fd_skno
							  ";
	}else{
		// SQL語句 - 沒紀錄的話就插入
		$sql_name_for_sell = "INSERT INTO ".$ly_sql_db_table_FD." (
								 fd_skno
								,fd_lang
								,fd_name
								,fd_spes
							 )
							 VALUES (
								  :fd_skno
								, :fd_lang --' 網路平台 '
								, :fd_name --'網路平台品名'
								, :fd_spes --''
							)
							";
	}
	$query_to_exec=null;
	
	

	
	$SK_SPEC_tw = rtrim($SK_SPEC_tw);
	// $SK_SPEC_en = rtrim(str_replace('Max ','Max. ',$SK_SPEC_en));
	$SK_SPEC_en = rtrim($SK_SPEC_en);
	$SK_features_arr = explode("\n",$SK_features_tw);
	$i=0;
	$SK_features_tw = "";
	while($i<count($SK_features_arr)) {
		$SK_features_tw .= $SK_features_arr[$i]."\r\n";
		$i ++ ;
	}
	$SK_features_tw = rtrim($SK_features_tw);
	
	$SK_features_arr = explode("\n",$SK_features_en);
	$i=0;
	$SK_features_en = "";
	while($i<count($SK_features_arr)) {
		$SK_features_en .= $SK_features_arr[$i]."\r\n";
		$i ++ ;
	}
	$SK_features_en = rtrim($SK_features_en);
	
	$SK_SMNETS = $SK_description_tw
				 .(($SK_description_tw == "")?"":"\r\n")."---Features---\r\n"
				 .$SK_features_tw
				 .(($SK_features_tw == "")?"":"\r\n")."---DESCRIPTION---\r\n"
				 .$SK_description_en
				 .(($SK_description_en == "")?"":"\r\n")."---Features---\r\n"
				 .$SK_features_en;
	
	$SK_SMNETS = rtrim($SK_SMNETS);

	
	$ly_bindParam_array = array();
	
	if($name_for_sell_tw==""){
		$name_for_sell_tw="";
	}
	
	for($i=1;$i<=4;$i++){
		$item = "SK_NO".$i;
		// echo $item." = ".$$item;
		
		if($$item){
			$item_all++;
			// 沒有建立'網路平台品名'
			if($bHave_fd_skno == false){
				$ly_bindParam_array = [
					  ':SK_USE' => $SK_Categories
					, ':SK_LOCATE' => $SK_ProdType
					, ':SK_SMNETS' => $SK_SMNETS
					, ':SK_SPEC' => $SK_SPEC_tw
					, ':SK_COLOR' => $Color
					, ':SK_ESPES' => $SK_SPEC_en
					, ':SK_SESPES' => $name_for_sell_en
					, ':SK_NO' => $$item
					, ':fd_name' => $name_for_sell_tw
					, ':fd_skno' => $SK_NO
					, ':fd_lang' => '網路平台'
					, ':fd_spes' => ''
				];
			}else{
				// 有建立'網路平台品名'
				$ly_bindParam_array = [
					  ':SK_USE' => $SK_Categories
					, ':SK_LOCATE' => $SK_ProdType
					, ':SK_SMNETS' => $SK_SMNETS
					, ':SK_SPEC' => $SK_SPEC_tw
					, ':SK_COLOR' => $Color
					, ':SK_ESPES' => $SK_SPEC_en
					, ':SK_SESPES' => $name_for_sell_en
					, ':SK_NO' => $$item
					, ':fd_name' => $name_for_sell_tw
					, ':fd_skno' => $SK_NO
				];
			}
			// 當輪到料號4更新時，切換到臨時資料庫(每次呼叫prod_update.php這個程式都會再次設為初始值(MyConfig.php))
			if($i==4){
				$ly_sql_db_table = $ly_sql_db_table_temp;
				$ly_sql_db_table_FD = $ly_sql_db_table_FD_temp;
				
				//查'網路平台品名'是否已經存在於PCT.dbo.SSTOCKFD_temp(SQL)
				$sql_name_for_sell = "SELECT fd_name 
									  FROM ".$ly_sql_db_table_FD." 
									  WHERE fd_skno = :fd_skno";
				
								  
				//查'網路平台品名'是否已經存在於PCT.dbo.SSTOCKFD_temp(執行)
				$query_to_exec = $pdo->bindQuery($sql_name_for_sell, [
					':fd_skno' => $SK_NO4
				]);
				$row_count = count($query_to_exec);
				$bHave_fd_skno = false;
				if($row_count){
					
					$bHave_fd_skno = true;
					// SQL語句 - 有紀錄的話就修改
					$sql_name_for_sell = "UPDATE ".$ly_sql_db_table_FD." 
										  SET fd_name = :fd_name
										  WHERE fd_skno = :fd_skno
										  ";
				}else{
					// SQL語句 - 沒紀錄的話就插入
					$sql_name_for_sell = "INSERT INTO ".$ly_sql_db_table_FD." (
											 fd_skno
											,fd_lang
											,fd_name
											,fd_spes
										 )
										 VALUES (
											  :fd_skno
											, :fd_lang --' 網路平台 '
											, :fd_name --'網路平台品名'
											, :fd_spes --''
										)
										";
				}
				$query_to_exec=null;
				
				if($bHave_fd_skno == false){
					$ly_bindParam_array = [
						  ':SK_USE' => $SK_Categories
						, ':SK_LOCATE' => $SK_ProdType
						, ':SK_SMNETS' => $SK_SMNETS
						, ':SK_SPEC' => $SK_SPEC_tw
						, ':SK_COLOR' => $Color
						, ':SK_ESPES' => $SK_SPEC_en
						, ':SK_SESPES' => $name_for_sell_en
						, ':SK_NO' => $$item
						, ':fd_name' => $name_for_sell_tw
						, ':fd_skno' => $$item
						, ':fd_lang' => '網路平台'
						, ':fd_spes' => ''
					];
				}else{
					// 有建立'網路平台品名'
					$ly_bindParam_array = [
						  ':SK_USE' => $SK_Categories
						, ':SK_LOCATE' => $SK_ProdType
						, ':SK_SMNETS' => $SK_SMNETS
						, ':SK_SPEC' => $SK_SPEC_tw
						, ':SK_COLOR' => $Color
						, ':SK_ESPES' => $SK_SPEC_en
						, ':SK_SESPES' => $name_for_sell_en
						, ':SK_NO' => $$item
						, ':fd_name' => $name_for_sell_tw
						, ':fd_skno' => $$item
					];
				}
				// 凌越系統資料庫.貨品明細資料 ".$dbname.".dbo.SSTOCKFD
				// PCT資料庫.貨品明細資料 PCT.dbo.SSTOCKFD_temp
				// $sql_name_for_sell = str_replace($ly_sql_db_table_FD, $ly_sql_db_table_FD_temp, $sql_name_for_sell);
			}
			
			// SQL語句 - 更新 - 凌越系統資料庫
			$sql = "UPDATE ".$ly_sql_db_table." 
					SET 
					  ".$ly_sql_db_table.".SK_USE = :SK_USE  --'產品分類一'
					, ".$ly_sql_db_table.".SK_LOCATE = :SK_LOCATE  --'產品分類二'
					, ".$ly_sql_db_table.".SK_SMNETS = :SK_SMNETS  --'產品描述&特色(中&英)'
					, ".$ly_sql_db_table.".SK_SPEC = :SK_SPEC  --'產品規格'
					, ".$ly_sql_db_table.".SK_COLOR = :SK_COLOR  --'顏色'
					, ".$ly_sql_db_table.".SK_ESPES = :SK_ESPES  --'產品規格(英文)'
					, ".$ly_sql_db_table.".SK_SESPES = :SK_SESPES  --'網路平台品名(英文)'
					WHERE ".$ly_sql_db_table.".SK_NO = :SK_NO
					".$sql_name_for_sell;
			
			
			echo '<pre>'; print_r($sql);echo '</pre>'; // 顯示SQL語句
			echo '<pre>'; print_r($ly_bindParam_array);echo '</pre>'; // 對應資料
			
			
		
		
		
			// 寫入資料到凌越系統資料庫SQL Server
			$query_to_exec = $pdo->bindQuery($sql, $ly_bindParam_array);

		}else{
			// echo $item."沒有填寫料號！";
		}
		
		// $item_all .= $$item;
		
	}	
	
	if($item_all){
		echo "(凌越)規格資料存入 ".$item_all." 個料號中";
		echo "<br>";
		if($pdo->GetStmtStat()){
			echo "(凌越)產品資料更新成功!<br>";
		}else{
			echo "(凌越)產品資料更新失敗!<br>";
			$pdo->error();
		}
	}else{
		echo "沒有填寫任何料號";
	}

	

	

	
//print_r($spec_item_name_array);

$query_to_exec=null;
	
	// 有勾選更新官網
	if($pct_web_update_check){
		
		// 產生需要的資料
		//---格式調整 產品特色(中文)---
		$SK_features_tw = str_replace('● ','<li>',$SK_features_tw);
		$SK_features_tw = str_replace('■ ','<li style="list-style-type: square;">',$SK_features_tw);
		$SK_features_tw = str_replace('◆ ','<li>',$SK_features_tw);
		$SK_features_tw = "<ul>".$SK_features_tw."</ul>";
		
		//---格式調整 產品特色(英文)---
		$SK_features_en = str_replace('● ','<li>',$SK_features_en);
		$SK_features_en = str_replace('■ ','<li style="list-style-type: square;">',$SK_features_en);
		$SK_features_en = str_replace('◆ ','<li>',$SK_features_en);
		$SK_features_en = "<ul>".$SK_features_en."</ul>";
		
		//---↓包裝內容↓---
		$CM_count = 1;		// 機器數量
		$content_M_tw = "<li>".$name_for_sell_tw." (".$Model.") x ".$CM_count."</li>";	// 機器本體		
		$content_M_en = "<li>".$name_for_sell_en." (".$Model.") x ".$CM_count."</li>";	// 機器本體
		
		// SQL查詢凌越系統材料表
		$sql_bom = "SELECT DISTINCT 
				SK_NO, 
				SK_NAME, 
				BD_DSKNO,  
				BD_DSKNM
				FROM ".$dbname.".dbo.SSTOCK
				LEFT JOIN 
				".$dbname.".dbo.BOMDT 
				on ".$dbname.".dbo.SSTOCK.SK_NO = ".$dbname.".dbo.BOMDT.BD_USKNO  --材料表
				WHERE SK_NO =:SK_NO";
	
		// 執行SQL及處理結果
		$query_bom = $pdo->bindQuery($sql_bom, [
			':SK_NO' => $SK_NO
		]);		
		$row_count = count($query_bom);
		if($row_count){
			// 找產品結構內配件並將符合資料存到變數$content_M
			foreach($query_bom as $row){	
				if (strpos($row['BD_DSKNM'], '說明書') !== FALSE) {
					$content_M_tw .= "<li>說明書 x 1</li>";
					$content_M_en .= "<li>User Manual x 1</li>";
				}
				if (strpos($row['BD_DSKNM'], '光牒片') !== FALSE) {  // 光牒片
					$content_M_tw .= "<li>光牒片 x 1</li>";
					$content_M_en .= "<li>Drive CD x 1</li>";
				}
				if (strpos($row['BD_DSKNM'], 'SWITCH POWER') !== FALSE) {  // 變壓器
					$content_M_tw .= "<li>變壓器 x 1</li>";
					$content_M_en .= "<li>Power adapter x 1</li>";
				}
				if (strpos($row['BD_DSKNM'], 'CABLE') !== FALSE) {  // 線材
					$content_M_tw .= "<li>線材 x 1</li>";
					$content_M_en .= "<li>Cable x 1</li>";
				}
/* 				if (strpos($row['BD_DSKNM'], '遙控器') !== FALSE 
					&& strpos($row['BD_DSKNM'], '遙控器用)') === FALSE) {  // 遙控器
					$content_M_tw .= "<li>遙控器 x 1</li>";
					$content_M_en .= "<li>IR Control x 1</li>";
				} */
				if (strpos($row['BD_DSKNM'], '遙控器(') !== FALSE) {  // 遙控器
					$content_M_tw .= "<li>遙控器 x 1</li>";
					$content_M_en .= "<li>IR Control x 1</li>";
				}
				if (strpos($row['BD_DSKNM'], 'USB A-TYPE對DC') !== FALSE 
					|| strpos($row['BD_DSKNM'], 'USB A to DC') !== FALSE) {  // 遙控器
					$content_M_tw .= "<li>"."USB 轉 DC 電源線 x 1</li>";
					$content_M_en .= "<li>"."USB to DC cable x 1</li>";
				}
				if (strpos($row['BD_DSKNM'], 'AUDIO∮3.5-M對按鍵開關') !== FALSE) {  // 線控按鈕
					$content_M_tw .= "<li>"."線控按鈕 x 1</li>";
					$content_M_en .= "<li>"."External button with cable x 1</li>";
				}
				if (strpos($row['BD_DSKNM'], '遙控器配線/發射') !== FALSE) {  // 線控按鈕
					$content_M_tw .= "<li>"."遙控器配線/發射 x 1</li>";
					$content_M_en .= "<li>"."Remote control wiring/transmission x 1</li>";
				}
				if (strpos($row['BD_DSKNM'], '遙控器配線/接收') !== FALSE) {  // 線控按鈕
					$content_M_tw .= "<li>"."遙控器配線/接收 x 1</li>";
					$content_M_en .= "<li>"."Remote Control Wiring/Receive x 1</li>";
				}
			}
		}
		$content_M_tw = "<ul>".$content_M_tw."</ul>";
		$content_M_en = "<ul>".$content_M_en."</ul>";
		//---↑包裝內容↑---
		
		$query_bom=null;
		
		// 從資料表彙總中查詢官網欄位對應
		$sql_pct_get_column_name = "SELECT DISTINCT 
			SpecItem.[spec_item_name]
			,SpecItem.[spec_item_name_en]
			,[spec_item_name_form]
			,COLUMN_NAME
			FROM
			(
				SELECT [spec_item_name],[spec_item_name_en],[spec_item_name_form]
				FROM [PCT].[dbo].[Menu_Spec_Item_Universal_1]
				UNION ALL
				SELECT [spec_item_name],[spec_item_name_en],[spec_item_name_form]
				FROM [PCT].[dbo].[Menu_Spec_Item]
				UNION ALL
				SELECT [spec_item_name],[spec_item_name_en],[spec_item_name_form]
				FROM [PCT].[dbo].[Menu_Spec_Item_Universal_2]
				UNION ALL
				SELECT [spec_item_name],[spec_item_name_en],[spec_item_name_form]
				FROM [PCT].[dbo].[Menu_Spec_Item_Other]
			)AS SpecItem
			LEFT JOIN openquery(MYSQL, 'SELECT * FROM `INFORMATION_SCHEMA`.COLUMNS')
			on spec_item_name = COLUMN_COMMENT or spec_item_name_en = COLUMN_COMMENT
			WHERE 
			TABLE_SCHEMA = 'www.pct-max.com' 
			AND TABLE_NAME = 'product_list'
			AND COLUMN_COMMENT != ''
			AND COLUMN_NAME != :dump"; 
		
		$query_pct_get_column_name = $pdo->bindQuery($sql_pct_get_column_name, [
			':dump' => ''
		]);
		$row_count = count($query_pct_get_column_name);
		if($row_count){
			
			// 初始化PDO關聯陣列
			$pct_bindParam_array = array();
			
			foreach($query_pct_get_column_name as $row){
				
				// 將官網返回的欄位名稱存到變數
				$pct_column_name .= $row['COLUMN_NAME'].",";
				$pct_column_name_value .= ":".$row['COLUMN_NAME'].",";
				
				// 將官網欄位名稱組成PDO預先執行指令
				$pct_column_name_prepare .= $row['COLUMN_NAME']." = :".$row['COLUMN_NAME'].",";
				
				// 將資料表彙總中的欄位名稱改為英文對應並作為變數名
				$spec_item_name_form_en = $row['spec_item_name_form']."_en";
				

				// 如果資料表彙總中的欄位名稱對應到的變數存在並且欄位名稱不為空值
				if(${$row['spec_item_name_form']} && $row['spec_item_name_form']!=null){
					
					// 如果當前的官網欄位名稱為中文欄位
					if(strpos($row['COLUMN_NAME'],'_en') ===FALSE){
						
						// 將官網欄位與資料表彙總欄位組成PDO關聯並存到PDO關聯陣列
						$pct_bindParam_array[":".$row['COLUMN_NAME']] = ${$row['spec_item_name_form']};
					}else{
						
						// 如果資料表彙總中的英文欄位名稱對應到的變數存在
						if($$spec_item_name_form_en){
							
							// 將官網欄位與資料表彙總欄位英文變數組成PDO關聯並存到PDO關聯陣列
							$pct_bindParam_array[":".$row['COLUMN_NAME']] = $$spec_item_name_form_en;
						}else{
							
							// 將官網欄位與空值組成PDO關聯並存到PDO關聯陣列
							$pct_bindParam_array[":".$row['COLUMN_NAME']] = "";
							
							// 如果資料表彙總中的欄位名稱對應到的變數不存在並且帶_en
							if(strpos($row['spec_item_name_form'],'_en') !==FALSE){
								// 將官網欄位與資料表彙總欄位組成PDO關聯並存到PDO關聯陣列
								$pct_bindParam_array[":".$row['COLUMN_NAME']] = ${$row['spec_item_name_form']};
							}
						}
					}
				}else{
					
					// 將官網欄位與空值組成PDO關聯並存到PDO關聯陣列
					$pct_bindParam_array[":".$row['COLUMN_NAME']] = "";
				}
			}
			
			// 將官網產品資料索引存到PDO關聯陣列
			$pct_bindParam_array[':data_id'] = $data_id;
			
			// 將PDO關聯陣列中，中英文產品名稱加上型號
			$pct_bindParam_array[':data_title'] = $name_for_sell_tw."(".$Model.")";
			$pct_bindParam_array[':data_title_en'] = $name_for_sell_en."(".$Model.")";
			
			// 將官網欄位名稱變數最後的逗點移除
			$pct_column_name = substr($pct_column_name,0,-1);
			$pct_column_name_value = substr($pct_column_name_value,0,-1);
			// $pct_column_name_array = array();
			$pct_column_name_array = explode(",", $pct_column_name);
			
			// 將官網欄位名稱組成的PDO預先執行指令中，最後的逗點移除
			$pct_column_name_prepare = substr($pct_column_name_prepare,0,-1);
			
			
		}
		
		// echo "<br>".$pct_column_name."<br>";
		// echo "<br>".$pct_column_name_prepare."<br>";
		
		// 有紀錄的話就修改
		if($data_id){
			//echo $data_id." 官網資料更新";
			$sql_pct_to_exec = "update openquery(MYSQL, 'SELECT * FROM `www.pct-max.com`.product_list')
							set ".$pct_column_name_prepare.
							" WHERE data_id = :data_id";
		}else{
			echo $data_id."官網資料新增";
			
			// 沒紀錄就插入
			$sql_pct_to_exec = "INSERT openquery(MYSQL, 'SELECT * FROM `www.pct-max.com`.product_list')("
									.$pct_column_name.
								")VALUES("
									.$pct_column_name_value.
								")
			";
		}
		// echo "<br>".$sql_pct_to_exec;
		// echo '<pre>'; print_r($pct_column_name_array); echo '</pre>';
		// echo '<pre>'; print_r($spec_item_name_array); echo '</pre>';
		
		// echo "<br>".$sql_pct_to_exec."<br>";
		// echo "<br>".$pct_column_name."<br>";
		// echo "<br>".$pct_column_name_value."<br>";
		// echo '<pre>'; print_r($pct_bindParam_array); echo '</pre>'; // 對應資料

		// 寫入資料到官網資料庫MySQL\
		if($data_id){
			$query_pct_to_exe = $pdo->bindQuery($sql_pct_to_exec, $pct_bindParam_array);
		}
		
		
		if($pdo->GetStmtStat()){
			echo "(官網)產品資料更新成功!<br>";
		}else{
			echo "(官網)產品資料更新失敗!<br>";
			$pdo->error();
		}
		$query_pct_to_exe=null;
	}
	
	
	

?>