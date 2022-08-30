<?php

require_once '../functions/MyPDO.php';
header('Content-Type:text/html;charset=utf8');
	
	set_time_limit(100);
	$Model = $_POST["Model"];
	
	$pdo = new MyPDO;
	$sql = "INSERT INTO PCT.dbo.Data_Prod_Reference (Model) VALUES (:Model)";
	$query = $pdo->bindQuery($sql, [
		':Model' => $Model
	]);
	
	echo " ".$Model." 插入成功！重新查詢即可使用";

	$query=null;

?>

