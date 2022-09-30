<?php
	$Company_name = "銘鵬科技";
?>

<!doctype html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
    <title><?=$Company_name."簡易EIP"?></title>
    <link rel="stylesheet" href="CSS/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <!-- <script src="https://releases.jquery.com/git/ui/jquery-ui-git.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>
    <script src="JS/index.js"></script>
    <script src="JS/MyFunction.js"></script>
  </head>
  <body>
    <div id="index_main">
      
      <div id="index_menu">
        <div><?=$Company_name."簡易EIP"?></div>
        <a href="javascript:load_content(1)">料號查詢</a>
        <a href="javascript:load_content(2)">貨品規格與銷售資料管理</a>
        <a href="javascript:load_content(3)">ERP線上使用狀況一覽</a>
        <a href="javascript:load_content(0)">登出</a>
        <div id="debug_content">
          <div id="debug0">debug0</div>
          <div id="debug1">debug1</div>
          <div id="debug2">debug2</div>
          <div id="debug3">debug3</div>
          <div id="debug4">debug4</div>
          <div id="debug5">debug5</div>
          <div id="debug6">debug6</div>
          <div id="debug7">debug7</div>
          <div id="debug8">debug8</div>
          <div id="debug9">debug9</div>
          <div id="debug10">debug10</div>
        </div>
      </div>
      <div id="load_content">
      請選擇要瀏覽的頁面
      </div>
    </div>
  </body>
</html>