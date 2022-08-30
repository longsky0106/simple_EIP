<!doctype html>
<html lang="zh-tw">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
  <title>部落格介紹範本</title>
  <!--<link rel="stylesheet" href="CSS/lyweb.css">-->
  <!--<script src="JS/jquery-1.12.1.min.js"></script>-->
</head>
<body style="font-family:微軟正黑體,microsoft jhenghei;">
<p style="max-width:700px">
		
		<?php 
			echo "<img alt=\"".$prod_model."\" style=\"width: 100%; height: auto;\" src=\"https://www.kvm-all.com.tw/upload/images/".$img_final_name."\" /></p>";
		?>
<div id="main">
	
	<div style="margin: 0px; padding: 0px; border: 0px; font-size: 28px; vertical-align: baseline; color: #800080;  line-height: 28px; text-align: center;">
		<strong><span><?php echo $name_for_sell ?></span></strong></div>
	<hr />
	<p>
		<span style="margin: 0px; padding: 0px; border: 0px; font-size: 20px; vertical-align: baseline;">
		<?php echo $description ?>
		</span></p>
	<hr />
<!--<p style="max-width:700px">
		<img alt="產品第二張圖" style="width: 100%; height: auto;" src="pic/MHC414-2.jpg" /></p>
	<hr />-->
	<p>
		<strong style="font-size: 20px; line-height: 21px; color: rgb(0, 0, 128);">產品特色：</strong>
		<div style="font-size: 16px;">
		<?php echo nl2br($features) ?>
		</div>
	</p>
	<hr />
	
	
		<!--<p><strong style="font-size: 20px; line-height: 21px; color: rgb(0, 0, 128);">產品應用：</strong>
				<p style="max-width:700px">
					<img alt="應用圖" style="width: 100%; height: auto;" src="pic/MHC414-應用圖.png" /></p>
			</p>
			<hr />-->
	<p>
		<strong style="font-size: 20px; line-height: 21px; color: rgb(0, 0, 128);">產品規格：</strong>
		<div id="contentwrap" style="max-width: 700px;font-size: 16px;">
		<?php echo $SK_SPEC ?>
		</div>
	</p>
	<hr />
	<p>
		<strong style="color: rgb(0, 0, 128);  font-size: 20px; line-height: 21px;">產品包裝內容：</strong></p>
	<ul style="font-size:16px;margin-left:2em;">
		<?php
			echo $content_M;
			echo $cable_for_prod;
			echo $Manual_for_prod;
			echo $Driver_CD;
			echo $SWITCH_POWER;
			echo $CABLE;
			echo $IR_control;
			echo $USB_POWER_CABLE;
			echo $External_button_tw;
		?>
	</ul>
	<hr />
	<p>
		<strong style="color: rgb(0, 0, 128);  font-size: 20px; line-height: 21px; margin: 0px; padding: 0px; border: 0px; vertical-align: baseline;">我們所提供為全新產品，並提供以下保證：</strong></p>
	<ul style="font-size: 16px;margin-left:2em;">
		<li>
			保固期限：<?php echo $warranty ?></li>
		<li>
			保固範圍：產品故障</li>
	</ul>
	<hr />
	<p style="font-size: 16px;line-height: 21px;color: rgb(162, 161, 166);">
		<span style="color: rgb(191, 20, 3);">注意事項：</span><br />
		<div style="margin-left:2em;font-size:16px;">
					▼ 產品顏色因個人之顯示器不同而有所差異，將以實際商品顏色為主<br />
					▼ 產品規格若敘述有誤，請以實品/原廠公告為準<br />
					▼ 使用前請確實遵從產品說明書內之操作指示及注意事項<br />
					<br />
		</div>
	</p>
		
	<p style="text-align: center; max-width: 700px;">
		<img alt="PCT品牌介紹" style="width: 100%; height: auto;" src="https://www.kvm-all.com.tw/upload/images/PCT%E5%93%81%E7%89%8C.png" /></p>
	<div style="text-align: center;font-size: 14px;line-height: 21px;color: rgb(191, 20, 3);text-align: -webkit-center;">
		<div style="color: rgb(162, 161, 166);">
			<a style="font-size: 16px;" href="https://www.pct-max.com.tw/" target="_blank">官網：銘鵬科技股份有限公司</a><br>
			<a style="font-size: 16px;" href="https://www.kvm-all.com.tw/" target="_blank">購買地點：全球KVM中心</a><br>
			----------------------------------------------------------------------------------------------<br />
			PCT 領導品牌　多電腦切換器　影音／訊號分配器　延伸器　轉換器　KVM-Over-IP　3C電腦週邊　整合方案</span>
		</div>
  </div>
</div>
</body>
</html>