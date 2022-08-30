<?php
$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

// vendor\mpdf\mpdf\ttfonts
// 新增字體 font-family
$fontData = [
	'微軟正黑體' => [
		'R' => 'MSJH.TTC',
		'B' => 'MSJHBD.TTC',
		'TTCfontID' => [
			'R' => 1,
			'B' => 1
		],
		// 'sip-ext' => 'mingliu-extb'
	],
	'microsoft jhenghei' => [
		'R' => 'MSJH.TTC',
		'B' => 'MSJHBD.TTC',
		'TTCfontID' => [
			'R' => 2,
			'B' => 2
		],
		// 'sip-ext' => 'pmingliu-extb'
	]
] + $fontData; // 要記得加上原本的 $fontData，
               // 否則 mPDF 內建字集會被清空而失效	

$default_font = '微軟正黑體';

?>