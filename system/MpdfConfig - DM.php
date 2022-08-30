<?php
require_once '../vendor/autoload.php';
require_once '../system/Mpdf - fontData.php';

// ----------------頁面設定---------------
// $format = 'A4'; // 版面大小，內容可為: [寬mm, 高mm], 'A4'
$format = [325.1,435.5]; // 版面大小，內容可為: [寬mm, 高mm], 'A4'
$orientation = 'P'; // 方向，內容可為: 'P' (垂直), 'L' (水平)

/* $margin_top = 12.3;
$margin_bottom = 5.4;
$margin_left = 14.3;
$margin_right = 14.3; */
$margin_top = 0;
$margin_right = 0;
$margin_bottom = 0;
$margin_left = 0;

$MpdfConfig = array(
	"autoScriptToLang" => false,
	"autoLangToFont" => false,
	"fontdata" => $fontData,
    // 'default_font' => $default_font,
	'format' => $format,
	'orientation' => $orientation,
	'margin_top' => $margin_top,
	'margin_bottom' => $margin_bottom,
	'margin_left' => $margin_left,
	'margin_right' => $margin_right,
	'margin_header' => 0,
	'margin_footer' => 0,
)



?>