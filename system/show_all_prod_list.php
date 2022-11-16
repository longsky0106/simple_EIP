<?php
require_once '../vendor/autoload.php';
require_once('../functions/MyPDO.php');
require_once '../functions/MyFunctions.php';
require_once '../system/MyConfig.php';
header('Content-Type:text/html;charset=utf8');
	
	$root_path = $_SERVER['HTTP_REFERER'];
	if(!$root_path){
		$root_path = "";
	}else{
		$root_path = "http://192.168.1.56/PHPtoPDF/system/";
	}

	$FasterImage_client = new \FasterImage\FasterImage();
	
	$pct_web_site_local_url = "http://192.168.10.111";
	$RemoteFile1 = "http://assets.pct-max.com.tw/PK1640-C/PK1640-C_F_R.png";
	
	set_time_limit(30);
	$search_text = strip_tags($_GET["data"]);
	$limit = (int)strip_tags($_GET["limit"]);
	$page = (int)strip_tags($_GET["page"]);
	if(!(isset($limit) && $limit >= 10 && is_int($limit))){
		$limit = 10;
	}
	if(!(isset($page)&&$page>=1&&is_int($page))){
		$page = 1;
	}
	
	$OFFSET = ($page - 1) * $limit;
	$pdo = new MyPDO;
	// ini_set( 'display_errors', 1 );

	$sql_pct_count = "SELECT
					ID
					,[Model]
					,'SK_USE' = case when SSTOCK.SK_USE is NUll then temp_SSTOCK.SK_USE 
								 else SSTOCK.SK_USE 
								 end
					,'SK_LOCATE' = case when SSTOCK.SK_LOCATE is NUll then temp_SSTOCK.SK_LOCATE 
								 else SSTOCK.SK_LOCATE 
								 end
					,'fd_name' = case when SSTOCKFD.fd_name is NUll then temp_STOCKFD.fd_name 
								 else SSTOCKFD.fd_name 
								 end
					,SSTOCK.SK_NAME
					,[SK_NO1]
					,[SK_NO2]
					,[SK_NO3]
					,[SK_NO4]
					,[Price]
					,[Suggested Price]
					,[Cost Price]
					,SPH_NowQtyByWare
					FROM (
						SELECT *
						FROM [PCT].[dbo].[Data_Prod_Reference]
					) as PCT
					LEFT JOIN XMLY5000.dbo.SSTOCK on PCT.SK_NO1 = XMLY5000.dbo.SSTOCK.SK_NO collate chinese_taiwan_stroke_ci_as
					LEFT JOIN PCT.dbo.SSTOCK_temp as temp_SSTOCK on PCT.SK_NO4 = temp_SSTOCK.SK_NO collate chinese_taiwan_stroke_ci_as
					LEFT JOIN XMLY5000.dbo.SSTOCKFD on PCT.SK_NO1 = XMLY5000.dbo.SSTOCKFD.fd_skno collate chinese_taiwan_stroke_ci_as
					LEFT JOIN PCT.dbo.SSTOCKFD_temp as temp_STOCKFD on PCT.SK_NO4 = temp_STOCKFD.fd_skno collate chinese_taiwan_stroke_ci_as
					LEFT JOIN (
						SELECT
						*
						FROM XMLY5000.DBO.View_SPHNowQtyByWare
						WHERE WD_WARE = 'A'
					)QTY  on PCT.SK_NO1 = QTY.WD_SKNO collate chinese_taiwan_stroke_ci_as
					WHERE Model+SSTOCK.SK_USE+SSTOCK.SK_LOCATE+SSTOCKFD.fd_name+temp_STOCKFD.fd_name+SK_NO1+SK_NO2+SK_NO3+SK_NO4 LIKE :search_text1 collate chinese_taiwan_stroke_ci_as
					OR Model+SK_NO1+SK_NO2+SK_NO3+SK_NO4 LIKE :search_text2 collate chinese_taiwan_stroke_ci_as
					ORDER BY Model";
	$bindArray = [
		':search_text1' => '%'.$search_text.'%'
		,':search_text2' => '%'.$search_text.'%'
	];
	$query_all = $pdo->bindQuery($sql_pct_count, $bindArray);
	$row_count_all = count($query_all);
	$per_page_count = $row_count_all/$limit+1;
	if($OFFSET>$row_count_all){
		$page = floor($per_page_count);
		$OFFSET = ($page - 1) * $limit;
	}
	
	//基本資料 - 在[PCT].[dbo].[Data_Prod_Reference]從型號找對應料號
	$sql_pct = $sql_pct_count."
				OFFSET ".$OFFSET." ROWS
				FETCH NEXT ".$limit." ROWS ONLY";
	$query = $pdo->bindQuery($sql_pct, $bindArray);
	$row_count = count($query);
