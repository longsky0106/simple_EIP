<!doctype html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
		<title>銘鵬行銷資料PDF產生器: <?=$Model?></title>
		<link rel="stylesheet" href="../CSS/DM - template1.css">
		<link rel="stylesheet" href="../CSS/DM - print.css">
	</head>
	<body>
		<div class="page_content">
			<img id="logo" alt="PCT logo" src="http://assets.pct-max.com.tw/PCT-logo(x700).png"/>
			<div class="model_name"><?=$Model?></div>
			<div class="prod_name"><?=$name_for_sell_en?></div>
			<hr>
			<div class="description_content">
				<div class="prod_main">
				<?php
					$filename = $Model;
					$path = 'http://assets.pct-max.com.tw/' . $Model . '/'; // 產品圖片路徑
					
					$array_img_type = [
						".jpg",
						".png",
						".gif"
					];
				
					$array_img_name = [
						"(no logo)",
						"_ps"
					];

					$stoploop = false;
					for($i = 0; $i<count($array_img_name) && !$stoploop; $i++){
						for($j = 0; $j<count($array_img_type); $j++){
							$img_path_filename = $path.$filename.str_replace(' ', '%20', $array_img_name[$i]).$array_img_type[$j];
							// echo $img_path_filename."<br>";
							if (@getimagesize($img_path_filename)) {
								$img_result = '<img src="'.$img_path_filename.'" class="img-responsive" />';
								$stoploop = true;
								break;
							}
							$img_result = "No pictures";
						}
						// if($stoploop){
							// break;
						// }
					}
					echo $img_result;
				?>
					<!--<img alt="產品主圖" src="http://assets.pct-max.com.tw/PK184C-60/PK184C-60_ps.png"/>-->
				</div>
				<div class="key_feature">
					<div class="feature_title">Key Feature:</div>
					<?=nl2br($SK_features_en)?>
				</div>
			</div>
			<div class="detail_content">
<?php
			
			$pdf_name_path = $path.$Model.'_device_overview.pdf';
			// echo $pdf_name_path;
			$file_headers = @get_headers($pdf_name_path);

			if($file_headers[0] == 'HTTP/1.0 404 Not Found' || $file_headers[0] == 'HTTP/1.1 404 Not Found'){
				  echo "The file does not exist: ".$Model.'_device_overview.pdf';
			} else if ($file_headers[0] == 'HTTP/1.0 302 Found' && $file_headers[7] == 'HTTP/1.0 404 Not Found'){
				echo "The file does not exist, and I got redirected to a custom 404 page..";
			} else {
?>
				<div class="device_overview">
					<h2>Device Overview:</h2>
					<hr>
<?php
					// 插入PDF
					$pdf_name = 'file_get_temp.pdf';

					// 下載暫存PDF到$pdf_name
					file_put_contents(
						$pdf_name,
						file_get_contents($pdf_name_path)
					);

					// 設定來源PDF檔案與頁數
					$pagecount = $mpdf->SetSourceFile($pdf_name);

					// 匯入樣板
					$tplId = $mpdf->importPage($pagecount);
					$x = 65; // 插入位置 (X座標)
					$y = 230; // 插入位置 (Y座標)
					$width = 195; // 指定插入PDF的寬度
					$height = 195; // 指定插入PDF的高度
					$mpdf->useTemplate($tplId, $x, $y, $width, $height);

					// 刪除暫存PDF
					If(unlink($pdf_name)){
					  // echo 'file was successfully deleted';
					}else{
					  // echo 'there was a problem deleting the file';
					}
?>
				</div>
<?php
			}
			
?>
			</div>
		</div>
	</body>
</html>