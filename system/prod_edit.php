<?php

require_once '../functions/MyPDO.php';
require_once '../system/MyConfig.php';
header('Content-Type:text/html;charset=utf8');
	
	set_time_limit(100);
	$SK_NO = $_POST["SK_NO"];
	//$SK_NO="PPUCSR048";
	$pdo = new MyPDO;

	
	$sql = "SELECT SK_NO, SK_NAME, SK_USE, SK_LOCATE, SK_NOWQTY, SK_SPEC, SK_UNIT, SK_COLOR, SK_SIZE, SK_SESPES, SK_ESPES, SK_REM, SK_SMNETS, BD_DSKNO, SK_FLD6, BD_DSKNM
			FROM (
				SELECT DISTINCT SK_NO, SK_NAME, SK_USE, SK_LOCATE, SK_NOWQTY, CONVERT(NVARCHAR(MAX),SK_SPEC) AS 'SK_SPEC', SK_UNIT, SK_COLOR, SK_SIZE, SK_SESPES, CONVERT(VARCHAR(MAX),SK_ESPES) AS 'SK_ESPES', CONVERT(NVARCHAR(MAX),SK_REM) AS 'SK_REM', CONVERT(NVARCHAR(MAX),SK_SMNETS) AS 'SK_SMNETS', BD_DSKNO, SK_FLD6, BD_DSKNM
				, ROW_NUMBER ( ) OVER ( PARTITION BY SK_NO order by SK_NO DESC) as rn
				FROM ".$ly_sql_db_table."
				LEFT JOIN ".$dbname.".dbo.BOMDT on ".$ly_sql_db_table.".SK_NO = ".$dbname.".dbo.BOMDT.BD_USKNO
			) AS SKM
			WHERE SK_NO =:SK_NO
			AND rn=1
			Order by SK_NO";
	$query = $pdo->bindQuery($sql, [
		':SK_NO' => $SK_NO
	]);		
	$row_count = count($query);
	if($row_count){
		foreach($query as $row){
			if($row['SK_USE'])
				echo "目前產品分類: ".$row['SK_USE']." > ".$row['SK_LOCATE']."</br>";
			else
				echo "目前產品分類: 未填寫</br>";
		}
	}
	//echo $SK_SPEC
	$query=null;

?>

修改產品分類: 	
<select id="categories" name="categories">
	<option value="0">選擇產品系列</option>
<?php 
		$shop_menu1_id="0";
		$sql =	"SELECT
				[shop_menu1_id]
				,[shop_menu1_name]
				,[shop_menu1_rem]
				FROM [PCT].[dbo].[Menu_Prod_Type_shop]
				WHERE shop_menu1_id !=:shop_menu1_id";
		$query = $pdo->bindQuery($sql, [
			':shop_menu1_id' => $shop_menu1_id
		]);		
		$row_count = count($query);
		if($row_count){
			foreach($query as $row){
?>	
				<option value="<?=$row['shop_menu1_id']?>"><?=$row['shop_menu1_name']?></option>
<?php		
			}
		}
		$query=null;
?>	
</select>
<!-- 以下為產品大類別所對應小類別 -->
<select id="ProdType" name="ProdType">
	<option value="0">選擇產品類別</option>
</select><br />
