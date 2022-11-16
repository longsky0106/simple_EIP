<?php

require_once '../functions/MyPDO.php';
header('Content-Type:text/html;charset=utf8');
	
	set_time_limit(100);
	$spec_item_name = $_POST["spec_item_name"];
	$spec_item_no = $_POST["spec_item_no"];
	$spec_item_lang = $_POST["spec_item_lang"];

	
	
	//$shop_menu1_id="1";
	$pdo = new MyPDO;
	
	if($spec_item_lang == "both"){
		$sql = "SELECT [spec_item_example".$spec_item_no."_tw]
				,[spec_item_example".$spec_item_no."_en]
			  FROM [PCT].[dbo].[Menu_Spec_Item]
			  WHERE spec_item_name_form = :spec_item_name";
	}else{
		$sql = "SELECT [spec_item_example".$spec_item_no."_".$spec_item_lang."]
			  FROM [PCT].[dbo].[Menu_Spec_Item]
			  WHERE spec_item_name_form = :spec_item_name";
	}
	
	$query = $pdo->bindQuery($sql, [
		':spec_item_name' => $spec_item_name
	]);		
	$row_count = count($query);
	if($row_count){
		foreach($query as $row){
			if($spec_item_lang == "both"){
				echo $row['spec_item_example'.$spec_item_no.'_tw']."|".$row['spec_item_example'.$spec_item_no.'_en'];
			}else{
				echo $row['spec_item_example'.$spec_item_no.'_'.$spec_item_lang.''];
			}

		}
	}
	
	
	$query=null;
?>	
