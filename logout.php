<?php
	$do_logout = $_REQUEST['data'];
// $do_logout = strip_tags($data['data']);
	// echo "|".$do_logout."|已登出";
	if(ISSET($do_logout)){
		session_start();
		unset($_SESSION['user']); // 清除使用者資料
		session_destroy(); // 銷毀所有資料(登出)
	}
	echo "已登出";

	// unset($_SESSION['user']); // 清除使用者資料
	// session_destroy(); // 銷毀所有資料(登出)
	// echo "已登出";
	//header('location:login.php');
?>