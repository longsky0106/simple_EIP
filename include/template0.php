<?php
$html = <<< HEREDOC
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
  <title>上架產品介紹範本</title>
  <!--<link rel="stylesheet" href="CSS/lyweb.css">-->
  <!--<script src="JS/jquery-1.12.1.min.js"></script>-->
</head>
<body>
<div id="main">
	<p style="max-width:700px">
		<img alt="產品大圖(600x600或800x800)" style="width: 100%; height: auto;" src="pic/MHC414.jpg" /></p>
	<div style="margin: 0px; padding: 0px; border: 0px; font-size: 28px; vertical-align: baseline; color: #800080;  line-height: 28px; text-align: center;">
		<strong><span>【PCT】{$name_for_sell}({$prod_model})</span></strong></div>
	<hr />
	<p>
		<span style="margin: 0px; padding: 0px; border: 0px; font-size: 20px; vertical-align: baseline;">
		{$description}
		</span></p>
	<hr />
<!--<p style="max-width:700px">
		<img alt="產品第二張圖" style="width: 100%; height: auto;" src="pic/MHC414-2.jpg" /></p>
	<hr />-->
	<p>
		<span style="font-size: 20px;line-height: 21px;margin: 0px; padding: 0px; border: 0px; vertical-align: baseline; color: rgb(0, 0, 128);">
		<strong>產品特色：</strong></span>
		<br>
		{$features}
	</p>
	<hr />
	
	
		<!--<p><strong style="font-size: 20px; line-height: 21px; color: rgb(0, 0, 128);">產品應用：</strong>
				<p style="max-width:700px">
					<img alt="應用圖" style="width: 100%; height: auto;" src="pic/MHC414-應用圖.png" /></p>
			</p>
			<hr />-->
	<p>
		<strong style="font-size: 20px; line-height: 21px; color: rgb(0, 0, 128);">產品規格：</strong>
		<div id="contentwrap" style="max-width: 700px;">
		{$SK_SPEC}
		</div>
	</p>
	<hr />
	<p>
		<strong style="color: rgb(0, 0, 128);  font-size: 20px; line-height: 21px;">產品包裝內容：</strong></p>
	<ul style="font-size:16px;margin-left:2em;">
		<li>
			XPC84 本體 x 1</li>
		<li>
			專用線材 x 8</li>
		<li>
			DC12V 變壓器 x 1</li>
		<li>
			說明書 x 1</li>
	</ul>
	<hr />
	<p>
		<strong style="color: rgb(0, 0, 128);  font-size: 20px; line-height: 21px; margin: 0px; padding: 0px; border: 0px; vertical-align: baseline;">我們所提供為全新產品，並提供以下保證：</strong></p>
	<ul style="font-size: 16px;margin-left:2em;">
		<li>
			保固期限：一年</li>
		<li>
			保固範圍：產品故障</li>
	</ul>
	<hr />
	<p style="font-size: 16px;line-height: 21px;color: rgb(162, 161, 166);">
		<span style="color: rgb(191, 20, 3);">注意事項：</span><br />
  <div style="margin-left:2em;font-size:16px;">
			▼ 下單購買前，請務必詳閱本站
			<span style="color: rgb(191, 20, 3);">【購物說明】</span>，以避免交易糾紛及徒增彼此困擾<br />
			▼ 下單購買後，視同接受本站之交易條款規則，不得異議<br />
			▼ 產品顏色因個人之顯示器不同而有所差異，將以實際商品顏色為主<br />
			▼ 產品規格若敘述有誤，請以實品/原廠公告為準<br />
			▼ 使用前請確實遵從產品說明書內之操作指示及注意事項<br />
			<br />
			<strong>【本站保留訂單成立與否的權利】</strong></span>
  </div>
	</p>
	<p style="text-align: center;max-width: 700px;"><img alt="PCT品牌介紹" style="width: 100%; height: auto;" src="/upload/images/PCT品牌-800.jpg" /></p>
	
	<div style="text-align: center;font-size: 14px;line-height: 21px;color: rgb(191, 20, 3);text-align: -webkit-center;">
		----------------------------------------------------------------------------------------------<br />
		如您有任何問題，歡迎聯繫本網站客服中心 02-7731-2002，謝謝！<br />
		全球KVM中心&nbsp;<a href="http://www.kvm-all.com.tw/default.asp" style="color: rgb(191, 20, 3);">http://www.kvm-all.com.tw</a><br />
		<br />
			<div style="color: rgb(162, 161, 166);">
				----------------------------------------------------------------------------------------------<br />
				PCT 領導品牌　多電腦切換器　影音／訊號分配器　延伸器　轉換器　KVM-Over-IP　3C電腦週邊　整合方案</span>
			</div>
  </div>
</div>
</body>
</html>

HEREDOC;
echo $html;
?>