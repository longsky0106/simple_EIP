<?php

require_once '../functions/MyPDO.php';
header('Content-Type:text/html;charset=utf8');
	
	set_time_limit(100);
	$spec_item_name = $_POST["spec_item_name"];
	$spec_item_no = $_POST["spec_item_no"];
	$spec_item_lang = $_POST["spec_item_lang"];

	
	
	//$shop_menu1_id="1";
	$pdo = new MyPDO;
	
	$sql = "SELECT [spec_item_example1_tw]
			  FROM [PCT].[dbo].[Menu_Spec_Item]
			  WHERE spec_item_name_form = :spec_item_name";
	$query = $pdo->bindQuery($sql, [
		':spec_item_name' => $spec_item_name
	]);		
	$row_count = count($query);
	if($row_count){
		foreach($query as $row){
			if($row['spec_item_example1_tw']){
				echo $row['spec_item_example1_tw'];
			}else{
				echo "empty";
			}
			
		}
	}
	
	
	$query=null;
?>	
