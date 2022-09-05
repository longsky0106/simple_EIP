<?php

$dbname = "XMLY5000";
	
// 檢查是否勾選使用臨時資料庫
$check_pct_sql_temp = $_POST["check_pct_sql_temp"];

// 凌越系統資料庫.貨品資料表
$ly_sql_db_table_std = $dbname.".dbo.SSTOCK";

// PCT資料庫.貨品資料表
$ly_sql_db_table_temp = "PCT.dbo.SSTOCK_temp";

// 凌越系統資料庫.貨品明細資料
$ly_sql_db_table_FD_std = $dbname.".dbo.SSTOCKFD";

// PCT資料庫.貨品明細資料
$ly_sql_db_table_FD_temp = "PCT.dbo.SSTOCKFD_temp";

// 目前程式使用的貨品資料表
$ly_sql_db_table = $ly_sql_db_table_std;

// 目前程式使用的貨品明細資料
$ly_sql_db_table_FD = $ly_sql_db_table_FD_std;

// 資料顯示備註
$sql_db_note = '';

// 如果勾選了使用臨時資料庫
if($check_pct_sql_temp == 'true'){
	
	// 將程式使用的資料庫設為PCT資料庫
	$ly_sql_db_table = $ly_sql_db_table_temp;
	$ly_sql_db_table_FD = $ly_sql_db_table_FD_temp;
	
	// 設定資料顯示備註內容
	$sql_db_note = '<span style="color:red;"><b>※臨時資料庫</b></span>';
}else{
	
	// 將程式使用的資料庫設為凌越系統資料庫
	$ly_sql_db_table = $ly_sql_db_table_std;
	$ly_sql_db_table_FD = $ly_sql_db_table_FD_std;
	
	// 清空資料顯示備註內容
	$sql_db_note = '';
}

	
?>