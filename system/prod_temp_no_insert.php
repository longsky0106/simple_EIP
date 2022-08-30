<?php

require_once '../functions/MyPDO.php';
header('Content-Type:text/html;charset=utf8');
	
	set_time_limit(100);
	$Model = $_POST["Model"];
	$SK_NO_temp = $Model.'_temp';
	
	$pdo = new MyPDO;
	
	// 新增一筆臨時料號
	$sql = "INSERT INTO PCT.dbo.SSTOCK_temp (SK_NO) VALUES (:SK_NO)";
	$query = $pdo->bindQuery($sql, [
		':SK_NO' => $SK_NO_temp
	]);
	
	echo " ".$SK_NO_temp."新增成功！";
	
	// 更新料號4
	$sql_base_data =   "UPDATE 
						PCT.dbo.Data_Prod_Reference 
						SET
						SK_NO4 = :SK_NO4
						WHERE Model =:Model";
					
	$query_update_base_data = $pdo->bindQuery($sql_base_data, [
		':SK_NO4' => $SK_NO_temp
		,':Model' => $Model
	]);
	
	if($pdo->GetStmtStat()){
		echo "<span style=\"color:blue;\"> (自建資料庫)基本資料更新成功!</span>";
	}else{
		echo "<span style=\"color:blue;\"> (自建資料庫)基本資料更新失敗!</span><br>";
		$pdo->error();
	}

	$query=null;
	$query_update_base_data=null;

?>

