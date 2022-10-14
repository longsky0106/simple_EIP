<?php

require_once('../functions/MyPDO.php');
require_once '../functions/MyFunctions.php';
require_once '../system/MyConfig.php';
header('Content-Type:text/html;charset=utf8');

	set_time_limit(30);
	$Model = $_POST["Model"];
	$data_id = '';

	// $pdo = new MyPDO;
	
	
	
	
	
	// 查詢
	$sql_ly = "SELECT SK_NO, SK_NAME, SK_NOWQTY, SK_SPEC, SK_UNIT, SK_COLOR, SK_SIZE, SK_SESPES, SK_ESPES, SK_REM, SK_SMNETS, BD_DSKNO, BM_USKNO, SK_USE, SK_LOCATE, fd_name
			FROM (
			SELECT DISTINCT ".$ly_sql_db_table.".SK_NO, ".$ly_sql_db_table.".SK_NAME, SK_NOWQTY, CONVERT(NVARCHAR(MAX),SK_SPEC) AS 'SK_SPEC', SK_UNIT, SK_COLOR, SK_SIZE, SK_SESPES, CONVERT(VARCHAR(MAX),SK_ESPES) AS 'SK_ESPES', CONVERT(NVARCHAR(MAX),SK_REM) AS 'SK_REM', CONVERT(NVARCHAR(MAX),SK_SMNETS) AS 'SK_SMNETS', BD_DSKNO, BD_DSKNM, BM_USKNO, SK_USE, SK_LOCATE, fd_name
			, ROW_NUMBER ( ) OVER ( PARTITION BY ".$ly_sql_db_table.".SK_NO order by ".$ly_sql_db_table.".SK_NO DESC) as rn
			FROM ".$ly_sql_db_table."
			LEFT JOIN ".$dbname.".dbo.BOMDT on ".$ly_sql_db_table.".SK_NO = ".$dbname.".dbo.BOMDT.BD_USKNO
			LEFT JOIN ".$ly_sql_db_table_FD." on ".$ly_sql_db_table.".SK_NO = ".$ly_sql_db_table_FD.".fd_skno
			LEFT JOIN (
				SELECT BM_USKNO,SK_NO
				FROM ".$dbname.".dbo.BOM
				LEFT JOIN ".$dbname.".dbo.BOMDT ON ( BOM.BM_USKNO = BOMDT.BD_USKNO )
				INNER JOIN ".$ly_sql_db_table." on (".$ly_sql_db_table.".sk_no=BOMDT.BD_DSKNO )
			) BOMUSE on ".$ly_sql_db_table.".SK_NO = BOMUSE.SK_NO
			) AS SKM
			WHERE rn = 1 and SK_NO =:SK_NO";
	// echo $sql_ly;
	
	
	
	//echo " ---共".$row_count."筆資料---";
	

?>
<!doctype html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
    <title><?=$Company_name."簡易EIP"?></title>
    <link rel="stylesheet" href="../CSS/prod_list.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <!-- <script src="https://releases.jquery.com/git/ui/jquery-ui-git.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>
    <script src="JS/index.js"></script>
    <script src="JS/MyFunction.js"></script>
  </head>
  <body>	
	
<div id="search_bar_L">
	   <input type="text" placeholder="請輸入產品型號" name="model">
	   <button type="" name="" value="">搜尋</button>
	   每頁顯示<select id="categories" name="categories">
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="50">50</option>
							<option value="100">100</option>
						 </select>| [1][2][3][4][5]
 </div> 
  <hr>
	<div id="main_content_L">
		<div class="data_room_L">

			<div id="copy_statu_L">複製</div>
			<div class="data_room_con0_L"><!-- pro_con_L: 欄，dr: 列 -->
				<button type="" name="" value="">新增一筆資料</button>
				<button type="" name="" value="">更新所選資料</button>
				<button type="" name="" value="">刪除所選資料</button>
			</div>
			<div class="data_room_con1_L"><!-- pro_con_L: 欄，dr: 列 -->
				<div class="pro_con_L0 dr0_L" >編號</div>
				<div class="pro_con_L1 dr0_L" ><input type="checkbox" name="select"></div>
				<div class="pro_con_L2 dr0_L" >圖片</div>
				<div class="pro_con_L3 dr0_L" >型號</div>
				<div class="pro_con_L4 dr0_L" >分類</div>
				<div class="pro_con_L5 dr0_L" >銷售品名</div>
				<div class="pro_con_L6 dr0_L" >廠內品名</div>
				<div class="pro_con_L7 dr0_L" >料號</div>
				<div class="pro_con_L8 dr0_L" >官網頁面</div>
				<div class="pro_con_L9 dr0_L" >售價與成本</div>
				<div class="pro_con_L10 dr0_L" >庫存</div>
				<div class="pro_con_L11 dr0_L" >銷售頁面範本</div>
			</div>
			
			<div class="data_room_con2_L">
				<div class="pro_con_L0 pn_L">
					 <div class="sk_data_L0 dr_L" >編號</div>
					 <div class="sk_data_L0 dr1_L" ><?=$img_result?></div>
				</div>
				<div class="pro_con_L1 pn_L">
					<div class="sk_data_L1 dr_L" >選擇</div>
					<div class="sk_data_L1 dr1_L" id="sk_no<?=$id_count?>" ><?=$row['SK_NO']?></div>
				</div>
				<div class="pro_con_L2 pn_L">
					<div class="sk_data_L2 dr_L" >圖片</div>
					<div class="sk_data_L2 dr1_L" ><?=$row['fd_name']?$row['fd_name']:'未填寫'?></div>
				</div>
				<div class="pro_con_L3 pn_L">
					<div class="sk_data_L3 dr_L" >型號</div>
					<div class="sk_data_L3 dr1_L" ><?=$row['SK_NAME']?></div>
				</div>
				<div class="pro_con_L4 pn_L">
					<div class="sk_data_L4 dr_L" >分類</div>
					<div class="sk_data_L4 dr1_L" >分類</div>
				</div>
				<div class="pro_con_L5 pn_L">
					<div class="sk_data_L5 dr_L" >銷售品名</div>
					<div class="sk_data_L5 dr1_L" >
						
					</div>
				</div>
				<div class="pro_con_L6 pn_L">
					<div class="sk_data_L6 dr_L" >廠內品名</div>
					<div class="sk_data_L6 dr1_L" >廠內品名</div>
				</div>
				<div class="pro_con_L7 pn_L">
					<div class="sk_data_L7 dr_L" >料號</div>
					<div class="sk_data_L7 dr1_L" >料號</div>
				</div>
				<div class="pro_con_L8 pn_L">
					<div class="sk_data_L8 dr_L" >官網頁面</div>
					<div class="sk_data_L8 dr1_L" >官網頁面</div>
				</div>
				<div class="pro_con_L9 pn_L">
					<div class="sk_data_L9 dr_L" >售價與成本</div>
					<div class="sk_data_L9 dr1_L" >售價與成本</div>
				</div>
				<div class="pro_con_L10 pn_L">
					<div class="sk_data_L10 dr_L" >庫存</div>
					<div class="sk_data_L10 dr1_L" >庫存</div>
				</div>
				<div class="pro_con_L11 pn_L">
					<div class="sk_data_L11 dr_L" >銷售頁面範本</div>
					<div class="sk_data_L11 dr1_L" >銷售頁面範本</div>
				</div>
			</div>
				

	
		</div>
	</div>
	
	
	
  </body>
</html>