?>
<!doctype html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title><?=$Company_name."簡易EIP"?></title>
    <link rel="stylesheet" href="<?=$root_path?>../CSS/prod_list.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script> -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>
    <!-- <script src="<?=$root_path?>../JS/index.js"></script> -->
    <script src="<?=$root_path?>../JS/MyFunction.js"></script>
    <script src="<?=$root_path?>../JS/prod_list_page.js"></script>
  </head>
  <body>	
	
<div id="search_bar_L">
	   <input type="text" placeholder="請輸入產品型號" name="model">
	   <button id="search_btn" type="" name="" value="">搜尋</button>
		每頁顯示數量
		<select id="display_per_page" name="display_per_page">
			<option value="10">10</option>
			<option value="20">20</option>
			<option value="50">50</option>
			<option value="100">100</option>
		 </select>
		 <?php
			if($row_count){
		?>		
			<div id="pagejump">	
		<?php	
				for($i=1;$i<=$per_page_count;$i++){
					if($i==$page){
		?>				
						[&thinsp;<?=$i?>&thinsp;]
		<?php				
					}else{
						
					
		?>
				<a href="javascript:load_page(<?=$i.",'".$search_text."'"?>)">[&thinsp;<?=$i?>&thinsp;]</a>
		<?php	
					}
				}
		?>		
			</div>
		<?php		
			}
		 ?>
		
 </div>
 <!-- <div id="page_load_status"></div> -->
  <hr>
	<div id="main_content_L">
		<div class="data_room_L">
			<div id="positioned"><div id="page_load_status"><div id="load_status_text">載入中...</div></div></div>
			<div id="copy_statu_L">複製</div>
			<div class="data_room_con0_L"><!-- pro_con_L: 欄，dr: 列 -->      
				<button id="create_btn" type="" name="" value="" onClick="btn_create_prod()">新增一筆資料</button>
				<!-- <button type="" name="" value="">更新所選資料</button> -->
				<button id="delete_btn" type="" name="" value="" onClick="btn_delete_prod()" disabled>刪除所選資料</button>
			</div>
			<div class="data_room_con1_L"><!-- pro_con_L: 欄，dr: 列 -->
				<div class="group1_title">
				<div class="pro_con_L0 dr0_L" >編號</div>
				<div class="pro_con_L1 dr0_L" >選擇</div>
				</div>
				<div class="group2_title">
				<div class="pro_con_L2 dr0_L" >圖片</div>
				<div class="pro_con_L3 dr0_L" >型號 / 料號</div>
				<div class="pro_con_L4 dr0_L" >產品分類</div>
				<div class="pro_con_L5 dr0_L" >品名</div>
				<div class="pro_con_L8 dr0_L" >官網頁面</div>
				<div class="pro_con_L9 dr0_L" >售價與成本</div>
				<div class="pro_con_L10 dr0_L" >庫存</div>
				<div class="pro_con_L11 dr0_L" >銷售頁面範本</div>
				</div>
			</div>
			

	
