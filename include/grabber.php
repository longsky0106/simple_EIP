<?php
//$root_path="http://www.pct-max.com.tw/";//相關路徑
$html = file_get_contents($_GET['url']);
$content = explode("\n", $html);

$URLs = array();

for($i=0;count($content)>$i;$i++)
{
	if(preg_match('/<a href=/', $content[$i]))
	{
		list($Gone,$Keep) = explode("href=\"", trim($content[$i]));
		list($Keep,$Gone) = explode("\">", $Keep);
		$html= strtr($html, array( "$Keep" => "http://www.pct-max.com.tw/", ));
	}
}

echo $html;
?>