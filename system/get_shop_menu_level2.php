<?php

require_once '../functions/MyPDO.php';
header('Content-Type:text/html;charset=utf8');
	
	set_time_limit(100);
	$shop_menu1_id = $_POST["shop_menu1_id"];
	
	//$shop_menu1_id="1";
	$pdo = new MyPDO;
	
	$sql = "SELECT 
			shop_menu1_id,
			shop_menu1_name,
			shop_menu1_rem,
			shop_menu2_id,
			shop_menu2_name,
			prod_class_id,
			prod_class_name
			,prod_class_rem
			FROM [PCT].[dbo].[Menu_Prod_Type_shop]
			LEFT JOIN [PCT].[dbo].[Menu_Prod_Class_shop] on shop_menu1_id = shop_menu1_index
			LEFT JOIN [PCT].[dbo].[Menu_Prod_Class] on spec_menu_class_index = prod_class_id
			WHERE [shop_menu1_id] =:shop_menu1_id";
	$query = $pdo->bindQuery($sql, [
		':shop_menu1_id' => $shop_menu1_id
	]);		
	$row_count = count($query);
	if($row_count){
?>		
		<option value="0">選擇產品類別</option>
<?php
		foreach($query as $row){
			if($row['shop_menu2_id']!=NULL){
?>	
						<option value="<?=$row['shop_menu2_id']?>"><?=$row['shop_menu2_name']?></option>
<?php	
			}else{
?>
				<option value="NULL">沒有對應選項</option>
<?php
			}
		}
	}
?>	
