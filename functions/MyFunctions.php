<?php

function array_search_partial($arr, $keyword) {
	foreach($arr as $index => $string) {
		if (strpos($string, $keyword) !== FALSE)
			return $index;
	}
}

/* function str_starts_with ( $haystack, $needle ) {
	return strpos( $haystack , $needle ) === 0;
} */

function checkRemoteFile($url)
{
	$timeout = 1; //timeout seconds

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	// don't download content
	curl_setopt($ch, CURLOPT_NOBODY, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);

	return (curl_exec($ch)!==FALSE);
}	
	
	
?>