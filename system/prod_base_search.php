<?php

require_once '../functions/MyPDO.php';
header('Content-Type:text/html;charset=utf8');
	
	set_time_limit(100);
	$Model = $_POST["Model"];
	
	$pdo = new MyPDO;
	$sql_search = "SELECT Model FROM PCT.dbo.Data_Prod_Reference WHERE Model = :Model";
	
	$query = $pdo->bindQuery($sql_search, [
		':Model' => $Model
	]);

	$row_count = count($query);
	
	if($row_count){
		echo " ".$Model." 已經存在，無法重複建立！";
		$query=null;
	}



?>

