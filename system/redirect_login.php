<?php
	session_start();
	echo $_SESSION['current_page']."current_page";
	if(isset($_SESSION['current_page'])){
		$location_page = $_SESSION['current_page'];
	}else{
		$location_page = "/include/index_load_content.php";
	}
	header('location:../'.$location_page);
	exit();
?>