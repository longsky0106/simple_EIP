<?php

function strip_tags_content($text) {
	return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
}


?>
<!doctype html>
<html lang="zh-tw">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
  <title>全球KVM中心上架產品介紹範本</title>
</head>
<body>
<div id="main">
	
	<div>
		<strong><span><?php echo $name_for_sell ?></span></strong></div>
	<br>
	
		<span>
		<?php
			$description = strip_tags_content($description);
			echo $description;
		?>
		</span>
	<br>
	<br>
		<div>
		<strong>產品特色：</strong><br>
		<?php echo nl2br($features) ?>
		</div>

	<br>

	
		<div>
		<strong>產品規格：</strong>
		<?php echo $SK_SPEC ?>
		</div>
	
	<br>

	<div>
		<strong>產品包裝內容：</strong><br>
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
	</div>
	<br>
	<div>
			<strong>我們所提供為全新產品，並提供以下保證：</strong><br>
			保固期限：<?php echo $warranty ?><br>
			保固範圍：產品故障
	</div>
	<br>
	
		<div>
		<span>注意事項：</span><br />
		
					▼ 下單購買前，請務必詳閱購物說明，以避免交易糾紛及徒增彼此困擾<br />
					▼ 產品顏色因個人之顯示器不同而有所差異，將以實際商品顏色為主<br />
					▼ 產品規格若敘述有誤，請以實品/原廠公告為準<br />
					▼ 使用前請確實遵從產品說明書內之操作指示及注意事項
		</div>
	
		
	
	<div>
		<div>
			----------------------------------------------------------------------------------------------<br />
			PCT 領導品牌　多電腦切換器　影音／訊號分配器　延伸器　轉換器　KVM-Over-IP　3C電腦週邊　整合方案</span>
		</div>
  </div>
</div>
</body>
</html>