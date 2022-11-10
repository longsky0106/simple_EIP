<?php
	$root_path = $_SERVER['HTTP_REFERER'];
	if(!$root_path){
		$root_path = "../";
	}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
  <title>凌越線上使用者一覽表</title>
  <link rel="stylesheet" href="<?=$root_path?>CSS/ly_online_user.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="<?=$root_path?>JS/ly_online_user.js"></script>
</head>
  <body>
    <span>凌越線上使用者一覽表</span><br>
    <ul>
      <li>本網頁每15秒自動更新內容</li>
      <li>系統每10分鐘清除斷線的使用者（避免佔用人數）</li>
      <li>生產製造：當總人數到達9人時，將自動踢除閒置30分鐘的使用者。當總人數到達上限時，將自動踢除閒置10分鐘的使用者。（避免佔用人數）</li>
      <li>「單據/功能操作」有時會顯示錯誤單據（顯示的單據非實際使用者所操作）</li>
      <li>如遇到單號卡住，可以請「單據/功能操作」中顯示該單號的使用者做重新登入動作。或使用「網路監控程式」對該使用者做「清除單一鎖定明細」（只清除單據鎖定狀態而不必讓使用者退出）</li>
    </ul>
    <br>
    <span><b>生產製造（10人版）</b></span>
    <div id="ly_bom"></div>
    <br>
    <span><b>出口貿易（2人版）</b></span>
    <div id="LyTrade"></div>
    <br>
    <span><b>會計財務（2人版）</b></span>
    <div id="LyAct"></div>
	</body>
</html>