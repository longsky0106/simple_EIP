<?php

require_once '../functions/MyPDO.php';
require_once '../functions/MyFunctions.php';
require_once '../system/MyConfig.php';
header('Content-Type:text/html;charset=utf8');

	usleep(100 * 1000);
	set_time_limit(100);
	
	$Model = strip_tags($_POST['Model']);
	$SK_NO1 = strip_tags($_POST['SK_NO1']);
	$SK_NO2 = strip_tags($_POST['SK_NO2']);
	$SK_NO3 = strip_tags($_POST['SK_NO3']);
	$SK_NO4 = strip_tags($_POST['SK_NO4']);
	$base_Price = strip_tags($_POST['base_Price']);
	$base_Suggested_Price = strip_tags($_POST['base_Suggested_Price']);
	$base_Cost_Price = strip_tags($_POST['base_Cost_Price']);
	
	$pdo = new MyPDO;
	
	// 更新售價
	$sql_base_data =   "UPDATE 
						PCT.dbo.Data_Prod_Reference 
						SET
						SK_NO1 = :SK_NO1
						,SK_NO2 = :SK_NO2
						,SK_NO3 = :SK_NO3
						,SK_NO4 = :SK_NO4
						,Price = :base_Price
						,[Suggested Price] = :base_Suggested_Price
						, [Cost Price] = :base_Cost_Price
						WHERE Model =:Model";
					
	$query_update_base_data = $pdo->bindQuery($sql_base_data, [
		':SK_NO1' => $SK_NO1
		,':SK_NO2' => $SK_NO2
		,':SK_NO3' => $SK_NO3
		,':SK_NO4' => $SK_NO4
		,':base_Price' => $base_Price
		,':base_Suggested_Price' => $base_Suggested_Price
		,':base_Cost_Price' => $base_Cost_Price
		,':Model' => $Model
	]);
	
	if($pdo->GetStmtStat()){
		echo "<span style=\"color:blue;\"> (自建資料庫)基本資料更新成功!</span>";
	}else{
		echo "<span style=\"color:blue;\"> (自建資料庫)基本資料更新失敗!</span><br>";
		$pdo->error();
	}
	
	$query_update_base_data=null;

	// 更新輸出到CSV檔案
	// include 'output_to_file.php';

?>