<?php	
	if($row_count){
		$i = 1;
		$i = ($page-1) * $limit + $i;
		foreach($query as $row){
			$Data_Prod_Ref_ID = $row['ID'];
			$Model = $row['Model'];
			$Model = str_replace('&','＆',$Model);
			$Category = $row['SK_USE']?$row['SK_USE'].">".$row['SK_LOCATE']:"";
			$prod_sales_name = $row['fd_name'];
			$SK_NAME = $row['SK_NAME'];			
			$SK_NO1 = $row['SK_NO1'];
			$SK_NO2 = $row['SK_NO2'];
			$SK_NO3 = $row['SK_NO3'];
			$SK_NO4 = $row['SK_NO4'];
			$Price = round($row['Price']);
			$Suggested_Price = round($row['Suggested Price']);
			$Cost_Price = round($row['Cost Price']);
			$QTY = round($row['SPH_NowQtyByWare']);
			
			$prod_model = $row['Model'];
					
			$filename = $prod_model;
			$path = 'http://assets.pct-max.com.tw/' . $prod_model . '/'; // 產品圖片路徑

			// 副檔名
			$array_img_type = [
				".jpg",
				".png",
				".gif"
			];
			
			// 型號後面檔名
			/* $array_img_name = [
				"(no logo)",
				"_ps",
				"_no logo",
				"_x700"
			]; */
			$array_img_name = [
				"_x120"
			];
			if(checkRemoteFile($RemoteFile1)){
				$stoploop = false;
				for($k = 0; $k<count($array_img_name) && !$stoploop; $k++){
					for($j = 0; $j<count($array_img_type); $j++){
						$img_path_filename = $path.$filename.str_replace(' ', '%20', $array_img_name[$k]).$array_img_type[$j];
						// $imageSize = @getimagesize($img_path_filename);
						
						$images = $FasterImage_client->batch([
							$img_path_filename
						]);
						
						foreach ($images as $image) {
							list($width,$height) = $image['size'];
						}

						if ($width) {
							// list($width, $height) = getimagesize($img_path_filename);

							if($width>=$height){
								$img_style = "width: 90%;height: auto;";
							}else{
								$img_style = "width: auto;height: 90%;";
							}
							$img_result = '<img src="'.$img_path_filename.'" style="'.$img_style.'" class="img-responsive" />';
							// $img_result = "載入中...";
							$stoploop = true;
							break;
						} 
						$img_result = '<div class="no_pic_hold">暫無圖片</div>';
					}
				}
			}else{
				$img_result = "無法取得連線";
			}
			
?>			
			<div class="data_room_con2_L">
				<div class="group1">
					<div class="pro_con_L0 pn_L">
						 <div class="sk_data_L0 dr_L" >編號</div>
						 <div class="sk_data_L0 dr1_L" ><?=$i?></div>
					</div>
					<div class="pro_con_L1 pn_L">
						<div class="sk_data_L1 dr_L" >選擇</div>
						<div class="sk_data_L1 dr1_L" id="list_no<?=$i?>" ><input type="checkbox" name="Data_Prod_Ref_ID[]" value="<?=$Data_Prod_Ref_ID?>" autocomplete="off"></div>
					</div>
				</div>
				<div class="group2">
					<div class="pro_con_L2 pn_L">
						<div class="sk_data_L2 dr_L" >圖片</div>
						<div class="sk_data_L2 dr1_L" ><?=$img_result?></div>
					</div>
					<div class="pro_con_L3 pn_L">
						<div class="sk_data_L3 dr_L" >型號/料號</div>
						<div class="sk_data_L3 dr1_L" >
							<div id="<?=$Data_Prod_Ref_ID?>"><a href="javascript:prod_data_edit('<?=$Model?>');"><?=$Model?></a></div>
							<div><?=$SK_NO1?></div>
						</div>
					</div>
					<div class="pro_con_L4 pn_L">
						<div class="sk_data_L4 dr_L" >分類</div>
						<div class="sk_data_L4 dr1_L" ><?=$Category?></div>
					</div>
					<div class="pro_con_L5 pn_L">
						<div class="sk_data_L5 dr_L" >品名</div>
						<div class="sk_data_L5 dr1_L" >
							<div><?=$prod_sales_name?></div><hr>
							<div><?=$SK_NAME?"廠內: ".$SK_NAME:""?></div>
						</div>
					</div>
					<div class="pro_con_L8 pn_L">
						<div class="sk_data_L8 dr_L" >官網頁面</div>
						<div class="sk_data_L8 dr1_L" >
						
						</div>
					</div>
					<div class="pro_con_L9 pn_L">
						<div class="sk_data_L9 dr_L" >售價與成本</div>
						<div class="sk_data_L9 dr1_L" >
							<div>售價: <div class="price"><?=$Price?></div></div>
							<div>建議售價: <div class="price"><?=$Suggested_Price?></div></div>
							<div>成本: <div class="price"><?=$Cost_Price?></div></div>
						</div>
					</div>
					<div class="pro_con_L10 pn_L">
						<div class="sk_data_L10 dr_L" >庫存</div>
						<div class="sk_data_L10 dr1_L" ><?=$QTY?></div>
					</div>
					<div class="pro_con_L11 pn_L">
						<div class="sk_data_L11 dr_L" >銷售頁面範本</div>
						<div class="sk_data_L11 dr1_L" >
						
						</div>
					</div>
				</div>
			</div>
			
<?php			
			$i++;
		}
		$i = 0;
		$query_all = null;
		$query = null;

	}
	$search_text = "";
?>
			
			
				

	
		</div>
	</div>
	
	
	
  </body>
</html>