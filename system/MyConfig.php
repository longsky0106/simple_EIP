<?php

$dbname1 = "XMLY5000";
$dbname2 = "PCT";
	
// 檢查是否勾選使用臨時資料庫
$check_pct_sql_temp = $_POST["check_pct_sql_temp"];

// 資料庫1.貨品資料表
$ly_sql_db_table_std = $dbname1.".dbo.SSTOCK";

// 資料庫2.貨品資料表
$ly_sql_db_table_temp = $dbname2.".dbo.SSTOCK_temp";

// 資料庫1.貨品明細資料
$ly_sql_db_table_FD_std = $dbname1.".dbo.SSTOCKFD";

// 資料庫2.貨品明細資料
$ly_sql_db_table_FD_temp = $dbname2.".dbo.SSTOCKFD_temp";

// 目前程式使用的貨品資料表
$ly_sql_db_table = $ly_sql_db_table_std;

// 目前程式使用的貨品明細資料
$ly_sql_db_table_FD = $ly_sql_db_table_FD_std;

// 資料顯示備註
$sql_db_note = '';

// 如果勾選了使用臨時資料庫
if($check_pct_sql_temp == 'true'){
	
	// 將程式使用的資料庫設為資料庫2
	$ly_sql_db_table = $ly_sql_db_table_temp;
	$ly_sql_db_table_FD = $ly_sql_db_table_FD_temp;
	
	// 設定資料顯示備註內容
	$sql_db_note = '<span style="color:red;"><b>※臨時資料庫</b></span>';
}else{
	
	// 將程式使用的資料庫設為資料庫1
	$ly_sql_db_table = $ly_sql_db_table_std;
	$ly_sql_db_table_FD = $ly_sql_db_table_FD_std;
	
	// 清空資料顯示備註內容
	$sql_db_note = '';
}

	
?>