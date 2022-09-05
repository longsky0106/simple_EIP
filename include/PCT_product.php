<?php   

require_once '../functions/MyPDO.php';
require_once '../system/MyConfig.php';
header('Content-Type:text/html;charset=utf8');
	
	set_time_limit(100);
	$Model = $_POST["Model"];
	$SK_NO = $_POST["SK_NO"]; $template = $_POST["template"];
	//$SK_NO="PPUCSR048";	$template = 'template1.php';
	$pdo = new MyPDO;

	// 設定變數
	$url_self = $PHP_SELF;  // 目前這個程式的檔名 $_SERVER['SCRIPT_NAME'];
	$path_picture = "/images/";   // 定義商品圖檔存放路徑
	$str_replace_set = '';
	

	$sql = "SELECT SK_NO, SK_NAME, SK_USE, SK_LOCATE, SK_NOWQTY, SK_SPEC, SK_UNIT, SK_COLOR, SK_SIZE, SK_SESPES, SK_ESPES, SK_REM, SK_SMNETS, BD_DSKNO, SK_FLD6, BD_DSKNM, fd_name
			FROM (
				SELECT DISTINCT SK_NO, SK_NAME, SK_USE, SK_LOCATE, SK_NOWQTY, CONVERT(NVARCHAR(MAX),SK_SPEC) AS 'SK_SPEC', SK_UNIT, SK_COLOR, SK_SIZE, SK_SESPES, CONVERT(VARCHAR(MAX),SK_ESPES) AS 'SK_ESPES', CONVERT(NVARCHAR(MAX),SK_REM) AS 'SK_REM', CONVERT(NVARCHAR(MAX),SK_SMNETS) AS 'SK_SMNETS', BD_DSKNO, SK_FLD6, BD_DSKNM, fd_name
				, ROW_NUMBER ( ) OVER ( PARTITION BY SK_NO order by SK_NO DESC) as rn
				FROM ".$ly_sql_db_table."
				LEFT JOIN XMLY5000.dbo.BOMDT on ".$ly_sql_db_table.".SK_NO = XMLY5000.dbo.BOMDT.BD_USKNO  --材料表
				LEFT JOIN ".$ly_sql_db_table_FD." on ".$ly_sql_db_table.".SK_NO = ".$ly_sql_db_table_FD.".fd_skno  --貨品明細
			) AS SKM
			WHERE SK_NO =:SK_NO
			AND rn=1
			Order by SK_NO";
		
	// 執行SQL及處理結果
	$query = $pdo->bindQuery($sql, [
		':SK_NO' => $SK_NO
	]);		
	$row_count = count($query);


	if($row_count){
		//$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
		foreach($query as $row){
		
			// 將查詢結果存入變數
			$arr_SPEC = explode(PHP_EOL, $row['SK_SPEC']);
			if (strpos($arr_SPEC[0], '	') !== FALSE) {
					$str_replace_set = '	';
			}
			else {
				$str_replace_set = '    ';
			}
			$prod_name = $row['SK_NAME'];  																//  廠內品名
			$prod_model = explode("/", $prod_name, 2)[0];   											// 型號
			
			$prod_model = $Model;
			
			$filename = $prod_model;
			$path = 'http://assets.pct-max.com.tw/' . $prod_model . '/';
			$img_src1 = $path . $filename . '.jpg';
			$img_src2 = $path . $filename . '.png';
			$img_src3 = $path . $filename . '_x700.jpg';
			$img_src4 = $path . $filename . '_x700.png';
			$img_src5 = $path . $filename . '_x800.jpg';
			$img_src6 = $path . $filename . '_x800.png';
			$img_src7 = $path . $filename . '.gif';
			$img_final_name = '';

			if (@getimagesize($img_src3)) {
				$img_final_name = str_replace($path, "", $img_src3);
			} else if (@getimagesize($img_src4)) {
				$img_final_name = str_replace($path, "", $img_src4);
			} else if (@getimagesize($img_src5)) {
				$img_final_name = str_replace($path, "", $img_src5);
			} else if (@getimagesize($img_src6)) {
				$img_final_name = str_replace($path, "", $img_src6);
			} else {
				$img_final_name = "";
			}
			
			if($row['fd_name']){																		// 銷售品名
				$name_for_sell =  "【PCT】".$row['fd_name']."(".$prod_model.")";
			}else{
				$name_for_sell = "【PCT】".explode($str_replace_set, $arr_SPEC[1])[1]."(".$prod_model.")" ; 
			}
			
			if($row['fd_name']){																		// 銷售品名2
				$name_for_sell2 =  $row['fd_name']."(".$prod_model.")";
			}else{
				$name_for_sell2 = explode($str_replace_set, $arr_SPEC[1])[1]."(".$prod_model.")" ; 
			}

			//$name_for_sell = "【PCT】".$row['SK_USE'].$row['SK_LOCATE']."(".$prod_model.")";
			$description_all = explode("---DESCRIPTION---", $row['SK_SMNETS'])[0];  					// 產品敘述(中文)
			$description = explode("---Features---", $description_all)[0];								// 產品敘述(中文)
			$arr_features = explode(PHP_EOL, explode("---Features---", $description_all)[1]);			// 產品特色(中文)
			$arr_features_index = count($arr_features)-1;
			$description_all_en = explode("---DESCRIPTION---", $row['SK_SMNETS'])[1];					// 產品敘述(英文)
			$description_en = explode("---Features---", $description_all_en)[0];						// 產品敘述(英文)
			$arr_features_en = explode(PHP_EOL, explode("---Features---", $description_all_en)[1]);		// 產品特色(英文)
			$arr_features_index_en = count($arr_features_en)-1;
			
			$arr_SPEC_index = count($arr_SPEC);															// 規格(中文)(未轉成表格)
			$EN_SPES = $row['SK_ESPES'];;															// 規格(英文)(未轉成表格)
			$path_picture = $path_picture;
			$warranty0 = "此商品屬『耗材類』，我們提供七天內新品瑕疵有限條件更換服務";
			$warranty1 = "一年";
			
			if (strpos($row['SK_IKIND'], 'CAB') !== FALSE
				|strpos($row['SK_IKIND'], 'CON') !== FALSE
				|strpos($row['SK_IKIND'], 'UCS') !== FALSE
				|strpos($row['SK_IKIND'], 'UCA') !== FALSE
				|strpos($row['SK_IKIND'], 'OEM') !== FALSE) {  // 保固 (產品類別-線材類為7天，否則為一年)
					$warranty = $warranty0;
			}
			else if($row['SK_IKIND']) {  // 保固 (產品類別-線材類為7天，否則為一年)
				$warranty = $warranty1;
			}else{
				$warranty = $warranty0;
			}
			
			//---包裝內容---
			$CM_count = 1;		// 機器數量
			$cable_count = 1;	// 線材數量
			$cable_count = preg_split('#(?=[0-9])#i', $prod_model)[1];	// 線材數量
			//$content_M = "<li>".$prod_model."本體 x ".$CM_count."</li>";	// 機器一定有
			$content_M = "<li>".$name_for_sell2." x ".$CM_count."</li>";	// 機器一定有
			//$cable_for_prod = strpos($prod_name,'含線')?"<li>專用線材 x ".$cable_count."</li>":"";
			
				
			$sql2 = "SELECT DISTINCT 
					SK_NO, 
					SK_NAME, 
					BD_DSKNO,  
					BD_DSKNM
					FROM ".$ly_sql_db_table."
					LEFT JOIN 
					XMLY5000.dbo.BOMDT 
					on ".$ly_sql_db_table.".SK_NO = XMLY5000.dbo.BOMDT.BD_USKNO  --材料表
					WHERE SK_NO =:SK_NO";
		
			// 執行SQL及處理結果
			$query2 = $pdo->bindQuery($sql2, [
				':SK_NO' => $SK_NO
			]);		
			$row_count = count($query2);
			if($row_count){
				foreach($query2 as $row){	// 配件 (找產品結構內配件)
					if (strpos($row['BD_DSKNM'], '說明書') !== FALSE) {
						$Manual_for_prod = "<li>說明書 x 1</li>";
					}
					if (strpos($row['BD_DSKNM'], '光牒片') !== FALSE) {  // 光牒片
						$Driver_CD = "<li>"."光牒片 x 1</li>";
					}
					if (strpos($row['BD_DSKNM'], 'SWITCH POWER') !== FALSE) {  // 變壓器
						$SWITCH_POWER = "<li>變壓器 x 1</li>";
					}
					if (strpos($row['BD_DSKNM'], 'CABLE') !== FALSE) {  // 線材
						$CABLE = "<li>"."線材 x 1</li>";
					}
					if (strpos($row['BD_DSKNM'], '遙控器') !== FALSE) {  // 遙控器
						$IR_control = "<li>"."遙控器 x 1</li>";
					}
					if (strpos($row['BD_DSKNM'], 'USB A-TYPE對DC') !== FALSE 
					|| strpos($row['BD_DSKNM'], 'USB A to DC') !== FALSE) {  // 遙控器
						$USB_POWER_CABLE = "<li>"."USB 轉 DC 電源線 x 1</li>";
					}
					if (strpos($row['BD_DSKNM'], 'AUDIO∮3.5-M對按鍵開關') !== FALSE) {  // 線控按鈕
						$External_button_tw .= "<li>"."線控按鈕 x 1</li>";
						$External_button_en .= "<li>"."External button with cable x 1</li>";
					}
				}
			}
			$query2=null;
			// 產品特色
			$i=1;
			while($i<$arr_features_index) {
				$features .= $arr_features[$i]."\r\n";
				$i ++ ;
			}
			
			// 產品特色(英文)
			$i=0;
			while($i<$arr_features_index_en) {
				$features_en .= $arr_features_en[$i+1]."<br>";
				$i ++ ;
			}

			// 規格-表格開頭(暫定)
			$SK_SPEC ="<table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">
				<tbody>";
			$i=0;
			while($i<$arr_SPEC_index) {
				$SK_SPEC .="<tr><td>";
				if (strpos($arr_SPEC[$i], '	') !== FALSE) {
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
		}
	}
	else {
		//echo "屬下無能，找不到資料，請見諒。(? "; 
	}
	
	
	date_default_timezone_set("Asia/Taipei");
	
	include $template;
	//echo $SK_SPEC;
	
	
	$query=null;	
	//echo '$SK_SPEC'.$SK_SPEC;

?>