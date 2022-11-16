<?php

require_once '../functions/MyPDO.php';
header('Content-Type:text/html;charset=utf8');
	
	set_time_limit(100);
	$IDs = $_POST["ID"];
	$IDs_implode = implode(",",$IDs);
	$Models = $_POST["Model"];
	$Models_implode = implode(",",$Models);
	
	$pdo = new MyPDO;
	$sql_delete = "DELETE FROM PCT.dbo.Data_Prod_Reference WHERE ID = :ID";
	
	$row_count = 0;
	foreach($IDs as $ID){
		$query = $pdo->bindQuery($sql_delete, [
			':ID' => $ID
		]);
		$row_count += $pdo->GetStmtStat();
	}
	
	if($row_count){
		echo "已刪除以下項目<br>".$Models_implode;
	}else{
		echo "刪除失敗";	
	}
	$query=null;	

?>

