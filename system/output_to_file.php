<?php
require_once '../functions/MyPDO.php';
header('Content-Type:text/html;charset=utf8');
/* header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="sample.csv"'); */

	
set_time_limit(100);

	

$BOM = "\xEF\xBB\xBF"; // UTF-8 BOM	
$user_CSV[0] = array('型號', '售價', '建議售價', '成本');

// 使用for 迴圈 i++ 遞增可以非常簡單遍歷資料庫內容
/* $user_CSV[1] = array('C01', 399, 450, 319);
$user_CSV[2] = array('UR311', 590, 750, 478);
$user_CSV[3] = array('HIX_Q12', 1200, 1500, 960);
$user_CSV[4] = array('TS202NE', 528, 650, 425); */

$pdo = new MyPDO;
	
	// 顯示所有連接埠介面
	$sql_pct = "SELECT [Model]
			  ,[Price]
			  ,[Suggested Price]
			  ,[Cost Price]
				FROM [PCT].[dbo].[Data_Prod_Reference]
				WHERE Model != :dump";
	$query = $pdo->bindQuery($sql_pct, [
		':dump' => ''
	]);			
	$row_count = count($query);
	if($row_count){
		echo "產生價格清單...";
		$i = 1;
		foreach($query as $row){
			
			// echo $row['Model'].", ".$row['Price'].", ".$row['Suggested Price'].", ".$row['Cost Price']."<br>";
			$user_CSV[$i] = array($row['Model'], $row['Price'], $row['Suggested Price'], $row['Cost Price']);
			$i++;
		}
		
	}
	$query=null;

$fileName = 'PCT_Prod_Shop_Price.csv';
// $filefolder = "D:\\www\\posi-test\\PHP_to_PDF\\output\\";
$filefolder = "\\\\NASBEFD94\\Auto_server\\DBSRV\\";
$filePath = $filefolder.$fileName;

//system('net use Y: \\NASBEFD94\Auto_server /user:lytwinmp lytwinmp')

// echo $filePath.'<br>';

if (is_dir($filefolder)){
	if ($dh = opendir($filePath)){
		print "able to access directory tree.";      
	}        
	
	if(file_exists($filePath)){
		unlink($filePath);
	}

	echo "更新價格CSV檔案...";
	// $fp = fopen('php://output', 'wb');
	$fp = fopen($filePath, 'w+');
	fwrite($fp, $BOM); // NEW LINE
	foreach ($user_CSV as $line) {
		// 雖然 CSV 代表“逗號分隔值” 
		// 在許多國家（包括法國），分隔符是“;”
		fputcsv($fp, $line, ',');
	}

	fwrite($fp, $userCSVData);
	fclose($fp);
	
	echo "更新完成!";
	
}
else{
	print "*無法存取檔案路徑";
}





?>