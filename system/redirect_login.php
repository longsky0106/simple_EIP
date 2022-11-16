<?php
	$root_path = $_SERVER['HTTP_REFERER'];
	if(!$root_path){
		$root_path = "";
	}else{
		$root_path = "/".explode("/",$_SERVER['SCRIPT_NAME'])[1]."/system/";
	}
	
	session_start();
	echo $_SESSION['current_page']."current_page";
	if(isset($_SESSION['current_page'])){
		$location_page = $_SESSION['current_page'];
	}else{
		$location_page = "/include/index_load_content.php";
	}
	header('location:'.$root_path.'../'.$location_page);
	exit();
?>