<?php
require_once '../functions/MyFunctions.php';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
  <title>PChome24H上架產品介紹範本</title>
  <!--<link rel="stylesheet" href="CSS/lyweb.css">-->
  <!--<script src="JS/jquery-1.12.1.min.js"></script>-->
</head>
<body>
	<div class="pic" style="margin-bottom: 4px;">
	圖片名稱: <?=$Model?>_x700.jpg 或 <?=$Model?>_x700.png <br>
	圖片位置: <span id="pic1">\\Nasbefd94\公司專區\共用中心\銘鵬產品圖檔\<?=$Model?></span>
	<button id="btn_pro_maker_content" class="btn" data-clipboard-target="#pic1">
		複製圖片位置
		</button>
	</div>
	
	<span style="margin: 0 0 0 10px;"><b>區塊1</b></span>
	<button id="btn_pro_maker_content" class="btn" data-clipboard-target="#main1">
	複製
	</button>
	<div id="main1" style="margin: 10px;margin-top:0;border: 2px solid Black;">
		
		<div style="margin: 0px; padding: 0px; border: 0px; font-size: 28px; vertical-align: baseline; color: #800080;  line-height: 28px; text-align: center;">
			<strong><span><?php echo $name_for_sell ?></span></strong></div>
		<hr />
		<p>
			<span style="margin: 0px; padding: 0px; border: 0px; font-size: 20px; vertical-align: baseline; color: #000001;">
			<?php echo nl2br($description) ?>
			</span></p>
		<hr />
	</div>
	<hr>


<?php

$RemoteFile = "http://assets.pct-max.com.tw/".$Model."/".$Model;
if(checkRemoteFile($RemoteFile."_device_overview.jpg")){
		 
	echo "有_device_overview";
	


?>

	
	<div class="pic" style="margin-bottom: 4px;">
	圖片名稱: <?=$Model?>_device_overview.jpg 介面一覽圖(可選，如果有的話) <br>
	圖片位置: <span id="pic2">\\Nasbefd94\公司專區\共用中心\銘鵬產品圖檔\<?=$Model?></span>
	<button id="btn_pro_maker_content" class="btn" data-clipboard-target="#pic2">
		複製圖片位置
		</button>
	</div>	

<?php

}else{
	// echo "沒有_device_overview";
}

?>
	

	<span style="margin: 0 0 0 10px;"><b>區塊2</b></span>
	<button id="btn_pro_maker_content" class="btn" data-clipboard-target="#main2" style="margin-left: 10px;">
	複製
	</button>
	<div id="main2" style="margin: 10px;margin-top:0;border: 2px solid Black;">
		<p>
			<strong style="font-size: 20px; line-height: 21px; color: rgb(0, 0, 128);">產品特色：</strong>
			<div style="font-size: 16px; color: #000001;">
			<?php echo nl2br($features) ?>
			</div>
		</p>
		<hr />
	</div>
	<hr>

<?php

$RemoteFile = "http://assets.pct-max.com.tw/".$Model."/".$Model;
if(checkRemoteFile($RemoteFile."_應用.png") 
	|| checkRemoteFile($RemoteFile."_應用圖.png")
	 || checkRemoteFile($RemoteFile."_應用圖.jpg")){
		 
	// echo "有應用圖";	
?>
	<span style="margin: 0 0 0 10px;"><b>區塊3</b></span>
	<button id="btn_pro_maker_content" class="btn" data-clipboard-target="#main3" style="margin-left: 10px;">
	複製
	</button>
	<div id="main3" style="margin: 10px;margin-top:0;border: 2px solid Black;">
			<p><strong style="font-size: 20px; line-height: 21px; color: rgb(0, 0, 128);">產品應用：</strong>
					
				</p>
				<hr />
	</div>
	<hr>
	
	<div class="pic" style="margin-bottom: 4px;">
	圖片名稱: <?=$Model?>_應用圖.jpg 產品應用圖 <br>
	圖片位置: <span id="pic4">\\Nasbefd94\公司專區\共用中心\銘鵬產品圖檔\<?=$Model?></span>
	<button id="btn_pro_maker_content" class="btn" data-clipboard-target="#pic4">
		複製圖片位置
		</button>
	</div>	
<?php

}else{
	// echo "沒有應用圖";
?>	
	<span style="margin: 0 0 0 10px;"><b>區塊3</b></span>
			沒有產品應用
	<hr>
	
	
<?php	
}

?>
	
	
	
	<span style="margin: 0 0 0 10px;"><b>區塊4</b></span>
	<button id="btn_pro_maker_content" class="btn" data-clipboard-target="#main4" style="margin-left: 10px;">
	複製
	</button>
	<div id="main4" style="margin: 10px;margin-top:0;border: 2px solid Black;">
		<p>
			<strong style="color: rgb(0, 0, 128);  font-size: 20px; line-height: 21px; margin: 0px; padding: 0px; border: 0px; vertical-align: baseline;">我們所提供為全新產品，並提供以下保證：</strong></p>
		<ul style="font-size: 16px;margin-left:2em; color: #000001;">
			<li>
				保固期限：<?php echo $warranty ?></li>
			<li>
				保固範圍：產品故障</li>
		</ul>
		<hr />
		<p style="font-size: 16px;line-height: 21px;color: rgb(162, 161, 166);">
			<span style="color: rgb(191, 20, 3);">注意事項：</span><br />
			<div style="margin-left:2em;font-size:16px; color: #000001;">
						▼ 產品顏色因個人之顯示器不同而有所差異，將以實際商品顏色為主<br />
						▼ 產品規格若敘述有誤，請以實品/原廠公告為準<br />
						▼ 使用前請確實遵從產品說明書內之操作指示及注意事項<br />
						<br />
			</div>
		</p>
	</div>
	<hr>
	
	<div class="pic" style="margin-bottom: 4px;">
	圖片名稱: 關於PCT服務項目_中文_方形x700(R)2019.05.27.png <br>
	圖片位置: <span id="pic5">\\Nasbefd94\公司專區\銘鵬企劃部\美工原始檔案\銘鵬PCT品牌介紹\關於 PCT 服務項目\</span>
	<button id="btn_pro_maker_content" class="btn" data-clipboard-target="#pic5">
		複製圖片位置
		</button>
	</div>	
	<span style="margin: 0 0 0 10px;"><b>區塊5</b></span>
	<button id="btn_pro_maker_content" class="btn" data-clipboard-target="#main5" style="margin-left: 10px;">
	複製
	</button>
	<div id="main5" style="margin: 10px;margin-top:0;border: 2px solid Black;">
		
		<div style="text-align: center;font-size: 14px;line-height: 21px; color: rgb(162, 161, 166);text-align: -webkit-center;">
				----------------------------------------------------------------------------------------------<br />
				PCT 領導品牌　多電腦切換器　影音／訊號分配器　延伸器　轉換器　KVM-Over-IP　3C電腦週邊　整合方案</span>
	  </div>
	</div>
	
</body>
</html>